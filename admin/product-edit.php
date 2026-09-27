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



        <?php
        include 'config/dbcon.php';

        if (isset($_GET['prod_id'])) {

            $product_id = $_GET['prod_id'];

            $query = "SELECT * FROM products WHERE id='$product_id'";
            $query_run = mysqli_query($conn, $query);

            if ($query_run && mysqli_num_rows($query_run) > 0) {
                $prodItem = mysqli_fetch_assoc($query_run);
            } else {
                echo "No such product found";
                exit;
            }
        }
        ?>

        <div class="content mt-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php include 'massege.php'; ?>
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h5 class="mb-0"> Products Edit</h5>
                                <a href="product.php" class="btn btn-danger btn-sm  ms-auto">Back</a>
                            </div>
                            <div class="card-body">


                                <form action="code.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="product_id" value="<?= $prodItem['id'] ?>">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="fw-bold mb-2">Select Cotegory</label>
                                            <?php
                                            $query = "SELECT * FROM categories";
                                            $query_run = mysqli_query($conn, $query);

                                            if (mysqli_num_rows($query_run) > 0) {

                                            ?>

                                                <select name="category_id" class="form-control">
                                                    <option value="">Select Category</option>
                                                    <?php foreach ($query_run as $item) { ?>
                                                        <option value="<?= $item['id'] ?>" <?= $prodItem['category_id'] == $item['id'] ?  'selected' : '' ?>>
                                                            <?= $item['name'] ?>
                                                        </option>
                                                    <?php } ?>

                                                </select>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label class="fw-bold mb-1"> Products Name</label>
                                                <input type="text" class="form-control" name="name" value="<?= $prodItem['name'] ?>" placeholder="Enter Product Name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-2">
                                                <label class="fw-bold mb-1"> Small Description</label>
                                                <textarea name="small_description" id="small_edit" class="form-control" rows="3" placeholder="Enter small description" id="small_desc"><?= $prodItem['small_description'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-2">
                                                <label class="fw-bold mb-1"> Long Description</label>
                                                <textarea name="long_description" id="long_edit" class="form-control lng-desc" rows="3" placeholder="Enter long description" id="long_desc"><?= $prodItem['long_description'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-2">
                                                <label class="fw-bold mb-1"> Price</label>
                                                <input type="text" name="price" value="<?= $prodItem['price'] ?>" class="form-control" placeholder="Enter Price">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-2">
                                                <label class="fw-bold mb-1">Offer Price</label>
                                                <input type="text" name="offerprice" value="<?= $prodItem['offerprice'] ?>" class="form-control" placeholder="Enter Offer Price">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-2">
                                                <label class="fw-bold mb-1">Tax</label><br>
                                                <input type="text" name="tax" value="<?= $prodItem['tax'] ?>" class="form-control" placeholder="Enter TAX">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-2">
                                                <label class="fw-bold mb-1">Quantity</label><br>
                                                <input type="text" name="quantity" value="<?= $prodItem['quantity'] ?>" class="form-control" placeholder="Enter Quantity">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group mb-2">
                                                <label class="fw-bold mb-1">Status (checked = show | Hide)</label> <br>
                                                <input type="checkbox" name="status" <?= $prodItem['status'] == '1' ? 'checked' : '' ?>> Show and Hide
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group mb-2">
                                                <label class="fw-bold mb-1">Upload Image</label> <br>
                                                <input type="file" name="image" class="form-control">
                                            </div>
                                            <img src="uploads-img/<?= $prodItem['image'] ?>" width="50px" height="50px" alt="image">
                                            <input type="hidden" name="old_image" value="<?= $prodItem['image'] ?>">

                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-2">

                                                <button type="submit" name="product_update" class="btn btn-primary w-50  btn-sm ">Update</button>
                                            </div>
                                        </div>


                                    </div>
                            </div>
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