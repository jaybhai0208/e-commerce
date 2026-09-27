<?php require_once __DIR__ . '/includes/config.php';
require_login('view-order.php');
$uid = (int)$_SESSION['user_id'];
$selected = (int)($_GET['id'] ?? 0);
if (!$selected) {
    $q = $conn->prepare("SELECT id FROM orders WHERE user_id=? ORDER BY id DESC LIMIT 1");
    $q->bind_param('i', $uid);
    $q->execute();
    $r = $q->get_result()->fetch_assoc();
    $selected = (int)($r['id'] ?? 0);
}
$orders = [];
$q = $conn->prepare("SELECT id,order_number,tracking_number,total,order_status,payment_status,created_at FROM orders WHERE user_id=? ORDER BY id DESC");
$q->bind_param('i', $uid);
$q->execute();
$orders = $q->get_result()->fetch_all(MYSQLI_ASSOC);
$o = null;
$items = [];
$returns = [];
$history = [];
if ($selected) {
    $q = $conn->prepare("SELECT * FROM orders WHERE id=? AND user_id=? LIMIT 1");
    $q->bind_param('ii', $selected, $uid);
    $q->execute();
    $o = $q->get_result()->fetch_assoc();
    if ($o) {
        $q = $conn->prepare("SELECT oi.*,p.image FROM order_items oi LEFT JOIN products p ON p.id=oi.product_id WHERE oi.order_id=?");
        $q->bind_param('i', $selected);
        $q->execute();
        $items = $q->get_result()->fetch_all(MYSQLI_ASSOC);
        $q = $conn->prepare("SELECT * FROM return_requests WHERE order_id=? AND user_id=? ORDER BY id DESC");
        $q->bind_param('ii', $selected, $uid);
        $q->execute();
        $returns = $q->get_result()->fetch_all(MYSQLI_ASSOC);
        $q = $conn->prepare("SELECT * FROM order_status_history WHERE order_id=? ORDER BY id ASC");
        $q->bind_param('i', $selected);
        $q->execute();
        $history = $q->get_result()->fetch_all(MYSQLI_ASSOC);
    }
} ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>View Orders - Khaatu Foods</title><?php include 'assets.php'; ?><style>
        .orders-card {
            border: 1px solid #eee;
            border-radius: 16px;
            padding: 20px;
            background: #fff;
            box-shadow: 0px 0px 15px gray;
        }

        .status-pill {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 30px;
            background: #f3f4f6;
            text-transform: capitalize
        }

        .track-box {
            border: 1px dashed #ccc;
            padding: 18px;
            border-radius: 14px
        }
        .track_this:hover{
            color:yellow;
        }
    </style>
</head>

