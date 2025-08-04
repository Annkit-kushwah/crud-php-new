<?php
session_start();
include 'connection.php';

// Redirect to login if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['sumbit'])) {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $mobile   = $_POST['mobile'];
    $password = $_POST['password'];

    // Handle image upload
    $image    = $_FILES['image']['name'];
    $tmpName  = $_FILES['image']['tmp_name'];
    $folder   = 'upload/';
    $newImage = uniqid() . '_' . $image;

    if (!file_exists($folder)) mkdir($folder);

    if (move_uploaded_file($tmpName, $folder . $newImage)) {
        $sql = "INSERT INTO student (name, email, mobile, image, password) 
                VALUES ('$name', '$email', '$mobile', '$newImage', '$password')";

        if (mysqli_query($con, $sql)) {
            header("Location: index.php");
            exit;
        } else {
            echo "DB Error!";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Student Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
</head>
<body>
  <div class="container my-3">
    <h1>Student Form</h1>
    <form method="post" enctype="multipart/form-data" id="studentForm" novalidate>
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" name="name" class="form-control" id="name" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" id="email" required>
      </div>
      <div class="mb-3">
        <label for="mobile" class="form-label">Mobile</label>
        <input type="number" name="mobile" class="form-control" id="mobile" required>
      </div>
      <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" name="image" class="form-control" id="image" required>
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" id="exampleInputPassword1" required>
      </div>
      <button type="submit" name="sumbit" class="btn btn-primary">Submit</button>
    </form>
  </div>

  <script>
    $(document).ready(function () {
      $("#studentForm").validate({
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
          image: {
            required: true,
            extension: "jpg|jpeg|png|gif"
          },
          password: {
            required: true,
            minlength: 6
          }
        },
        messages: {
          name: "Please enter at least 2 characters",
          email: "Please enter a valid email",
          mobile: "Enter a valid 10-digit mobile number",
          image: "Upload a valid image file (jpg, jpeg, png, gif)",
          password: "Password must be at least 6 characters"
        },
        errorClass: "text-danger"
      });
    });
  </script>
</body>
</html>
