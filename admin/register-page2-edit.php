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
    <!--end::Accessibility Meta Tags-->
    <!--begin::Primary Meta Tags-->
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta
        name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance." />
    <meta
        name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant" />
    <meta name="supported-color-schemes" content="light dark" />
    <?php include 'assets.php'; ?>

</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper">
        <?php
        session_start();
        include 'header.php';
        include 'aside.php';
        include('config/dbcon.php')

        ?>



        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Dashboard</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit - Register</li>
                            </ol>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content Header-->


            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="m-0">Edit-Register User</h4>
                            <a href="register-page2.php" class="btn btn-danger  mb-3">Back</a>
                        </div>
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-md-6">
                                    <form action="code.php" method="POST">
                                        <div class="modal-body">
                                            <?php
                                            if (isset($_GET['user_id'])) {

                                                $user_id = $_GET['user_id'];

                                                $query = "SELECT * FROM users WHERE id='$user_id' LIMIT 2";
                                                $query_run = mysqli_query($conn, $query);

                                                if (mysqli_num_rows($query_run) > 0) {
                                                    foreach ($query_run as $row) {
                                            ?>
                                                        <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                                        <div class="form-group my-2">
                                                            <label class="my-2 fw-bold">Name:-</label>
                                                            <input type="text" name="name" value="<?= $row['name'] ?>" class="form-control" required>
                                                        </div>

                                                        <div class="form-group my-2">
                                                            <label class="my-2 fw-bold">Phone:-</label>
                                                            <input type="number" name="phone" value="<?= $row['phone'] ?>" class="form-control" required>
                                                        </div>

                                                        <div class="form-group my-2">
                                                            <label class="my-2 fw-bold">Email:-</label>
                                                            <input type="email" name="email" value="<?= $row['email'] ?>" class="form-control" required>
                                                        </div>

                                                        <div class="form-group my-2">
                                                            <label class="my-2 fw-bold">Password:-</label>
                                                            <input type="password" name="password" value="<?= $row['password'] ?>" class="form-control" required>
                                                        </div>
                                                        <div class="form-group my-2">
                                                            <label class="my-2 fw-bold">Give Role:-</label>
                                                            <select name="role_as" class="form-control" required>
                                                                <option value="">select</option>
                                                                <option value="0">User</option>
                                                                <option value="1">Admin</option>

                                                            </select>
                                                        </div>

                                            <?php
                                                    }
                                                } else {
                                                    echo "<p>No Record Found..!</p>";
                                                }
                                            }
                                            ?>


                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" name="updateUser" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </main>


        <?php include 'footer.php'; ?>
    </div>

    <?php include 'script.php'; ?>
</body>

</html>