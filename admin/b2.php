<?php
include 'authentication.php';
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
                        <h5 class="mb-0">Add Blog</h5>
                        <a href="blogs.php" class="btn btn-danger btn-sm ms-auto">Back</a>
                    </div>

                    <div class="card-body">

                        <!-- FORM START -->
                        <form action="code.php" method="POST" enctype="multipart/form-data">

                            <div class="row">

                                <!-- TITLE -->
                                <div class="col-md-12 mt-3">
                                    <label class="fw-bold">Title</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>

                                <!-- SHORT DESCRIPTION -->
                                <div class="col-md-6 mt-3">
                                    <label class="fw-bold">Short Description</label>
                                    <textarea name="short_description" class="form-control"></textarea>
                                </div>

                                <!-- LONG DESCRIPTION -->
                                <div class="col-md-6 mt-3">
                                    <label class="fw-bold">Long Description</label>
                                    <textarea name="long_description" class="form-control"></textarea>
                                </div>

                                <!-- IMAGE -->
                                <div class="col-md-6 mt-3">
                                    <label class="fw-bold">Upload Image</label>
                                    <input type="file" name="image" class="form-control" required>
                                </div>

                                <!-- STATUS -->
                                <div class="col-md-6 mt-3">
                                    <label class="fw-bold d-block">Status</label>
                                    <input type="checkbox" name="status"> Active
                                </div>

                                <!-- BUTTON -->
                                <div class="col-md-12 mt-4">
                                    <button type="submit" name="blog_save" class="btn btn-primary w-100">
                                        Save Blog
                                    </button>
                                </div>

                            </div>

                        </form>
                        <!-- FORM END -->

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<?php include 'script.php'; ?>