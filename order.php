<?php require_once __DIR__ . '/includes/config.php';
$id = (int)($_GET['id'] ?? ($_SESSION['last_order_id'] ?? 0));
$uid = (int)($_SESSION['user_id'] ?? 0);
$s = $conn->prepare("SELECT * FROM orders WHERE id=? AND user_id=? LIMIT 1");
$s->bind_param('ii', $id, $uid);
$s->execute();
$o = $s->get_result()->fetch_assoc();
if (!$o) {
    redirect('index.php');
}
$s = $conn->prepare("SELECT oi.*,p.image FROM order_items oi LEFT JOIN products p ON p.id=oi.product_id WHERE oi.order_id=?");
$s->bind_param('i', $id);
$s->execute();
$items = $s->get_result()->fetch_all(MYSQLI_ASSOC); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order <?= e($o['order_number']) ?> - Khaatu Foods</title><?php include 'assets.php'; ?>
</head>

<body><?php include 'header.php'; ?>
    <div class="flat-spacing-3">
        <div class="container">
            <div class="title-success-order text-center">
                <h2 class="title letter-space-1">Thank you for your order!</h2>
                <p><?= $o['order_status'] === 'confirmed' ? 'Your payment has been verified and your order is confirmed.' : 'Your order has been successfully placed.' ?></p>
            </div>
            <div class="box-progress-order">
                <div class="order-progress-item order-code text-center">
                    <div class="title text-caption-01 fw-semibold">Order number</div>
                    <div><?= e($o['order_number']) ?></div>
                </div>
                <div class="order-progress-item order-date text-center">
                    <div class="title text-caption-01 fw-semibold">Order date</div>
                    <div><?= e($o['created_at']) ?></div>
                </div>
                <div class="order-progress-item order-total text-center">
                    <div class="title text-caption-01 fw-semibold">Order total</div>
                    <div><?= money($o['total']) ?></div>
                </div>
                <div class="order-progress-item payment-method text-center">
                    <div class="title text-caption-01 fw-semibold">Payment</div>
                    <div><?= e(strtoupper($o['payment_method'])) ?></div>
                </div>
            </div>
            <div class="row mt-40">
                <div class="col-lg-7">
                    <div class="cart-box order-box mt-5">
                        
                        <div class="title text-body-1 fw-medium ">Order Details</div>
                        <ul class="list-order-product"><?php foreach ($items as $i): ?><li class="order-item fw-medium"><a href="product.php?id=<?= $i['product_id'] ?>" class="img-prd"><img loading="lazy" width="100" height="133" src="admin/uploads-img/<?= e($i['image'] ?? '') ?>" alt="<?= e($i['product_name']) ?>"></a>
                                    <div class="infor-prd"><a href="product.php?id=<?= $i['product_id'] ?>" class="prd_name fw-medium lh-24 link link-underline"><?= e($i['product_name']) ?></a>
                                        <div class="text-caption-01">Quantity: <?= e($i['quantity']) ?></div>
                                    </div><span class="quantity-price text-primary"><?= money($i['line_total']) ?></span>
                                </li><?php endforeach; ?></ul>
                        <div class="last-total h5 fw-medium"><span>Total</span><span class="total-price-order"><?= money($o['total']) ?></span></div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="cart-box mt-5">
                        <div class="title text-body-1 fw-medium">Customer Details</div>
                        <p class="mt-16"><?= e($o['customer_name']) ?><br><?= e($o['email']) ?><br><?= e($o['phone']) ?></p>
                        <p><?= nl2br(e($o['address'])) ?><br><?= e($o['city']) ?>, <?= e($o['state']) ?> - <?= e($o['pincode']) ?><br><?= e($o['country']) ?></p>
                        <p><strong>Order Status:</strong> <?= e($o['order_status']) ?><br><strong>Tracking:</strong> <a href="track-order.php?tracking=<?= urlencode($o['tracking_number']) ?>"><?= e($o['tracking_number'] ?: 'Not assigned') ?></a></p>
                        <a href="return-request.php?order_id=<?= (int)$o['id'] ?>" class="tf-btn btn-stroke w-100 mt-10">Request Return / Replacement</a><a href="index.php" class="tf-btn animate-btn w-100 mt-10">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>