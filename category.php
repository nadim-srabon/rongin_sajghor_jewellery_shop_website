<?php include 'includes/header.php'; ?>

<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-content">
        <h1>Our Categories</h1>
        <p>Discover Pieces Crafted With Elegance</p>
        <!-- <a href="#" class="btn-gold">Shop Now</a> -->
    </div>
</section>
<?php
require_once 'config/db.php';


/* GET CATEGORY ID SAFELY */
$category_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

/* INVALID ID CHECK */
if ($category_id <= 0) {
    die("Invalid category.");
}

/* GET CATEGORY */
$stmt = $conn->prepare("
    SELECT * FROM categories
    WHERE id = ?
");
$stmt->bind_param("i", $category_id);
$stmt->execute();

$category = $stmt->get_result()->fetch_assoc();

/* CATEGORY NOT FOUND */
if (!$category) {
    die("Category not found.");
}

/* GET PRODUCTS OF CATEGORY */
$stmt = $conn->prepare("
    SELECT * FROM products
    WHERE status = 1
    AND category_id = ?
    ORDER BY id DESC
");
$stmt->bind_param("i", $category_id);
$stmt->execute();

$result = $stmt->get_result();
?>

<!-- HERO -->
<section class="shop-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <!-- <h1><?php echo htmlspecialchars($category['name']); ?></h1> -->
        <!-- <p>
            Explore our premium
            <?php echo htmlspecialchars($category['name']); ?>
            collection
        </p> -->
    </div>
</section>

<!-- CATEGORY MENU -->
<div class="category-top-nav">
    <?php
    $all_categories = $conn->query("
        SELECT * FROM categories
        ORDER BY id ASC
    ");

    while ($cat = $all_categories->fetch_assoc()):
        ?>
        <a href="category.php?id=<?php echo $cat['id']; ?>"
            class="category-nav-link <?php echo ($cat['id'] == $category_id) ? 'active' : ''; ?>">
            <?php echo htmlspecialchars($cat['name']); ?>
        </a>
    <?php endwhile; ?>
</div>

<!-- PRODUCTS -->
<section class="products-section">

    <h2 class="section-title">
        <?php echo htmlspecialchars($category['name']); ?> Collection
    </h2>

    <div class="product-grid">

        <?php if ($result->num_rows > 0): ?>

            <?php while ($product = $result->fetch_assoc()): ?>

                <div class="product-card">

                    <div class="product-image">

                        <img src="assets/images/<?php echo htmlspecialchars($product['image']); ?>" class="main-img"
                            alt="<?php echo htmlspecialchars($product['name']); ?>">

                        <?php if (!empty($product['hover_image'])): ?>
                            <img src="assets/images/<?php echo htmlspecialchars($product['hover_image']); ?>" class="hover-img"
                                alt="Hover Image">
                        <?php endif; ?>

                        <!-- WISHLIST -->
                        <a href="add-to-wishlist.php?id=<?php echo $product['id']; ?>" class="wishlist-btn">
                            ❤️
                        </a>

                        <!-- CART -->
                        <form action="add-to-cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                            <button type="submit" class="add-cart">
                                Add to Cart
                            </button>
                        </form>

                    </div>

                    <div class="product-info">

                        <p class="category-label">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </p>

                        <h3 class="product-title">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h3>

                        <div class="price-box">

                            <?php if (!empty($product['discount_price'])): ?>
                                <span class="old-price">
                                    ৳<?php echo number_format($product['price'], 2); ?>
                                </span>

                                <span class="price">
                                    ৳<?php echo number_format($product['discount_price'], 2); ?>
                                </span>
                            <?php else: ?>
                                <span class="price">
                                    ৳<?php echo number_format($product['price'], 2); ?>
                                </span>
                            <?php endif; ?>

                        </div>

                        <p class="stock-text">
                            Stock: <?php echo $product['stock']; ?>
                        </p>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="empty-products">
                <h3>No products available in this category 😢</h3>
                <p>Please check back later.</p>
            </div>

        <?php endif; ?>

    </div>

</section>

<?php include 'includes/footer.php'; ?>