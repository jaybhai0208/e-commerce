<?php require_once __DIR__ . '/includes/config.php';
$u = current_user();
if ($u) redirect('index.php'); ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - Khaatu Foods</title><?php include 'assets.php'; ?>
</head>

<body>
    <?php include 'header.php'; ?>
    <section class="flat-spacing">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="tf-page-login">
                        <div class="box-login">
                            <h3 class="title text-center mb-20">Sign In</h3>
                            <?php if (!empty($_SESSION['flash'])) {
                                echo '<div class="alert alert-danger">' . e($_SESSION['flash']) . '</div>';
                                unset($_SESSION['flash']);
                            } ?>
                            <form method="post" action="auth.php" class="form-log"><input type="hidden" name="action" value="login"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                                <div class="form-content">
                                    <fieldset class="tf-field">
                                        <label class="tf-lable fw-medium">Email Address <span class="text-primary">*</span></label>
                                        <input type="email" name="email" required>
                                    </fieldset>
                                    <fieldset class="tf-field">
                                        <label class="tf-lable fw-medium">Password <span class="text-primary">*</span></label>
                                        <input type="password" name="password" required>
                                    </fieldset>
                                </div>
                                <button class="tf-btn animate-btn w-100" type="submit">Login</button>
                            </form>
                            <p class="text-center mt-20">Don't have an account? <a class="link text-decoration-underline" href="register.php">Create Account</a></p>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <?php include 'footer.php'; ?>
</body>

</html>