<?php
include("config.php");

if (isset($_GET['id'])) {

    $id = $_GET['id'];
$sql = "SELECT l.*, li.image_path
        FROM listings l
        LEFT JOIN listing_images li
        ON l.listing_id = li.listing_id
        AND li.is_main = 1
        WHERE l.listing_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("Product not found");
    }

} else {
    die("Invalid product");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title><?php echo $product['product_name']; ?></title>
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
            <a class="nav-link" href="user_account.php">My Account</a>
            </li>
            </ul>
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
           <li class="nav-item"><a class="nav-link" href="wishlist.php"><i class="bi bi-heart-fill"></i></a></li>   
          <li class="nav-item"><a class="nav-link" href="cart.php"><i class="bi bi-cart-fill"></i></a></li>
          <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-person-fill"></i></a></li>
      </ul>
      </div>
      </div>
</nav>
      
<div class="row">

    <!-- IMAGE -->
    <div class="col-md-6">

        <img src="<?= htmlspecialchars($product['image_path'] ?? 'default.png') ?>"
             class="img-fluid rounded"
             style="max-height:400px; object-fit:cover;">

    </div>

    <!-- DETAILS -->
    <div class="col-md-6">

        <h2><?= htmlspecialchars($product['title']) ?></h2>

        <h4 class="text-success">
            R<?= number_format($product['price'],2) ?>
        </h4>

        <p>
            <?= htmlspecialchars($product['description']) ?>
        </p>

        <p>
            <strong>Stock:</strong>
            <?= $product['stock_qty'] ?>
        </p>

        <!-- ADD TO CART -->
        <a href="add_to_cart.php?id=<?= $product['listing_id'] ?>"
           class="btn btn-success">
           Add to Cart
        </a>

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