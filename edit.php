<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$query = mysqli_query($con, "SELECT * FROM student WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $mobile   = $_POST['mobile'];
    $password = $_POST['password'];

    $image    = $_FILES['image']['name'];
    $tmpName  = $_FILES['image']['tmp_name'];
    $folder   = 'upload/';
    $newImage = $data['image'];

    if (!empty($image)) {
        $newImage = uniqid() . '_' . $image;
        move_uploaded_file($tmpName, $folder . $newImage);
    }

    $sql = "UPDATE student SET 
                name='$name', 
                email='$email', 
                mobile='$mobile', 
                image='$newImage', 
                password='$password' 
            WHERE id=$id";

    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Data updated successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "Update failed!";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
</head>
<body>
  <div class="container my-3">
    <h1>Edit Student</h1>
    <form method="post" enctype="multipart/form-data" id="editForm">
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="<?= $data['name'] ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= $data['email'] ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Mobile</label>
        <input type="number" name="mobile" class="form-control" value="<?= $data['mobile'] ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Current Image</label><br>
        <img src="upload/<?= $data['image'] ?>" width="100">
      </div>
      <div class="mb-3">
        <label class="form-label">Change Image (optional)</label>
        <input type="file" name="image" class="form-control" accept="image/*">
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" value="<?= $data['password'] ?>" required minlength="4">
      </div>
      <button type="submit" name="update" class="btn btn-success">Update</button>
    </form>
  </div>

  <script>
    $(document).ready(function () {
      $('#editForm').validate({
        rules: {
          name: {
            required: true,
            minlength: 2
          },
          email: {
            required: true,
            email: true
          },
          mobile: {
            required: true,
            digits: true,
            minlength: 10,
            maxlength: 10
          },
          password: {
            required: true,
            minlength: 4
          },
          image: {
            extension: "jpg|jpeg|png|gif"
          }
        },
        messages: {
          name: "Please enter at least 2 characters",
          email: "Please enter a valid email",
          mobile: "Please enter a valid 10-digit mobile number",
          password: "Password must be at least 4 characters",
          image: "Only image files are allowed (jpg, jpeg, png, gif)"
        }
      });
    });
  </script>
</body>
</html>
