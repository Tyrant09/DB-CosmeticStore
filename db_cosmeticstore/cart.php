<?php
session_start();

// เชื่อมต่อฐานข้อมูล
require_once 'db_connect.php';

// ฟังก์ชันเพื่อแสดงสินค้าที่อยู่ในตะกร้า
function displayCart() {
    global $conn;

    // กำหนดให้ $cartItems เป็นอาร์เรย์เปล่าถ้ายังไม่ได้กำหนด
    $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

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

            echo "<div class='product'>";
            echo "<h3 class='product-name'>$productName</h3>";
            echo "<br>";
            
            // ปุ่มเพิ่ม/ลดจำนวนสินค้า
            echo "<div class='product-quantity'>";
            echo "<form action='update_cart.php' method='post' class='quantity-form'>"; // เพิ่ม class สำหรับทำงานกับ JS
            echo "<input type='hidden' name='product_id' value='$productId'>";
            echo "<button type='submit' name='action' value='decrease' class='quantity-button'>-</button>";
            echo "<span class='quantity'>$quantity</span>";
            echo "<button type='submit' name='action' value='increase' class='quantity-button'>+</button>";
            echo "</form>";
            echo "</div>";
            
            echo "<p class='product-total'>รวม: ฿" . number_format($productPrice * $quantity, 2) . "</p>";
            echo "</div>";
        }

        // แสดงราคาสุทธิของตะกร้า
        echo "<h2 class='total-price'>ราคาสุทธิ: ฿" . number_format($totalPrice, 2) . "</h2>";
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
    <title>ตะกร้าสินค้า - Cosmetic Store</title>
    <link rel="stylesheet" href="css/styles1.css">
    <!-- Link JavaScript -->
    <script src="js/cart.js"></script>
    <script src="js/handleBackButton.js"></script>
</head>
<body>
    <header>
        <h1 class="header-title">ตะกร้าสินค้า</h1>
    </header>
    <main class="cart-main">
        <?php displayCart(); ?>

        <!-- ปุ่มย้อนกลับ และ ชำระเงิน -->
        <div class="checkout-buttons">
            <a href="index.php" class="back-button">ย้อนกลับ</a>
            <form action="payment.php" method="post" class="checkout">
                <button type="submit" class="checkout-button">ชำระเงิน</button>
            </form>
        </div>
    </main>
    <footer class="footer">
        <p>&copy; 2024 Cosmetic Store. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
