<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$orders = $conn->query("
    SELECT orders.*, users.name AS user_name, users.email
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.id DESC
");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Orders</title>
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

            <h1 class="page-title">Manage Orders</h1>

            <table class="admin-table">
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Update Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>

                <?php while ($row = $orders->fetch_assoc()): ?>

                    <tr>
                        <td>#<?php echo $row['id']; ?></td>

                        <td>
                            <?php echo htmlspecialchars($row['customer_name']); ?><br>
                            <small><?php echo htmlspecialchars($row['phone']); ?></small>
                        </td>

                        <td>
                            ৳<?php echo number_format($row['total_amount'], 2); ?>
                        </td>

                        <td>
                            <span class="status <?php echo strtolower($row['status']); ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>

                        <!-- UPDATE FORM PER ORDER -->
                        <td>
                            <form method="POST" action="update-order-status.php">
                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">

                                <select name="status">
                                    <option <?php if ($row['status'] == "Pending")
                                        echo "selected"; ?>>Pending</option>
                                    <option <?php if ($row['status'] == "Processing")
                                        echo "selected"; ?>>Processing</option>
                                    <option <?php if ($row['status'] == "Shipped")
                                        echo "selected"; ?>>Shipped</option>
                                    <option <?php if ($row['status'] == "Delivered")
                                        echo "selected"; ?>>Delivered</option>
                                    <option <?php if ($row['status'] == "Cancelled")
                                        echo "selected"; ?>>Cancelled</option>
                                </select>

                                <button type="submit" class="btn btn-view">
                                    Update
                                </button>
                            </form>
                        </td>

                        <td>
                            <?php echo $row['created_at']; ?>
                        </td>

                        <td>
                            <a href="order-details.php?id=<?php echo $row['id']; ?>" class="btn btn-view">
                                View
                            </a>
                        </td>
                    </tr>

                <?php endwhile; ?>

            </table>

        </main>

    </div>

</body>

</html>