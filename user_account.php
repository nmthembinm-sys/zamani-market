<?php if(isset($_GET['success'])) { ?>
    <div class="alert alert-success">
        Profile updated successfully!
    </div>
<?php } ?>
<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found in database.");
}

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM orders WHERE buyer_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$order_count = $stmt->get_result()->fetch_assoc()['total'];


$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM wishlist WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$wishlist_count = $stmt->get_result()->fetch_assoc()['total'];


$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM reviews WHERE buyer_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$review_count = $stmt->get_result()->fetch_assoc()['total'];


$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM disputes WHERE raised_by = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$dispute_count = $stmt->get_result()->fetch_assoc()['total'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My account-Zamani Market</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
body{
    background:#f8f9fa;
}

.profile-card{
    background:white;
    border-radius:15px;
    padding:30px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

.profile-img{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    border:4px solid #a379c3;
}

.stat-card{
    background:white;
    border-radius:10px;
    padding:20px;
    text-align:center;
    box-shadow:0 2px 8px rgba(0,0,0,0.08);
}

.btn-purple{
    background:#a379c3;
    color:white;
}

.btn-purple:hover{
    background:#8f62b4;
    color:white;
}
</style>
</head>
<body>
  <nav class="navbar navbar-expand-md navbar-light fixed-top" style="background-color: #a379c3;">
    <div class="container-fluid">
       <a class="navbar-brand logo-text" href="#">
    Zamani Market
  </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse " id="navbarExampleDefault">
        <ul class="navbar-nav me-auto mb-md-0">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="products.php">Products</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="seller dashboard.php">Sell</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="help.php">Help</a>
            </li>
            </ul>
            <form class="d-flex" role="search">
          <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-dark my-2 my-sm-0" type="submit">Search</button>
        </form>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="wishlist.php"><i class="bi bi-heart-fill"></i></a></li> 
              <li class="nav-item"><a class="nav-link" href="cart.php"><i class="bi bi-cart-fill"></i></a></li>
          <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-person-fill"></i></a></li>
      </ul>
      </div>
      </div>
</nav>

<div class="container py-5">

    <div class="card shadow border-0">
        <div class="card-body text-center">

            

            <h3><?php echo $user['full_name']; ?></h3>

           <p><strong>Name:</strong> <?php echo $user['full_name']; ?></p>

<p><strong>Email:</strong> <?php echo $user['email']; ?></p>

<p><strong>Phone:</strong> <?php echo $user['phone_number']; ?></p>

<p><strong>Member Since:</strong>
<?php echo date("d M Y", strtotime($user['created_at'])); ?>
</p>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal" style="background-color:#a379c3;" class="btn btn-primary">
                Edit Profile
            </button>

        </div>
    </div>

    <div class="row mt-4">

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    Account Information
                </div>

                <div class="card-body">
                    <p><strong>Name:</strong> <?php echo $user['full_name']; ?></p>
<p><strong>Email:</strong> <?php echo $user['email']; ?></p>
<p><strong>Phone:</strong> <?php echo $user['phone_number'] ?? 'Not Added'; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    Shopping Activity
                </div>

                <div class="card-body">

                    <p>Total Orders: <?php echo $order_count; ?></p>
                    <p>Wishlist Items: <?php echo $wishlist_count; ?></p>
                    <p>Reviews Submitted: <?php echo $review_count; ?></p>
                    <p>Open Disputes: <?php echo $dispute_count; ?></p>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header">
            Quick Actions
        </div>

        <div class="list-group list-group-flush">
            <a href="orders.php" class="list-group-item list-group-item-action">
                My Orders
            </a>

            <a href="wishlist.php" class="list-group-item list-group-item-action">
                Wishlist
            </a>

            <a href="reviews.php" class="list-group-item list-group-item-action">
                My Reviews
            </a>

            <a href="addresses.php" class="list-group-item list-group-item-action">
                Saved Addresses
            </a>

            <a href="disputes.php" class="list-group-item list-group-item-action">
                My Disputes
            </a>

            <a href="logout.php" class="list-group-item list-group-item-action text-danger">
                Logout
            </a>
        </div>
    </div>

</div>

<div class="modal fade" id="editProfileModal" tabindex="-1">

  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="update_profile.php">

        <div class="modal-body">

          <div class="mb-3">
            <label>Full Name</label>
            <input type="text" name="full_name" class="form-control"
                   value="<?php echo $user['full_name']; ?>" required>
          </div>

          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?php echo $user['email']; ?>" required>
          </div>

          <div class="mb-3">
            <label>Phone Number</label>
            <input type="text" name="phone_number" class="form-control"
                   value="<?php echo $user['phone_number']; ?>">
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="update_profile" class="btn btn-success">
            Save Changes
          </button>
        </div>

      </form>

    </div>
  </div>

</div>

<footer class="pt-4 my-md-5 pt-md-5 border-top">
  <div class="row">
<div class="col-12 col-md">
</div>
<div class="col-6 col-md">
<h5>Shop</h5>
<li><a class="text-dark text-decoration-none" href="products.php">Products</a></li>
<li><a class="text-dark text-decoration-none" href="products.php">Catagories</a></li>
<li><a class="text-dark text-decoration-none" href="products.php">New Arrivals</a></li>
<li><a class="text-dark text-decoration-none" href="index.php">Featured Items</a></li>
<li><a class="text-dark text-decoration-none" href="index.php">Deals and Promotions</a></li>
</div>
<div class="col-6 col-md">
<h5>Account</h5>
<ul class="list-unstyled text-small">
<li><a class="text-dark text-decoration-none" href="login.php">Sign In</a></li>
<li><a class="text-dark text-decoration-none" href="register.php">Register</a></li>
<li><a class="text-dark text-decoration-none" href="user-account">Order History</a></li>
</ul>
</div>
<div class="col-6 col-md">
<h5>Community</h5>
<ul class="list-unstyled text-small">
<li><a class="text-dark text-decoration-none" href="#">Become a Seller</a></li>
<li><a class="text-dark text-decoration-none" href="#">Seller Guidelines</a></li>
<li><a class="text-dark text-decoration-none" href="#">Community Rules</a></li>
<li><a class="text-dark text-decoration-none" href="#">Report an issue</a></li>
</ul>
</div>
  </div>
</footer>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script> 
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>