<?php
require_once __DIR__ . '/includes/config.php';

$isAjax = isset($_POST['ajax']) && $_POST['ajax'] === '1';
function place_json($data, $status=200){
    http_response_code($status);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !check_csrf($_POST['csrf'] ?? '')) {
    if($isAjax) place_json(['ok'=>false,'message'=>'Invalid request.'],400);
    http_response_code(400); exit('Invalid request');
}
$uid=(int)($_SESSION['user_id']??0);
if(!$uid){
    if($isAjax) place_json(['ok'=>false,'message'=>'Please login before checkout.'],401);
    $_SESSION['after_login']='checkout.php'; redirect('login.php');
}

$snapshot=$_SESSION['checkout_snapshot']??null;
if(!is_array($snapshot)||empty($snapshot['items'])||!isset($snapshot['total'])){
    if($isAjax) place_json(['ok'=>false,'message'=>'Checkout session expired. Please refresh checkout and try again.'],400);
    $_SESSION['flash']='Checkout session expired. Please refresh checkout.';
    redirect('checkout.php');
}
[$currentItems,$currentSubtotal]=cart_totals($uid);
$currentMap=[];
foreach($currentItems as $ci){
    $currentMap[(int)$ci['product_id']]=(int)$ci['quantity'];
}
$snapshotItems=$snapshot['items'];
$snapMap=[];
$subtotal=0;
foreach($snapshotItems as $si){
    $pid=(int)($si['product_id']??0);
    $qty=(int)($si['quantity']??0);
    $unit=(float)($si['unit_price']??0);
    if($pid<1||$qty<1||$unit<0){$snapshotItems=[];break;}
    $snapMap[$pid]=$qty;
    $subtotal += round($unit*$qty,2);
}
$subtotal=round($subtotal,2);
if(!$snapshotItems || $snapMap !== $currentMap){
    if($isAjax) place_json(['ok'=>false,'message'=>'Your cart changed after checkout was opened. Please refresh the checkout page so the displayed total and payable amount match.'],409);
    $_SESSION['flash']='Your cart changed. Please refresh checkout before placing the order.';
    redirect('checkout.php');
}
$checkoutTotal=round((float)$snapshot['total'],2);
if(abs($subtotal-$checkoutTotal)>0.009){
    if($isAjax) place_json(['ok'=>false,'message'=>'Checkout total changed. Please refresh checkout and try again.'],409);
    $_SESSION['flash']='Checkout total changed. Please refresh checkout.';
    redirect('checkout.php');
}

$first=trim($_POST['first_name']??'');
$last=trim($_POST['last_name']??'');
$email=trim($_POST['email']??'');
$phone=trim($_POST['phone']??'');
$country=trim($_POST['country']??'');
$city=trim($_POST['city']??'');
$address=trim($_POST['address']??'');
$state=trim($_POST['state']??'');
$pincode=trim($_POST['pincode']??'');
$payment=$_POST['payment_method']??'cod';
if(!in_array($payment,['cod','razorpay'],true)) $payment='cod';

if(!$first||!$last||!filter_var($email,FILTER_VALIDATE_EMAIL)||!$phone||!$country||!$city||!$address||!$state||!preg_match('/^[0-9]{5,6}$/',$pincode)){
    if($isAjax) place_json(['ok'=>false,'message'=>'Please complete all required checkout fields.'],422);
    $_SESSION['flash']='Please complete all required checkout fields.';
    redirect('checkout.php');
}

$name=trim($first.' '.$last);
$orderNo='KF-'.date('Ymd').'-'.strtoupper(bin2hex(random_bytes(4)));
$tracking='KFT-'.strtoupper(bin2hex(random_bytes(5)));
$shipping=0.00;$discount=0.00;$total=$checkoutTotal;
$paymentStatus='pending';
$status=$payment==='cod'?'placed':'payment_pending';
$orderId=0;

