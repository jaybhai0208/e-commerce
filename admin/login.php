<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_SESSION['auth']) && $_SESSION['auth'] === true && (int)($_SESSION['role_as'] ?? 0) === 1) {
    $_SESSION['status'] = "You have already logged in";
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'assets.php'; ?>

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .login-header {
            text-align: center;
            padding: 25px 20px;
        }

        .login-header h3 {
            font-weight: 700;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #4e73df;
        }
    </style>
</head>

<body>

    <div class="login-card bg-white">

        <div class="login-header">
            <h3>Welcome Back </h3>
            <p class="text-muted mb-0">Login to continue</p>
        </div>
        <div class="card-body p-4">
            <?php include 'massege.php'; ?>
            <form action="logincode.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope-fill"></i>
                        </span>
                        <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                    </div>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" name="login_btn" class="btn btn-primary btn-lg">
                        Sign In
                    </button>
                </div>
                <div class="text-center mt-3">
                    <a href="forgot-password.php" class="text-decoration-none d-block">
                        Forgot Password?
                    </a>
                    <a href="register-page2.php" class="text-decoration-none d-block mt-2">
                        Register Now
                    </a>
                </div>
            </form>
        </div>
    </div>

    <?php include 'script.php'; ?>
</body>

</html>