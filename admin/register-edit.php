<?php
include 'authentication.php';
require_once 'config/dbcon.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    die('Invalid user ID');
}

$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ? LIMIT 1");

if (!$stmt) {
    die('Database error: ' . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$user) {
    die('User not found');
}

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin</title> <?php include 'assets.php'; ?>
</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper"> <?php include 'header.php';
                                include 'aside.php'; ?> <main class="app-main"> <!-- ================= HEADER ================= -->
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0"> Edit User </h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"> <a href="index.php"> Home </a> </li>
                                <li class="breadcrumb-item"> <a href="register-page2.php"> Users </a> </li>
                                <li class="breadcrumb-item active" aria-current="page"> Edit User </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div> <!-- ================= CONTENT ================= -->
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10">
                        <div class="card shadow-sm"> <!-- CARD HEADER -->
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title mb-0"> <i class="bi bi-person-gear me-2"></i> Edit User </h3> <a href="register-page2.php" class="btn btn-secondary btn-sm ms-auto"> <i class="bi bi-arrow-left me-1"></i> Back </a>
                            </div> <!-- FORM -->
                            <form action="code.php" method="POST" id="editUserForm">
                                <div class="card-body"> <!-- USER ID --> <input type="hidden" name="user_id" value="<?= (int)$user['id']; ?>">
                                    <div class="row"> <!-- NAME -->
                                        <div class="col-md-6 mb-3"> <label class="form-label fw-bold"> Full Name <span class="text-danger">*</span> </label> <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']); ?>" placeholder="Enter full name" maxlength="100" required> </div> <!-- PHONE -->
                                        <div class="col-md-6 mb-3"> <label class="form-label fw-bold"> Phone Number <span class="text-danger">*</span> </label> <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']); ?>" placeholder="Enter phone number" maxlength="20" required> </div> <!-- EMAIL -->
                                        <div class="col-md-6 mb-3"> <label class="form-label fw-bold"> Email Address <span class="text-danger">*</span> </label> <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" placeholder="Enter email address" maxlength="150" required> </div> <!-- ROLE -->
                                        <div class="col-md-6 mb-3"> <label class="form-label fw-bold"> Role <span class="text-danger">*</span> </label> <select name="role_as" class="form-select" required>
                                                <option value="0" <?= ((int)$user['role_as'] === 0) ? 'selected' : ''; ?>> User </option>
                                                <option value="1" <?= ((int)$user['role_as'] === 1) ? 'selected' : ''; ?>> Admin </option>
                                            </select> </div> <!-- STATUS -->
                                        <div class="col-md-6 mb-3"> <label class="form-label fw-bold"> Account Status </label> <select name="status" class="form-select">
                                                <option value="1" <?= ((int)$user['status'] === 1) ? 'selected' : ''; ?>> Active </option>
                                                <option value="0" <?= ((int)$user['status'] === 0) ? 'selected' : ''; ?>> Inactive </option>
                                            </select> </div> <!-- PASSWORD -->
                                        <div class="col-md-6 mb-3"> <label class="form-label fw-bold"> New Password </label>
                                            <div class="input-group"> <input type="password" name="password" id="newPassword" class="form-control" placeholder="Leave blank to keep old password" minlength="6"> <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()"> <i id="passwordIcon" class="bi bi-eye"> </i> </button> </div> <small class="text-muted"> Leave blank if you don't want to change the password. </small>
                                        </div>
                                    </div>
                                </div> <!-- FOOTER -->
                                <div class="card-footer d-flex justify-content-end gap-2"> <a href="register-page2.php" class="btn btn-secondary"> Cancel </a> <button type="submit" name="updateUser" class="btn btn-primary"> <i class="bi bi-save me-1"></i> Update User </button> </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main> <?php include 'footer.php'; ?> </div> <?php include 'script.php'; ?> <script>
        function togglePassword() {
            const password = document.getElementById('newPassword');
            const icon = document.getElementById('passwordIcon');
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>

</html>