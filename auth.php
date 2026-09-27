
<?php
require_once __DIR__ . '/includes/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !check_csrf($_POST['csrf'] ?? '')) {
    http_response_code(400);
    exit('Invalid request');
}
$action = $_POST['action'] ?? '';
$isJson = in_array($action, ['send_register_otp', 'verify_register_otp', 'complete_register'], true);
if ($isJson) {
    header('Content-Type: application/json; charset=UTF-8');
}
function json_out($ok, $message, $extra = [])
{
    echo json_encode(array_merge(['ok' => $ok, 'message' => $message], $extra));
    exit;
}
if ($action === 'login') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $pass = $_POST['password'] ?? '';
    $s = $conn->prepare("SELECT id,name,phone,email,password,role_as,status FROM users WHERE email=? LIMIT 1");
    $s->bind_param('s', $email);
    $s->execute();
    $u = $s->get_result()->fetch_assoc();
    if (!$u || (int)$u['status'] !== 1 || !password_verify($pass, $u['password'])) {
        $_SESSION['flash'] = 'Invalid email or password.';
        redirect('login.php');
    }
    session_regenerate_id(true);
    $_SESSION['user_id'] = $u['id'];
    $_SESSION['auth'] = true;
    $_SESSION['role_as'] = (int)$u['role_as'];
    merge_guest_cart_into_user((int)$u['id']);
    $to = $_SESSION['after_login'] ?? 'index.php';
    unset($_SESSION['after_login']);
    redirect($to);
}
if ($action === 'send_register_otp') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    if ($name === '' || $phone === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        json_out(false, 'Please enter valid name, phone and email.');
    }
    $s = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $s->bind_param('s', $email);
    $s->execute();
    if ($s->get_result()->num_rows > 0) {
        json_out(false, 'Email is already registered. Please login.');
    }
    $otp = (string)random_int(100000, 999999);
    $hash = password_hash($otp, PASSWORD_DEFAULT);
    $expires = date('Y-m-d H:i:s', time() + 600);
    $s = $conn->prepare("DELETE FROM email_otps WHERE email=? AND purpose='register'");
    $s->bind_param('s', $email);
    $s->execute();
    $s = $conn->prepare("INSERT INTO email_otps(email,purpose,otp_hash,expires_at,attempts) VALUES(?,'register',?,?,0)");
    $s->bind_param('sss', $email, $hash, $expires);
    if (!$s->execute()) {
        json_out(false, 'Could not create OTP. Please try again.');
    }
    $_SESSION['register_pending'] = [
        'name' => $name,
        'phone' => $phone,
        'email' => $email
    ];
    $_SESSION['register_otp_sent_at'] = time();
    unset($_SESSION['register_email_verified']);
    unset($_SESSION['smtp_error']);
    $sent = send_otp_email($email, $name, $otp, 'register');
    if (!$sent) {
        $s = $conn->prepare("DELETE FROM email_otps WHERE email=? AND purpose='register'");
        $s->bind_param('s', $email);
        $s->execute();
        $smtpError = trim((string)($_SESSION['smtp_error'] ?? ''));
        unset($_SESSION['smtp_error']);
        json_out(false, $smtpError !== '' ? 'OTP email could not be sent: ' . $smtpError : 'OTP email could not be sent. Please check SMTP configuration.');
    }
    json_out(true, 'OTP sent to your email.');
}
if ($action === 'verify_register_otp') {
    $pending = $_SESSION['register_pending'] ?? null;
    $otp = trim($_POST['otp'] ?? '');
    if (!$pending) {
        json_out(false, 'Registration session expired. Please start again.');
    }
    if (!preg_match('/^[0-9]{6}$/', $otp)) {
        json_out(false, 'Enter the 6-digit OTP.');
    }
    $s = $conn->prepare("SELECT id,otp_hash,expires_at,attempts FROM email_otps WHERE email=? AND purpose='register' LIMIT 1");
    $s->bind_param('s', $pending['email']);
    $s->execute();
    $row = $s->get_result()->fetch_assoc();
    if (!$row) {
        json_out(false, 'OTP not found. Please resend a new OTP.');
    }
    if (strtotime($row['expires_at']) < time()) {
        $s = $conn->prepare("DELETE FROM email_otps WHERE id=?");
        $s->bind_param('i', $row['id']);
        $s->execute();
        json_out(false, 'OTP expired. Please resend.');
    }
    if ((int)$row['attempts'] >= 5) {
        json_out(false, 'Too many incorrect attempts. Please resend the OTP.');
    }
    $s = $conn->prepare("UPDATE email_otps SET attempts=attempts+1 WHERE id=?");
    $s->bind_param('i', $row['id']);
    $s->execute();
    if (!password_verify($otp, $row['otp_hash'])) {
        json_out(false, 'Invalid OTP.');
    }
    $s = $conn->prepare("DELETE FROM email_otps WHERE id=?");
    $s->bind_param('i', $row['id']);
    $s->execute();
    $_SESSION['register_email_verified'] = true;
    json_out(true, 'Email verified. You can now create your password.');
}
if ($action === 'complete_register') {
    $pending = $_SESSION['register_pending'] ?? null;
    if (!$pending || empty($_SESSION['register_email_verified'])) {
        json_out(false, 'Please verify the email OTP first.');
    }
    $p = $_POST['password'] ?? '';
    $pc = $_POST['password_confirmation'] ?? '';
    if (strlen($p) < 8) {
        json_out(false, 'Password must be at least 8 characters.');
    }
    if ($p !== $pc) {
        json_out(false, 'Both passwords must match.');
    }
    $s = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $s->bind_param('s', $pending['email']);
    $s->execute();
    if ($s->get_result()->num_rows > 0) {
        json_out(false, 'Email is already registered. Please login.');
    }
    $hash = password_hash($p, PASSWORD_DEFAULT);
    $role = 0;
    $status = 1;
    $s = $conn->prepare("INSERT INTO users(name,phone,email,password,role_as,status) VALUES(?,?,?,?,?,?)");
    $s->bind_param('ssssii', $pending['name'], $pending['phone'], $pending['email'], $hash, $role, $status);
    if (!$s->execute()) {
        json_out(false, 'Could not create account. Please try again.');
    }
    $uid = $s->insert_id;
    session_regenerate_id(true);
    $_SESSION['user_id'] = $uid;
    $_SESSION['auth'] = true;
    $_SESSION['role_as'] = 0;
    unset($_SESSION['register_pending'], $_SESSION['register_email_verified'], $_SESSION['register_otp_sent_at'], $_SESSION['smtp_error']);
    merge_guest_cart_into_user($uid);
    json_out(true, 'Registration successful.', ['redirect' => 'register-success.php']);
}
http_response_code(400);
echo 'Unknown action';
