<?php
require_once __DIR__.'/includes/config.php';
header('Content-Type: application/json; charset=UTF-8');
if($_SERVER['REQUEST_METHOD']!=='POST'||!check_csrf($_POST['csrf']??'')){http_response_code(400);echo json_encode(['ok'=>false]);exit;}
$uid=(int)($_SESSION['user_id']??0);$orderId=(int)($_POST['order_id']??0);
if(!$uid||!$orderId){echo json_encode(['ok'=>false]);exit;}
$s=$conn->prepare("SELECT id FROM orders WHERE id=? AND user_id=? AND payment_status='pending' LIMIT 1");$s->bind_param('ii',$orderId,$uid);$s->execute();
if($s->get_result()->fetch_assoc()){
    $failed='failed';$status='payment_failed';
    $s=$conn->prepare("UPDATE orders SET payment_status=?,order_status=? WHERE id=?");$s->bind_param('ssi',$failed,$status,$orderId);$s->execute();
    add_order_history($orderId,'payment_failed','Razorpay reported a payment failure.');
}
echo json_encode(['ok'=>true]);
