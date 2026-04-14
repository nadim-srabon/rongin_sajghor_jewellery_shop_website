<?php
session_start();
require_once '../config/db.php';
require_once '../includes/mail.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$order_id = (int) $_POST['order_id'];
$status = $_POST['status'];

/* GET ORDER + USER */
$stmt = $conn->prepare("
    SELECT orders.*, users.email, users.name
    FROM orders
    JOIN users ON orders.user_id = users.id
    WHERE orders.id = ?
");

$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

/* UPDATE STATUS */
$stmt = $conn->prepare("
    UPDATE orders 
    SET status = ?, status_updated_at = NOW()
    WHERE id = ?
");

$stmt->bind_param("si", $status, $order_id);
$stmt->execute();

/* EMAIL CONTENT */
$subject = "Your Order #$order_id is $status";

$body = "
<h2>Order Update</h2>
<p>Dear {$order['name']},</p>

<p>Your order status has been updated.</p>

<h3>Order ID: #$order_id</h3>
<h3>Status: $status</h3>

<p>Thank you for shopping with us ❤️</p>
";

/* SEND EMAIL */
sendMail($order['email'], $subject, $body);

header("Location: orders.php?msg=updated");
exit();
?>