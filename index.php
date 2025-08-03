<?php
session_start();
include 'connection.php';

// Redirect to login if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// ✅ Handle Delete
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get image filename to delete from folder
    $getImage = mysqli_query($con, "SELECT image FROM student WHERE id = $id");
    $imageData = mysqli_fetch_assoc($getImage);
    $imagePath = 'upload/' . $imageData['image'];

    // Delete image file if exists
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    // Delete DB record
    $sql = mysqli_query($con, "DELETE FROM student WHERE id = $id");

    if ($sql) {
        echo "<script>alert('Record deleted successfully'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Delete failed!');</script>";
    }
}

// ✅ Fetch all students
$query = mysqli_query($con, "SELECT * FROM student");
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Student Listing</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container my-3">
    <h1>Student Listing</h1>
    
    <a href="create.php" class="btn btn-primary mb-3">Add</a>
    <a href="logout.php" class="btn btn-danger mb-3">Logout</a>
    
    <table class="table table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Sr No</th>
          <th>Name</th>
          <th>Email</th>
          <th>Image</th>
          <th>Mobile</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i = 1;
        while ($row = mysqli_fetch_assoc($query)) {
          echo "<tr>
                  <td>{$i}</td>
                  <td>{$row['name']}</td>
                  <td>{$row['email']}</td>
                  <td><img src='upload/{$row['image']}' width='70' height='70'></td>
                  <td>{$row['mobile']}</td>
                  <td>
                      <a href='edit.php?id={$row['id']}' class='btn btn-sm btn-warning'>Edit</a>
                      <a href='index.php?id={$row['id']}' onclick=\"return confirm('Are you sure you want to delete this record?')\" class='btn btn-sm btn-danger'>Delete</a>
                  </td>
                </tr>";
          $i++;
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
