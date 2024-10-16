<?php
session_start();

// เชื่อมต่อฐานข้อมูล
require_once 'db_connect.php';

// ตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    echo "<p class='empty-cart'>ตะกร้าของคุณว่างเปล่า</p>";
    exit();
}

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

            echo "<div class='product'>";
            echo "<h3 class='product-name'>$productName</h3>";
            echo "<p class='product-price'>ราคา: ฿" . number_format($productPrice, 2) . "</p>";
            echo "<p class='product-quantity'>จำนวน: $quantity</p>";
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
</head>
<body>
    <header>
        <h1 class="header-title">ตะกร้าสินค้า</h1>
    </header>
    <main class="cart-main">
        <?php displayCart(); ?>

        <!-- ปุ่มย้อนกลับ -->
        <form action="payment_success.php" method="post" class="checkout">
            <button class="back-button">
                <a href="categories.php?category_id=<?php echo isset($categoryId) ? $categoryId : ''; ?>">ย้อนกลับ</a>
            </button>

            <!-- ปุ่มชำระเงิน -->
            <button type="submit" class="checkout-button">ชำระเงิน</button>
        </form>
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
