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

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-content">
            <h1>Our Collection</h1>
            <p>Discover Pieces Crafted With Elegance</p>
            <a href="#" class="btn-gold">Shop Now</a>
        </div>
    </section>

    <!-- SHOP SECTION -->
    <section class="home-products">

        <div class="section-header">
            <h2>Shop Jewellery</h2>
            <p>Luxury Designs Made For You</p>
        </div>

        <div class="product-grid">

            <!-- Product Cards -->
            <?php include 'includes/product-card.php'; ?>

        </div>

    </section>

    <?php include 'includes/footer.php'; ?>