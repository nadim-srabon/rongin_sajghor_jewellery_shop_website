<?php
session_start();
include 'config/db.php';

$user_id = $_SESSION['user_id'];

$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$total = 0;

foreach ($_SESSION['cart'] as $id => $qty) {
    $stmt = $conn->prepare("SELECT price FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $total += $product['price'] * $qty;
}

$stmt = $conn->prepare("INSERT INTO orders 
(user_id,total_amount,shipping_name,shipping_phone,shipping_address)
VALUES (?,?,?,?,?)");

$stmt->bind_param("idsss", $user_id, $total, $name, $phone, $address);
$stmt->execute();

$order_id = $conn->insert_id;

foreach ($_SESSION['cart'] as $id => $qty) {
    $stmt = $conn->prepare("SELECT price FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    $stmt2 = $conn->prepare("INSERT INTO order_items 
    (order_id,product_id,quantity,price)
    VALUES (?,?,?,?)");

    $stmt2->bind_param("iiid", $order_id, $id, $qty, $product['price']);
    $stmt2->execute();
}

unset($_SESSION['cart']);

header("Location: order-success.php?id=" . $order_id);
exit();
?>