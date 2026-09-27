<?php
require_once __DIR__.'/includes/config.php';
require_login('return-request.php');

$uid=(int)$_SESSION['user_id'];
$orderId=(int)($_GET['order_id']??$_POST['order_id']??0);
$productId=(int)($_POST['product_id']??0);
$flash=$_SESSION['flash']??'';
unset($_SESSION['flash']);

$s=$conn->prepare("SELECT * FROM orders WHERE id=? AND user_id=? LIMIT 1");
$s->bind_param('ii',$orderId,$uid);$s->execute();
$order=$s->get_result()->fetch_assoc();
if(!$order) redirect('view-order.php');

if(in_array($order['order_status'],['payment_pending','payment_failed','cancelled'],true)){
    $_SESSION['flash']='This order is not eligible for a return request yet.';
    redirect('order.php?id='.$orderId);
}

$s=$conn->prepare("SELECT oi.*,p.image FROM order_items oi LEFT JOIN products p ON p.id=oi.product_id WHERE oi.order_id=?");
$s->bind_param('i',$orderId);$s->execute();
$items=$s->get_result()->fetch_all(MYSQLI_ASSOC);

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!check_csrf($_POST['csrf']??'')){http_response_code(400);exit('Invalid request');}
    $reason=trim($_POST['reason']??'');
    $qty=(int)($_POST['quantity']??1);
    $allowedReasons=['Damaged product','Wrong product received','Product not as described','Quality issue','Missing item','Other'];
    $reasonType=trim($_POST['reason_type']??'Other');
    if(!in_array($reasonType,$allowedReasons,true))$reasonType='Other';
    if($reasonType==='Other' && $reason==='')$reason='Other';
    elseif($reasonType!=='Other')$reason=$reasonType.($reason!==''?' — '.$reason:'');
    $selected=null;
    foreach($items as $it){if((int)$it['product_id']===$productId){$selected=$it;break;}}
    if(!$selected){$selected=$items[0]??null;$productId=(int)($selected['product_id']??0);}
    if(!$selected||$reason===''){$_SESSION['flash']='Please select a product and enter a return reason.';redirect('return-request.php?order_id='.$orderId);}
    $maxQty=(int)$selected['quantity'];
    $qty=max(1,min($qty,$maxQty));

    $s=$conn->prepare("SELECT id FROM return_requests WHERE order_id=? AND user_id=? AND product_id=? AND status IN ('requested','approved') LIMIT 1");
    $s->bind_param('iii',$orderId,$uid,$productId);$s->execute();
    if($s->get_result()->fetch_assoc()){
        $_SESSION['flash']='A return request for this product is already pending.';
        redirect('order.php?id='.$orderId);
    }
    $productName=(string)$selected['product_name'];
    $unitPrice=(float)$selected['unit_price'];
    $status='requested';
    $s=$conn->prepare("INSERT INTO return_requests(order_id,user_id,product_id,product_name,unit_price,quantity,reason,status) VALUES(?,?,?,?,?,?,?,?)");
    $s->bind_param('iiisdiss',$orderId,$uid,$productId,$productName,$unitPrice,$qty,$reason,$status);
    $s->execute();
    add_order_history($orderId,'return_requested','Return requested for '.$productName.' x '.$qty);
    send_app_email(ORDER_TO_EMAIL,'Return Request - '.$order['order_number'],"Return request from ".APP_NAME."\n\nOrder: ".$order['order_number']."\nCustomer: ".$order['customer_name']."\nPhone: ".$order['phone']."\nEmail: ".$order['email']."\nAddress: ".$order['address'].", ".$order['city'].", ".$order['state']." - ".$order['pincode']."\nProduct: ".$productName."\nPrice: ".money($unitPrice)."\nQuantity: ".$qty."\nReason: ".$reason, false);
    $_SESSION['flash']='Return request submitted successfully. Admin will review it.';
    redirect('order.php?id='.$orderId);
}
?>
<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Return Request - <?= e($order['order_number']) ?></title><?php include 'assets.php'; ?>
<style>.return-card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:24px}.return-item{border:1px solid #eee;border-radius:12px;padding:12px;margin-bottom:10px}.return-item img{width:70px;height:70px;object-fit:cover;border-radius:8px}</style></head>
<body><?php include 'header.php'; ?>
<section class="flat-spacing"><div class="container">
<h2 class="mb-30">Return / Replacement Request</h2>
<?php if($flash): ?><div class="alert alert-info"><?= e($flash) ?></div><?php endif; ?>
<div class="row g-4">
<div class="col-lg-7"><div class="return-card">
<h5>Customer & Order Details</h5>
<p class="mt-15"><b>Name:</b> <?= e($order['customer_name']) ?><br><b>Phone:</b> <?= e($order['phone']) ?><br><b>Email:</b> <?= e($order['email']) ?><br><b>Order:</b> <?= e($order['order_number']) ?><br><b>Order Total:</b> <?= money($order['total']) ?></p>
<p><b>Address:</b><br><?= nl2br(e($order['address'])) ?><br><?= e($order['city']) ?>, <?= e($order['state']) ?> - <?= e($order['pincode']) ?><br><?= e($order['country']) ?></p>
<form method="post">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="order_id" value="<?= $orderId ?>">
<label class="form-label fw-semibold">Product*</label>
<select name="product_id" class="form-select mb-3" required>
<?php foreach($items as $it): ?><option value="<?= (int)$it['product_id'] ?>"><?= e($it['product_name']) ?> — <?= money($it['unit_price']) ?> × <?= (int)$it['quantity'] ?></option><?php endforeach; ?>
</select>
<label class="form-label fw-semibold">Quantity*</label><input type="number" name="quantity" min="1" value="1" class="form-control mb-3" required>
<label class="form-label fw-semibold">Reason*</label>
<select name="reason_type" class="form-select mb-3" required><option>Damaged product</option><option>Wrong product received</option><option>Product not as described</option><option>Quality issue</option><option>Missing item</option><option>Other</option></select>
<textarea name="reason" class="form-control" rows="5" placeholder="Please explain the issue / return reason"></textarea>
<button class="tf-btn animate-btn w-100 mt-15">Submit Return Request</button>
</form>
</div></div>
<div class="col-lg-5"><div class="return-card"><h5>Products in this order</h5>
<?php foreach($items as $it): ?><div class="return-item d-flex align-items-center gap-3"><img src="admin/uploads-img/<?= e($it['image']??'') ?>" alt=""><div><b><?= e($it['product_name']) ?></b><br><small>Qty: <?= (int)$it['quantity'] ?> · Price: <?= money($it['unit_price']) ?></small></div></div><?php endforeach; ?>
<p class="small text-muted mt-15">Your request is stored with your name, phone, email, address, product, price, quantity and reason so the admin can review it.</p></div></div>
</div></div></section><?php include 'footer.php'; ?></body></html>
