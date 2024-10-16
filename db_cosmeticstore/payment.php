<?php
session_start();

// เชื่อมต่อฐานข้อมูล
require_once 'db_connect.php';

// ตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่
// if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
//     echo "<p class='empty-cart'>ตะกร้าของคุณว่างเปล่า</p>";
//     exit();
// }

// ฟังก์ชันเพื่อแสดงสินค้าที่อยู่ในตะกร้า
function displayCart() {
    global $conn;

    // ดึงข้อมูลสินค้าในตะกร้าจากเซสชัน
    $cartItems = $_SESSION['cart'];

    if (empty($cartItems)) {
        echo "<p class='empty-cart'>ไม่มีสินค้าในตะกร้า</p>";
        return;
    }

    // สร้าง placeholders สำหรับ SQL query
    $placeholders = implode(',', array_fill(0, count($cartItems), '?'));

    // คำสั่ง SQL เพื่อดึงข้อมูลสินค้า
    $sql = "SELECT product_id, product_name, price FROM Products WHERE product_id IN ($placeholders)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo "<p class='error'>เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL</p>";
        return; 
    }

    // Bind parameter และ execute
    $types = str_repeat('i', count($cartItems));
    $stmt->bind_param($types, ...array_keys($cartItems));
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && mysqli_num_rows($result) > 0) {
        $totalPrice = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            $productId = $row['product_id'];
            $productName = $row['product_name'];
            $productPrice = $row['price'];
            $quantity = $cartItems[$productId];

            // คำนวณราคารวม
            $totalPrice += $productPrice * $quantity;

            echo "<div class='payment-item'>";
            echo "<h3 class='payment-item-name'>$productName</h3>";
            echo "<p class='payment-item-price'>ราคา: ฿" . number_format($productPrice, 2) . "</p>";
            echo "<p class='payment-item-quantity'>จำนวน: $quantity</p>";
            echo "<p class='payment-item-total'>รวม: ฿" . number_format($productPrice * $quantity, 2) . "</p>";
            echo "</div>";
        }

        // แสดงราคาสุทธิของตะกร้า
        echo "<h2 class='payment-total'>ราคาสุทธิ: ฿" . number_format($totalPrice, 2) . "</h2>";
    } else {
        echo "<p class='error'>ไม่พบสินค้าในฐานข้อมูล</p>";
    }
}

// ตรวจสอบ category_id จาก session
$categoryId = isset($_SESSION['category_id']) ? $_SESSION['category_id'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าชำระเงิน - Cosmetic Store</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="css/styles1.css">
    <link rel="stylesheet" href="css/styles2.css">
    <style>
        /* body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
        } */
    </style>
</head>
<body>
    <header>
        <h1 class="payment-header-title">หน้าชำระเงิน</h1>
    </header>
    <main class="payment-main">
        <?php displayCart(); ?>

        <!-- ฟอร์มการชำระเงิน -->
        <form action="payment_success.php" method="post" class="payment-form">
            <label for="name">ชื่อ:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">อีเมล:</label>
            <input type="email" id="email" name="email" required>

            <label for="address">ที่อยู่สำหรับจัดส่ง:</label>
            <textarea id="address" name="address" required></textarea>

            <!-- ห่อหุ้มปุ่มใน div ใหม่ -->
            <div class="payment-buttons">
                <button class="payment-back-button">
                    <a href="cart.php" class="back-link">ย้อนกลับ</a>
                </button>
                <button type="submit" class="payment-submit-button">ยืนยันการชำระเงิน</button>
            </div>
        </form>
    </main>
    <br><br><br><br>
    <footer class="footer">
        <p>&copy; 2024 Cosmetic Store. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script> <!-- Popper.js -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> <!-- Bootstrap JS -->
</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
