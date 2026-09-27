<?php
include 'authentication.php';
include 'config/dbcon.php';

if (!isset($_GET['id'])) {
    $_SESSION['status'] = "Invalid Sub Category ID";
    header("Location: sub-category.php");
    exit();
}

$id = $_GET['id'];

$query = "SELECT * FROM subcategories WHERE id='$id'";
$query_run = mysqli_query($conn, $query);

if (mysqli_num_rows($query_run) == 0) {
    $_SESSION['status'] = "Sub Category Not Found";
    header("Location: sub-category.php");
    exit();
}

$data = mysqli_fetch_assoc($query_run);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
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
        include 'header.php';
        include 'aside.php';
        ?>


        <div class="container mt-5">

            <div class="card shadow">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0">Edit Sub Category</h5>

                    <a href="sub-category.php" class="btn btn-danger btn-sm ms-auto">
                        Back
                    </a>
                </div>

                <div class="card-body">

                    <form action="code.php" method="POST">

                        <input type="hidden" name="subcategory_id" value="<?= $data['id']; ?>">

                        <!-- CATEGORY -->
                        <div class="mb-3">
                            <label class="fw-bold">Main Category</label>
                            <select name="category_id" class="form-select" required>

                                <?php
                                $cat_query = "SELECT * FROM categories WHERE status='1'";
                                $cat_run = mysqli_query($conn, $cat_query);

                                while ($cat = mysqli_fetch_assoc($cat_run)) {
                                ?>
                                    <option value="<?= $cat['id']; ?>"
                                        <?= $cat['id'] == $data['category_id'] ? 'selected' : ''; ?>>
                                        <?= $cat['name']; ?>
                                    </option>
                                <?php } ?>

                            </select>
                        </div>

                        <!-- NAME -->
                        <div class="mb-3">
                            <label class="fw-bold">Sub Category Name</label>
                            <input type="text" name="name" class="form-control"
                                value="<?= $data['name']; ?>" required>
                        </div>

                        <!-- DESCRIPTION -->
                        <div class="mb-3">
                            <label class="fw-bold">Description</label>
                            <textarea name="description" class="form-control"><?= $data['description']; ?></textarea>
                        </div>

                        <!-- STATUS -->
                        <div class="mb-3">
                            <label class="fw-bold">Status</label><br>
                            <input type="checkbox" name="status" value="1"
                                <?= $data['status'] == 1 ? 'checked' : ''; ?>> Active
                        </div>

                        <!-- BUTTON -->
                        <div class="mb-3">
                            <button type="submit" name="update_subcategory" class="btn btn-success">
                                Update Sub Category
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>

</body>

</html>