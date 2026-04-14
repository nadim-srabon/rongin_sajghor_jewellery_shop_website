<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = (int) $_GET['id'];

/* FETCH ORDER */
$stmt = $conn->prepare("
    SELECT * FROM orders 
    WHERE id = ? AND user_id = ?
");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "<h2 style='text-align:center;margin:50px;'>Order not found</h2>";
    exit();
}

/* FETCH ITEMS */
$stmt = $conn->prepare("
    SELECT oi.*, p.name 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();

// include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rongin Sajghor</title>

    <link rel="stylesheet" href="/jewellery-store/assets/css/style.css">

</head>

<body>

    <header>
        <div class="logo">RONGIN SAJGHOR</div>

        <nav>
            <ul>
                <!-- <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="#">Collections</a></li>
                <li><a href="journal.php">Journal</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li> -->
            </ul>
        </nav>

        <div class="nav-icons">
            <!-- <a href="login.php">Account</a>
            <a href="#">Cart</a> -->
        </div>
    </header>

    <!-- HERO -->
    <section class="account-hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Order Details</h1>
            <p>View your complete order information</p>
        </div>
    </section>

    <!-- TOP MENU -->
    <div class="account-top-menu">
        <a href="dashboard.php" class="menu-link">Dashboard</a>
        <a href="orders.php" class="menu-link active">My Orders</a>
        <a href="track-order.php" class="menu-link">Track Order</a>
        <a href="profile.php" class="menu-link">My Profile</a>
        <a href="../cart.php" class="menu-link">My Cart</a>
        <a href="../logout.php" class="menu-link logout-link">Logout</a>
    </div>

    <div class="dashboard-wrapper">

        <div class="order-details-card">

            <!-- HEADER -->
            <div class="order-header">
                <div>
                    <h2>Order #<?php echo $order['id']; ?></h2>
                    <p><?php echo date("d M Y", strtotime($order['created_at'])); ?></p>
                </div>

                <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                    <?php echo ucfirst($order['status']); ?>
                </span>
            </div>

            <!-- DELIVERY INFO -->
            <div class="order-section">
                <h3>Delivery Information</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                <p><strong>City:</strong> <?php echo htmlspecialchars($order['city']); ?></p>

                <?php if (!empty($order['order_note'])): ?>
                    <p><strong>Note:</strong> <?php echo htmlspecialchars($order['order_note']); ?></p>
                <?php endif; ?>
            </div>

            <!-- ITEMS -->
            <div class="order-section">
                <h3>Ordered Items</h3>

                <?php while ($item = $items->fetch_assoc()): ?>
                    <?php $subtotal = $item['price'] * $item['quantity']; ?>

                    <div class="order-item">
                        <div>
                            <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                            <p>Qty: <?php echo $item['quantity']; ?></p>
                        </div>

                        <div class="text-right">
                            <p>৳<?php echo number_format($item['price'], 2); ?></p>
                            <strong>৳<?php echo number_format($subtotal, 2); ?></strong>
                        </div>
                    </div>

                <?php endwhile; ?>
            </div>

            <!-- TOTAL -->
            <div class="order-total-box">
                <h3>Total: ৳<?php echo number_format($order['total_amount'], 2); ?></h3>
            </div>

        </div>

    </div>

    <?php include '../includes/footer.php'; ?>