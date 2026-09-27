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


        <div class="content mt-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php include 'massege.php'; ?>
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h5 class="mb-0">Add Products</h5>
                                <a href="product-add.php" class="btn btn-primary btn-sm ms-auto">Add</a>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped align-middle">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Categories</th>
                                            <th>Name</th>
                                            <th style="width: 150px;">
                                                Small Description
                                            </th>
                                            <th class="long-desc" style="width: 400px;">
                                                Long Description
                                            </th>
                                            <!-- <th>Status</th> -->
                                            <!-- <th>Created At</th> -->
                                            <th>Image</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    <tbody>

                                        <?php
                                        $query = "SELECT products.*, categories.name AS category_name
                                        FROM products
                                        LEFT JOIN categories 
                                        ON products.category_id = categories.id";

                                        $query_run = mysqli_query($conn, $query);

                                        if (mysqli_num_rows($query_run) > 0) {
                                            foreach ($query_run as $prod_item) {
                                        ?>
                                                <tr>
                                                    <td><?= $prod_item['id']; ?></td>
                                                    <td><?= $prod_item['category_name']; ?></td>
                                                    <td><?= $prod_item['name']; ?></td>
                                                    <td><?= $prod_item['small_description']; ?></td>
                                                    <td class="long-desc"><?= $prod_item['long_description']; ?></td>
                                                    <td>
                                                        <img src="<?= 'uploads-img/' . $prod_item['image']; ?>" style="width:150px;">
                                                    </td>
                                                    <td>
                                                        <a href="product-edit.php?prod_id=<?= $prod_item['id']; ?>"
                                                            class="btn btn-success btn-sm">Edit</a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="code.php?delete_product=<?= $prod_item['id']; ?>"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Are you sure you want to delete this product?');">
                                                            Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="8">No Record Found</td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                    </thead>
                                </table>
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