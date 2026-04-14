<?php
session_start();

if (isset($_POST['product_id'], $_POST['quantity'])) {

    $id = (int) $_POST['product_id'];
    $qty = (int) $_POST['quantity'];

    if ($qty > 0) {
        $_SESSION['cart'][$id] = $qty;
    }
}

header("Location: cart.php");
exit();