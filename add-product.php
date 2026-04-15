<?php
session_start();
require_once '../config/db.php';

/* AUTH CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

/* FETCH CATEGORIES */
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");

/* HANDLE FORM */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float) $_POST['price'];
    $discount_price = !empty($_POST['discount_price']) ? (float) $_POST['discount_price'] : null;
    $stock = (int) $_POST['stock'];
    $status = (int) $_POST['status'];
    $category_id = (int) $_POST['category_id'];

    /* IMAGE HANDLING */
    $image = '';
    $hover_image = '';

    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/" . $image);
    }

    if (!empty($_FILES['hover_image']['name'])) {
        $hover_image = time() . '_' . $_FILES['hover_image']['name'];
        move_uploaded_file($_FILES['hover_image']['tmp_name'], "../assets/images/" . $hover_image);
    }

    /* INSERT QUERY */
    $stmt = $conn->prepare("
        INSERT INTO products
        (name, description, price, discount_price, image, hover_image, stock, status, category_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssddssiii",
        $name,
        $description,
        $price,
        $discount_price,
        $image,
        $hover_image,
        $stock,
        $status,
        $category_id
    );

    $stmt->execute();

    header("Location: products.php?msg=added");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Product</title>
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
            <h1 class="page-title">Add Product</h1>

            <form method="POST" enctype="multipart/form-data" class="admin-form">

                <!-- NAME -->
                <input type="text" name="name" placeholder="Product Name" required>

                <!-- DESCRIPTION -->
                <textarea name="description" placeholder="Description"></textarea>

                <!-- PRICE -->
                <input type="number" name="price" placeholder="Price" step="0.01" required>

                <!-- DISCOUNT PRICE -->
                <input type="number" name="discount_price" placeholder="Discount Price (optional)" step="0.01">

                <!-- STOCK -->
                <input type="number" name="stock" placeholder="Stock" required>

                <!-- CATEGORY -->
                <label>Category</label>
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $cat['id']; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <!-- STATUS -->
                <label>Status</label>
                <select name="status">
                    <option value="1">Active</option>
                    <option value="0">Hidden</option>
                </select>

                <!-- MAIN IMAGE -->
                <label>Main Image</label>
                <input type="file" name="image" required>

                <!-- HOVER IMAGE -->
                <label>Hover Image</label>
                <input type="file" name="hover_image">

                <!-- SUBMIT -->
                <button type="submit" class="btn btn-approve">
                    Save Product
                </button>

            </form>
        </main>

    </div>

</body>

</html>