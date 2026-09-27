<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'authentication.php';
include 'config/dbcon.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit-Gift Category</title>
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



        <!-- ================modal========================= -->

        <!-- Modal -->
        <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="code.php" method="POST">
                        <div class="modal-body">

                            <!-- Category Name -->
                            <div class="mb-3">
                                <label class="form-label">Category Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter category name" required>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Enter description"
                                    required></textarea>
                            </div>

                            <!-- Trending -->
                            <div class="form-check mb-2">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="trending"
                                    value="1">
                                <label class="form-check-label" for="trending">
                                    Trending
                                </label>
                            </div>

                            <!-- Status -->
                            <!-- <div class="form-check mb-3">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="status"
                                    value="1">
                                <label class="form-check-label" for="status">
                                    status
                                </label>
                            </div> -->

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" name="add_category" class="btn btn-primary">
                                Save
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!-- ================modal========================= -->
        <div class="content mt-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php include 'massege.php'; ?>
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h5 class="mb-0">Edit-Gift Category</h5>
                                <a href="category.php" class="btn btn-danger btn-sm fw-bold ms-auto">Back</a>
                            </div>
                            <div class="card-body">


                                <form action="code.php" method="POST">
                                    <?php
                                    if (isset($_GET['id'])) {
                                        $cate_id = $_GET['id'];
                                        $query = "SELECT * FROM categories WHERE id='$cate_id' ";
                                        $query_run = mysqli_query($conn, $query);

                                        foreach ($query_run as $item):
                                    ?>

                                            <input type="hidden" name="cate_id" value="<?= $item['id']; ?>">
                                            <div class="modal-body">

                                                <!-- Category Name -->
                                                <div class="mb-3">
                                                    <label class="form-label">Category Name</label>
                                                    <input type="text" name="name" value="<?= $item['name']; ?>" class="form-control" placeholder="Enter category name" required>
                                                </div>

                                                <!-- Description -->
                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <textarea
                                                        name="description"
                                                        class="form-control"
                                                        rows="4"
                                                        placeholder="Enter description"
                                                        required><?= $item['description']; ?></textarea>
                                                </div>

                                                <!-- Trending -->
                                                <div class="form-check mb-2">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        name="trending"
                                                        <?= $item['trending'] == "1" ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="trending">
                                                        Trending
                                                    </label>
                                                </div>

                                                <!-- Status -->
                                                <!-- <div class="form-check mb-3">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        name="status"
                                                        <?= $item['status'] == "1" ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="status">
                                                        status
                                                    </label>
                                                </div> -->
                                            </div>

                                            <div class="modal-footer gap-3">
                                                <a href="category.php" class="btn btn-danger btn-sm fw-bold">Back</a>
                                                <button type="submit" name="update_category" class="btn btn-sm fw-bold btn-success">
                                                    Update
                                                </button>
                                            </div>

                                    <?php
                                        endforeach;
                                    } else {
                                        echo "NO ID Found";
                                    }
                                    ?>


                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>






        <?php include 'footer.php'; ?>
    </div>

    <?php include 'script.php'; ?>
</body>

</html>