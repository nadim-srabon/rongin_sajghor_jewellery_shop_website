<?php
session_start();
require_once '../config/db.php';

/* AUTH CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

/* CHECK PRODUCT ID */
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = (int) $_GET['id'];

/* FETCH PRODUCT */
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

/* FETCH CATEGORIES */
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");

/* UPDATE PRODUCT */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float) $_POST['price'];
    $discount_price = !empty($_POST['discount_price']) ? (float) $_POST['discount_price'] : null;
    $stock = (int) $_POST['stock'];
    $status = (int) $_POST['status'];
    $category_id = (int) $_POST['category_id'];

    $image = $product['image'];
    $hover_image = $product['hover_image'];

    /* MAIN IMAGE */
    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/" . $image);
    }

    /* HOVER IMAGE */
    if (!empty($_FILES['hover_image']['name'])) {
        $hover_image = time() . '_' . $_FILES['hover_image']['name'];
        move_uploaded_file($_FILES['hover_image']['tmp_name'], "../assets/images/" . $hover_image);
    }

    /* UPDATE QUERY */
    $stmt = $conn->prepare("
        UPDATE products
        SET
            name = ?,
            description = ?,
            price = ?,
            discount_price = ?,
            image = ?,
            hover_image = ?,
            stock = ?,
            status = ?,
            category_id = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "ssddssiiii",
        $name,
        $description,
        $price,
        $discount_price,
        $image,
        $hover_image,
        $stock,
        $status,
        $category_id,
        $product_id
    );

    $stmt->execute();

    header("Location: products.php?msg=updated");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="/jewellery-store/assets/css/style.css">
</head>

<body>

    <div class="admin-wrapper">

        <!-- SIDEBAR -->
        <aside class="admin-sidebar">
            <div class="admin-logo">Admin Panel</div>

            <ul class="admin-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="products.php" class="active">Products</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </aside>

        <!-- CONTENT -->
        <main class="admin-content">
            <h1 class="page-title">Edit Product</h1>

            <form method="POST" enctype="multipart/form-data" class="admin-form">

                <!-- NAME -->
                <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

                <!-- DESCRIPTION -->
                <textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>

                <!-- PRICE -->
                <input type="number" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>

                <!-- DISCOUNT -->
                <input type="number" name="discount_price" step="0.01"
                    value="<?php echo $product['discount_price']; ?>">

                <!-- STOCK -->
                <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>

                <!-- CATEGORY -->
                <label>Category</label>
                <select name="category_id" required>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $product['category_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <!-- STATUS -->
                <label>Status</label>
                <select name="status">
                    <option value="1" <?php echo $product['status'] == 1 ? 'selected' : ''; ?>>
                        Active
                    </option>
                    <option value="0" <?php echo $product['status'] == 0 ? 'selected' : ''; ?>>
                        Hidden
                    </option>
                </select>

                <!-- MAIN IMAGE -->
                <label>Current Main Image</label>
                <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" width="100"
                    style="border-radius:8px; margin-bottom:10px;">

                <input type="file" name="image">

                <!-- HOVER IMAGE -->
                <label>Current Hover Image</label>
                <img src="../assets/images/<?php echo htmlspecialchars($product['hover_image']); ?>" width="100"
                    style="border-radius:8px; margin-bottom:10px;">

                <input type="file" name="hover_image">

                <!-- SUBMIT -->
                <button type="submit" class="btn btn-approve">
                    Update Product
                </button>

            </form>
        </main>

    </div>

</body>

</html>