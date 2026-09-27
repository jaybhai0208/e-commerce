<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'authentication.php';
include 'config/dbcon.php';

?>


<!DOCTYPE html>
<html>

<head>
    <title>Sub Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'assets.php'; ?>
</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper">
        <?php
        include 'header.php';
        include 'aside.php';
        ?>


        <div class="container mt-5">

            <?php if (isset($_SESSION['status'])) { ?>
                <div class="alert alert-info">
                    <?= $_SESSION['status']; ?>
                </div>
                <?php unset($_SESSION['status']); ?>
            <?php } ?>

            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sub Category List</h5>
                    <a href="sub-category-add.php" class="btn btn-primary btn-sm ms-auto">Add Sub Category</a>
                </div>

                <div class="card-body table-responsive">

                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $query = "
SELECT subcategories.*, categories.name AS category_name
FROM subcategories
LEFT JOIN categories ON subcategories.category_id = categories.id
ORDER BY subcategories.id DESC
";




                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_assoc($query_run)) {
                            ?>
                                    <tr>
                                        <td><?= $row['id']; ?></td>
                                        <td><?= $row['category_name'] ?? 'No Category'; ?></td>

                                        <td><?= $row['name']; ?></td>
                                        <td>
                                            <?= $row['status'] == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'; ?>
                                        </td>
                                        <td>
                                            <a href="sub-category-edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>

                                            <form action="code.php" method="POST" class="d-inline">
                                                <input type="hidden" name="subcategory_delete_id" value="<?= $row['id']; ?>">
                                                <button type="submit" name="delete_subcategory" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Delete this sub category?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='5'>No Sub Categories Found</td></tr>";
                            }
                            ?>
                        </tbody>

                    </table>

                </div>
            </div>
        </div>

</body>

</html>