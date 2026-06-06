<?php
session_start();
include("../config.php");

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {

    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        header("Location: ../index.php");
        exit();
    }
}

$error = "";

if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        // password check
        if ($password == $user['password']) {

            if ($user['role'] !== 'admin') {
                $error = "Access denied: Not an admin account.";
            } else {

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];

                header("Location: admin_dashboard.php");
                exit();
            }

        } else {
            $error = "Invalid password";
        }

    } else {
        $error = "Email not found";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link rel="stylesheet" href="css/style.css">
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">

      <div class="card shadow p-4">
        <div class="mb-3">
          <a href="index.php" class="text-dark text-decoration-none">
  <i class="bi bi-arrow-left-circle fs-2"></i>
</a>
        </div>

        <h3 class="text-center mb-4">login</h3>

<form method="POST">

  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" placeholder="Enter email" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
  </div>

  <button type="submit" name="login" class="btn w-100"
    style="background-color:#a379c3;" color:white;">
    Login
  </button>

</form>
        <div class="text-center mt-3">
<p>Dont have an account?</p>
          <a href="register.php" class="text-decoration-none">Create Account</a>
        </div>

      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>