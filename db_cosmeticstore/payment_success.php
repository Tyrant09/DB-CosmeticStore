<?php
session_start();
require_once 'db_connect.php'; // Connect to the database

// ตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    header("Location: cart.php"); // หากตะกร้าว่าง กลับไปหน้าตะกร้า
    exit();
}

// Get the cart items
$cart = $_SESSION['cart'];

// Process the payment and update the stock
foreach ($cart as $product_id => $quantity) {
    // Update the stock in the database
    $sql_update = "UPDATE Products SET stock_quantity = stock_quantity - ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param('ii', $quantity, $product_id); // Bind quantity and product ID

    if (!$stmt->execute()) {
        echo "<p class='error-message'>เกิดข้อผิดพลาดในการอัปเดตสต็อก: " . $stmt->error . "</p>";
        exit();
    }
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
