<?php
require_once __DIR__ . '/config/mailer.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid Request');
}
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $subject === '' || $message === '') die('Please fill all required fields.');
$html = '<div style="font-family:Arial,sans-serif;max-width:650px;margin:auto">
<h2>Khaatu Foods Contact Enquiry</h2>
<table style="width:100%;border-collapse:collapse">
    <tr>
        <td style="padding:10px;background:#f3f4f6"><b>Name</b></td>
        <td style="padding:10px">' . htmlspecialchars($name) . '</td>
    </tr>
    <tr>
        <td style="padding:10px;background:#f3f4f6"><b>Email</b></td>
        <td style="padding:10px">' . htmlspecialchars($email) . '</td>
    </tr>
    <tr>
        <td style="padding:10px;background:#f3f4f6"><b>Phone</b></td>
        <td style="padding:10px">' . htmlspecialchars($phone) . '</td>
    </tr>
    <tr>
        <td style="padding:10px;background:#f3f4f6"><b>Subject</b></td>
        <td style="padding:10px">' . htmlspecialchars($subject) . '</td>
    </tr>
    <tr>
        <td style="padding:10px;background:#f3f4f6"><b>Message</b></td>
        <td style="padding:10px">' . nl2br(htmlspecialchars($message)) . '</td>
    </tr>
</table>
</div>';
if (!shop_mail_configured()) die('Email is not configured. Please set Gmail SMTP details in config/mailer.php first.');
if (shop_mail(SHOP_MAIL_FROM, 'Website Enquiry - ' . $subject, $html)) {
    header('Location: thankyou.php');
    exit;
}
die('Email could not be sent. Check Gmail SMTP/App Password configuration.');
