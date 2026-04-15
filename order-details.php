<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = (int) $_GET['id'];

$stmt = $conn->prepare("
    SELECT * FROM orders WHERE id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

$items = $conn->query("
    SELECT order_items.*, products.name
    FROM order_items
    JOIN products ON order_items.product_id = products.id
    WHERE order_items.order_id = $order_id
");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Order Details</title>
    <link rel="stylesheet" href="/jewellery-store/assets/css/style.css">
</head>

<body>

    <div class="admin-wrapper">

        <aside class="admin-sidebar">
            <div class="admin-logo">Admin Panel</div>

            <ul class="admin-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="orders.php" class="active">Orders</a></li>
            </ul>
        </aside>

        <main class="admin-content">
            <h1 class="page-title">Order #
                <?php echo $order['id']; ?>
            </h1>

            <?php if (isset($_GET['msg'])): ?>

                <?php if ($_GET['msg'] == 'approved'): ?>
                    <div class="alert alert-success">
                        ✅ Order approved successfully and stock updated.
                    </div>
                <?php endif; ?>

                <?php if ($_GET['msg'] == 'cancelled'): ?>
                    <div class="alert alert-danger">
                        ❌ Order has been cancelled successfully.
                    </div>
                <?php endif; ?>

            <?php endif; ?>

            <div class="dash-card">
                <h4>Customer</h4>
                <p>
                    <?php echo htmlspecialchars($order['customer_name']); ?>
                </p>
                <p>
                    <?php echo htmlspecialchars($order['phone']); ?>
                </p>
                <p>
                    <?php echo htmlspecialchars($order['address']); ?>
                </p>
                <p>
                    <?php echo htmlspecialchars($order['city']); ?>
                </p>
            </div>

            <br>

            <table class="admin-table">
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                </tr>

                <?php while ($item = $items->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($item['name']); ?>
                        </td>
                        <td>
                            <?php echo $item['quantity']; ?>
                        </td>
                        <td>৳
                            <?php echo number_format($item['price'], 2); ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <br>
            <a href="generate-invoice.php?id=<?php echo $order['id']; ?>" class="btn btn-view" target="_blank">
                Generate Invoice (PDF)
            </a>
            <a href="generate-invoice.php?id=<?php echo $order['id']; ?>" class="btn btn-view" target="_blank">
                Download Invoice PDF
            </a>

            <?php if ($order['status'] == 'Pending'): ?>
                <a href="update-order-status.php?id=<?php echo $order_id; ?>&status=Approved" class="btn btn-approve">
                    Approve Order
                </a>


                <a href="update-order-status.php?id=<?php echo $order_id; ?>&status=Cancelled" class="btn btn-cancel">
                    Cancel Order
                </a>
            <?php endif; ?>

        </main>

    </div>

</body>

</html>