<?php
session_start(); // เริ่มต้น session
require_once 'db_connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่ง product_id หรือไม่
if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']); // รับค่า product_id จาก URL

    // คำสั่ง SQL เพื่อดึงข้อมูลสินค้าตาม product_id
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<p>Product not found.</p>";
        exit();
    }
} else {
    echo "<p>No product selected.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?> - Cosmetic Store</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="css/styles1.css"> <!-- เชื่อมต่อไฟล์ CSS -->
    <link rel="stylesheet" href="css/styles2.css">
    <style>
        h2 {
            text-align: start;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <h1 class="store-title">Welcome to Cosmetic Store</h1>
            <div class="user-options">
                <?php if (isset($_SESSION['username'])): ?>
                    <p class="user-greeting">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>! 
                        <a href="logout.php" class="logout-btn">Logout</a>
                        <a href="profile.php" class="profile-btn">ดูโปรไฟล์</a>
                    </p>
                <?php else: ?>
                    <a href="login.php" class="login-btn">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Menu Bar -->
    <nav class="navbar">
        <ul>
            <li><a href="index.php">หน้าแรก</a></li>
            <li><a href="categories.php">หมวดหมู่สินค้า</a></li>
            <li><a href="brands.php">แบรนด์สินค้า</a></li>
            <li><a href="about.php">เกี่ยวกับเรา</a></li>
            <li><a href="contact.php">ช่องทางติดต่อ</a></li>
            <?php if (isset($_SESSION['username'])): ?> <!-- เช็คการล็อกอิน -->
                <li><a href="cart.php">ตะกร้าสินค้า</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- โครงสร้าง HTML ของคุณ -->
    <div class="detail-container">
        <!-- ภาพผลิตภัณฑ์ -->
        <div class="image-container">
            <?php
                $imageData = base64_encode($product['image_url']);
                echo "<img src='data:image/jpeg;base64," . $imageData . "' alt='" . htmlspecialchars($product['product_name']) . "'>";
            ?>
        </div>

        <!-- รายละเอียดผลิตภัณฑ์ -->
        <div class="details-container">
            <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
            <p class="price">฿<?php echo number_format($product['price'], 2); ?></p>
            
            <!-- ขนาดผลิตภัณฑ์ -->
            <div class="size-selector">
                <label for="size">ขนาด: </label>
                <select name="size" id="size">
                    <option value="300ml">300ml</option>
                    <option value="100ml">100ml</option>
                </select>
            </div>

            <!-- จำนวนและปุ่ม -->
            <div class="quantity-buttons">
                <input type="number" id="quantity" name="quantity" value="1" min="1">
                <form action="add_to_cart.php" method="POST" style="display:inline;">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    <input type="hidden" name="quantity" id="formQuantity" value="1">
                    <input type="submit" value="Add to Cart">
                </form>
            </div>

            <script>
                document.getElementById('quantity').addEventListener('input', function() {
                    document.getElementById('formQuantity').value = this.value;
                });
            </script>

            <!-- การแชร์สื่อสังคม -->
            <div class="social-share">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>

            <!-- รายละเอียดสินค้าเพิ่มเติม (Tabs) -->
            <div class="tabs">
                <ul>
                    <li class="active" data-tab="details">รายละเอียด</li>
                    <li data-tab="reviews">รีวิวผู้ใช้จริง</li>
                    <li data-tab="how-to-use">วิธีใช้</li>
                </ul>

                <div id="details" class="tab-content active">
                    <h2>รายละเอียดสินค้า</h2>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                </div>

                <div id="reviews" class="tab-content">
                    <h2>รีวิวผู้ใช้จริง</h2>
                    <p>ยังไม่มีรีวิวสำหรับผลิตภัณฑ์นี้</p>
                </div>

                <div id="how-to-use" class="tab-content">
                    <h2>วิธีใช้</h2>
                    <p>ใช้ผลิตภัณฑ์ตามคำแนะนำที่ระบุในบรรจุภัณฑ์</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Cosmetic Store. All rights reserved.</p>
    </footer>

    <!-- ลิงก์ไปยังไฟล์ tabs.js -->
    <script src="js/tabs.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script> <!-- Popper.js -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> <!-- Bootstrap JS -->
</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
