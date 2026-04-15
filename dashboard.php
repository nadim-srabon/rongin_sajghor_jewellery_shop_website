<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$total_orders = $conn->query("SELECT COUNT(*) as total FROM orders")
    ->fetch_assoc()['total'];

$pending_orders = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status='Pending'")
    ->fetch_assoc()['total'];

$total_products = $conn->query("SELECT COUNT(*) as total FROM products")
    ->fetch_assoc()['total'];

$total_revenue = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status='Approved'")
    ->fetch_assoc()['total'];

$total_revenue = $total_revenue ?: 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/jewellery-store/assets/css/style.css">
</head>

<body>

    <div class="admin-wrapper">

        <aside class="admin-sidebar">
            <div class="admin-logo">Admin Panel</div>

            <ul class="admin-menu">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="customers.php">Customers</a></li>
                <li><a href="inventory.php">Inventory</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="admin-content">
            <h1 class="page-title">Dashboard</h1>

            <div class="dashboard-grid">

                <div class="dash-card">
                    <h4>Total Orders</h4>
                    <p><?php echo $total_orders; ?></p>
                </div>

                <div class="dash-card">
                    <h4>Pending Orders</h4>
                    <p><?php echo $pending_orders; ?></p>
                </div>

                <div class="dash-card">
                    <h4>Total Products</h4>
                    <p><?php echo $total_products; ?></p>
                </div>

                <div class="dash-card">
                    <h4>Revenue</h4>
                    <p>৳<?php echo number_format($total_revenue, 2); ?></p>
                </div>

            </div>
        </main>

    </div>

</body>

</html>