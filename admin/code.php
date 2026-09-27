<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'authentication.php';
include('config/dbcon.php');


// ===========================product-add==================

if (isset($_POST['product_save'])) {
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $slug = strtolower(trim($name));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);

    $small_description = $_POST['small_description'];
    $long_description = $_POST['long_description'];
    $price = $_POST['price'];
    $offerprice = $_POST['offerprice'];
    $tax = $_POST['tax'];
    $quantity = $_POST['quantity'];
    $status = isset($_POST['status']) ? '1' : '0';
    $image = $_FILES['image']['name'];

    $allowed_extention = array('png', 'jpg', 'jpeg');
    $file_extention = pathinfo($image, PATHINFO_EXTENSION);

    $filename = time() . '.' . $file_extention;

    // move_uploaded_file($_FILES['image']['tmp_name'], 'uploads-img/' . $filename);


    if (!in_array($file_extention, $allowed_extention)) {
        $_SESSION['status'] = "You are allowed with only png, jpg, jpeg images";
        header('Location:product-add.php');
        exit(0);
    } else {
        $query = "INSERT INTO products (category_id, name, slug, small_description, long_description, price, offerprice, tax, quantity, image, status) 
        VALUES ('$category_id', '$name', '$slug', '$small_description', '$long_description', '$price', '$offerprice', '$tax', '$quantity', '$filename', '$status')";
        $query_run = mysqli_query($conn, $query);
        if ($query_run) {
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads-img/' . $filename);
            $detail = $conn->prepare("INSERT INTO product_details(product_id,short_description,description) VALUES(?,?,?) ON DUPLICATE KEY UPDATE short_description=VALUES(short_description),description=VALUES(description)");
            $newProductId = $conn->insert_id;
            $detail->bind_param('iss', $newProductId, $small_description, $long_description);
            $detail->execute();
            $_SESSION['status'] = "Product Added Successfully !";
            header('Location:product-add.php');
            exit(0);
        } else {
            $_SESSION['status'] = "Something Went Wrong..!";
            header('Location:product-add.php');
            exit(0);
        }
    }
}

// ===========================product-update==========================
if (isset($_POST['product_update'])) {
    $product_id = $_POST['product_id'];
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $small_description = $_POST['small_description'];
    $long_description = $_POST['long_description'];
    $price = $_POST['price'];
    $offerprice = $_POST['offerprice'];
    $tax = $_POST['tax'];
    $quantity = $_POST['quantity'];
    $status = isset($_POST['status']) ? '1' : '0';

    $image = $_FILES['image']['name'];
    $old_image = $_POST['old_image'];
    if ($image != '') {

        $file_extention = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        $allowed_extention = ['png', 'jpg', 'jpeg'];

        if (!in_array($file_extention, $allowed_extention)) {
            $_SESSION['status'] = "Only png, jpg, jpeg allowed";
            header('Location:product-edit.php?prod_id=' . $product_id);
            exit;
        }

        $filename = time() . '.' . $file_extention;
        $image_name = $filename;
    } else {
        $image_name = $old_image;
    }

    $query = "UPDATE products SET
     category_id='$category_id',
     name='$name',
      small_description='$small_description',
      long_description='$long_description',
      price='$price',
      offerprice='$offerprice',
      quantity='$quantity',
      image='$image_name',
      tax='$tax',
      status='$status'
       WHERE id='$product_id' ";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $detail = $conn->prepare("INSERT INTO product_details(product_id,short_description,description) VALUES(?,?,?) ON DUPLICATE KEY UPDATE short_description=VALUES(short_description),description=VALUES(description)");
        $detail->bind_param('iss', $product_id, $small_description, $long_description);
        $detail->execute();
        if ($image != '') {
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads-img/' . $filename);
            if (file_exists('uploads-img/' . $old_image)) {
                unlink("uploads-img/" . $old_image);
            }
        }
        $_SESSION['status'] = "Product Update Successfully !";
        header('Location:product-edit.php?prod_id=' . $product_id);
        exit(0);
    } else {
        $_SESSION['status'] = "Product Not Updated..!";
        header('Location:product-edit.php?prod_id=' . $product_id);
        exit(0);
    }
}


