<?php
include("config.php");

if(isset($_POST['register'])){

    $fullname = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $phone = isset($_POST['phone_number']) && $_POST['phone_number'] !== ''
    ? $_POST['phone_number']
    : null;

    $stmt = $conn->prepare("
        INSERT INTO users (full_name, email, password, phone_number, role)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sssss", $fullname, $email, $password, $phone, $role);

    if($stmt->execute()){

        $user_id = $stmt->insert_id;

        if($role == "seller"){

            $shop_name = $fullname . "'s Shop";

            $seller_stmt = $conn->prepare("
                INSERT INTO sellers (user_id, shop_name)
                VALUES (?, ?)
            ");

            $seller_stmt->bind_param("is", $user_id, $shop_name);
            $seller_stmt->execute();

            header("Location: seller_dashboard.php");
            exit();
        }

        header("Location: index.php");
        exit();

    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
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
            ← Back to Home
        </a>
    </div>

    <h3 class="text-center mb-4">Create Account</h3>

    <form method="POST">

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="full_name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Phone Number</label>
        <input type="text" name="phone_name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Account Type</label>
        <select name="role" class="form-control" required>
            <option value="buyer">Buyer</option>
            <option value="seller">Seller</option>
        </select>
    </div>

    <button type="submit" name="register" style="background-color:#a379c3;" class="btn btn-success w-100">
        Create Account
    </button>

</form>
</div>

<div class="text-center mt-3">
          <p class="mb-0">Already have an account?</p>
          <a href="login.php" class="text-decoration-none" class="me-3">Login here</a>
          <a href="seller_login.php" class="text-decoration-none" class="me-3">(Click Here for sellers)</a>
        </div>

      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>