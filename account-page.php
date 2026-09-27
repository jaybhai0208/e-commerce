<?php require_once __DIR__ . '/includes/config.php';
require_login('account-page.php');
$u = current_user();
$s = $conn->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY id DESC");
$s->bind_param('i', $u['id']);
$s->execute();
$orders = $s->get_result()->fetch_all(MYSQLI_ASSOC); ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>My Account - Khaatu Foods</title><?php include 'assets.php'; ?>
</head>

<body>
    <?php include 'header.php'; ?>
    <section class="flat-spacing">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pb-5">
                        <h2 class="text-center py-3 bg-secondary text-white rounded">My Account</h2>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cart-box">
                        <h5><?= e($u['name']) ?></h5>
                        <p><?= e($u['email']) ?><br><?= e($u['phone']) ?></p>
                        <a href="logout.php"
                            class="tf-btn btn-stroke w-100"
                            onclick="return confirm('Are you sure you want to logout?');">
                            Logout
                        </a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="cart-box">
                        <h5 class="title mb-20">My Orders</h5><?php if (!$orders): ?><p>No orders yet.</p><?php else: ?><div class="table-responsive">
                                <table class="tf-table-page-cart w-100">
                                    <thead>
                                        <tr>
                                            <th>Order</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody><?php foreach ($orders as $o): ?><tr>
                                                <td><?= e($o['order_number']) ?></td>
                                                <td><?= money($o['total']) ?></td>
                                                <td><?= e($o['order_status']) ?></td>
                                                <td><a href="order.php?id=<?= $o['id'] ?>" class="tf-btn-line-3">View</a></td>
                                            </tr><?php endforeach; ?></tbody>
                                </table>
                            </div><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
</body>

</html>