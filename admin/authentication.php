
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

/*
|--------------------------------------------------------------------------
| ADMIN LOGIN CHECK
|--------------------------------------------------------------------------
| Agar login nahi hai ya admin role nahi hai,
| to seedha admin login page par bhejo.
*/

$is_logged_in = isset($_SESSION['auth']) && $_SESSION['auth'] === true;

$is_admin = isset($_SESSION['role_as'])
    && (int)$_SESSION['role_as'] === 1;

if (!$is_logged_in || !$is_admin) {

    // Purana invalid admin session clear karo
    unset($_SESSION['auth']);
    unset($_SESSION['role_as']);
    unset($_SESSION['auth_status']);

    // Login page par bhejo
    $_SESSION['auth_status'] = "Please login as Admin to access the dashboard.";

    header("Location: login.php");
    exit();
}
?>