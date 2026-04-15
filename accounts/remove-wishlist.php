<?php
session_start();
require_once '../config/db.php';

/* CHECK LOGIN */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

/* DEBUG CHECK */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID");
}

$product_id = (int) $_GET['id'];

/* DELETE QUERY */
$stmt = $conn->prepare("
    DELETE FROM wishlist
    WHERE user_id = ? AND product_id = ?
    LIMIT 1
");

$stmt->bind_param("ii", $user_id, $product_id);

if (!$stmt->execute()) {
    die("Delete failed: " . $stmt->error);
}

/* SUCCESS */
header("Location: wishlist.php");
exit();
?>