<?php include 'authentication.php';
require_once 'config/dbcon.php';
if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $allowed = ['payment_pending', 'payment_failed', 'placed', 'confirmed', 'packed', 'shipped', 'delivered', 'cancelled', 'returned'];
    $status = in_array($_POST['status'], $allowed) ? $_POST['status'] : 'placed';
    $pay = in_array($_POST['payment_status'], ['pending', 'paid', 'failed', 'refunded']) ? $_POST['payment_status'] : 'pending';
    $old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT order_status FROM orders WHERE id=$id"));
    if ($old && $old['order_status'] !== 'cancelled' && $status === 'cancelled') {
        $rr = mysqli_query($conn, "SELECT product_id,quantity FROM order_items WHERE order_id=$id");
        while ($ii = mysqli_fetch_assoc($rr)) mysqli_query($conn, "UPDATE products SET quantity=quantity+" . (int)$ii['quantity'] . " WHERE id=" . (int)$ii['product_id']);
    }
    $st = mysqli_prepare($conn, "UPDATE orders SET order_status=?,payment_status=? WHERE id=?");
    mysqli_stmt_bind_param($st, 'ssi', $status, $pay, $id);
    mysqli_stmt_execute($st);
    mysqli_query($conn, "INSERT INTO order_status_history(order_id,status,note) VALUES($id,'$status','Updated from admin panel')");
    header("Location: order-view.php?id=$id");
    exit;
}
if (isset($_POST['return_id'])) {
    $id = (int)$_POST['return_id'];
    $newStatus = in_array($_POST['return_status'] ?? '', ['requested', 'approved', 'rejected', 'completed'], true) ? $_POST['return_status'] : 'requested';
    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM return_requests WHERE id=$id LIMIT 1"));
    if ($r) {
        $oldStatus = $r['status'];
        $st = mysqli_prepare($conn, "UPDATE return_requests SET status=? WHERE id=?");
        mysqli_stmt_bind_param($st, 'si', $newStatus, $id);
        mysqli_stmt_execute($st);
        if ($newStatus === 'completed' && $oldStatus !== 'completed') {
            if (!empty($r['product_id'])) {
                mysqli_query($conn, "UPDATE products SET quantity=quantity+" . (int)$r['quantity'] . " WHERE id=" . (int)$r['product_id']);
            }
            mysqli_query($conn, "UPDATE orders SET order_status='returned' WHERE id=" . (int)$r['order_id']);
            mysqli_query($conn, "INSERT INTO order_status_history(order_id,status,note) VALUES(" . (int)$r['order_id'] . ",'returned','Return completed for " . mysqli_real_escape_string($conn, (string)$r['product_name']) . "')");
        } elseif ($newStatus !== $oldStatus) {
            mysqli_query($conn, "INSERT INTO order_status_history(order_id,status,note) VALUES(" . (int)$r['order_id'] . ",'return_requested','Return request status changed to " . mysqli_real_escape_string($conn, $newStatus) . "')");
        }
        header("Location: order-view.php?id=" . (int)$r['order_id']);
        exit;
    }
}
header('Location: orders.php');
