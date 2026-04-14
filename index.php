<?php
require_once 'config/db.php';
include 'includes/header.php';
?>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <h1>Timeless Elegance</h1>
        <p>Handcrafted Jewellery for Every Occasion</p>
        <a href="shop.php" class="btn-gold">Explore Collection</a>
    </div>
</section>

<!-- CATEGORY -->
<section class="section">
    <h2 class="section-title">Shop By Category</h2>

    <div class="category-grid">

        <?php
        $categories = $conn->query("SELECT * FROM categories");

        while ($cat = $categories->fetch_assoc()) {
            ?>
            <a href="category.php?id=<?php echo (int) $cat['id']; ?>" class="category-card">
                <?php echo htmlspecialchars($cat['name']); ?>
            </a>
        <?php } ?>

    </div>
</section>

<!-- PRODUCTS -->
<section class="products-section">
    <h2 class="section-title">Our Collection</h2>

    <div class="product-grid">
        <?php include 'includes/product-card.php'; ?>
    </div>
</section>

<!-- AUTO SLIDER -->
<section class="auto-slider">
    <h2 class="section-title">Trending Jewellery</h2>

    <div class="slider-wrapper">
        <div class="slider-track">

            <?php
            $products = $conn->query("SELECT * FROM products WHERE status=1 ORDER BY id DESC");

            while ($product = $products->fetch_assoc()):
                ?>
                <div class="slider-card">
                    <img src="assets/images/<?php echo $product['image']; ?>">
                    <p><?php echo $product['name']; ?></p>
                </div>
            <?php endwhile; ?>

            <!-- DUPLICATE FOR LOOP -->
            <?php
            $products = $conn->query("SELECT * FROM products WHERE status=1 ORDER BY id DESC");

            while ($product = $products->fetch_assoc()):
                ?>
                <div class="slider-card">
                    <img src="assets/images/<?php echo $product['image']; ?>">
                    <p><?php echo $product['name']; ?></p>
                </div>
            <?php endwhile; ?>

        </div>
    </div>
</section>

<!-- TRUST SECTION -->
<section class="trust-section">
    <div class="trust-grid">

        <div class="trust-card">
            <h3>💎 Premium Quality</h3>
            <p>Carefully handcrafted with top materials</p>
        </div>

        <div class="trust-card">
            <h3>🚚 Fast Delivery</h3>
            <p>Quick and reliable shipping</p>
        </div>

        <div class="trust-card">
            <h3>🔄 Easy Returns</h3>
            <p>Hassle-free return policy</p>
        </div>

        <div class="trust-card">
            <h3>💳 Secure Payment</h3>
            <p>100% safe and secure payments</p>
        </div>

    </div>
</section>

<!-- SCRIPT: CURSOR EFFECT -->
<script>
    document.addEventListener("mousemove", (e) => {
        const x = e.clientX / window.innerWidth;
        const y = e.clientY / window.innerHeight;

        document.querySelector(".hero").style.transform =
            `translate(${x * 8}px, ${y * 8}px)`;
    });
</script>

<?php include 'includes/footer.php'; ?>