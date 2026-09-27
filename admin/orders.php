<?php include 'authentication.php';
require_once 'config/dbcon.php'; ?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Orders</title><?php include 'assets.php'; ?>
</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper"><?php include 'header.php';
                                include 'aside.php'; ?><main class="app-main">
            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between">
                    <h2>Orders</h2><a href="../track-order.php" class="btn btn-outline-secondary">Guest Tracking</a>
                </div>
                <div class="card mt-3">
                    <div class="card-body table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody><?php $r = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
                                    while ($o = mysqli_fetch_assoc($r)): ?><tr>
                                        <td><b><?= htmlspecialchars($o['order_number']) ?></b></td>
                                        <td><?= htmlspecialchars($o['customer_name']) ?><br><small><?= htmlspecialchars($o['phone']) ?></small></td>
                                        <td>₹<?= number_format($o['total'], 2) ?></td>
                                        <td><?= htmlspecialchars($o['payment_status']) ?></td>
                                        <td><span class="badge bg-primary"><?= htmlspecialchars($o['order_status']) ?></span></td>
                                        <td><?= htmlspecialchars($o['created_at']) ?></td>
                                        <td><a class="btn btn-sm btn-primary" href="order-view.php?id=<?= $o['id'] ?>">View</a></td>
                                    </tr><?php endwhile; ?></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>