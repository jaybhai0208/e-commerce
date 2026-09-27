<?php
session_start();
unset($_SESSION['auth'],$_SESSION['role_as'],$_SESSION['auth_user'],$_SESSION['status'],$_SESSION['auth_status']);
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Location: login.php');
exit;
