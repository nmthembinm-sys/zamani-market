<?php
session_start();
include("config.php");

$user_id = $_SESSION['user_id'] ?? null;
$subtotal = 0;

if (!$user_id) {
    die("You must be logged in.");
}

$sql = "SELECT c.cart_id, c.quantity,
               l.listing_id,
               l.title,
               l.price,
               li.image_path
        FROM cart c
        INNER JOIN listings l 
            ON c.listing_id = l.listing_id
        LEFT JOIN listing_images li 
            ON l.listing_id = li.listing_id 
            AND li.is_main = 1
        WHERE c.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Zamani Market</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .cart-item img {
    max-width: 90px;
    height: auto;
}

.quantity-input {
    width: 60px;
}

.cart-summary {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
}

body {
    background: #f5f5f5;
}
</style>
</head>
<body>

<div class="container py-5">

<h2 class="mb-4">🛒 Your Shopping Cart</h2>

<div class="row">

<div class="col-lg-8">

<div class="card shadow-sm">
<div class="card-body">

<?php
if($result->num_rows > 0){

while($row = $result->fetch_assoc()){

$itemTotal = $row['price'] * $row['quantity'];
$subtotal += $itemTotal;
?>

<div class="row align-items-center mb-3 cart-item">

<div class="col-md-3">
     <?php
                $image = !empty($row['image_path']) ? $row['image_path'] : 'default.png';
                ?>

                <img src="<?= htmlspecialchars($image) ?>"
                     class="card-img-top"
                     style="height:250px; object-fit:cover;">

</div>

<div class="col-md-4">
<h5><?php echo $row['listing_id']; ?></h5>
<p>R <?php echo number_format($row['price'],2); ?></p>
</div>

<div class="col-md-3">

<div class="input-group">

<button class="btn btn-outline-secondary btn-sm qty-btn"
        data-id="<?php echo $row['cart_id']; ?>"
        data-action="decrease">
-
</button>

<input type="text"
       class="form-control text-center quantity-input"
       value="<?php echo $row['quantity']; ?>"
       readonly>

<button class="btn btn-outline-secondary btn-sm qty-btn"
        data-id="<?php echo $row['cart_id']; ?>"
        data-action="increase">
+
</button>

</div>

</div>

<div class="col-md-2 text-end">

<strong>
R <?php echo number_format($itemTotal,2); ?>
</strong>

<br>

<a href="remove_cart.php?id=<?php echo $row['cart_id']; ?>"
   class="btn btn-sm btn-danger mt-2">
    <i class="bi bi-trash"></i>
</a>

</div>

</div>

<hr>

<?php
}
}
else{
echo "<h5>Your cart is empty.</h5>";
}
?>

</div>
</div>

<a href="products.php" class="btn btn-outline-primary mt-3">
Continue Shopping
</a>

</div>

<div class="col-lg-4">

<?php
$shipping = 10;
$tax = 20;
$total = $subtotal + $shipping + $tax;
?>

<div class="card shadow-sm">

<div class="card-body">

<h5>Order Summary</h5>

<div class="d-flex justify-content-between">
<span>Subtotal</span>
<span>R <?php echo number_format($subtotal,2); ?></span>
</div>

<div class="d-flex justify-content-between">
<span>Shipping</span>
<span>R <?php echo number_format($shipping,2); ?></span>
</div>

<div class="d-flex justify-content-between">
<span>Tax</span>
<span>R <?php echo number_format($tax,2); ?></span>
</div>

<hr>

<div class="d-flex justify-content-between fw-bold">
<span>Total</span>
<span>R <?php echo number_format($total,2); ?></span>
</div>

<button class="btn btn-success w-100 mt-3" style="background-color:#a379c3;" back
        onclick="window.location.href='checkout.php'">
    Proceed to Checkout
</button>

</div>
</div>

</div>

</div>

</div>

<script src="cart.js"></script>

</body>
</html>