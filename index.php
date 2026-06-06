<?php
session_start();
include("config.php");

// Check login status
$isLoggedIn = isset($_SESSION['user_id']);
?>    
</div>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Zamani Market</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
            <a class="nav-link" href="seller_login.php">Sell</a>
          </li>
          <li class="nav-item">
            <li class="nav-item">
            <a class="nav-link" href="user_account.php">My Account</a>
          </li>
       <li class="nav-item">
            <li class="nav-item">
            <a class="nav-link" href="admin/admin_login.php">admin dashboard</a>
          </li>
            </ul>
      <
           <form class="d-flex" method="GET" action="search_products.php">
    <input 
        type="text" 
        name="query" 
        placeholder="Search products..."
        required
        class="form-control me-2"
    >
    <button class="btn btn-outline" type="submit">Search</button>
</form>
          <ul class="navbar-nav ms-auto">

    <li class="nav-item">
        <a class="nav-link" href="wishlist.php">
            <i class="bi bi-heart-fill"></i>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="cart.php">
            <i class="bi bi-cart-fill"></i>
        </a>
    </li>

    <?php if ($isLoggedIn): ?>

        <li class="nav-item">
            <span class="nav-link">
                Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>
            </span>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
        </li>

    <?php else: ?>

        <li class="nav-item">
            <a class="nav-link" href="login.php">
                <i class="bi bi-person-fill"></i> Login
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="register.php">Register</a>
        </li>

    <?php endif; ?>

</ul>
</nav>
      
    <div class="bg-light border-bottom">
<div class="container">
  <ul class="nav justify-content-center flex-wrap py-2">
<li class="nav-item"><a class="nav-link text-dark" href="#">Fashion</a></li>
<li class="nav-item"><a class="nav-link text-dark" href="#">Electronics</a></li>
<li class="nav-item"><a class="nav-link text-dark" href="#">Home & Living</a></li>
<li class="nav-item"><a class="nav-link text-dark" href="#">Beauty</a></li>
<li class="nav-item"><a class="nav-link text-dark" href="#">Health</a></li>
<li class="nav-item"><a class="nav-link text-dark" href="#">Sports and Outdoors</a></li>
<li class="nav-item"><a class="nav-link text-dark" href="#">Books and Education</a></li>
</ul>
</div>
</div>

<main role="main" style="margin-top: 8px;">

<section>
  
  <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
<div class="carousel-indicators">
  <button type="button" data-bs-type="#myCarousel" data-bs-slide-to="0" class="active"></button>
<button type="button" data-bs-target="#mycarousel" data-bs-slide-to="1"></button>
</div>
<div class="carousel-inner">
  <div class="carousel-item active">
<div style="height:300px; background:#a379c3">
<div class="carousel-caption">
<h1>Zamani Market</h1>
<p> Shop South Africa's marketplace</p>
<a class="btn btn-outline-dark btn-lg" href="register.php"> Sign up Today</a>
</div>
</div>
  </div>
  <div class="carousel-item">
<div style="height:300px;background:#a379c3">
<div class="carousel-caption">
<h1>Join the Sellers Club</h1>
<p>Start selling your products today</p>
<a class="btn btn-outline-dark btn-lg">Become a seller</a>
</div>
</div>
  </div>
</div>
  </div>
</section>


<section id="products">

<div class="album py-5 bg-light">

<div class="container">

    <h2 class="fs-1"><strong>Today's Picks</strong></h2>

    <div class="row row-cols-1 row-cols-md-3 g-4">

    <?php
$sql = "SELECT l.listing_id, l.title, l.price, li.image_path
        FROM listings l
        LEFT JOIN listing_images li
        ON l.listing_id = li.listing_id
        AND li.is_main = 1
        ORDER BY l.created_at DESC
        LIMIT 6";

$result = $conn->query($sql);
?>

<div class="products">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            
            <div class="product">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p>R <?php echo $row['price']; ?></p>

                <?php if (!empty($row['image_path'])): ?>
                    <img src="<?php echo $row['image_path']; ?>" width="150">
                <?php endif; ?>
            </div>

        <?php endwhile; ?>
    <?php else: ?>
        <p>No products available.</p>
    <?php endif; ?>
</div>

</div>

</div>

</section>
</main>
<footer class="pt-4 my-md-5 pt-md-5 border-top" style="background-color: #a379c3;">
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
<li><a class="text-dark text-decoration-none" href="Resgister.php">Become a Seller</a></li>
<li><a class="text-dark text-decoration-none" href="Seller_dasboard.php">Seller Dasboard</a></li>
<li><a class="text-dark text-decoration-none" href="disputes.php">Disputes</a></li>
<li><a class="text-dark text-decoration-none" href="admin_dashboard.php">Admin</a></li>
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