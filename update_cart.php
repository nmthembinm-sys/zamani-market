<?php
include("config.php");

if(isset($_POST['cart_id']) && isset($_POST['action']))
{
    $cart_id = $_POST['cart_id'];
    $action = $_POST['action'];

    $sql = "SELECT quantity FROM cart WHERE cart_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$cart_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $qty = $row['quantity'];

    if($action == "increase"){
        $qty++;
    }

    if($action == "decrease" && $qty > 1){
        $qty--;
    }

    $update = $conn->prepare(
        "UPDATE cart SET quantity=? WHERE cart_id=?"
    );

    $update->bind_param("ii",$qty,$cart_id);
    $update->execute();

    echo "success";
}
?>