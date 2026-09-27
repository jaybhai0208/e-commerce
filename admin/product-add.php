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
                                <h5 class="mb-0"> Products Add</h5>
                                <a href="product.php" class="btn btn-danger btn-sm  ms-auto">Back</a>
                            </div>
                            <div class="card-body">
                                <form action="code.php" method="POST" enctype="multipart/form-data">

                                    <div class="row">

                                        <!-- CATEGORY -->
                                        <div class="col-md-12">
                                            <label class="fw-bold mb-2">Select Category</label>
                                            <?php
                                            $query = "SELECT * FROM categories ";
                                            $query_run = mysqli_query($conn, $query);
                                            ?>
                                            <select name="category_id" class="form-control" required>
                                                <?php foreach ($query_run as $item) { ?>
                                                    <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <!-- SUB CATEGORY -->
                                        <!--  -->

                                        <!-- PRODUCT NAME -->
                                        <div class="col-md-12 mt-3">
                                            <label class="fw-bold mb-1">Product Name</label>
                                            <input type="text" class="form-control" name="name" required>
                                        </div>
                                        <!-- SMALL DESC -->
                                        <div class="col-md-6 mt-3">
                                            <label class="fw-bold mb-1">Small Description</label>
                                            <textarea
                                                name="small_description"
                                                id="small_description"
                                                class="form-control">
    </textarea>
                                        </div>

                                        <!-- LONG DESC -->
                                        <div class="col-md-6 mt-3">
                                            <label class="fw-bold mb-1">Long Description</label>
                                            <textarea
                                                name="long_description"
                                                id="long_description"
                                                class="form-control">
    </textarea>
                                        </div>

                                        <!-- PRICE -->
                                        <div class="col-md-3 mt-3">
                                            <label class="fw-bold mb-1">Price</label>
                                            <input type="text" name="price" class="form-control">
                                        </div>

                                        <!-- OFFER PRICE -->
                                        <div class="col-md-3 mt-3">
                                            <label class="fw-bold mb-1">Offer Price</label>
                                            <input type="text" name="offerprice" class="form-control">
                                        </div>

                                        <!-- TAX -->
                                        <div class="col-md-3 mt-3">
                                            <label class="fw-bold mb-1">Tax</label>
                                            <input type="text" name="tax" class="form-control">
                                        </div>

                                        <!-- QUANTITY -->
                                        <div class="col-md-3 mt-3">
                                            <label class="fw-bold mb-1">Quantity</label>
                                            <input type="text" name="quantity" class="form-control">
                                        </div>

                                        <!-- IMAGE -->
                                        <div class="col-md-8 mt-3">
                                            <label class="fw-bold mb-1">Upload Image</label>
                                            <input type="file" name="image" class="form-control" required>
                                        </div>

                                        <!-- BUTTON -->
                                        <div class="col-md-4 mt-4">
                                            <button type="submit" name="product_save" class="btn btn-primary w-100">
                                                Save Product
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>


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