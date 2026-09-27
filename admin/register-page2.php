<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'authentication.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My-1st-admin</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />

    <?php include 'assets.php'; ?>
</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper">
        <?php

        include 'header.php';
        include 'aside.php';
        include('config/dbcon.php');
        ?>

        <!-- ===================== Add User Modal ======================== -->
        <div class="modal fade" id="add_user" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Add User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="code.php" method="POST">
                        <div class="modal-body">

                            <div class="form-group my-2">
                                <label>Name:</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="form-group my-2">
                                <label>Phone:</label>
                                <input type="number" name="phone" class="form-control" required>
                            </div>

                            <div class="form-group my-2">
                                <label>Email:</label>
                                <span class="email_error text-danger"></span>
                                <input type="email" name="email" class="form-control email_id" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group my-2">
                                        <label>Password:</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group my-2">
                                        <label>Confirm Password:</label>
                                        <input type="password" name="confirmpassword" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="addUser" class="btn btn-primary">Save</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>


        <!-- ===================== Delete Modal ======================== -->
        <div class="modal fade" id="deleteModel">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Delete User</h1>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="code.php" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="delete_id" class="delete_user_id">
                            <p>Are you sure you want to delete this user?</p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="deleteUserbtn" class="btn btn-danger">Yes, Delete</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>


        <!-- ===================== Main Content ======================== -->
        <main class="app-main">

            <div class="app-content-header">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-sm-6">
                            <!-- <h3>Dashboard</h3> -->
                        </div>

                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active">Register</li>
                            </ol>
                        </div>
                    </div>

                </div>
            </div>

            <div class="container-fluid">

                <?php
                if (isset($_SESSION['status'])) {
                    echo "<p>" . $_SESSION['status'] . "</p>";
                    unset($_SESSION['status']);
                }
                ?>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Register User</h3>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_user">Add User</a>
                </div>

                <input type="text" id="customSearch" class="form-control mb-3" placeholder="Search anything...">

                <table id="myTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>


                    <tbody>
                        <?php
                        $query = "SELECT * FROM users";
                        $query_run = mysqli_query($conn, $query);

                        if (mysqli_num_rows($query_run) > 0) {
                            foreach ($query_run as $row) {
                        ?>
                                <tr>
                                    <td><?= $row['id']; ?></td>
                                    <td><?= $row['name']; ?></td>
                                    <td><?= $row['phone']; ?></td>
                                    <td><?= $row['email']; ?></td>

                                    <td>
                                        <?php
                                        if ($row['role_as'] == "0") {
                                            echo "User";
                                        } elseif ($row['role_as'] == "1") {
                                            echo "Admin";
                                        } else {
                                            echo "Invalid";
                                        }
                                        ?>
                                    </td>

                                    <td>
                                        <a href="register-page2-edit.php?user_id=<?= $row['id']; ?>"
                                            class="btn btn-info btn-sm">Edit</a>

                                        <button value="<?= $row['id']; ?>"
                                            class="btn btn-danger btn-sm deletebtn">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='6'>No Record Found</td></tr>";
                        }
                        ?>
                    </tbody>

                </table>

            </div>

        </main>

        <?php include 'script.php'; ?>


        <!-- ========== Fixed Email Check Ajax ========== -->
        <script>
            $(document).ready(function() {
                $(".email_id").keyup(function() {
                    let email = $(".email_id").val();

                    $.ajax({
                        type: "POST",
                        url: "code.php",
                        data: {
                            check_Emailbtn: 1,
                            email: email
                        },
                        success: function(response) {
                            $(".email_error").text(response);
                        }
                    });
                });
            });
        </script>

        <!-- ========== Delete Button Script ========== -->
        <script>
            $(document).ready(function() {
                $(".deletebtn").click(function() {
                    var id = $(this).val();
                    $(".delete_user_id").val(id);
                    $("#deleteModel").modal("show");
                });
            });
        </script>

        <?php include 'footer.php'; ?>

    </div>
</body>

</html>