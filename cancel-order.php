<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

/* VERIFY ORDER */
$stmt = $conn->prepare("
    SELECT * FROM orders
    WHERE id = ? AND user_id = ?
");

$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();

$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header("Location: orders.php");
    exit();
}

/* ALLOW ONLY PENDING / PROCESSING */
if (
    $order['status'] != 'Pending' &&
    $order['status'] != 'Processing'
) {
    header("Location: orders.php");
    exit();
}

/* CANCEL ORDER */
$stmt = $conn->prepare("
    UPDATE orders
    SET status = 'Cancelled'
    WHERE id = ?
");

$stmt->bind_param("i", $order_id);
$stmt->execute();

/* RESTORE STOCK */
$items = $conn->query("
    SELECT * FROM order_items
    WHERE order_id = $order_id
");

while ($item = $items->fetch_assoc()) {

    $stmt = $conn->prepare("
        UPDATE products
        SET stock = stock + ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "ii",
        $item['quantity'],
        $item['product_id']
    );

    $stmt->execute();
}

header("Location: orders.php");
exit();
?>