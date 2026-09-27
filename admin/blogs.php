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
    <title>BLogs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->
    <!--begin::Primary Meta Tags-->

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
                                <h5 class="mb-0">Add Blogs</h5>
                                <a href="blogs-add.php" class="btn btn-primary btn-sm ms-auto">Add</a>
                            </div>
                            <div class="card-body table-responsive">



                                <!-- ======================================================= -->
                                <table border="1" class="table table-bordered table-striped align-middle">
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>

                                    <?php
                                    $query = "SELECT * FROM blogs ORDER BY id DESC";
                                    $query_run = mysqli_query($conn, $query);

                                    if (mysqli_num_rows($query_run) > 0) {
                                        foreach ($query_run as $row) {
                                    ?>
                                            <tr>
                                                <td><?= $row['id']; ?></td>
                                                <td><?= $row['title']; ?></td>

                                                <td>
                                                    <img src="uploads-img/<?= $row['image']; ?>" width="80">
                                                </td>

                                                <td><?= $row['status'] == '1' ? "Active" : "Hidden"; ?></td>

                                                <td>
                                                    <a href="blog-edit.php?id=<?= $row['id']; ?>">Edit</a>

                                                    <a href="code.php?delete_blog=<?= $row['id']; ?>"
                                                        onclick="return confirm('Delete this blog?')">
                                                        Delete
                                                    </a>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>No Blogs Found</td></tr>";
                                    }
                                    ?>

                                </table>
                                <!-- ======================================================= -->
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