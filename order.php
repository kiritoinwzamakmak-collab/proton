<?php
include 'db_connect.php';
include 'send_email_mock.php';

$error_message = "";
$success_redirect = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $menu_items = $conn->real_escape_string(implode(", ", $_POST['menu'])); // รวมเมนูเป็น String

    if (empty($name) || empty($email) || empty($_POST['menu'])) {
        $error_message = "กรุณากรอกชื่อ Email และเลือกเมนูอาหาร";
    } else {
        // 1. คำนวณเลขคิวใหม่ (โดยนับจำนวนคำสั่งซื้อที่มีอยู่ทั้งหมด)
        $result = $conn->query("SELECT MAX(queue_number) AS max_queue FROM orders");
        $row = $result->fetch_assoc();
        $new_queue = ($row['max_queue'] ?? 0) + 1;

        // 2. บันทึกคำสั่งซื้อลงฐานข้อมูล
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_email, customer_phone, menu_items, queue_number, order_status) VALUES (?, ?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("ssssi", $name, $email, $phone, $menu_items, $new_queue);

        if ($stmt->execute()) {
            $last_id = $stmt->insert_id;
            $stmt->close();
            
            // 3. จำลองการส่งอีเมลยืนยัน
            $send_status = send_order_confirmation_email($email, $name, $new_queue, $menu_items, 'Pending');
            
            // 4. ส่งผู้ใช้ไปยังหน้าสรุปคำสั่งซื้อ
            header("Location: order_complete.php?order_id=" . $last_id);
            exit;
        } else {
            $error_message = "เกิดข้อผิดพลาดในการบันทึกคำสั่งซื้อ: " . $conn->error;
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบสั่งอาหาร</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="tel"], textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .menu-options label { display: inline-block; margin-right: 15px; font-weight: normal; }
        button { background-color: #ff9500; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; width: 100%; }
        .error { color: red; text-align: center; margin-bottom: 15px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🍔 สั่งอาหาร</h2>
        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form action="order.php" method="POST">
            <div class="form-group">
                <label for="name">ชื่อผู้สั่ง:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email สำหรับรับข้อมูลคิว:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="phone">เบอร์โทรศัพท์ (ไม่บังคับ):</label>
                <input type="tel" id="phone" name="phone">
            </div>
            
            <div class="form-group">
                <label>เมนูอาหารที่ต้องการสั่ง:</label>
                <div class="menu-options">
                    <label><input type="checkbox" name="menu[]" value="ข้าวผัดกะเพรา"> ข้าวผัดกะเพรา</label><br>
                    <label><input type="checkbox" name="menu[]" value="ราดหน้า"> ราดหน้า</label><br>
                    <label><input type="checkbox" name="menu[]" value="ผัดไทย"> ผัดไทย</label><br>
                    <label><input type="checkbox" name="menu[]" value="ชาเย็น"> ชาเย็น</label><br>
                </div>
            </div>
            
            <button type="submit">ยืนยันคำสั่งซื้อ</button>
        </form>
    </div>
</body>
</html>
