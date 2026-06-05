<?php
session_start();
include("config.php");

if(isset($_POST['submit_review'])) {

    $buyer_id   = $_SESSION['user_id']; // adjust if your session name differs
    $listing_id = $_POST['listing_id'];
    $order_id   = $_POST['order_id'];
    $rating     = $_POST['rating'];
    $comment    = $_POST['comment'];

    $stmt = $conn->prepare("
        INSERT INTO reviews (buyer_id, listing_id, order_id, rating, comment)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("iiiis", $buyer_id, $listing_id, $order_id, $rating, $comment);
    $stmt->execute();
}

$sql = "SELECT * FROM reviews ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reviews-Zamani Market</title>
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
              <li class="nav-item"><a class="nav-link" href="cart.php"><i class="bi bi-cart-fill"></i></a></li>
          <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-person-fill"></i></a></li>
      </ul>
      </div>
      </div>
</nav>
<main>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Product Reviews</h2>
<div class="card p-3 mb-4">
        <h5>Leave a Review</h5>

        <form method="POST">

            <div class="row">
                <div class="col-md-4">
                    <input type="number" name="listing_id" class="form-control" placeholder="Listing ID" required>
                </div>

                <div class="col-md-4">
                    <input type="number" name="order_id" class="form-control" placeholder="Order ID" required>
                </div>

                <div class="col-md-4">
                    <select name="rating" class="form-control" required>
                        <option value="">Rating</option>
                        <option value="1">1 ⭐</option>
                        <option value="2">2 ⭐⭐</option>
                        <option value="3">3 ⭐⭐⭐</option>
                        <option value="4">4 ⭐⭐⭐⭐</option>
                        <option value="5">5 ⭐⭐⭐⭐⭐</option>
                    </select>
                </div>
            </div>
<textarea name="comment" class="form-control mt-3" placeholder="Write comment (optional)"></textarea>
<button type="submit" name="submit_review" class="btn btn-success mt-3">
                Submit Review
            </button>
</form>
    </div>
     <?php while($row = mysqli_fetch_assoc($result)) { ?>

        <div class="card mb-3 p-3">

            <div class="d-flex justify-content-between">
                <strong>Buyer ID: <?php echo $row['buyer_id']; ?></strong>
                <span><?php echo $row['created_at']; ?></span>
            </div>

            <div>
                Rating:
                <?php
                for($i = 1; $i <= 5; $i++) {
                    echo $i <= $row['rating'] ? "⭐" : "☆";
                }
                ?>
            </div>

            <p class="mt-2">
                <?php echo htmlspecialchars($row['comment']); ?>
            </p>

        </div>

    <?php } ?>

</div>
</main>
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