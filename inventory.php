<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

/*
    Get all products sorted by stock level
*/
$products = $conn->query("
    SELECT * FROM products
    ORDER BY stock ASC
");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Inventory Management</title>
    <link rel="stylesheet" href="/jewellery-store/assets/css/style.css">
</head>

<body>

    <div class="admin-wrapper">

        <!-- SIDEBAR -->
        <aside class="admin-sidebar">
            <div class="admin-logo">Admin Panel</div>

            <ul class="admin-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="customers.php">Customers</a></li>
                <li><a href="inventory.php" class="active">Inventory</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="admin-content">

            <h1 class="page-title">Inventory Management</h1>

            <table class="admin-table">
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php while ($row = $products->fetch_assoc()): ?>

                    <?php
                    $stock = (int) $row['stock'];

                    if ($stock <= 0) {
                        $stock_class = "danger";
                        $stock_text = "Out of Stock";
                    } elseif ($stock <= 5) {
                        $stock_class = "warning";
                        $stock_text = "Low Stock";
                    } else {
                        $stock_class = "success";
                        $stock_text = "In Stock";
                    }
                    ?>

                    <tr>
                        <td>#
                            <?php echo $row['id']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['name']); ?>
                        </td>

                        <td>
                            <img src="../assets/images/<?php echo $row['image']; ?>" width="50" style="border-radius:8px;">
                        </td>

                        <td>
                            <strong>
                                <?php echo $stock; ?>
                            </strong>
                        </td>

                        <td>
                            <span class="stock-badge <?php echo $stock_class; ?>">
                                <?php echo $stock_text; ?>
                            </span>
                        </td>

                        <td>
                            <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="btn btn-view">
                                Update
                            </a>
                        </td>
                    </tr>

                <?php endwhile; ?>

            </table>

        </main>

    </div>

</body>

</html>