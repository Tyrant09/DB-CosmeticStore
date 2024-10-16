<?php
session_start();

// ตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    header("Location: cart.php"); // หากตะกร้าว่าง กลับไปหน้าตะกร้า
    exit();
}

// Clear the cart after successful payment
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงินสำเร็จ - Cosmetic Store</title>
    <link rel="stylesheet" href="css/styles1.css">
    <style>
        h2 {
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1 class="header-title">ชำระเงินสำเร็จ</h1>
    </header>
    <main class="success-main">
        <div class="success-message">
            <h2>การสั่งซื้อของคุณเสร็จสิ้นเรียบร้อย!</h2>
            <p>ขอบคุณที่เลือกซื้อสินค้ากับเรา</p>
            <a href="index.php" class="back-to-home">กลับไปหน้าหลัก</a>
        </div>
    </main>
    <footer class="footer">
        <p>&copy; 2024 Cosmetic Store. All rights reserved.</p>
    </footer>
</body>
</html>
