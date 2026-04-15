<?php
include 'config/db.php';

$query = $conn->query("SELECT * FROM products WHERE status=1 ORDER BY id DESC");

while ($product = $query->fetch_assoc()) {
    ?>

    <div class="product-card">

        <div class="product-image">
            <img src="assets/images/<?php echo $product['image']; ?>" class="main-img">
            <img src="assets/images/<?php echo $product['hover_image']; ?>" class="hover-img">

            <form action="add-to-cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button class="add-cart">Add to Cart</button>
                <a href="add-to-wishlist.php?id=<?php echo $product['id']; ?>" class="wishlist-btn">❤️</a>
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