<?php
require_once __DIR__ . '/includes/config.php';

if($_SERVER['REQUEST_METHOD']!=='POST' || !check_csrf($_POST['csrf']??'')){
    http_response_code(400);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['ok'=>false,'message'=>'Invalid request.']);
    exit;
}
header('Content-Type: application/json; charset=UTF-8');

$orderId=(int)($_POST['order_id']??0);
$rzOrderId=trim($_POST['razorpay_order_id']??'');
$rzPaymentId=trim($_POST['razorpay_payment_id']??'');
$signature=trim($_POST['razorpay_signature']??'');
$uid=(int)($_SESSION['user_id']??0);

if(!$uid||!$orderId||$rzOrderId===''||$rzPaymentId===''||$signature===''){
    echo json_encode(['ok'=>false,'message'=>'Payment verification data is incomplete.']);exit;
}

$paymentCaptured=false;
    $expectedAmount=0;
try{
    $s=$conn->prepare("SELECT * FROM orders WHERE id=? AND user_id=? LIMIT 1");
    $s->bind_param('ii',$orderId,$uid);$s->execute();
    $order=$s->get_result()->fetch_assoc();
    if(!$order) throw new Exception('Order not found.');
    if(($order['razorpay_order_id']??'')!==$rzOrderId) throw new Exception('Razorpay order ID mismatch.');
    if($order['payment_status']==='paid' && $order['order_status']==='confirmed'){
        echo json_encode(['ok'=>true,'redirect'=>'order.php?id='.$orderId]);exit;
    }
    if(!razorpay_verify_signature($rzOrderId,$rzPaymentId,$signature)) throw new Exception('Payment signature verification failed.');

    $payment=razorpay_api('GET','payments/'.rawurlencode($rzPaymentId));
    $paidAmount=(int)($payment['amount']??0);
    $expected=(int)round(((float)$order['total'])*100);
    $expectedAmount=$expected;
    if($paidAmount!==$expected) throw new Exception('Payment amount does not match the checkout total.');
    if(strtolower((string)($payment['currency']??'INR'))!=='inr') throw new Exception('Payment currency mismatch.');
    if(($payment['status']??'')!=='captured') throw new Exception('Payment received but not captured yet. Please contact support if your bank was charged.');
    $paymentCaptured=true;

    $conn->begin_transaction();

    $s=$conn->prepare("SELECT * FROM orders WHERE id=? AND user_id=? FOR UPDATE");
    $s->bind_param('ii',$orderId,$uid);$s->execute();
    $order=$s->get_result()->fetch_assoc();
    if(!$order) throw new Exception('Order not found.');
    if($order['payment_status']==='paid'){
        $conn->commit();
        echo json_encode(['ok'=>true,'redirect'=>'order.php?id='.$orderId]);exit;
    }

    $itemsQ=$conn->prepare("SELECT * FROM order_items WHERE order_id=?");
    $itemsQ->bind_param('i',$orderId);$itemsQ->execute();
    $items=$itemsQ->get_result()->fetch_all(MYSQLI_ASSOC);
    if(!$items) throw new Exception('Order items not found.');

    foreach($items as $i){
        $pid=(int)$i['product_id'];$qty=(int)$i['quantity'];
        $ps=$conn->prepare("SELECT id,quantity,status FROM products WHERE id=? FOR UPDATE");
        $ps->bind_param('i',$pid);$ps->execute();
        $p=$ps->get_result()->fetch_assoc();
        if(!$p||(int)$p['status']!==1||(int)$p['quantity']<$qty) throw new Exception('Payment is successful, but stock is no longer available for one or more items. Please contact support for refund assistance.');
    }
    foreach($items as $i){
        $pid=(int)$i['product_id'];$qty=(int)$i['quantity'];
        $ps=$conn->prepare("UPDATE products SET quantity=quantity-? WHERE id=? AND quantity>=?");
        $ps->bind_param('iii',$qty,$pid,$qty);$ps->execute();
        if($ps->affected_rows!==1) throw new Exception('Unable to reserve stock after payment.');
    }

    $paid='paid';$confirmed='confirmed';
    $s=$conn->prepare("UPDATE orders SET payment_status=?,order_status=?,razorpay_payment_id=?,razorpay_signature=? WHERE id=?");
    $s->bind_param('ssssi',$paid,$confirmed,$rzPaymentId,$signature,$orderId);
    $s->execute();
    add_order_history($orderId,'confirmed','Razorpay payment verified and captured.');
    $s=$conn->prepare("DELETE FROM cart WHERE user_id=?");$s->bind_param('i',$uid);$s->execute();
    $conn->commit();

    $order['payment_status']='paid';$order['order_status']='confirmed';$order['razorpay_payment_id']=$rzPaymentId;$order['razorpay_signature']=$signature;
    send_order_confirmation_email($order,$items);
    $lines=[];foreach($items as $i)$lines[]=$i['product_name'].' x '.$i['quantity'].' = '.money($i['line_total']);
    send_app_email(ORDER_TO_EMAIL,'New Paid Razorpay Order '.$order['order_number'],"Paid Razorpay order from ".APP_NAME."\n\nOrder: ".$order['order_number']."\nCustomer: ".$order['customer_name']."\nEmail: ".$order['email']."\nPhone: ".$order['phone']."\nAddress: ".$order['address'].", ".$order['city'].", ".$order['state']." - ".$order['pincode'].", ".$order['country']."\nRazorpay Payment ID: ".$rzPaymentId."\n\n".implode("\n",$lines)."\n\nTotal: ".money($order['total']),false);

    unset($_SESSION['checkout_snapshot']);
    $_SESSION['last_order_id']=$orderId;
    echo json_encode(['ok'=>true,'redirect'=>'order.php?id='.$orderId]);
}catch(Throwable $e){
    try{$conn->rollback();}catch(Throwable $ignore){}
    $message=$e->getMessage();
    if($paymentCaptured && $rzPaymentId!==''){
        try{
            razorpay_api('POST','payments/'.rawurlencode($rzPaymentId).'/refund',['amount'=>$expectedAmount,'notes'=>['order_id'=>(string)$orderId,'reason'=>'Order verification/stock validation failed']]);
            $refunded='refunded';$cancelled='cancelled';
            $s=$conn->prepare("UPDATE orders SET payment_status=?,order_status=?,razorpay_payment_id=?,razorpay_signature=? WHERE id=? AND payment_status<>'paid'");
            $s->bind_param('ssssi',$refunded,$cancelled,$rzPaymentId,$signature,$orderId);$s->execute();
            add_order_history($orderId,'cancelled','Payment was captured but order verification failed; Razorpay refund was initiated.');
            $message.=' The captured payment was sent for refund because the order could not be confirmed.';
        }catch(Throwable $refundError){
            $message.=' IMPORTANT: automatic refund could not be initiated: '.$refundError->getMessage();
            try{
                $failed='failed';$status='payment_failed';
                $s=$conn->prepare("UPDATE orders SET payment_status=?,order_status=? WHERE id=? AND payment_status<>'paid'");
                $s->bind_param('ssi',$failed,$status,$orderId);$s->execute();
                add_order_history($orderId,'payment_failed','Captured payment could not be reconciled automatically: '.$refundError->getMessage());
            }catch(Throwable $ignore){}
        }
    } elseif($orderId){
        try{
            $failed='failed';$status='payment_failed';
            $s=$conn->prepare("UPDATE orders SET payment_status=?,order_status=? WHERE id=? AND payment_status<>'paid'");
            $s->bind_param('ssi',$failed,$status,$orderId);$s->execute();
            add_order_history($orderId,'payment_failed',$message);
        }catch(Throwable $ignore){}
    }
    echo json_encode(['ok'=>false,'message'=>$message],400);
}
