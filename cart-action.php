<?php
require_once __DIR__.'/includes/config.php';
if($_SERVER['REQUEST_METHOD']!=='POST'||!check_csrf($_POST['csrf']??'')){http_response_code(400);exit('Invalid request');}
$action=$_POST['action']??'';
$uid=(int)($_SESSION['user_id']??0);

if($action==='add'){
    $pid=(int)($_POST['product_id']??0); $qty=max(1,(int)($_POST['quantity']??1));
    $s=$conn->prepare("SELECT quantity,status FROM products WHERE id=?");$s->bind_param('i',$pid);$s->execute();$p=$s->get_result()->fetch_assoc();
    if(!$p||!(int)$p['status']){$_SESSION['flash']='Product unavailable.';redirect($_SERVER['HTTP_REFERER']??'index.php');}
    $qty=min($qty,(int)$p['quantity']); if($qty<1){$_SESSION['flash']='Product is out of stock.';redirect($_SERVER['HTTP_REFERER']??'index.php');}
    if($uid){
        $s=$conn->prepare("SELECT id,quantity FROM cart WHERE user_id=? AND product_id=?");$s->bind_param('ii',$uid,$pid);$s->execute();$row=$s->get_result()->fetch_assoc();
        if($row){$new=min((int)$p['quantity'],$row['quantity']+$qty);$u=$conn->prepare("UPDATE cart SET quantity=?,updated_at=NOW() WHERE id=?");$u->bind_param('ii',$new,$row['id']);$u->execute();}
        else {$i=$conn->prepare("INSERT INTO cart(user_id,product_id,quantity) VALUES(?,?,?)");$i->bind_param('iii',$uid,$pid,$qty);$i->execute();}
    }else{
        if(!isset($_SESSION['guest_cart'])) $_SESSION['guest_cart']=[];
        $old=(int)($_SESSION['guest_cart'][$pid]??0);
        $_SESSION['guest_cart'][$pid]=min((int)$p['quantity'],$old+$qty);
    }
    redirect($_SERVER['HTTP_REFERER']??'view-cart.php');
}

$cid=(int)($_POST['cart_id']??0);
$pid=(int)($_POST['product_id']??0);
if($action==='update'){
    $qty=max(1,(int)($_POST['quantity']??1));
    if($uid){
        $s=$conn->prepare("UPDATE cart c JOIN products p ON p.id=c.product_id SET c.quantity=LEAST(?,p.quantity),c.updated_at=NOW() WHERE c.id=? AND c.user_id=? AND p.status=1");$s->bind_param('iii',$qty,$cid,$uid);$s->execute();
    }else{
        $s=$conn->prepare("SELECT quantity FROM products WHERE id=? AND status=1");$s->bind_param('i',$pid);$s->execute();$p=$s->get_result()->fetch_assoc();
        if($p){$_SESSION['guest_cart'][$pid]=min($qty,(int)$p['quantity']);}
    }
    redirect('view-cart.php');
}
if($action==='remove'){
    if($uid){$s=$conn->prepare("DELETE FROM cart WHERE id=? AND user_id=?");$s->bind_param('ii',$cid,$uid);$s->execute();}
    else unset($_SESSION['guest_cart'][$pid]);
    redirect('view-cart.php');
}
redirect('view-cart.php');
