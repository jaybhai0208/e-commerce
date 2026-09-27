<?php error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'authentication.php';
include 'config/dbcon.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Users / Register - Admin</title> <?php include 'assets.php'; ?>
</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper"> <?php include 'header.php';
                                include 'aside.php'; ?> <!-- ================= CONTENT ================= -->
        <div class="content mt-3">
            <div class="container-fluid"> <!-- MESSAGE --> <?php include 'massege.php'; ?> <!-- ================= USER LIST ================= -->
                <div class="card shadow-sm">
                    <div class="card-header d-flex align-items-center">
                        <h3 class="card-title mb-0"> <i class="bi bi-people-fill me-2"></i> Users / Register </h3> <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#addUserModal"> <i class="bi bi-person-plus-fill me-1"></i> Add User </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="60">#</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th width="150">Action</th>
                                    </tr>
                                </thead>
                                <tbody> <?php $query = " SELECT id, name, phone, email, role_as, status, created_at FROM users ORDER BY id DESC ";
                                        $query_run = mysqli_query($conn, $query);
                                        if ($query_run && mysqli_num_rows($query_run) > 0): $count = 1;
                                            while ($user = mysqli_fetch_assoc($query_run)): ?> <tr>
                                                <td> <?= $count++; ?> </td>
                                                <td> <strong> <?= htmlspecialchars($user['name']); ?> </strong> </td>
                                                <td> <?= htmlspecialchars($user['phone']); ?> </td>
                                                <td> <?= htmlspecialchars($user['email']); ?> </td> <!-- ROLE -->
                                                <td> <?php if ((int)$user['role_as'] === 1): ?> <span class="badge bg-danger"> Admin </span> <?php else: ?> <span class="badge bg-primary"> User </span> <?php endif; ?> </td> <!-- STATUS -->
                                                <td> <?php if ((int)$user['status'] === 1): ?> <span class="badge bg-success"> Active </span> <?php else: ?> <span class="badge bg-secondary"> Inactive </span> <?php endif; ?> </td>
                                                <td> <?= htmlspecialchars($user['created_at']); ?> </td> <!-- ACTION -->
                                                <td>
                                                    <div class="d-flex gap-1"> <!-- EDIT --> <a href="register-page2.php?id=<?= (int)$user['id']; ?>" class="btn btn-sm btn-success" title="Edit User"> <i class="bi bi-pencil-square"></i> </a> <!-- DELETE -->
                                                        <form action="code.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');"> <input type="hidden" name="delete_id" value="<?= (int)$user['id']; ?>"> <button type="submit" name="deleteUserbtn" class="btn btn-sm btn-danger" title="Delete User"> <i class="bi bi-trash"></i> </button> </form>
                                                    </div>
                                                </td>
                                            </tr> <?php endwhile;
                                            else: ?> <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted"> <i class="bi bi-people fs-2 d-block mb-2"></i> No users found. </div>
                                            </td>
                                        </tr> <?php endif; ?> </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- ================= ADD USER MODAL ================= -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content"> <!-- HEADER -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel"> <i class="bi bi-person-plus-fill me-2"></i> Register New User </h5> <button type="button" class="btn-close" data-bs-dismiss="modal"> </button>
                    </div> <!-- FORM -->
                    <form action="code.php" method="POST" id="addUserForm">
                        <div class="modal-body">
                            <div class="row"> <!-- NAME -->
                                <div class="col-md-6 mb-3"> <label class="form-label"> Full Name <span class="text-danger">*</span> </label> <input type="text" name="name" class="form-control" placeholder="Enter full name" required maxlength="100"> </div> <!-- PHONE -->
                                <div class="col-md-6 mb-3"> <label class="form-label"> Phone Number <span class="text-danger">*</span> </label> <input type="tel" name="phone" class="form-control" placeholder="Enter phone number" required maxlength="20"> </div> <!-- EMAIL -->
                                <div class="col-md-6 mb-3"> <label class="form-label"> Email Address <span class="text-danger">*</span> </label> <input type="email" name="email" class="form-control" placeholder="Enter email address" required maxlength="150"> </div> <!-- ROLE -->
                                <div class="col-md-6 mb-3"> <label class="form-label"> Role <span class="text-danger">*</span> </label> <select name="role_as" class="form-select" required>
                                        <option value="0"> User </option>
                                        <option value="1"> Admin </option>
                                    </select> </div> <!-- PASSWORD -->
                                <div class="col-md-6 mb-3"> <label class="form-label"> Password <span class="text-danger">*</span> </label>
                                    <div class="input-group"> <input type="password" name="password" id="registerPassword" class="form-control" placeholder="Enter password" minlength="6" required> <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('registerPassword', this)"> <i class="bi bi-eye"></i> </button> </div> <small class="text-muted"> Minimum 6 characters </small>
                                </div> <!-- CONFIRM PASSWORD -->
                                <div class="col-md-6 mb-3"> <label class="form-label"> Confirm Password <span class="text-danger">*</span> </label>
                                    <div class="input-group"> <input type="password" name="confirmpassword" id="confirmPassword" class="form-control" placeholder="Confirm password" minlength="6" required> <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirmPassword', this)"> <i class="bi bi-eye"></i> </button> </div>
                                </div> <!-- PASSWORD MATCH -->
                                <div class="col-12">
                                    <div id="passwordMessage" class="small"> </div>
                                </div>
                            </div>
                        </div> <!-- FOOTER -->
                        <div class="modal-footer"> <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Cancel </button> <button type="submit" name="addUser" class="btn btn-primary"> <i class="bi bi-person-plus-fill me-1"></i> Register User </button> </div>
                    </form>
                </div>
            </div>
        </div> <?php include 'footer.php'; ?>
    </div> <?php include 'script.php'; ?> <script>
        /* =============================== SHOW / HIDE PASSWORD ================================ */
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        } /* =============================== PASSWORD MATCH CHECK ================================ */
        const passwordInput = document.getElementById('registerPassword');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const passwordMessage = document.getElementById('passwordMessage');

        function checkPassword() {
            if (confirmPasswordInput.value === '') {
                passwordMessage.innerHTML = '';
                return;
            }
            if (passwordInput.value === confirmPasswordInput.value) {
                passwordMessage.innerHTML = '<span class="text-success">' + '<i class="bi bi-check-circle"></i> ' + 'Passwords match' + '</span>';
            } else {
                passwordMessage.innerHTML = '<span class="text-danger">' + '<i class="bi bi-x-circle"></i> ' + 'Passwords do not match' + '</span>';
            }
        }
        passwordInput.addEventListener('input', checkPassword);
        confirmPasswordInput.addEventListener('input', checkPassword); /* =============================== FORM SUBMIT VALIDATION ================================ */
        document.getElementById('addUserForm').addEventListener('submit', function(event) {
            if (passwordInput.value !== confirmPasswordInput.value) {
                event.preventDefault();
                alert('Password and Confirm Password do not match.');
                confirmPasswordInput.focus();
                return false;
            }
        });
    </script>
</body>

</html>