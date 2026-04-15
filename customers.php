<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

/*
    Get all users with order stats
*/
$customers = $conn->query("
    SELECT 
        users.*,
        COUNT(orders.id) AS total_orders,
        COALESCE(SUM(orders.total_amount), 0) AS total_spent
    FROM users
    LEFT JOIN orders 
        ON users.id = orders.user_id
    GROUP BY users.id
    ORDER BY users.id DESC
");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Customers</title>
    <link rel="stylesheet" href="/jewellery-store/assets/css/style.css">
</head>

<body>

    <div class="admin-wrapper">

        <!-- SIDEBAR -->
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

        <!-- CONTENT -->
        <main class="admin-content">
            <h1 class="page-title">Customers</h1>

            <table class="admin-table">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Total Orders</th>
                    <th>Total Spent</th>
                    <th>Joined</th>
                </tr>

                <?php while ($row = $customers->fetch_assoc()): ?>
                    <tr>
                        <td>#
                            <?php echo $row['id']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['email']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?>
                        </td>

                        <td>
                            <?php echo ucfirst($row['role']); ?>
                        </td>

                        <td>
                            <?php echo ucfirst($row['status']); ?>
                        </td>

                        <td>
                            <?php echo $row['total_orders']; ?>
                        </td>

                        <td>
                            ৳
                            <?php echo number_format($row['total_spent'], 2); ?>
                        </td>

                        <td>
                            <?php echo date("d M Y", strtotime($row['created_at'])); ?>
                        </td>
                    </tr>
                <?php endwhile; ?>

            </table>
        </main>

    </div>

</body>

</html>