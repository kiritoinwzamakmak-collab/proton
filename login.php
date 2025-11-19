<?php
session_start();
// เชื่อมต่อฐานข้อมูล (ใช้ค่าเดียวกับด้านบน)
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "my_auth_db";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 1. เตรียมคำสั่ง SQL เพื่อดึงข้อมูลผู้ใช้ (โดยใช้ username)
    $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $password_hash = $user['password_hash'];

        // 2. ตรวจสอบรหัสผ่านที่เข้ารหัสแล้ว
        if (password_verify($password, $password_hash)) {
            // รหัสผ่านถูกต้อง สร้าง Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            echo "เข้าสู่ระบบสำเร็จ! ยินดีต้อนรับ " . $username . ". <a href='logout.php'>ออกจากระบบ</a>";
            // header("Location: dashboard.php"); // สามารถเปลี่ยนไปหน้าอื่นได้
            exit;
        } else {
            echo "รหัสผ่านไม่ถูกต้อง";
        }
    } else {
        echo "ไม่พบ Username นี้";
    }

    $stmt->close();
}
$conn->close();
?>

<form action="login.php" method="POST">
    <h2>เข้าสู่ระบบ (Log In)</h2>
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">เข้าสู่ระบบ</button>
</form>
