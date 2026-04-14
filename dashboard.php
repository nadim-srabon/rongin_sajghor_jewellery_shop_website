<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* USER */
$user = $conn->query("
    SELECT * FROM users
    WHERE id = $user_id
")->fetch_assoc();

/* STATS */
$order_count = $conn->query("
    SELECT COUNT(*) as total
    FROM orders
    WHERE user_id = $user_id
")->fetch_assoc()['total'];

$total_spent = $conn->query("
    SELECT SUM(total_amount) as total
    FROM orders
    WHERE user_id = $user_id
")->fetch_assoc()['total'];

$pending_orders = $conn->query("
    SELECT COUNT(*) as total
    FROM orders
    WHERE user_id = $user_id
    AND status IN ('Pending','Processing')
")->fetch_assoc()['total'];

$delivered_orders = $conn->query("
    SELECT COUNT(*) as total
    FROM orders
    WHERE user_id = $user_id
    AND status = 'Delivered'
")->fetch_assoc()['total'];

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
            <h1>My Account</h1>
            <p>Manage your orders and explore collections</p>
        </div>
    </section>

    <!-- TOP MENU -->
    <div class="account-top-menu">
        <a href="dashboard.php" class="menu-link active">Dashboard</a>
        <a href="orders.php" class="menu-link">My Orders</a>
        <a href="wishlist.php" class="menu-link">Wishlist</a>
        <a href="track-order.php" class="menu-link">Track Order</a>
        <a href="profile.php" class="menu-link">My Profile</a>
        <a href="cart.php" class="menu-link">My Cart</a>
        <a href="../logout.php" class="menu-link logout-link">Logout</a>
    </div>

    <div class="dashboard-wrapper">

        <!-- WELCOME -->
        <div class="welcome-card">
            <h2>Hello, <?php echo htmlspecialchars($user['name']); ?> 👋</h2>
            <p>Here’s a quick overview of your account activity.</p>
        </div>

        <!-- STATS -->
        <div class="dashboard-cards">

            <div class="dash-card">
                <h4>Total Orders</h4>
                <p><?php echo $order_count; ?></p>
            </div>

            <div class="dash-card">
                <h4>Total Spent</h4>
                <p>৳<?php echo number_format($total_spent ?: 0, 2); ?></p>
            </div>

            <div class="dash-card">
                <h4>Pending Orders</h4>
                <p><?php echo $pending_orders; ?></p>
            </div>

            <div class="dash-card">
                <h4>Delivered</h4>
                <p><?php echo $delivered_orders; ?></p>
            </div>

        </div>

        <!-- RECENT ORDERS -->
        <h2 class="section-title">Recent Orders</h2>

        <div class="recent-orders">
            <?php
            $recent_orders = $conn->query("
            SELECT * FROM orders
            WHERE user_id = $user_id
            ORDER BY id DESC
            LIMIT 3
        ");

            while ($order = $recent_orders->fetch_assoc()):
                ?>
                <div class="order-preview-card">
                    <h4>Order #<?php echo $order['id']; ?></h4>
                    <p>Status: <?php echo $order['status']; ?></p>
                    <p>Total: ৳<?php echo number_format($order['total_amount'], 2); ?></p>
                    <a href="orders.php">View Details</a>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- PRODUCT GRID -->
        <h2 class="section-title">Explore Jewellery</h2>

        <div class="product-grid">

            <?php

            $query = $conn->query("SELECT * FROM products WHERE status=1 ORDER BY id DESC");

            while ($product = $query->fetch_assoc()) {
                ?>

                <div class="product-card">

                    <div class="product-image">
                        <img src="../assets/images/<?php echo $product['image']; ?>" class="main-img">
                        <img src="../assets/images/<?php echo $product['hover_image']; ?>" class="hover-img">

                        <form action="../add-to-cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button class="add-cart">Add to Cart</button>
                            <a href="add-wishlist.php?id=<?php echo $product['id']; ?>" class="wishlist-btn">❤️</a>
                        </form>
                    </div>

                    <div class="product-info">
                        <p class="category">Jewellery</p>
                        <h3 class="product-title">
                            <?php echo $product['name']; ?>
                        </h3>
                        <?php if ($product['stock'] > 0): ?>
                            <p>In Stock:
                                <?php echo $product['stock']; ?>
                            </p>
                        <?php else: ?>
                            <p style="color:red;">Out of Stock</p>
                        <?php endif; ?>

                        <div class="price-box">
                            <span class="price">৳
                                <?php echo $product['price']; ?>
                            </span>

                            <?php if ($product['discount_price']) { ?>
                                <span class="old-price">৳
                                    <?php echo $product['discount_price']; ?>
                                </span>
                            <?php } ?>
                        </div>
                    </div>

                </div>

            <?php } ?>

        </div>

        <!-- AUTO RUNNING PRODUCTS -->
        <h2 class="section-title">Explore More Jewellery</h2>

        <div class="marquee-wrapper">
            <div class="marquee-track">

                <?php
                $products = $conn->query("
                SELECT * FROM products
                ORDER BY id DESC
            ");

                while ($product = $products->fetch_assoc()):
                    ?>
                    <div class="marquee-product-card">
                        <img src="../assets/images/<?php echo $product['image']; ?>">
                        <h4><?php echo $product['name']; ?></h4>
                        <p>৳<?php echo number_format($product['price'], 2); ?></p>
                    </div>
                <?php endwhile; ?>

                <?php
                $products = $conn->query("
                SELECT * FROM products
                ORDER BY id DESC
            ");

                while ($product = $products->fetch_assoc()):
                    ?>
                    <div class="marquee-product-card">
                        <img src="../assets/images/<?php echo $product['image']; ?>">
                        <h4><?php echo $product['name']; ?></h4>
                        <p>৳<?php echo number_format($product['price'], 2); ?></p>
                    </div>
                <?php endwhile; ?>

            </div>
        </div>

    </div>

    <?php include '../includes/footer.php'; ?>