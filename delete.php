<?php
session_start();
require_once '../config/db.php';

/* AUTH CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

/* CHECK ID */
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = (int) $_GET['id'];

/* GET PRODUCT (FOR IMAGE DELETE) */
$stmt = $conn->prepare("SELECT image, hover_image FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

/* DELETE IMAGES */
if (!empty($product['image']) && file_exists("../assets/images/" . $product['image'])) {
    unlink("../assets/images/" . $product['image']);
}

if (!empty($product['hover_image']) && file_exists("../assets/images/" . $product['hover_image'])) {
    unlink("../assets/images/" . $product['hover_image']);
}

/* DELETE PRODUCT */
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();

header("Location: products.php?msg=deleted");
exit();
?>