<?php
require_once 'config/ecommerce.php';
$email = strtolower(trim($_POST['email'] ?? ''));
$next = trim($_POST['next'] ?? 'my-orders.php');
if (!is_gmail($email)) die('Please use a valid @gmail.com address.');
if (!preg_match('/^[a-zA-Z0-9._\/-]+\.php(?:\?.*)?$/', $next) || strpos($next, '://') !== false) $next = 'my-orders.php';
$name = 'Customer';
$st = mysqli_prepare($conn, "SELECT name FROM users WHERE email=? LIMIT 1");
mysqli_stmt_bind_param($st, 's', $email);
mysqli_stmt_execute($st);
$u = mysqli_fetch_assoc(mysqli_stmt_get_result($st));
if ($u && !empty($u['name'])) $name = $u['name'];
$otp = (string)random_int(100000, 999999);
$hash = password_hash($otp, PASSWORD_DEFAULT);
$expires = date('Y-m-d H:i:s', time() + 600);
$del = mysqli_prepare($conn, "DELETE FROM email_otps WHERE email=? AND purpose='login'");
mysqli_stmt_bind_param($del, 's', $email);
mysqli_stmt_execute($del);
$purpose = 'login';
$q = mysqli_prepare($conn, "INSERT INTO email_otps(email,purpose,otp_hash,expires_at) VALUES(?,?,?,?)");
mysqli_stmt_bind_param($q, 'ssss', $email, $purpose, $hash, $expires);
if (!mysqli_stmt_execute($q)) die('Could not create OTP.');
$sent = send_otp_email($email, $name, $otp, 'login');
$_SESSION['otp_email'] = $email;
$_SESSION['otp_purpose'] = 'login';
$_SESSION['otp_next'] = $next;
if (!$sent) {
    $_SESSION['dev_otp'] = $otp;
    die('OTP email could not be sent. Please check SMTP configuration.');
}
header('Location: verify-otp.php?purpose=login');
exit;
