<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

if (isset($_GET['id'])) {

    $product_id = (int) $_GET['id'];

    $stmt = $conn->prepare("
        DELETE FROM wishlist
        WHERE user_id = ? AND product_id = ?
    ");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
}

header("Location: wishlist.php");
exit();