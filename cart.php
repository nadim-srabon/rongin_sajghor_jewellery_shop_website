<?php
session_start();
require_once 'config/db.php';

$cart = $_SESSION['cart'] ?? [];

include 'includes/header.php';
?>

<!-- HERO -->
<section class="account-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>My Cart</h1>
        <p>Review your selected products</p>
    </div>
</section>

<div class="dashboard-wrapper">

    <h2 class="section-title">Shopping Cart</h2>

    <?php if (!empty($cart)): ?>

        <div class="cart-container">

            <?php
            $total = 0;

            foreach ($cart as $product_id => $qty):

                $product_id = (int) $product_id;

                $result = $conn->query("
                    SELECT * FROM products WHERE id = $product_id
                ");
                $product = $result->fetch_assoc();

                if (!$product)
                    continue;

                $subtotal = $product['price'] * $qty;
                $total += $subtotal;
                ?>

                <div class="cart-item">

                    <div class="cart-image">
                        <img src="assets/images/<?php echo $product['image']; ?>">
                    </div>

                    <div class="cart-info">
                        <h3>
                            <?php echo $product['name']; ?>
                        </h3>
                        <p>Price: ৳
                            <?php echo number_format($product['price'], 2); ?>
                        </p>
                    </div>

                    <div class="cart-qty">
                        <form action="update-cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                            <input type="number" name="quantity" value="<?php echo $qty; ?>" min="1">
                            <button>Update</button>
                        </form>
                    </div>

                    <div class="cart-subtotal">
                        <p>৳
                            <?php echo number_format($subtotal, 2); ?>
                        </p>
                    </div>

                    <div class="cart-remove">
                        <a href="remove-cart.php?id=<?php echo $product_id; ?>">✖</a>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <!-- TOTAL -->
        <div class="cart-summary">
            <h3>Total: ৳
                <?php echo number_format($total, 2); ?>
            </h3>
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        </div>

    <?php else: ?>

        <div class="empty-cart">
            <h3>Your cart is empty 🛒</h3>
            <a href="index.php" class="checkout-btn">Shop Now</a>
        </div>

    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>