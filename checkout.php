<?php
session_start();
require_once 'config/db.php';

/* =========================
   SECURITY CHECKS FIRST
========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];

/* =========================
   FETCH PRODUCTS ONCE
========================= */
$ids = array_keys($cart);
$id_list = implode(',', array_map('intval', $ids));

$products = [];
$total = 0;

$result = $conn->query("SELECT * FROM products WHERE id IN ($id_list)");

while ($row = $result->fetch_assoc()) {
    $products[$row['id']] = $row;
    $total += $row['price'] * $cart[$row['id']];
}

/* =========================
   PROCESS ORDER
========================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $customer_name = trim($_POST['customer_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $order_note = trim($_POST['order_note']);

    /* START TRANSACTION */
    $conn->begin_transaction();

    try {

        /* INSERT ORDER */
        $stmt = $conn->prepare("
            INSERT INTO orders 
            (user_id, total_amount, customer_name, phone, address, city, order_note)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "idsssss",
            $user_id,
            $total,
            $customer_name,
            $phone,
            $address,
            $city,
            $order_note
        );

        $stmt->execute();
        $order_id = $conn->insert_id;

        /* INSERT ORDER ITEMS + UPDATE STOCK */
        foreach ($cart as $id => $qty) {

            $product = $products[$id];
            $price = $product['price'];

            /* order items */
            $stmt = $conn->prepare("
                INSERT INTO order_items 
                (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->bind_param("iiid", $order_id, $id, $qty, $price);
            $stmt->execute();

            /* reduce stock */
            $stmt = $conn->prepare("
                UPDATE products 
                SET stock = stock - ?
                WHERE id = ?
            ");

            $stmt->bind_param("ii", $qty, $id);
            $stmt->execute();
        }

        /* COMMIT */
        $conn->commit();

        /* CLEAR CART */
        unset($_SESSION['cart']);

        header("Location: accounts/orders.php");
        exit();

    } catch (Exception $e) {

        $conn->rollback();
        die("Order failed. Please try again.");
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="checkout-wrapper">

    <h2 class="checkout-title">Checkout</h2>

    <div class="checkout-layout">

        <!-- FORM -->
        <form method="POST" class="checkout-form">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="customer_name" required>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" required>
            </div>

            <div class="form-group">
                <label>Delivery Address</label>
                <textarea name="address" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label>City / Area</label>
                <input type="text" name="city" required>
            </div>

            <div class="form-group">
                <label>Order Note (Optional)</label>
                <textarea name="order_note" rows="3"></textarea>
            </div>

            <button type="submit" class="place-order-btn">
                Confirm Order
            </button>
        </form>

        <!-- SUMMARY -->
        <div class="checkout-summary">
            <h3>Order Summary</h3>

            <p>Total Items: <?php echo count($cart); ?></p>

            <h2>৳<?php echo number_format($total, 2); ?></h2>

            <p class="note">
                Cash on Delivery (COD)
            </p>
        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>