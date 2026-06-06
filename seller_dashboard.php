<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get seller info
$seller = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM sellers WHERE user_id='$user_id'"
));

$seller_id = $seller['seller_id'] ?? 0;
$salesData = array_fill(1, 12, 0);

$result = mysqli_query($conn,"
    SELECT 
        MONTH(created_at) AS month,
        COUNT(order_id) AS total_sales
    FROM orders
    WHERE seller_id = '$seller_id'
    AND order_status = 'delivered'
    GROUP BY MONTH(created_at)
");

while ($row = mysqli_fetch_assoc($result)) {
    $salesData[(int)$row['month']] = (int)$row['total_sales'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Seller Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { background:#f4f4f4; }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #a379c3;
            color: black;
            padding: 20px;
        }
        .sidebar a {
            color: black;
            display: block;
            padding: 10px;
            text-decoration: none;
        }
        .sidebar a:hover { background: #444; }
        .main {
            margin-left: 260px;
            padding: 20px;
        }
        .card { border-radius: 12px; }
    </style>
</head>

<body>


<div class="sidebar">
    <h4><?= htmlspecialchars($seller['shop_name'] ?? 'Seller') ?></h4>
    <hr>

    <a href="add_listing.php">Add Listing</a>
    <a href="manage_listings.php">Manage Listings</a>
    <a href="delivery_tracking.php">Delivery Tracking</a>
</div>

<div class="main">

    <h2>Welcome, <?= htmlspecialchars($seller['shop_name'] ?? 'Seller') ?></h2>

    <div class="row mt-4">

        <?php
        $totalListings = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT COUNT(*) as c FROM listings WHERE seller_id='$seller_id'"
        ))['c'];

        $totalSales = $seller['total_sales'];

        $avgRating = $seller['rating'];
        ?>

        <div class="col-md-4">
            <div class="card p-3 bg-white">
                <h5>Listings</h5>
                <h3><?= $totalListings ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 bg-white">
                <h5>Total Sales</h5>
                <h3><?= $totalSales ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 bg-white">
                <h5>Rating</h5>
                <h3><?= $avgRating ?></h3>
            </div>
        </div>
    </div>

    <div class="card p-3 mt-4">
        <h5>Sales Analytics (Monthly)</h5>
        <canvas id="salesChart"></canvas>
    </div>

    <div class="card p-3 mt-4">
        <h5>Delivery Status Overview</h5>

        <?php
        $delivery = mysqli_query($conn,"
    SELECT cb.status, COUNT(*) as total
    FROM courier_bookings cb
    JOIN listings l ON l.listing_id = cb.order_id
    WHERE l.seller_id = '$seller_id'
    GROUP BY cb.status
");
        ?>

        <ul class="list-group">
            <?php while($d = mysqli_fetch_assoc($delivery)) { ?>
                <li class="list-group-item d-flex justify-content-between">
                    <?= ucfirst($d['status']) ?>
                    <span class="badge bg-primary"><?= $d['total'] ?></span>
                </li>
            <?php } ?>
        </ul>
    </div>

</div>

<!-- CHART DATA -->
<script>
const ctx = document.getElementById('salesChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["Jan","Feb","Mar","Apr","May","Jun"],
        datasets: [{
            label: 'Sales',
            data: [
    <?= $salesData[1] ?>,
    <?= $salesData[2] ?>,
    <?= $salesData[3] ?>,
    <?= $salesData[4] ?>,
    <?= $salesData[5] ?>,
    <?= $salesData[6] ?>,
    <?= $salesData[7] ?>,
    <?= $salesData[8] ?>,
    <?= $salesData[9] ?>,
    <?= $salesData[10] ?>,
    <?= $salesData[11] ?>,
    <?= $salesData[12] ?>
],
            borderColor: 'blue',
            tension: 0.3
        }]
    }
});
</script>

</body>
</html>
