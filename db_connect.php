<?php
// เชื่อมต่อฐานข้อมูล (เปลี่ยนค่าตามข้อมูลของคุณ)
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
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. ตรวจสอบข้อมูลเบื้องต้น
    if (empty($username) || empty($email) || empty($password)) {
        echo "กรุณากรอกข้อมูลให้ครบถ้วน";
        exit;
    }

    // 2. เข้ารหัสรหัสผ่าน (ใช้ฟังก์ชัน password_hash เพื่อความปลอดภัย)
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // 3. เตรียมคำสั่ง SQL เพื่อป้องกัน SQL Injection
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password_hash);

    // 4. ประมวลผลและตรวจสอบ
    if ($stmt->execute()) {
        echo "ลงทะเบียนสำเร็จ! <a href='login.php'>เข้าสู่ระบบ</a>";
    } else {
        // อาจเป็นเพราะ username หรือ email ซ้ำ
        echo "เกิดข้อผิดพลาดในการลงทะเบียน: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
<form action="signup.php" method="POST">
    <h2>ลงทะเบียน (Sign Up)</h2>
    Username: <input type="text" name="username" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">ลงทะเบียน</button>
</form>
