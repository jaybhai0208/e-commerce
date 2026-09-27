<?php
session_start();
include('db.php'); // database connection file

// FETCH BLOGS
$query = "SELECT * FROM blogs ORDER BY id DESC";
$query_run = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Blog</title>

</head>

<body>

    <h2>Add Blog</h2>

    <?php
    if (isset($_SESSION['status'])) {
        echo "<p class='msg'>" . $_SESSION['status'] . "</p>";
        unset($_SESSION['status']);
    }
    ?>

    <form action="code.php" method="POST" enctype="multipart/form-data">

        <label>Title</label>
        <input type="text" name="title" required>

        <label>Short Description</label>
        <textarea name="short_description" required></textarea>

        <label>Long Description</label>
        <textarea name="long_description" required></textarea>

        <label>Image</label>
        <input type="file" name="image" required>

        <label>
            <input type="checkbox" name="status"> Active
        </label>

        <br><br>
        <button type="submit" name="blog_save">Save Blog</button>
    </form>


    <hr>

    <h2>All Blogs</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Slug</th>
            <th>Image</th>
            <th>Status</th>
        </tr>

        <?php
        if (mysqli_num_rows($query_run) > 0) {
            foreach ($query_run as $row) {
        ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['title']; ?></td>
                    <td><?= $row['slug']; ?></td>
                    <td>
                        <img src="uploads-img/<?= $row['image']; ?>" alt="">
                    </td>
                    <td><?= $row['status'] == '1' ? 'Active' : 'Inactive'; ?></td>
                </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='5'>No Data Found</td></tr>";
        }
        ?>
    </table>

</body>

</html>