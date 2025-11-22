<?php
include 'db_connect.php';

// รับ Order ID ที่ถูกส่งมาจากหน้า order.php
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id === 0) {
    die("ไม่พบรหัสคำสั่งซื้อ");
}

// ดึงข้อมูลคำสั่งซื้อจาก DB
$stmt = $conn->prepare("SELECT customer_name, queue_number, menu_items, order_status FROM orders WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order_data = $result->fetch_assoc();

if (!$order_data) {
    die("ไม่พบข้อมูลคำสั่งซื้อในระบบ");
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ยืนยันคำสั่งซื้อ</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #e6ffe6; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 15px rgba(0, 100, 0, 0.2); text-align: center; }
        .success-icon { color: green; font-size: 50px; margin-bottom: 20px; }
        h2 { color: green; margin-bottom: 10px; }
        .queue-box { background-color: #ff9500; color: white; padding: 15px; border-radius: 5px; font-size: 24px; font-weight: bold; margin: 20px 0; }
        p { text-align: left; line-height: 1.6; }
        .details { border-top: 1px solid #ccc; margin-top: 20px; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✅</div>
        <h2>คำสั่งซื้อสำเร็จแล้ว!</h2>
        <p style="text-align: center;">เราได้รับคำสั่งซื้อของคุณเรียบร้อยแล้ว กรุณารอการแจ้งเตือนทาง Email</p>
        
        <div class="queue-box">
            เลขคิวของคุณ: <?php echo htmlspecialchars($order_data['queue_number']); ?>
        </div>
        
        <div class="details">
            <p><strong>ชื่อผู้สั่ง:</strong> <?php echo htmlspecialchars($order_data['customer_name']); ?></p>
            <p><strong>เมนูที่สั่ง:</strong> <?php echo nl2br(htmlspecialchars($order_data['menu_items'])); ?></p>
            <p><strong>สถานะปัจจุบัน:</strong> <span style="color: blue;"><?php echo htmlspecialchars($order_data['order_status']); ?></span></p>
        </div>
        
        <p style="text-align: center; margin-top: 30px;"><a href="order.php">กลับไปหน้าสั่งอาหาร</a></p>
    </div>
</body>
</html>
