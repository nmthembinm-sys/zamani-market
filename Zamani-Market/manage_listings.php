<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get seller
$seller = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM sellers WHERE user_id='$user_id'"
));

$seller_id = $seller['seller_id'] ?? 0;

// Delete listing (basic action)
if (isset($_GET['delete'])) {
    $listing_id = $_GET['delete'];

    mysqli_query($conn, "
        DELETE FROM listings 
        WHERE listing_id='$listing_id' 
        AND seller_id='$seller_id'
    ");

    header("Location: manage_listings.php");
    exit();
}

// Get listings
$listings = mysqli_query($conn, "
    SELECT * FROM listings 
    WHERE seller_id='$seller_id'
    ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Listings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4><?= htmlspecialchars($seller['shop_name'] ?? 'Seller') ?></h4>
    <hr>

    <a href="seller_dashboard.php">Dashboard</a>
    <a href="add_listing.php">Add Listing</a>
    <a href="manage_listings.php">Manage Listings</a>
    <a href="delivery_tracking.php">Delivery</a>
</div>

<!-- MAIN -->
<div class="main">

    <h2>Manage Listings</h2>
    <p>View, edit, and delete your products</p>

    <div class="table-container mt-3">

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php while($row = mysqli_fetch_assoc($listings)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td>R <?= $row['price'] ?></td>
                        <td><?= $row['stock_qty'] ?></td>
                        <td>
                            <span class="badge bg-info">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                        <td><?= $row['created_at'] ?></td>
                        <td>

                            <!-- EDIT -->
                            <a href="edit_listing.php?id=<?= $row['listing_id'] ?>"
                               class="btn btn-sm btn-primary">
                                Edit
                            </a>

                            <!-- DELETE -->
                            <a href="manage_listings.php?delete=<?= $row['listing_id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Delete this listing?')">
                                Delete
                            </a>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>

        </table>

    </div>

</div>

</body>
</html>