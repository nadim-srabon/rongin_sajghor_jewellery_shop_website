<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$products = $conn->query("
    SELECT * FROM products
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Products</title>
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

        <!-- MAIN CONTENT -->
        <main class="admin-content">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
                <h1 class="page-title">Manage Products</h1>
                <a href="add-product.php" class="btn btn-approve">+ Add Product</a>
            </div>

            <table class="admin-table">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php while ($row = $products->fetch_assoc()): ?>
                    <tr>
                        <td>#
                            <?php echo $row['id']; ?>
                        </td>

                        <td>
                            <img src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>" width="60"
                                style="border-radius:8px;">
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['name']); ?>
                        </td>

                        <td>
                            ৳
                            <?php echo number_format($row['price'], 2); ?>
                        </td>

                        <td>
                            <?php echo $row['stock']; ?>
                        </td>

                        <td>
                            <?php echo $row['status'] ? 'Active' : 'Hidden'; ?>
                        </td>

                        <td>
                            <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="btn btn-view">
                                Edit
                            </a>

                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-cancel"
                                onclick="return confirm('Are you sure?')">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>

            </table>
        </main>

    </div>

</body>

</html>