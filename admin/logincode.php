<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('config/dbcon.php');

if (isset($_POST['login_btn'])) {
    // Input sanitize karo
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Email check karo
    $log_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $log_query_run = mysqli_query($conn, $log_query);

    if (mysqli_num_rows($log_query_run) > 0) {
        $row = mysqli_fetch_assoc($log_query_run);


        if ((int)$row['role_as'] !== 1) {
            $_SESSION['status'] = "This account is not an admin account.";
            header("Location: login.php");
            exit();
        }

        if (password_verify($password, $row['password'])) {


            $_SESSION['auth'] = true;
            $_SESSION['role_as'] = $row['role_as'];
            $_SESSION['auth_user'] = [
                'user_id' => $row['id'],
                'user_name' => $row['name'],
                'user_email' => $row['email'],
                'user_phone' => $row['phone']
            ];

            $_SESSION['status'] = "Logged In Successfully...!";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['status'] = "Incorrect Password";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['status'] = "Email Not Found";
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['status'] = "Access Denied";
    header("Location: login.php");
    exit();
}
