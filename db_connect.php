<?php
$servername = "localhost";
$db_username = "root"; // แก้ไขตามข้อมูลของคุณ
$db_password = "";     // แก้ไขตามข้อมูลของคุณ
$dbname = "food_order_db";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