try{
    $conn->begin_transaction();

    foreach($snapshotItems as $i){
        $pid=(int)$i['product_id'];$qty=(int)$i['quantity'];
        $s=$conn->prepare("SELECT id,name,quantity,status FROM products WHERE id=? FOR UPDATE");
        $s->bind_param('i',$pid);$s->execute();
        $p=$s->get_result()->fetch_assoc();
        if(!$p||(int)$p['status']!==1||(int)$p['quantity']<$qty){
            throw new Exception('One of the products is no longer available in the selected quantity.');
        }
    }

    $s=$conn->prepare("INSERT INTO orders(user_id,order_number,customer_name,email,phone,address,country,city,state,pincode,subtotal,shipping,discount,total,payment_method,payment_status,order_status,tracking_number) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $s->bind_param('isssssssssddddssss',$uid,$orderNo,$name,$email,$phone,$address,$country,$city,$state,$pincode,$subtotal,$shipping,$discount,$total,$payment,$paymentStatus,$status,$tracking);
    $s->execute();
    $orderId=(int)$s->insert_id;

    foreach($snapshotItems as $i){
        $pid=(int)$i['product_id'];$qty=(int)$i['quantity'];$unit=(float)$i['unit_price'];$line=round($unit*$qty,2);$productName=(string)$i['name'];
        $s=$conn->prepare("INSERT INTO order_items(order_id,product_id,product_name,unit_price,quantity,line_total) VALUES(?,?,?,?,?,?)");
        $s->bind_param('iisdid',$orderId,$pid,$productName,$unit,$qty,$line);$s->execute();

        if($payment==='cod'){
            $s=$conn->prepare("UPDATE products SET quantity=quantity-? WHERE id=? AND quantity>=?");
            $s->bind_param('iii',$qty,$pid,$qty);$s->execute();
            if($s->affected_rows!==1) throw new Exception('Stock changed. Please try again.');
        }
    }

    if(!empty($_POST['save_address'])){
        $conn->query("UPDATE customer_addresses SET is_default=0 WHERE user_id=".(int)$uid);
        $s=$conn->prepare("INSERT INTO customer_addresses(user_id,label,address_line,city,state,pincode,country,is_default) VALUES(?,?,?,?,?,?,?,1)");
        $label='Default';$s->bind_param('issssss',$uid,$label,$address,$city,$state,$pincode,$country);$s->execute();
    }

    if($payment==='cod'){
        $s=$conn->prepare("DELETE FROM cart WHERE user_id=?");$s->bind_param('i',$uid);$s->execute();
        add_order_history($orderId,'placed','Cash on Delivery order placed.');
    }
    $conn->commit();

    unset($_SESSION['pending_checkout']);
    $_SESSION['last_order_id']=$orderId;

    if($payment==='cod'){
        $orderMail=['order_number'=>$orderNo,'customer_name'=>$name,'email'=>$email,'phone'=>$phone,'address'=>$address,'country'=>$country,'city'=>$city,'state'=>$state,'pincode'=>$pincode,'subtotal'=>$subtotal,'shipping'=>$shipping,'discount'=>$discount,'total'=>$total,'tracking_number'=>$tracking,'order_status'=>$status,'payment_method'=>'cod','payment_status'=>'pending'];
        send_order_confirmation_email($orderMail,$snapshotItems);
        $lines=[];foreach($snapshotItems as $i)$lines[]=$i['name'].' x '.$i['quantity'].' = '.money($i['line_total']);
        send_app_email(ORDER_TO_EMAIL,'New COD Order '.$orderNo,"New COD order from ".APP_NAME."\n\nOrder: $orderNo\nCustomer: $name\nEmail: $email\nPhone: $phone\nAddress: $address, $city, $state - $pincode, $country\nTracking: $tracking\n\n".implode("\n",$lines)."\n\nTotal: ".money($total),false);
        if($isAjax) place_json(['ok'=>true,'redirect'=>'order.php?id='.$orderId]);
        redirect('order.php?id='.$orderId);
    }

    try{
        $rz=razorpay_create_order($total,$orderNo,['order_id'=>(string)$orderId,'order_number'=>$orderNo]);
        $rzId=(string)($rz['id']??'');
        $rzAmount=(int)($rz['amount']??0);
        if($rzId===''||$rzAmount!==((int)round($total*100))) throw new Exception('Razorpay returned an invalid order amount.');
        $s=$conn->prepare("UPDATE orders SET razorpay_order_id=? WHERE id=?");
        $s->bind_param('si',$rzId,$orderId);$s->execute();
        add_order_history($orderId,'payment_pending','Razorpay payment order created.');
        if($isAjax) place_json(['ok'=>true,'razorpay'=>['key_id'=>RAZORPAY_KEY_ID,'order_id'=>$rzId,'amount'=>$rzAmount,'currency'=>'INR'],'customer'=>['name'=>$name,'email'=>$email,'phone'=>$phone],'order_id'=>$orderId,'order_number'=>$orderNo]);
    }catch(Throwable $rzError){
        $conn->begin_transaction();
        $failed='failed';$note='Razorpay order creation failed: '.$rzError->getMessage();
        $s=$conn->prepare("UPDATE orders SET payment_status=?,order_status=? WHERE id=?");
        $failedStatus='payment_failed';$s->bind_param('ssi',$failed,$failedStatus,$orderId);$s->execute();
        add_order_history($orderId,'payment_failed',$note);
        $conn->commit();
        if($isAjax) place_json(['ok'=>false,'message'=>$rzError->getMessage(),'order_id'=>$orderId],502);
        $_SESSION['flash']=$rzError->getMessage();redirect('checkout.php');
    }
}catch(Throwable $e){
    if($conn->errno || $conn->connect_errno){try{$conn->rollback();}catch(Throwable $ignore){}}
    $_SESSION['flash']=$e->getMessage();
    if($isAjax) place_json(['ok'=>false,'message'=>$e->getMessage()],500);
    redirect('checkout.php');
}
