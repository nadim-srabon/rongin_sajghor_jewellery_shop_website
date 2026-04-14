<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

/* FETCH WISHLIST */
$stmt = $conn->prepare("
    SELECT p.* FROM wishlist w
    JOIN products p ON w.product_id = p.id
    WHERE w.user_id = ?
    ORDER BY w.id DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

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
            <h1>My Wishlist</h1>
            <p>Your saved favourite products</p>
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

        <h2 class="section-title">Your Wishlist</h2>

        <div class="product-grid">

            <?php if ($result->num_rows > 0): ?>

                <?php while ($product = $result->fetch_assoc()): ?>

                    <div class="product-card">

                        <div class="product-image">
                            <img src="../assets/images/<?php echo $product['image']; ?>">

                            <!-- REMOVE -->
                            <a href="../remove-wishlist.php?id=<?php echo $product['id']; ?>" class="wishlist-remove">✖</a>

                            <!-- ADD TO CART -->
                            <form action="../add-to-cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button class="add-cart">Add to Cart</button>
                            </form>
                        </div>

                        <div class="product-info">
                            <h3>
                                <?php echo $product['name']; ?>
                            </h3>
                            <p>৳
                                <?php echo number_format($product['price'], 2); ?>
                            </p>
                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="empty-cart">
                    <h3>Your wishlist is empty ❤️</h3>
                    <a href="dashboard.php" class="checkout-btn">Explore Products</a>
                </div>

            <?php endif; ?>

        </div>

    </div>

    <?php include '../includes/footer.php'; ?>