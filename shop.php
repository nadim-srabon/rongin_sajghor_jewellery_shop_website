<?php include 'includes/header.php'; ?>

<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-content">
        <h1>Our Collection</h1>
        <p>Discover Pieces Crafted With Elegance</p>
        <a href="#" class="btn-gold">Shop Now</a>
    </div>
</section>

<?php
session_start();
require_once 'config/db.php';

/* =========================
   FILTERS
========================= */



$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';

/* =========================
   QUERY
========================= */

$sql = "SELECT * FROM products WHERE status=1";

/* SEARCH */
if ($search) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND name LIKE '%$search%'";
}

/* SORT */
if ($sort == 'low') {
    $sql .= " ORDER BY price ASC";
} elseif ($sort == 'high') {
    $sql .= " ORDER BY price DESC";
} else {
    $sql .= " ORDER BY id DESC";
}

$result = $conn->query($sql);

/* DEBUG (optional) */
if (!$result) {
    die("SQL Error: " . $conn->error);
}
?>

<!-- HERO -->
<section class="shop-hero">
    <!-- <h1>Shop Jewellery</h1>
    <p>Browse our beautiful collection</p> -->
</section>

<!-- FILTER BAR -->
<div class="shop-filters">

    <!-- SEARCH -->
    <form method="GET" class="search-form">
        <input type="text" name="search" placeholder="Search products..."
            value="<?php echo htmlspecialchars($search); ?>">
        <button>Search</button>
    </form>

    <!-- SORT -->
    <form method="GET">
        <select name="sort" onchange="this.form.submit()">
            <option value="">Default</option>
            <option value="low" <?php if ($sort == 'low')
                echo 'selected'; ?>>Price Low → High</option>
            <option value="high" <?php if ($sort == 'high')
                echo 'selected'; ?>>Price High → Low</option>
        </select>
    </form>

</div>

<!-- PRODUCTS -->
<section class="products-section">

    <div class="product-grid">

        <?php if ($result->num_rows > 0): ?>

            <?php while ($product = $result->fetch_assoc()): ?>

                <div class="product-card">

                    <div class="product-image">

                        <img src="assets/images/<?php echo $product['image']; ?>" class="main-img">

                        <?php if ($product['hover_image']): ?>
                            <img src="assets/images/<?php echo $product['hover_image']; ?>" class="hover-img">
                        <?php endif; ?>

                        <!-- WISHLIST -->
                        <a href="add-to-wishlist.php?id=<?php echo $product['id']; ?>" class="wishlist-btn">❤️</a>

                        <!-- CART -->
                        <form action="add-to-cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button class="add-cart">Add to Cart</button>
                        </form>

                    </div>

                    <div class="product-info">
                        <h3><?php echo $product['name']; ?></h3>

                        <div class="price-box">
                            <span class="price">৳<?php echo $product['price']; ?></span>

                            <?php if ($product['discount_price']): ?>
                                <span class="old-price">৳<?php echo $product['discount_price']; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="empty">
                <h3>No products found 😢</h3>
            </div>

        <?php endif; ?>

    </div>

</section>

<?php include 'includes/footer.php'; ?>