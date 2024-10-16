<?php
session_start();

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบแล้วและเป็น user หรือไม่
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    // ถ้าไม่ใช่ user ให้เปลี่ยนเส้นทางไปยังหน้า login
    header('Location: login.php');
    exit();
}

// โค้ดสำหรับหน้าผู้ใช้งานทั่วไป
echo "ยินดีต้อนรับ User, " . $_SESSION['username'];
?>
