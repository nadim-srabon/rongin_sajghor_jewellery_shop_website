<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "jewellery_store";
$port = 3307;



$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Database connection failed");
}
?>