<body><?php include 'header.php'; ?><section class="flat-spacing">
        <div class="container">
            <h2 class="mb-30 text-center bg-secondary text-white py-2 rounded">My Orders</h2>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="orders-card">
                        <h5>Order History</h5>
                        <?php if (!$orders): ?><p class="mt-20">No orders found.</p>
                            <?php else: foreach ($orders as $oo): ?>
                                <a href="view-order.php?id=<?= $oo['id'] ?>" class="d-block border-bottom py-3" style="text-decoration:none">
                                    <b><?= e($oo['order_number']) ?></b>
                                    <br>
                                    <small><?= e($oo['created_at']) ?> · <?= money($oo['total']) ?></small>
                                    <br>
                                    <span class="status-pill bg-warning text-dark"><?= e($oo['order_status']) ?></span>
                                </a>



                        <?php endforeach;
                        endif; ?>
                        <h5>Delivery & Payment</h5>
                        <p><?= e($o['customer_name']) ?><br>
                            <?= e($o['phone']) ?><br>
                            <?= e($o['email']) ?></p>
                        <p><?= nl2br(e($o['address'])) ?><br>
                            <?= e($o['city']) ?>, <?= e($o['state']) ?> - <?= e($o['pincode']) ?><br>
                            <?= e($o['country']) ?></p>
                        <p>Payment:
                            <b><?= e(strtoupper($o['payment_method'])) ?>
                            </b> · Payment Status: <b class="bg-warning text-dark border border-warning rounded p-2"><?= e($o['payment_status']) ?></b>
                        </p>
                        <h5>Order Updates</h5>
                        <?php if ($history): foreach ($history as $hh): ?>
                                <div class="border-start ps-3 py-2">
                                    <b><?= e($hh['status']) ?></b>
                                    <small><?= e($hh['created_at'] ?? '') ?></small>
                                    <?php if (!empty($hh['note'])): ?><br>
                                        <small><?= e($hh['note']) ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach;
                        else: ?>
                            <p>No status updates yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-6"><?php if ($o): ?><div class="orders-card">
                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <div>
                                    <h4><?= e($o['order_number']) ?></h4>
                                    <p class="mb-0">Placed on <?= e($o['created_at']) ?></p>
                                </div><span class="status-pill"><?= e($o['order_status']) ?></span>
                            </div>
                            <div class="track-box mt-20"><b>Tracking Number:</b> <?= e($o['tracking_number'] ?: 'Not assigned') ?><br>
                            <a class="track_this" href="track-order.php?tracking=<?= urlencode($o['tracking_number']) ?>" class="link bg-dark text-white rounded p-2">Track this order</a></div>
                            <hr>
                            <h5>Products</h5><?php foreach ($items as $i): ?><div class="d-flex align-items-center gap-3 border-bottom py-3">
                                    <img src="admin/uploads-img/<?= e($i['image'] ?? '') ?>" width="70" height="90" style="object-fit:cover;border-radius:8px" onerror="this.style.display='none'">
                                    <div class="flex-grow-1"><b><?= e($i['product_name']) ?>
                                        </b><br>
                                        <small>Quantity: <?= e($i['quantity']) ?> · Unit: <?= money($i['unit_price']) ?></small>
                                    </div>
                                    <b><?= money($i['line_total']) ?></b>
                                </div><?php endforeach; ?>
                            <div class="d-flex justify-content-between py-3">
                                <b>Total</b><b><?= money($o['total']) ?></b>
                            </div>
                            <hr>
                            <!-- <h5>Delivery & Payment</h5>
                            <p><?= e($o['customer_name']) ?><br>
                                <?= e($o['phone']) ?><br>
                                <?= e($o['email']) ?></p>
                            <p><?= nl2br(e($o['address'])) ?><br>
                                <?= e($o['city']) ?>, <?= e($o['state']) ?> - <?= e($o['pincode']) ?><br>
                                <?= e($o['country']) ?></p>
                            <p>Payment:
                                <b><?= e(strtoupper($o['payment_method'])) ?>
                                </b> · Payment Status: <b class="bg-warning text-dark border border-warning rounded p-2"><?= e($o['payment_status']) ?></b>
                            </p>
                            <h5>Order Updates</h5>
                            <?php if ($history): foreach ($history as $hh): ?>
                                    <div class="border-start ps-3 py-2">
                                        <b><?= e($hh['status']) ?></b>
                                        <small><?= e($hh['created_at'] ?? '') ?></small>
                                        <?php if (!empty($hh['note'])): ?><br>
                                            <small><?= e($hh['note']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach;
                                            else: ?>
                                <p>No status updates yet.</p>
                            <?php endif; ?> -->
                            <h5 class="mt-20">Return</h5>
                            <?php if ($returns): foreach ($returns as $rr): ?>
                                    <div class="alert alert-light border">
                                        <b>Status:</b>
                                        <?= e($rr['status']) ?><br><?= e($rr['reason']) ?>
                                    </div>
                                <?php endforeach;
                                            else: ?>
                                <form method="post" action="return-request.php">
                                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                    <textarea name="reason" class="w-100 text-danger" placeholder="Reason for return" required> </textarea>
                                    <button class="tf-btn btn-stroke mt-10">Request Return</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="orders-card">
                            <p>Select an order to view its complete details.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section><?php include 'footer.php'; ?></body>

</html>