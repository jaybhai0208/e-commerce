<?php require_once __DIR__ . '/includes/config.php';
$o = null;
$history = [];
$key = trim($_REQUEST['tracking'] ?? $_REQUEST['order_number'] ?? '');
if ($key) {
    $s = $conn->prepare("SELECT * FROM orders WHERE tracking_number=? OR order_number=? LIMIT 1");
    $s->bind_param('ss', $key, $key);
    $s->execute();
    $o = $s->get_result()->fetch_assoc();
    if ($o) {
        $s = $conn->prepare("SELECT * FROM order_status_history WHERE order_id=? ORDER BY id ASC");
        $s->bind_param('i', $o['id']);
        $s->execute();
        $history = $s->get_result()->fetch_all(MYSQLI_ASSOC);
    }
} ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Track Order - Khaatu Foods</title><?php include 'assets.php'; ?><style>
        .track-panel {
            border: 1px solid #eee;
            border-radius: 16px;
            padding: 24px;
            background: #fff;
            box-shadow: 0px 0px 16px gray;
        }

        .track-step {
            border-left: 3px solid #111;
            padding: 8px 0 8px 16px;
            margin-left: 8px
        }
    </style>
</head>

<body><?php include 'header.php'; ?><section class="flat-spacing">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="track-panel">
                        <h3 class="text-center mb-20 bg-secondary text-white py-2 rounded">Track Your Order</h3>
                        <form method="post" class="form-log">
                            <fieldset class="tf-field">
                                <label class="tf-lable">Tracking Number or Order Number</label>
                            <input type="text" name="tracking" value="<?= e($key) ?>" required placeholder="KFT-...">
                        </fieldset>
                        <button class="tf-btn animate-btn w-100">Track</button>
                        </form>
                        <?php if ($key): ?>
                            
                            <div class="mt-30"><?php if ($o): ?><h5><?= e($o['order_number']) ?></h5>
                                    <p>Tracking Number: <b><?= e($o['tracking_number'] ?: 'Not assigned') ?></b><br>Status: <b><?= e($o['order_status']) ?></b><br>Total: <b><?= money($o['total']) ?></b></p>
                                    <h5 class="mt-20">Tracking History</h5><?php if ($history): foreach ($history as $hh): ?><div class="track-step"><b><?= e($hh['status']) ?></b><br><small><?= e($hh['created_at'] ?? '') ?></small><?php if (!empty($hh['note'])): ?><br><?= e($hh['note']) ?><?php endif; ?></div><?php endforeach;
                                                                                                                                                                                                                                                                                                else: ?><p>Order placed successfully. More updates will appear here.</p><?php endif; ?><?php else: ?><div class="alert alert-danger">Order or tracking number not found.</div><?php endif; ?>
                            </div><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section><?php include 'footer.php'; ?></body>

</html>