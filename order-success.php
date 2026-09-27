<?php require_once 'config/ecommerce.php';
$no = trim($_GET['order'] ?? '');
$o = null;
$items = [];
if ($no) {
    $st = mysqli_prepare($conn, "SELECT * FROM orders WHERE order_number=? LIMIT 1");
    mysqli_stmt_bind_param($st, 's', $no);
    mysqli_stmt_execute($st);
    $o = mysqli_fetch_assoc(mysqli_stmt_get_result($st));
    if ($o) {
        $r = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id=" . (int)$o['id']);
        while ($i = mysqli_fetch_assoc($r)) $items[] = $i;
    }
} ?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Order Confirmed</title>
    <style>
        .order-summary {
            border: 1px solid #eee;
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .06)
        }

        .order-summary .summary-head {
            padding: 24px;
            border-bottom: 1px solid #eee
        }

        .order-summary .summary-row {
            padding: 14px 24px;
            border-bottom: 1px solid #f0f0f0
        }

        .order-summary .total-row {
            font-size: 20px;
            font-weight: 700;
            background: #fafafa
        }

        .order-meta {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px
        }

        .order-meta>div {
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 12px
        }

        .address-box {
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 14px;
            height: 100%
        }

        @media(max-width:767px) {
            .order-meta {
                grid-template-columns: 1fr 1fr
            }
        }
    </style><?php include 'assets.php'; ?>
</head>

<body><?php include 'header.php'; ?><div class="container py-5">
        <div class="order-summary">
            <div class="summary-head">
                <h1 class="text-success mb-2">Order Confirmed Successfully!</h1>
                <p class="mb-0">Thank you. Your order has been placed and confirmation email has been sent when SMTP is configured.</p>
            </div>
            <?php if ($o): ?><div class="summary-head">
                    <div class="order-meta">
                        <div><small>Order Number</small><br><b><?= e($o['order_number']) ?></b></div>
                        <div><small>Tracking Number</small><br><b><?= e($o['tracking_number'] ?: 'Not assigned') ?></b></div>
                        <div><small>Payment</small><br><b><?= e(strtoupper($o['payment_method'])) ?></b></div>
                        <div><small>Total</small><br><b><?= money($o['total']) ?></b></div>
                    </div>
                </div>
                <div class="row g-4 p-4">
                    <div class="col-lg-7">
                        <div>
                            <h5 class="mb-3">Order Summary</h5><?php foreach ($items as $i): ?><div class="summary-row d-flex justify-content-between"><span><b><?= e($i['product_name']) ?></b><br><small>Qty: <?= e($i['quantity']) ?> × <?= money($i['unit_price']) ?></small></span><b><?= money($i['line_total']) ?></b></div>
                                <?php endforeach; ?>
                                <div class="summary-row total-row d-flex justify-content-between"><span>Grand Total</span>
                                <span><?= money($o['total']) ?></span></div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="address-box">
                            <h5>Delivery Address</h5>
                            <p><?= e($o['customer_name']) ?><br><?= e($o['phone']) ?><br><?= e($o['email']) ?></p>
                            <p><?= nl2br(e($o['address'])) ?><br><?= e($o['city']) ?>, <?= e($o['state']) ?> - <?= e($o['pincode']) ?><br><?= e($o['country']) ?></p>
                        </div>
                    </div>
                </div>
                <div class="summary-head d-flex flex-wrap gap-2"><a class="tf-btn animate-btn" href="track-order.php?tracking=<?= urlencode($o['tracking_number']) ?>">Track Order</a><a class="tf-btn btn-stroke" href="view-order.php">View Orders</a></div>
            <?php else: ?><div class="summary-head">
                    <p>Order number: <b><?= e($no) ?></b></p>
                </div><?php endif; ?>
        </div>
    </div><?php include 'footer.php'; ?></body>

</html>