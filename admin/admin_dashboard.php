<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];

$sql = "SELECT au.*, ar.role_name
        FROM admin_users au
        JOIN admin_roles ar ON au.role_id = ar.role_id
        WHERE au.user_id = '$user_id'";

$result = mysqli_query($conn, $sql);

$isAdmin = ($result && mysqli_num_rows($result) > 0);

$users = $conn->query("SELECT COUNT(*) AS total FROM users")
              ->fetch_assoc()['total'] ?? 0;

$listings = $conn->query("SELECT COUNT(*) AS total FROM listings")
                 ->fetch_assoc()['total'] ?? 0;

$orders = $conn->query("SELECT COUNT(*) AS total FROM orders")
               ->fetch_assoc()['total'] ?? 0;

$disputes = $conn->query("SELECT COUNT(*) AS total FROM disputes")
                 ->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Zamani Market Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:white;
        }

        .sidebar{
            width:250px;
            min-height:100vh;
            background:#a379c3;
        }

        .sidebar a{
            color:white;
            text-decoration:none;
            display:block;
            padding:12px 20px;
        }

        .sidebar a:hover{
            background:#343a40;
        }

        .content{
            flex:1;
            padding:25px;
        }

        .stat-card{
            border:none;
            border-radius:15px;
        }
    </style>
</head>

<body>

<div class="d-flex">

    <div class="sidebar">

        <h3 class="text-white text-center py-4">
            Zamani Market Admin
        </h3>

        <a href="admin dashboard.php">Dashboard</a>

        <a href="manage users.php">Manage Users</a>

        <a href="manage_products.php">Products</a>

        <a href="manage_orders.php">Orders</a>

        <a href="manage_sellers.php">Sellers</a>

        <a href="manage_disputes.php">Disputes</a>

        <a href="categories.php">Categories</a>

        <a href="reports.php">Reports</a>

        <a href="settings.php">Settings</a>

        <a href="../logout.php" class="text-danger">
            Logout
        </a>

    </div>

    <!-- CONTENT -->
    <div class="content">

        <h2 class="mb-4">
            Admin Dashboard
        </h2>

        <div class="row">

            <div class="col-md-3 mb-3">
                <div class="card stat-card text-white" style="background-color: #a379c3;">
                    <div class="card-body">
                        <h5>Total Users</h5>
                        <h2><?= $users ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card stat-card text-white" style="background-color: #a379c3;">
                    <div class="card-body">
                        <h5>Products</h5>
                        <h2><?= $listings ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card stat-card text-white" style="background-color: #a379c3;">
                    <div class="card-body">
                        <h5>Orders</h5>
                        <h2><?= $orders ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card stat-card text-white" style="background-color: #a379c3;">
                    <div class="card-body">
                        <h5>Disputes</h5>
                        <h2><?= $disputes ?></h2>
                    </div>
                </div>
            </div>

        </div>

       
        <div class="card mt-4">
            <div class="card-header">
                Recent Activity
            </div>

            <div class="card-body">

                <ul class="list-group">

                    <li class="list-group-item">
                        New users registered
                    </li>

                    <li class="list-group-item">
                        New products listed
                    </li>

                    <li class="list-group-item">
                        Orders placed
                    </li>

                    <li class="list-group-item">
                        Open disputes
                    </li>

                </ul>

            </div>
        </div>

    </div>

</div>

</body>
</html>