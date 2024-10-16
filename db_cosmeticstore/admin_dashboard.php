<?php
session_start();

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบแล้วและเป็น admin หรือไม่
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // ถ้าไม่ใช่ admin ให้เปลี่ยนเส้นทางไปยังหน้า login
    header('Location: login.php');
    exit();
}

// โค้ดสำหรับหน้าจัดการระบบ admin
// echo "ยินดีต้อนรับ Admin, " . $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เมนูการจัดการ</title>
    <link rel="stylesheet" href="css/styles3.css?v=999"> <!-- เชื่อมโยงกับไฟล์ CSS -->
</head>
<body>
    <div class="admin-welcome-message">ยินดีต้อนรับ Admin, <?php echo htmlspecialchars($_SESSION['username']); ?></div>
    <h2>เมนูการจัดการ</h2>
    <div class="card"> <!-- การ์ดครอบเมนู -->
        <div class="menu">
            <div class="menu-item">
                <h3>แก้ไขสินค้า</h3>
                <a href="manage_products.php">ไปที่หน้าแก้ไขสินค้า</a>
            </div>
            <div class="menu-item">
                <h3>แก้ไขผู้ใช้</h3>
                <a href="manage_users.php">ไปที่หน้าแก้ไขผู้ใช้</a>
            </div>
        </div>
        <button class="logout-button" onclick="window.location.href='logout.php'">ออกจากระบบ</button>
    </div>
</body>
</html>
