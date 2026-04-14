<?php
require 'config/db.php';
session_start();

$error = "";

if (isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {

        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email=?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $error = "Email already exists.";
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO users (name,email,password) VALUES (?,?,?)"
            );
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashedPassword);
            mysqli_stmt_execute($stmt);

            header("Location: login.php?registered=1");
            exit();
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

<section class="auth-page">

    <div class="auth-box">

        <h2>Create Account</h2>

        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button name="register">Register</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="login.php">Login</a>
        </div>

    </div>

</section>

<?php include 'includes/footer.php'; ?>