<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$order = null;

if (isset($_GET['id'])) {

    $order_id = (int) $_GET['id'];

    $stmt = $conn->prepare("
        SELECT * FROM orders 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
}

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
            <h1>Track Order</h1>
            <p>Follow your order journey</p>
        </div>
    </section>

    <!-- MENU -->
    <div class="account-top-menu">
        <a href="dashboard.php" class="menu-link active">Dashboard</a>
        <a href="orders.php" class="menu-link">My Orders</a>
        <a href="wishlist.php" class="menu-link">Wishlist</a>
        <a href="track-order.php" class="menu-link">Track Order</a>
        <a href="profile.php" class="menu-link">My Profile</a>
        <a href="../cart.php" class="menu-link">My Cart</a>
        <a href="../logout.php" class="menu-link logout-link">Logout</a>
    </div>

    <div class="dashboard-wrapper">

        <div class="form-card">
            <h2>Enter Order ID</h2>

            <form method="GET" class="form-box">
                <input type="number" name="id" placeholder="Order ID" required>
                <button class="btn-primary">Track</button>
            </form>
        </div>

        <?php if ($order): ?>

            <?php
            $status = strtolower($order['status']);

            $steps = [
                'pending' => 1,
                'approved' => 2,
                'shipped' => 3,
                'delivered' => 4
            ];

            $current = $steps[$status] ?? 1;
            ?>

            <div class="track-card">

                <h3>Order #<?php echo $order['id']; ?></h3>

                <div class="timeline">

                    <div class="step <?php echo $current >= 1 ? 'active' : ''; ?>">
                        <span>1</span>
                        <p>Placed</p>
                    </div>

                    <div class="step <?php echo $current >= 2 ? 'active' : ''; ?>">
                        <span>2</span>
                        <p>Approved</p>
                    </div>

                    <div class="step <?php echo $current >= 3 ? 'active' : ''; ?>">
                        <span>3</span>
                        <p>Shipped</p>
                    </div>

                    <div class="step <?php echo $current >= 4 ? 'active' : ''; ?>">
                        <span>4</span>
                        <p>Delivered</p>
                    </div>

                </div>

            </div>

        <?php endif; ?>

    </div>

    <?php include '../includes/footer.php'; ?>