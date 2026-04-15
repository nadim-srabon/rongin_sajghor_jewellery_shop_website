<?php include 'includes/header.php'; ?>

<div class="dashboard">

    <h2>My Dashboard</h2>

    <?php
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY id DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <table>
        <tr>
            <th>Order ID</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>

            <tr>
                <td>#
                    <?php echo $row['id']; ?>
                </td>
                <td>৳
                    <?php echo $row['total_amount']; ?>
                </td>
                <td>
                    <?php echo $row['status']; ?>
                </td>
                <td>
                    <?php echo $row['created_at']; ?>
                </td>
                <td><a href="order-details.php?id=<?php echo $row['id']; ?>">View</a></td>
            </tr>

        <?php } ?>

    </table>

</div>

<?php include 'includes/footer.php'; ?>