// ===================================

// $allowed_extention = array('png', 'jpg', 'jpeg');
// $file_extention = pathinfo($image, PATHINFO_EXTENSION);


// move_uploaded_file($_FILES['image']['tmp_name'], 'uploads-img/' . $filename);


// if (!in_array($file_extention, $allowed_extention)) {
//     $_SESSION['status'] = "You are allowed with only png, jpg, jpeg images";
//     header('Location:product-add.php');
//     exit(0);
// } else {
// }

// ===========================logout==========================
// if (isset($_POST['logout_btn'])) {
//     unset($_SESSION['auth']);
//     unset($_SESSION['auth_user']);
//     $_SESSION['status'] = "Logged out successfully..!";
//     header('location:login.php');
//     exit();
// }


if (isset($_POST['logout_btn'])) {
    session_start();
    session_unset();
    session_destroy();


    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    header("Location: login.php");
    exit();
}


// =================check-email=============

if (isset($_POST['check_Emailbtn'])) {

    $email = $_POST['email'];
    $checkemail = "SELECT email FROM users WHERE email='$email' ";
    $checkemail_run = mysqli_query($conn, $checkemail);

    if (mysqli_num_rows($checkemail_run) > 0) {

        echo "Email id already taken..!";
    } else {
        echo "It's Available";
    }
}
// =================check-email=============


// ==================add-user====================
if (isset($_POST['addUser'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    if ($password == $confirmpassword) {

        $checkemail = "SELECT email FROM users WHERE email='$email' ";
        $checkemail_run = mysqli_query($conn, $checkemail);

        if (mysqli_num_rows($checkemail_run) > 0) {

            $_SESSION['status'] = '<h5 class="alert alert-danger">Email Id is already taken...!</h5>';
            header("location:register-page2.php");
            exit();
        } else {

            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $user_query = "INSERT INTO users (name,phone,email,password) VALUE ('$name', '$phone', '$email', '$password_hash')";
            $user_query_run = mysqli_query($conn, $user_query);

            if ($user_query_run) {
                $_SESSION['status'] = '<div class="alert alert-success text-bold"><h3>User Added Successfully!</h3></div>';
                header("location:register-page2.php");
            } else {
                $_SESSION['status'] = '<div class="alert alert-danger text-bold"><h3>User Registration Failed</h3></div>';
                header("location:register-page2.php");
                exit();
            }
        }
    } else {
        $_SESSION['status'] = '<div class="alert alert-danger text-bold">User Registration Failed!</div>';
        header("location:register-page2.php");
        exit();
    }
}
// ==================add-user====================

// ===================== category_add =================
if (isset($_POST['add_category'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // slug generate
    $slug = strtolower(trim($name));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);


    $trending = isset($_POST['trending']) ? '1' : '0';
    $status   = isset($_POST['status']) ? '1' : '0';

    $category_query = "
        INSERT INTO categories (name, slug, description, trending, status)
        VALUES ('$name', '$slug', '$description', '$trending', '$status')
    ";

    $cate_query_run = mysqli_query($conn, $category_query);

    if ($cate_query_run) {

        $_SESSION['status'] =
            '<div class="alert alert-success fw-bold">
        Category inserted successfully!
        </div>';
    } else {

        $_SESSION['status'] =
            '<div class="alert alert-danger fw-bold">
        Category insertion failed!
        </div>';
    }

    header("Location: category.php");
    exit();
}
// =====================category_add=================


// ============================categories-update==================

if (isset($_POST['update_category'])) {
    $cate_id = $_POST['cate_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $trending = $_POST['trending'] == true ? '1' : '0';
    $status = $_POST['status'] == true ? '1' : '0';


    $query = "UPDATE categories SET name='$name', description='$description', trending='$trending', status='$status' WHERE id='$cate_id' ";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $_SESSION['status'] =
            '<div class="alert alert-success fw-bold">
                    Category updated successfully!
                </div>';
        header("Location: category.php");
        exit();
    } else {
        $_SESSION['status'] =
            '<div class="alert alert-danger fw-bold">
                    Category updating failed!
                </div>';
        header("Location: category.php");
        exit();
    }
}


// ============================categories-update==================





// ===============================categories delete=============================

if (isset($_POST['cate_delete'])) {
    $cate_id = $_POST['cate_delete_id'];

    $query = "DELETE FROM categories WHERE id='$cate_id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $_SESSION['status'] = '<h5>Category Deleted Successfully!</h5>';
        header("Location: category.php");
        exit();
    } else {
        $_SESSION['status'] = '<div class="alert alert-danger"><h3>Category Deleting Failed</h3></div>';
        header("Location: category.php");
        exit();
    }
}


// ===============================sub category insert===============?

if (isset($_POST['add_subcategory'])) {

    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $status = isset($_POST['status']) ? 1 : 0;

    $query = "INSERT INTO subcategories (category_id,name,description,status)
VALUES ('$category_id','$name','$description','$status')";

    mysqli_query($conn, $query);

    $_SESSION['status'] = "Sub Category Added Successfully!";
    header("Location: sub-category.php");
    exit();
}


// ===================== ADD SUB CATEGORY =====================
if (isset($_POST['add_subcategory'])) {

    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = isset($_POST['status']) ? 1 : 0;

    $query = "INSERT INTO subcategories (category_id, name, description, status)
              VALUES ('$category_id', '$name', '$description', '$status')";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $_SESSION['status'] = "Sub Category Added Successfully!";
    } else {
        $_SESSION['status'] = "Sub Category Add Failed!";
    }

    header("Location: sub-category.php");
    exit();
}




// ===================== UPDATE SUB CATEGORY =====================
if (isset($_POST['update_subcategory'])) {
    $id = $_POST['subcategory_id'];
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $status = isset($_POST['status']) ? 1 : 0;

    $query = "UPDATE subcategories 
              SET category_id='$category_id', 
                  name='$name',
                  description='$description',
                  status='$status'
              WHERE id='$id'";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $_SESSION['status'] = "Sub Category Updated Successfully!";
    } else {
        $_SESSION['status'] = "Sub Category Update Failed!";
    }

    header("Location: sub-category.php");
    exit();
}





