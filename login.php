<?php
require 'config/db.php';
session_start();

$error = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT id,name,password,role,status FROM users WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {

        if ($user['status'] != 'active') {
            $error = "Account blocked.";
        } elseif (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: accounts/dashboard.php");
            }
            exit();

        } else {
            $error = "Invalid credentials.";
        }

    } else {
        $error = "Invalid credentials.";
    }
}
?>


<?php include 'includes/header.php'; ?>

<section class="auth-page">

    <div class="auth-box">

        <h2>Login</h2>

        <form method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button name="login">Continue</button>
        </form>

        <div class="auth-footer">
            Not registered? <a href="register.php">Sign Up Now</a>
        </div>

    </div>

</section>

<?php include 'includes/footer.php'; ?>