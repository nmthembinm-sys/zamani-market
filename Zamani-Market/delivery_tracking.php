<?php
include("config.php");

$booking = null;
$error = "";

if (isset($_GET['waybill']) || isset($_GET['order_id'])) {

    $waybill = $_GET['waybill'] ?? null;
    $order_id = $_GET['order_id'] ?? null;

    if ($waybill) {
        $sql = "SELECT * FROM courier_bookings WHERE waybill_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $waybill);
    } else {
        $sql = "SELECT * FROM courier_bookings WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    if (!$booking) {
        $error = "No delivery found for your search.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delivery Tracking</title>
    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
            padding: 20px;
        }

        .box {
            max-width: 750px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        input {
            width: 70%;
            padding: 10px;
        }

        button {
            padding: 10px 15px;
            background: #a379c3;
            color: white;
            border: none;
            cursor: pointer;
        }

        .step {
            padding: 10px;
            margin: 8px 0;
            border-left: 5px solid #ccc;
            background: #f9f9f9;
        }

        .active {
            border-left-color: green;
            background: #eaffea;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="box">

    <h2>📦 Delivery Tracking</h2>

    <form method="GET">
        <input type="text" name="waybill" placeholder="Enter Waybill Number">
        <button type="submit">Track</button>
    </form>

    <hr>

    <?php if ($booking): ?>

        <h3>Booking ID: <?= $booking['booking_id'] ?></h3>

        <p><b>Waybill:</b> <?= $booking['waybill_number'] ?></p>
        <p><b>Courier:</b> <?= $booking['courier_name'] ?></p>

        <p><b>Pickup Address:</b> <?= $booking['pickup_address'] ?></p>
        <p><b>Delivery Address:</b> <?= $booking['delivery_address'] ?></p>

        <p><b>Booked At:</b> <?= $booking['booked_at'] ?></p>
        <p><b>Delivered At:</b> <?= $booking['delivered_at'] ?? 'Not delivered yet' ?></p>

        <hr>

        <h3>Status</h3>

        <div class="step <?= $booking['status'] == 'booked' ? 'active' : '' ?>">
            📦 Booked
        </div>

        <div class="step <?= in_array($booking['status'], ['picked_up','in_transit','delivered']) ? 'active' : '' ?>">
            🚚 Picked Up
        </div>

        <div class="step <?= in_array($booking['status'], ['in_transit','delivered']) ? 'active' : '' ?>">
            🚛 In Transit
        </div>

        <div class="step <?= $booking['status'] == 'delivered' ? 'active' : '' ?>">
            ✅ Delivered
        </div>

    <?php elseif ($error): ?>

        <p class="error"><?= $error ?></p>

    <?php endif; ?>

</div>

</body>
</html>