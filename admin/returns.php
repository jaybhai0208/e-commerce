<?php
include 'authentication.php';
require_once 'config/dbcon.php';
$r=mysqli_query($conn,"SELECT rr.*,o.order_number,o.total AS order_total,o.payment_method,o.payment_status,o.order_status,o.customer_name,o.phone,o.email FROM return_requests rr JOIN orders o ON o.id=rr.order_id ORDER BY rr.id DESC");
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Return Requests</title><?php include 'assets.php'; ?></head>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary"><div class="app-wrapper"><?php include 'header.php'; include 'aside.php'; ?><main class="app-main"><div class="container-fluid p-4">
<div class="d-flex justify-content-between align-items-center"><h2>Return Requests</h2><a href="orders.php" class="btn btn-outline-secondary">Orders</a></div>
<div class="card mt-3"><div class="card-body table-responsive"><table class="table table-striped align-middle"><thead><tr><th>Order</th><th>Customer</th><th>Product</th><th>Qty</th><th>Price</th><th>Reason</th><th>Return Status</th><th>Payment</th><th>Date</th><th></th></tr></thead><tbody>
<?php if(mysqli_num_rows($r)): while($rr=mysqli_fetch_assoc($r)): ?>
<tr>
<td><a href="order-view.php?id=<?= (int)$rr['order_id'] ?>"><?= htmlspecialchars($rr['order_number']) ?></a></td>
<td><?= htmlspecialchars($rr['customer_name'] ?? '') ?><br><small><?= htmlspecialchars($rr['phone'] ?? '') ?><br><?= htmlspecialchars($rr['email'] ?? '') ?></small></td>
<td><?= htmlspecialchars($rr['product_name'] ?? 'Full order') ?></td><td><?= (int)$rr['quantity'] ?></td><td>₹<?= number_format((float)$rr['unit_price'],2) ?></td>
<td style="min-width:220px"><?= nl2br(htmlspecialchars($rr['reason'])) ?></td>
<td><span class="badge bg-warning text-dark"><?= htmlspecialchars($rr['status']) ?></span></td>
<td><?= htmlspecialchars($rr['payment_status']) ?><br><small><?= htmlspecialchars(strtoupper($rr['payment_method'])) ?></small></td>
<td><?= htmlspecialchars($rr['created_at']) ?></td>
<td><a class="btn btn-sm btn-primary" href="order-view.php?id=<?= (int)$rr['order_id'] ?>">Review</a></td>
</tr>
<?php endwhile; else: ?><tr><td colspan="10" class="text-center">No return requests found.</td></tr><?php endif; ?>
</tbody></table></div></div>
</div></main></div></body></html>
