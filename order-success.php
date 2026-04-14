<?php include 'includes/header.php'; ?>

<div class="success-box">
    <h2>Order Placed Successfully!</h2>
    <p>Your Order ID: #
        <?php echo $_GET['id']; ?>
    </p>

    <a href="dashboard.php" class="btn">Go to Dashboard</a>
</div>

<?php include 'includes/footer.php'; ?>