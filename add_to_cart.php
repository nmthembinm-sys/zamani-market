<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("config.php");

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die("You must be logged in.");
}

$listing_id = $_GET['id'] ?? null;

if (!$listing_id) {
    die("Invalid product.");
}

/* CHECK IF ITEM EXISTS IN CART */
$check = $conn->prepare("
    SELECT * FROM cart 
    WHERE user_id = ? AND listing_id = ?
");

$check->bind_param("ii", $user_id, $listing_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {

    /* UPDATE QUANTITY */
    $update = $conn->prepare("
        UPDATE cart 
        SET quantity = quantity + 1 
        WHERE user_id = ? AND listing_id = ?
    ");

    $update->bind_param("ii", $user_id, $listing_id);
    $update->execute();

} else {

    /* INSERT NEW ITEM */
    $insert = $conn->prepare("
        INSERT INTO cart (user_id, listing_id, quantity)
        VALUES (?, ?, 1)
    ");

    $insert->bind_param("ii", $user_id, $listing_id);
    $insert->execute();
}

header("Location: cart.php");
exit();
?>