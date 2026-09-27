<?php include 'authentication.php';
require_once 'config/dbcon.php';
$id = (int)($_GET['id'] ?? 0);
$o = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM orders WHERE id=$id"));
if (!$o) die('Order not found');
$items = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id=$id");
$returns = mysqli_query($conn, "SELECT * FROM return_requests WHERE order_id=$id ORDER BY id DESC"); ?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Order <?= htmlspecialchars($o['order_number']) ?></title><?php include 'assets.php'; ?>
</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper"><?php include 'header.php';
                                include 'aside.php'; ?><main class="app-main">
            <div class="container-fluid p-4"><a href="orders.php">← Orders</a>
                <h2 class="mt-2"><?= htmlspecialchars($o['order_number']) ?></h2>
                <p><b>Tracking:</b> <?= htmlspecialchars($o['tracking_number']) ?> | <b>Payment:</b> <?= htmlspecialchars(strtoupper($o['payment_method'])) ?></p>
                <div class="row">
                    <div class="col-lg-7">
                        <div class="card card-body">
                            <h5>Items</h5><?php while ($i = mysqli_fetch_assoc($items)): ?><div class="d-flex justify-content-between border-bottom py-2"><span><?= htmlspecialchars($i['product_name']) ?> × <?= $i['quantity'] ?></span><b>₹<?= number_format($i['line_total'], 2) ?></b></div><?php endwhile; ?><h4 class="text-end mt-3">Total ₹<?= number_format($o['total'], 2) ?></h4>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="card card-body">
                            <h5>Customer</h5>
                            <p><?= htmlspecialchars($o['customer_name']) ?><br><?= htmlspecialchars($o['phone']) ?><br><?= htmlspecialchars($o['email']) ?><br><?= nl2br(htmlspecialchars($o['address'])) ?><br><?= htmlspecialchars($o['city']) ?>, <?= htmlspecialchars($o['state']) ?> - <?= htmlspecialchars($o['pincode']) ?></p>
                            <form method="post" action="order-code.php"><input type="hidden" name="id" value="<?= $id ?>"><label>Status</label><select name="status" class="form-select mb-2"><?php foreach (['payment_pending', 'payment_failed', 'placed', 'confirmed', 'packed', 'shipped', 'delivered', 'cancelled', 'returned'] as $s): ?><option <?= $o['order_status'] === $s ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?></select><label>Payment Status</label><select name="payment_status" class="form-select mb-2"><?php foreach (['pending', 'paid', 'failed', 'refunded'] as $s): ?><option <?= $o['payment_status'] === $s ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?></select><button class="btn btn-primary w-100">Update Order</button></form>
                        </div>
                        <div class="card card-body mt-3">
                            <h5>Return Requests</h5><?php if (mysqli_num_rows($returns)): while ($rr = mysqli_fetch_assoc($returns)): ?>
<p class="border-bottom pb-2">
<b>Status:</b> <?= htmlspecialchars($rr['status']) ?><br>
<b>Customer:</b> <?= htmlspecialchars($o['customer_name']) ?> · <?= htmlspecialchars($o['phone']) ?> · <?= htmlspecialchars($o['email']) ?><br>
<b>Address:</b> <?= nl2br(htmlspecialchars($o['address'])) ?>, <?= htmlspecialchars($o['city']) ?>, <?= htmlspecialchars($o['state']) ?> - <?= htmlspecialchars($o['pincode']) ?><br>
<b>Product:</b> <?= htmlspecialchars($rr['product_name'] ?? 'Full order') ?><br>
<b>Price:</b> ₹<?= number_format((float)($rr['unit_price'] ?? 0),2) ?> · <b>Qty:</b> <?= (int)($rr['quantity'] ?? 1) ?><br>
<b>Reason:</b> <?= nl2br(htmlspecialchars($rr['reason'])) ?><br>
<small>Requested: <?= htmlspecialchars($rr['created_at']) ?></small>
</p>
                                    <form method="post" action="order-code.php"><input type="hidden" name="return_id" value="<?= $rr['id'] ?>"><select name="return_status" class="form-select mb-2">
                                            <option>requested</option>
                                            <option>approved</option>
                                            <option>rejected</option>
                                            <option>completed</option>
                                        </select><button class="btn btn-warning btn-sm">Update Return</button></form><?php endwhile;
                                                                                                                else: ?>No return request.<?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>