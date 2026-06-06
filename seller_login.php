<?php
session_start();
include("config.php");

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        // SAVE SESSION
        $_SESSION['user_id'] = $user['user_id'];

        // CHECK IF SELLER
        $checkSeller = mysqli_query($conn,
            "SELECT * FROM sellers WHERE user_id = {$user['user_id']}"
        );

        if (mysqli_num_rows($checkSeller) > 0) {

            $_SESSION['seller'] = true;

            header("Location: seller_dashboard.php");
            exit();

        } else {
            header("Location: index.php"); // normal user
            exit();
        }

    } else {
        echo "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login-Zamani Market</title>
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

        <h3 class="text-center mb-4">Login</h3>

<form method="POST">

  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" placeholder="Enter email" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
  </div>
<form method="POST">
  <button type="submit" name="login" class="btn w-100"
    style="background-color:#a379c3; color:white;">
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