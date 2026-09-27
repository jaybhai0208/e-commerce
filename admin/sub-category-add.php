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
    <title>Add Sub Category</title>
    <?php include 'assets.php'; ?>
</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">

    <div class="app-wrapper">
        <?php include 'header.php';
        include 'aside.php'; ?>

        <div class="container mt-5">

            <?php if (isset($_SESSION['status'])) { ?>
                <div class="alert alert-info"><?= $_SESSION['status']; ?></div>
            <?php unset($_SESSION['status']);
            } ?>

            <div class="card shadow">

                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0">Add Sub Category</h5>
                    <a href="sub-category.php" class="btn btn-danger btn-sm ms-auto">Back</a>
                </div>

                <div class="card-body">

                    <form action="code.php" method="POST">

                        <!-- CATEGORY -->
                        <div class="mb-3">
                            <label class="fw-bold">Main Category</label>
                            <select name="category_id" class="form-select" required>

                                <option value="">-- Select Category --</option>

                                <?php
                                $query = "SELECT id, name FROM categories WHERE status='1' ORDER BY name ASC";
                                $query_run = mysqli_query($conn, $query);

                                if (mysqli_num_rows($query_run) > 0) {
                                    while ($row = mysqli_fetch_assoc($query_run)) {
                                ?>
                                        <option value="<?= $row['id']; ?>">
                                            <?= htmlspecialchars($row['name']); ?>
                                        </option>
                                <?php
                                    }
                                } else {
                                    echo "<option value=''>No Category Found</option>";
                                }
                                ?>

                            </select>
                        </div>

                        <!-- NAME -->
                        <div class="mb-3">
                            <label class="fw-bold">Sub Category Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <!-- DESCRIPTION -->
                        <div class="mb-3">
                            <label class="fw-bold">Description</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>

                        <!-- STATUS -->
                        <div class="mb-3">
                            <label class="fw-bold">Status</label><br>
                            <input type="checkbox" name="status" value="1" checked> Active
                        </div>

                        <!-- BUTTON -->
                        <div class="mb-3">
                            <button type="submit" name="add_subcategory" class="btn btn-primary">
                                Save Sub Category
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</body>

</html>