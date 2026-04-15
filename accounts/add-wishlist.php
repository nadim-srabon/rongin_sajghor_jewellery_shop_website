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

    // Check if already exists
    $check = $conn->prepare("
        SELECT id FROM wishlist 
        WHERE user_id = ? AND product_id = ?
    ");
    $check->bind_param("ii", $user_id, $product_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows == 0) {
        $stmt = $conn->prepare("
            INSERT INTO wishlist (user_id, product_id)
            VALUES (?, ?)
        ");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}