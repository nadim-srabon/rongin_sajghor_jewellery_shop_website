<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* GET USER */
$stmt = $conn->prepare("
    SELECT * FROM users
    WHERE id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* UPDATE PROFILE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);

    /* update basic user table */
    $stmt = $conn->prepare("
        UPDATE users
        SET name = ?, email = ?, phone = ?
        WHERE id = ?
    ");
    $stmt->bind_param("sssi", $name, $email, $phone, $user_id);
    $stmt->execute();

    /* optional: update latest order info defaults if needed */
    $_SESSION['success_message'] = "Profile updated successfully.";

    header("Location: profile.php");
    exit();
}

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
            <h1>My Profile</h1>
            <p>Manage your account details</p>
        </div>
    </section>

    <!-- TOP MENU -->
    <div class="account-top-menu">
        <a href="dashboard.php" class="menu-link">Dashboard</a>
        <a href="orders.php" class="menu-link">My Orders</a>
        <a href="track-order.php" class="menu-link">Track Order</a>
        <a href="profile.php" class="menu-link active">My Profile</a>
        <a href="../cart.php" class="menu-link">My Cart</a>
        <a href="../logout.php" class="menu-link logout-link">Logout</a>
        <a href="change-password.php" class="menu-link logout-link">Change Password</a>
    </div>

    <div class="dashboard-wrapper">

        <?php if (!empty($_SESSION['success_message'])): ?>
            <div class="profile-alert success-alert">
                <?php
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>

        <div class="profile-card">
            <h2>Account Information</h2>

            <form method="POST" class="profile-form">

                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                            required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                    </div>

                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" placeholder="Dhaka">
                    </div>
                </div>

                <div class="form-group">
                    <label>Default Address</label>
                    <textarea name="address" rows="4" placeholder="Enter your delivery address"></textarea>
                </div>

                <button type="submit" class="save-profile-btn">
                    Save Changes
                </button>


            </form>
        </div>

    </div>

    <?php include '../includes/footer.php'; ?>