// ================== DELETE SUB CATEGORY ==================
if (isset($_POST['delete_subcategory'])) {
    $id = $_POST['subcategory_delete_id'];

    $query = "DELETE FROM subcategories WHERE id='$id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $_SESSION['status'] = "Sub Category Deleted Successfully!";
    } else {
        $_SESSION['status'] = "Sub Category Delete Failed!";
    }

    header("Location: sub-category.php");
    exit();
}


// =====================updateUser=====================
if (isset($_POST['updateUser'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_as = $_POST['role_as'];


    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET name='$name', phone='$phone', email='$email', password='$password_hash', role_as='$role_as' WHERE id='$user_id' ";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $_SESSION['status'] = '<div class="alert alert-success text-bold"> User Updated Successfully..!</div>';
        header("location:register-page2.php");
        exit();
    } else {
        $_SESSION['status'] = '<div class="alert alert-danger text-bold">User Updating Failed</div>';
        header("location:register-page2.php");
        exit();
    }
}

// =====================updateUser=====================






if (isset($_POST['deleteUserbtn'])) {
    $userid = $_POST['delete_id'];
    $query = "DELETE FROM users WHERE id='$userid' ";
    $query_run = mysqli_query($conn, $query);
    if ($query_run) {
        $_SESSION['status'] = '<div class="alert alert-success text-bold"><h3> User Deleted Successfully..!</h3></div>';
        header("location:register-page2.php");
        exit();
    } else {
        $_SESSION['status'] = '<div class="alert alert-danger text-bold"> <h3>User Deleting Failed</h3></div>';
        header("location:register-page2.php");
        exit();
    }
}
// ====================logout============

if (isset($_GET['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// ================delete-product=============




if (isset($_GET['delete_product'])) {

    $product_id = mysqli_real_escape_string($conn, $_GET['delete_product']);


    $img_query = "SELECT image FROM products WHERE id='$product_id'";
    $img_query_run = mysqli_query($conn, $img_query);
    $img_data = mysqli_fetch_assoc($img_query_run);


    $delete_query = "DELETE FROM products WHERE id='$product_id'";
    $delete_query_run = mysqli_query($conn, $delete_query);

    if (!$delete_query_run) {
        die("Delete Failed: " . mysqli_error($conn));
    }


    if (!empty($img_data['image']) && file_exists('uploads-img/' . $img_data['image'])) {
        unlink('uploads-img/' . $img_data['image']);
    }

    $_SESSION['status'] = "Product Deleted Successfully!";
    header("Location: product.php");
    exit();
}

// =====================================================================================

// =========================== BLOG ADD ==========================
if (isset($_POST['blog_save'])) {

    $title = $_POST['title'];


    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);

    $short_description = $_POST['short_description'];
    $long_description  = $_POST['long_description'];
    $status = isset($_POST['status']) ? '1' : '0';

    $image = $_FILES['image']['name'];

    $allowed_extention = ['png', 'jpg', 'jpeg'];
    $file_extention = strtolower(pathinfo($image, PATHINFO_EXTENSION));

    $filename = time() . '.' . $file_extention;

    if (!in_array($file_extention, $allowed_extention)) {
        $_SESSION['status'] = "Only png, jpg, jpeg allowed!";
        header('Location: blogs-add.php');
        exit();
    }

    $query = "INSERT INTO blogs (title, slug, short_description, long_description, image, status) 
              VALUES ('$title', '$slug', '$short_description', '$long_description', '$filename', '$status')";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads-img/' . $filename);
        $_SESSION['status'] = "Blog Added Successfully!";
    } else {
        $_SESSION['status'] = "Blog Not Added!";
    }

    header("Location: blogs-add.php");
    exit();
}
// =========================== BLOG UPDATE ==========================
if (isset($_POST['blog_update'])) {

    $blog_id = $_POST['blog_id'];
    $title = $_POST['title'];
    $short_description = $_POST['short_description'];
    $long_description  = $_POST['long_description'];
    $status = isset($_POST['status']) ? '1' : '0';

    $image = $_FILES['image']['name'];
    $old_image = $_POST['old_image'];

    if ($image != '') {
        $file_extention = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        $allowed_extention = ['png', 'jpg', 'jpeg'];

        if (!in_array($file_extention, $allowed_extention)) {
            $_SESSION['status'] = "Only png, jpg, jpeg allowed!";
            header("Location: blog-edit.php?id=" . $blog_id);
            exit();
        }

        $filename = time() . '.' . $file_extention;
        $image_name = $filename;
    } else {
        $image_name = $old_image;
    }

    $query = "UPDATE blogs SET 
                title='$title',
                short_description='$short_description',
                long_description='$long_description',
                image='$image_name',
                status='$status'
              WHERE id='$blog_id'";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {

        if ($image != '') {
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads-img/' . $filename);

            if (file_exists('uploads-img/' . $old_image)) {
                unlink('uploads-img/' . $old_image);
            }
        }

        $_SESSION['status'] = "Blog Updated Successfully!";
    } else {
        $_SESSION['status'] = "Blog Update Failed!";
    }

    header("Location: blog-edit.php?id=" . $blog_id);
    exit();
}


// =========================== BLOG DELETE ==========================
if (isset($_GET['delete_blog'])) {

    $blog_id = $_GET['delete_blog'];

    $img_query = "SELECT image FROM blogs WHERE id='$blog_id'";
    $img_run = mysqli_query($conn, $img_query);
    $img_data = mysqli_fetch_assoc($img_run);

    $delete_query = "DELETE FROM blogs WHERE id='$blog_id'";
    $delete_run = mysqli_query($conn, $delete_query);

    if ($delete_run) {

        if (!empty($img_data['image']) && file_exists('uploads-img/' . $img_data['image'])) {
            unlink('uploads-img/' . $img_data['image']);
        }

        $_SESSION['status'] = "Blog Deleted Successfully!";
    } else {
        $_SESSION['status'] = "Blog Delete Failed!";
    }

    header("Location: blog.php");
    exit();
}
