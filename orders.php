<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$orders = $conn->query("
    SELECT * FROM orders
    WHERE user_id = $user_id
    ORDER BY id DESC
");

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
            <h1>My Orders</h1>
            <p>Track and manage all your purchases</p>
        </div>
    </section>

    <!-- TOP MENU -->
    <div class="account-top-menu">
        <a href="dashboard.php" class="menu-link">Dashboard</a>
        <a href="orders.php" class="menu-link active">My Orders</a>
        <a href="wishlist.php" class="menu-link">Wishlist</a>
        <a href="track-order.php" class="menu-link">Track Order</a>
        <a href="profile.php" class="menu-link">My Profile</a>
        <a href="../cart.php" class="menu-link">My Cart</a>
        <a href="../logout.php" class="menu-link logout-link">Logout</a>
    </div>

    <div class="dashboard-wrapper">

        <h2 class="section-title">Order History</h2>

        <?php if ($orders->num_rows > 0): ?>

            <div class="orders-list">

                <?php while ($row = $orders->fetch_assoc()): ?>

                    <div class="customer-order-card">

                        <div class="order-header-row">
                            <h3>Order #<?php echo $row['id']; ?></h3>

                            <span class="order-status-badge status-<?php echo strtolower($row['status']); ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </div>

                        <div class="order-meta">
                            <p><strong>Date:</strong> <?php echo $row['created_at']; ?></p>
                            <p><strong>Total:</strong> ৳<?php echo number_format($row['total_amount'], 2); ?></p>
                            <p><strong>City:</strong> <?php echo htmlspecialchars($row['city']); ?></p>
                        </div>

                        <div class="order-action-buttons">

                            <a href="order-details.php?id=<?php echo $row['id']; ?>" class="btn-order-action">
                                View Details
                            </a>

                            <a href="track-order.php?id=<?php echo $row['id']; ?>" class="btn-order-action">
                                Track
                            </a>

                            <a href="../admin/generate-invoice.php?id=<?php echo $row['id']; ?>" class="btn-order-action">
                                Invoice
                            </a>

                            <?php if (
                                $row['status'] == 'Pending' ||
                                $row['status'] == 'Processing'
                            ): ?>
                                <a href="cancel-order.php?id=<?php echo $row['id']; ?>" class="btn-order-action btn-cancel"
                                    onclick="return confirm('Cancel this order?')">
                                    Cancel Order
                                </a>
                            <?php endif; ?>

                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

        <?php else: ?>

            <div class="empty-orders">
                <h3>No orders found 🛍️</h3>
                <a href="dashboard.php" class="btn-order-action">Continue Shopping</a>
            </div>

        <?php endif; ?>

    </div>

    <?php include '../includes/footer.php'; ?>