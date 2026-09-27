<?php
$envFile = __DIR__ . '/../.env';
if (is_file($envFile) && is_readable($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) continue;
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        if ($value !== '' && (($value[0] === '"' && substr($value, -1) === '"') || ($value[0] === "'" && substr($value, -1) === "'"))) {
            $value = substr($value, 1, -1);
        }
        if ($key !== '') {
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
        }
    }
}
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'samesite' => 'Lax'
    ]);
    session_start();
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';
$dbName = getenv('DB_NAME') ?: 'jay_ecom';
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
$conn->set_charset('utf8mb4');
define('ORDER_TO_EMAIL', getenv('ORDER_TO_EMAIL') ?: '');
define('APP_NAME', 'Khaatu Foods');
define('CURRENCY', '₹');
function e($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function redirect($url){header('Location: '.$url);exit;}
function csrf_token(){if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function check_csrf($token){return is_string($token)&&hash_equals($_SESSION['csrf']??'',$token);}
function current_user(){if(empty($_SESSION['user_id']))return null;global $conn;$s=$conn->prepare("SELECT id,name,phone,email,role_as FROM users WHERE id=? AND status=1 LIMIT 1");$s->bind_param('i',$_SESSION['user_id']);$s->execute();return $s->get_result()->fetch_assoc()?:null;}
function require_login($return='checkout.php'){if(!current_user()){$_SESSION['after_login']=$return;redirect('login.php');}}
function money($n){return CURRENCY.number_format((float)$n,2);}
function guest_cart(){if(!isset($_SESSION['guest_cart'])||!is_array($_SESSION['guest_cart']))$_SESSION['guest_cart']=[];return $_SESSION['guest_cart'];}
function guest_cart_count(){$total=0;foreach(guest_cart() as $qty)$total+=(int)$qty;return $total;}
function merge_guest_cart_into_user($userId){global $conn;$guest=guest_cart();if(!$guest)return;foreach($guest as $pid=>$qty){$pid=(int)$pid;$qty=max(1,(int)$qty);$s=$conn->prepare("SELECT quantity,status FROM products WHERE id=?");$s->bind_param('i',$pid);$s->execute();$p=$s->get_result()->fetch_assoc();if(!$p||!(int)$p['status']||(int)$p['quantity']<1)continue;$qty=min($qty,(int)$p['quantity']);$s=$conn->prepare("SELECT id,quantity FROM cart WHERE user_id=? AND product_id=?");$s->bind_param('ii',$userId,$pid);$s->execute();$row=$s->get_result()->fetch_assoc();if($row){$new=min((int)$p['quantity'],(int)$row['quantity']+$qty);$u=$conn->prepare("UPDATE cart SET quantity=?,updated_at=NOW() WHERE id=?");$u->bind_param('ii',$new,$row['id']);$u->execute();}else{$i=$conn->prepare("INSERT INTO cart(user_id,product_id,quantity) VALUES(?,?,?)");$i->bind_param('iii',$userId,$pid,$qty);$i->execute();}}unset($_SESSION['guest_cart']);}
function cart_items($userId=null){global $conn;if($userId){$s=$conn->prepare("SELECT c.id cart_id,c.product_id,c.quantity,p.name,p.slug,p.price,p.offerprice,p.tax,p.quantity stock,p.image FROM cart c JOIN products p ON p.id=c.product_id WHERE c.user_id=? AND p.status=1 ORDER BY c.id DESC");$s->bind_param('i',$userId);$s->execute();return $s->get_result()->fetch_all(MYSQLI_ASSOC);}$guest=guest_cart();if(!$guest)return [];$rows=[];foreach(array_keys($guest) as $pid){$pid=(int)$pid;$s=$conn->prepare("SELECT id cart_id,id product_id,name,slug,price,offerprice,tax,quantity stock,image FROM products WHERE id=? AND status=1 LIMIT 1");$s->bind_param('i',$pid);$s->execute();$r=$s->get_result()->fetch_assoc();if($r){$r['quantity']=(int)$guest[$pid];$rows[]=$r;}}return $rows;}
function cart_totals($userId=null){$items=cart_items($userId);$subtotal=0;foreach($items as &$i){$i['unit_price']=(float)($i['offerprice']>0?$i['offerprice']:$i['price']);$i['line_total']=$i['unit_price']*$i['quantity'];$subtotal+=$i['line_total'];}return [$items,$subtotal];}
function smtp_error($message){$message=trim(preg_replace('/\s+/',' ',$message));if(strlen($message)>500)$message=substr($message,0,500).'...';$_SESSION['smtp_error']=$message;error_log('Khaatu Foods SMTP error: '.$message);}
function send_app_email($to,$subject,$body,$html=false){
    $smtpHost=trim((string)(getenv('SMTP_HOST')?:''));
    $smtpUser=trim((string)(getenv('SMTP_USER')?:''));
    $smtpPass=(string)(getenv('SMTP_PASS')?:'');
    $smtpPort=(int)(getenv('SMTP_PORT')?:587);
    $from=trim((string)(getenv('MAIL_FROM')?:$smtpUser));
    $fromName=trim((string)(getenv('MAIL_FROM_NAME')?:APP_NAME));
    if($smtpHost===''){smtp_error('SMTP_HOST is missing. Add SMTP settings to the project root .env file.');return false;}
    if($smtpUser===''||$smtpPass===''){smtp_error('SMTP_USER or SMTP_PASS is missing. Add your Gmail address and App Password to .env.');return false;}
    if($from==='')$from=$smtpUser;
    $autoload=__DIR__.'/../mailer/autoload.php';
    if(!is_file($autoload)){smtp_error('PHPMailer autoload.php was not found at '.$autoload);return false;}
    require_once $autoload;
    try{
        $mail=new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host=$smtpHost;
        $mail->SMTPAuth=true;
        $mail->Username=$smtpUser;
        $mail->Password=$smtpPass;
        $mail->Port=$smtpPort;
        $mail->SMTPSecure=($smtpPort===465)?PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS:PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Timeout=25;
        $mail->CharSet='UTF-8';
        $mail->setFrom($from,$fromName);
        $mail->addAddress($to);
        $mail->isHTML($html);
        $mail->Subject=$subject;
        $mail->Body=$body;
        if(!$html)$mail->AltBody=$body;
        $mail->send();
        unset($_SESSION['smtp_error']);
        return true;
    }catch(Throwable $e){smtp_error($e->getMessage());return false;}
}
function send_otp_email($to,$nameOrOtp,$otp=null,$purpose='register'){
    if($otp===null){$name='Customer';$otp=$nameOrOtp;$purpose='register';}else{$name=trim((string)$nameOrOtp);if($name==='')$name='Customer';}
    $to=strtolower(trim((string)$to));$otp=trim((string)$otp);
    if(!filter_var($to,FILTER_VALIDATE_EMAIL)){smtp_error('Invalid recipient email address.');return false;}
    if(!preg_match('/^[0-9]{6}$/',$otp)){smtp_error('Invalid OTP value.');return false;}
    $subject=strtolower($purpose)==='login'?APP_NAME.' - Login OTP':APP_NAME.' - Email Verification OTP';
    $label=strtolower($purpose)==='login'?'login':'email verification';
    $body="Hello ".$name.",\n\nYour ".APP_NAME." ".$label." OTP is: ".$otp."\n\nThis OTP is valid for 10 minutes. If you did not request this, please ignore this email.\n\nRegards,\n".APP_NAME;
    return send_app_email($to,$subject,$body,false);
}
function send_order_confirmation_email($order,$items){
    $total = (float)($order['total'] ?? $order['total_amount'] ?? 0);
    $subtotal = (float)($order['subtotal'] ?? 0);
    $shipping = (float)($order['shipping'] ?? 0);
    $discount = (float)($order['discount'] ?? 0);
    $paymentStatus = (string)($order['payment_status'] ?? 'pending');
    $paymentMethod = (string)($order['payment_method'] ?? 'COD');
    $lines = [];
    foreach($items as $i){
        $productName = $i['name'] ?? $i['product_name'] ?? 'Product';
        $qty = (int)($i['quantity'] ?? 0);
        $line = (float)($i['line_total'] ?? 0);
        $unit = (float)($i['unit_price'] ?? ($qty > 0 ? $line / $qty : 0));
        $lines[] = '<tr>
            <td style="padding:10px;border-bottom:1px solid #eee;">'.e($productName).'</td>
            <td style="padding:10px;border-bottom:1px solid #eee;text-align:center;">'.$qty.'</td>
            <td style="padding:10px;border-bottom:1px solid #eee;text-align:right;">'.money($unit).'</td>
            <td style="padding:10px;border-bottom:1px solid #eee;text-align:right;">'.money($line).'</td>
        </tr>';
    }
    $address=e($order['address'] ?? '').'<br>'.e($order['city'] ?? '').', '.e($order['state'] ?? '').' - '.e($order['pincode'] ?? '').'<br>'.e($order['country'] ?? '');
    $html='<!doctype html><html><body style="margin:0;background:#f5f7fa;font-family:Arial,sans-serif;color:#222">
    <div style="max-width:700px;margin:25px auto;background:#fff;border-radius:14px;overflow:hidden;border:1px solid #eee">
      <div style="background:#111827;color:#fff;padding:24px;text-align:center"><h2 style="margin:0">'.e(APP_NAME).'</h2><p style="margin:7px 0 0;color:#d1d5db">Order Confirmation</p></div>
      <div style="padding:25px">
        <h2 style="margin-top:0">Order Confirmed Successfully</h2>
        <p>Hello <b>'.e($order['customer_name'] ?? 'Customer').'</b>, your order has been confirmed.</p>
        <p><b>Order:</b> '.e($order['order_number'] ?? '').'<br><b>Tracking:</b> '.e($order['tracking_number'] ?? 'Will be assigned soon').'<br><b>Status:</b> '.e($order['order_status'] ?? 'confirmed').'<br><b>Payment:</b> '.e(strtoupper($paymentMethod)).' ('.e($paymentStatus).')</p>
        <table style="width:100%;border-collapse:collapse;border:1px solid #eee">
          <tr style="background:#f3f4f6"><th style="padding:10px;text-align:left">Product</th><th style="padding:10px">Qty</th><th style="padding:10px;text-align:right">Price</th><th style="padding:10px;text-align:right">Amount</th></tr>
          '.implode('',$lines).'
        </table>
        <table style="width:100%;margin-top:18px;border-collapse:collapse">
          <tr><td style="padding:6px 0">Subtotal</td><td style="padding:6px 0;text-align:right">'.money($subtotal).'</td></tr>
          <tr><td style="padding:6px 0">Shipping</td><td style="padding:6px 0;text-align:right">'.($shipping>0?money($shipping):'FREE').'</td></tr>
          <tr><td style="padding:6px 0">Discount</td><td style="padding:6px 0;text-align:right">'.($discount>0?'-'.money($discount):money(0)).'</td></tr>
          <tr><td style="padding:12px 0;border-top:2px solid #111827;font-size:18px"><b>Grand Total</b></td><td style="padding:12px 0;border-top:2px solid #111827;text-align:right;font-size:20px"><b>'.money($total).'</b></td></tr>
        </table>
        <h3>Delivery Address</h3><p>'.$address.'</p>
      </div>
      <div style="background:#111827;color:#d1d5db;text-align:center;padding:15px;font-size:12px">© '.date('Y').' '.e(APP_NAME).'</div>
    </div></body></html>';
    return send_app_email($order['email'],$paymentMethod==='razorpay' ? APP_NAME.' - Payment Confirmed - '.$order['order_number'] : APP_NAME.' - Order Confirmed - '.$order['order_number'],$html,true);
}
function razorpay_api($method,$endpoint,$payload=null){
    if(RAZORPAY_KEY_ID==='' || RAZORPAY_KEY_SECRET==='') throw new Exception('Razorpay keys are not configured. Add RAZORPAY_KEY_ID and RAZORPAY_KEY_SECRET to .env.');
    if(!function_exists('curl_init')) throw new Exception('PHP cURL extension is required for Razorpay.');
    $ch=curl_init('https://api.razorpay.com/v1/'.ltrim($endpoint,'/'));
    $headers=['Content-Type: application/json'];
    curl_setopt_array($ch,[
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_CUSTOMREQUEST=>strtoupper($method),
        CURLOPT_USERPWD=>RAZORPAY_KEY_ID.':'.RAZORPAY_KEY_SECRET,
        CURLOPT_HTTPAUTH=>CURLAUTH_BASIC,
        CURLOPT_HTTPHEADER=>$headers,
        CURLOPT_TIMEOUT=>30,
        CURLOPT_CONNECTTIMEOUT=>10
    ]);
    if($payload!==null) curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($payload,JSON_UNESCAPED_SLASHES));
    $raw=curl_exec($ch);
    $errno=curl_errno($ch);
    $error=curl_error($ch);
    $status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($raw===false || $errno) throw new Exception('Razorpay connection failed: '.$error);
    $data=json_decode($raw,true);
    if($status<200 || $status>=300) {
        $message=$data['error']['description'] ?? 'Razorpay API error.';
        throw new Exception($message);
    }
    if(!is_array($data)) throw new Exception('Invalid response received from Razorpay.');
    return $data;
}
function razorpay_create_order($amount,$receipt,$notes=[]){
    return razorpay_api('POST','orders',[
        'amount'=>(int)round($amount*100),
        'currency'=>RAZORPAY_CURRENCY,
        'receipt'=>(string)$receipt,
        'notes'=>$notes
    ]);
}
function razorpay_verify_signature($orderId,$paymentId,$signature){
    if(RAZORPAY_KEY_SECRET==='') return false;
    $expected=hash_hmac('sha256',$orderId.'|'.$paymentId,RAZORPAY_KEY_SECRET);
    return hash_equals($expected,(string)$signature);
}
function add_order_history($orderId,$status,$note=''){
    global $conn;
    $s=$conn->prepare("INSERT INTO order_status_history(order_id,status,note) VALUES(?,?,?)");
    $s->bind_param('iss',$orderId,$status,$note);
    $s->execute();
}

