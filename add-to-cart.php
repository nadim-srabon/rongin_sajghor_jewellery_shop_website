<?php
session_start();
require_once 'config/db.php';
include 'includes/header.php';

?>

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {

    $product_id = (int) $_POST['product_id'];

    // Create cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Increase quantity if already exists
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    header("Location: cart.php");
    exit();
}

// fallback
header("Location: accounts/dashboard.php");
exit();
?>

<?php include 'includes/footer.php'; ?>