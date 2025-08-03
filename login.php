<?php
include 'connection.php';
session_start(); // Optional if you want to store session info

$error = ""; // Variable to store error message

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $pwd = $_POST['password'];

    $query = "SELECT * FROM student WHERE email = '$email' AND password = '$pwd'";
    $data = mysqli_query($con, $query);

    if (mysqli_num_rows($data) > 0) {
        // Optional: Store user info in session
        $_SESSION['email'] = $email;
$_SESSION['loggedin'] = true;
        // Redirect to index.php
        header("Location: index.php");
        exit();
    } else {
        $error = "Please login correctly.";
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container my-3">
        <h1>Login Form</h1>
        <?php if ($error != ""): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post">
          <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
