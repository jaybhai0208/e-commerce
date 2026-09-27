<?php
require_once 'config/ecommerce.php';
$purpose='login';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email=$_SESSION['otp_email']??''; $otp=trim($_POST['otp']??''); $next=$_SESSION['otp_next']??'my-orders.php';
    if(!is_gmail($email)||!preg_match('/^\d{6}$/',$otp)) die('Invalid OTP.');
    $st=mysqli_prepare($conn,"SELECT * FROM email_otps WHERE email=? AND purpose='login' AND verified_at IS NULL ORDER BY id DESC LIMIT 1"); mysqli_stmt_bind_param($st,'s',$email); mysqli_stmt_execute($st); $row=mysqli_fetch_assoc(mysqli_stmt_get_result($st));
    if(!$row || strtotime($row['expires_at'])<time() || !password_verify($otp,$row['otp_hash'])) die('Invalid or expired OTP. Please request a new OTP.');
    mysqli_query($conn,"UPDATE email_otps SET verified_at=NOW() WHERE id=".(int)$row['id']);
    $u=null; $st=mysqli_prepare($conn,"SELECT * FROM users WHERE email=? LIMIT 1"); mysqli_stmt_bind_param($st,'s',$email); mysqli_stmt_execute($st); $u=mysqli_fetch_assoc(mysqli_stmt_get_result($st));
    if(!$u){ $name='Customer'; $blank=password_hash(bin2hex(random_bytes(16)),PASSWORD_DEFAULT); $phone=''; $ins=mysqli_prepare($conn,"INSERT INTO users(name,phone,email,password,role_as) VALUES(?,?,?,?,0)"); mysqli_stmt_bind_param($ins,'ssss',$name,$phone,$email,$blank); mysqli_stmt_execute($ins); $uid=mysqli_insert_id($conn); } else { $uid=(int)$u['id']; $name=$u['name']; }
    $_SESSION['customer_auth']=true; $_SESSION['customer_id']=$uid; $_SESSION['customer_email']=$email; $_SESSION['customer_name']=$name;
    unset($_SESSION['otp_email'],$_SESSION['otp_purpose'],$_SESSION['dev_otp']);
    header('Location: '.$next); exit;
}
$email=$_SESSION['otp_email']??''; $dev=$_SESSION['dev_otp']??'';
if(!is_gmail($email)){header('Location: login.php');exit;}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Verify OTP | JAY ECOM</title><?php include 'assets.php';?></head><body><?php include 'header.php';?><div class="container py-5"><div class="row justify-content-center"><div class="col-md-6 col-lg-5"><div class="card shadow border-0"><div class="card-body p-4 p-lg-5 text-center"><h2>Verify your Gmail</h2><p class="text-muted">We sent a 6-digit OTP to <b><?=e($email)?></b>.</p><form method="post"><input name="otp" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" class="form-control form-control-lg text-center" style="letter-spacing:8px" placeholder="000000" required><button class="btn btn-primary btn-lg w-100 mt-4">Verify & Sign In</button></form><?php if($dev):?><div class="alert alert-warning mt-4 text-start"><b>Local development:</b> Gmail SMTP is not configured, so the OTP is shown here: <strong><?=e($dev)?></strong></div><?php endif;?></div></div></div></div></div><?php include 'footer.php';?></body></html>
