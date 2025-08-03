<?php
session_start();
include 'connection.php';
// Redirect to login if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}
// Get the ID from the URL
$id = $_GET['id'];

// Fetch existing data
$query = mysqli_query($con, "SELECT * FROM student WHERE id = $id");
$data = mysqli_fetch_assoc($query);

// Update data on form submission
if (isset($_POST['update'])) {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $mobile   = $_POST['mobile'];
    $password = $_POST['password'];

    // Handle image update
    $image    = $_FILES['image']['name'];
    $tmpName  = $_FILES['image']['tmp_name'];
    $folder   = 'upload/';
    $newImage = $data['image']; // default is old image

    // If new image selected, upload it and replace the old one
    if (!empty($image)) {
        $newImage = uniqid() . '_' . $image;
        move_uploaded_file($tmpName, $folder . $newImage);
    }

    // Update query
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
</head>
<body>
  <div class="container my-3">
    <h1>Edit Student</h1>
    <form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="<?= $data['name'] ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= $data['email'] ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Mobile</label>
        <input type="number" name="mobile" class="form-control" value="<?= $data['mobile'] ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Current Image</label><br>
        <img src="upload/<?= $data['image'] ?>" width="100">
      </div>
      <div class="mb-3">
        <label class="form-label">Change Image (optional)</label>
        <input type="file" name="image" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" value="<?= $data['password'] ?>">
      </div>
      <button type="submit" name="update" class="btn btn-success">Update</button>
    </form>
  </div>
</body>
</html>

