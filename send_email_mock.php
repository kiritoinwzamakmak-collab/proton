<?php
// ฟังก์ชันจำลองการส่งอีเมล (ในระบบจริง คุณจะใช้ไลบรารีเช่น PHPMailer)
function send_order_confirmation_email($email, $name, $queue, $menu, $status) {
    // -------------------------------------------------------------------
    // **หมายเหตุ:** ในเซิร์ฟเวอร์จริง ฟังก์ชัน mail() ของ PHP จะทำงาน 
    // หรือคุณต้องใช้ PHPMailer เพื่อส่งอีเมลผ่าน SMTP Server ภายนอก
    // -------------------------------------------------------------------

    $subject = "ยืนยันคำสั่งซื้อ #{$queue} และสถานะคิวของคุณ";
    $message = "เรียนคุณ {$name},\n\n";
    $message .= "คำสั่งซื้อของคุณได้รับการยืนยันแล้ว\n";
    $message .= "เลขคิวของคุณคือ: {$queue}\n";
    $message .= "เมนูที่สั่ง: {$menu}\n";
    $message .= "สถานะปัจจุบัน: {$status}\n\n";
    $message .= "ขอบคุณที่ใช้บริการ\n";
    
    // สำหรับการจำลอง:
    error_log("--- Email Sent Mock ---");
    error_log("To: {$email}");
    error_log("Subject: {$subject}");
    error_log("Body: {$message}");
    error_log("-----------------------\n");
    
    return true; // สมมติว่าส่งสำเร็จ
}
?>
