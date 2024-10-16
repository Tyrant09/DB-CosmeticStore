<?php
// เริ่มต้นเซสชัน
session_start();

// เชื่อมต่อฐานข้อมูล
require_once 'db_connect.php';

// ดึงข้อมูลหมวดหมู่สินค้าจากฐานข้อมูล
$sql = "SELECT * FROM Categories"; // คำสั่ง SQL ดึงข้อมูลหมวดหมู่
$result = mysqli_query($conn, $sql);

// ตรวจสอบว่ามีการส่ง category_id มาหรือไม่
if (isset($_GET['category_id'])) {
    // เก็บ category_id ไว้ในเซสชัน
    $_SESSION['category_id'] = $_GET['category_id'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หมวดหมู่สินค้า - Cosmetic Store</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- เชื่อมต่อ Bootstrap CSS -->
    <!-- เชื่อมต่อไฟล์ CSS -->
    <link rel="stylesheet" href="css/styles1.css?v=9999"> 
</head>
<body>
    <!-- Header -->
    <header class="main-header">
    <div class="container">
        <h1 class="store-title">Welcome to Cosmetic Store</h1>
        <div class="user-options">
            <?php if (isset($_SESSION['username'])): ?>
                <p class="user-greeting">Hello, <?php echo $_SESSION['username']; ?>! 
                    <a href="logout.php" class="logout-btn">Logout</a>
                    <a href="profile.php" class="profile-btn">ดูโปรไฟล์</a>
                </p>
                <!-- ปุ่มดูโปรไฟล์ -->
                
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


    <!-- Section: Categories -->
    <section class="categories">
        <h2>รายการหมวดหมู่สินค้า</h2>
        <div class="row">
            <?php
            if (mysqli_num_rows($result) > 0) {
                // วนลูปเพื่อแสดงหมวดหมู่ที่ดึงจากฐานข้อมูล
                while ($row = mysqli_fetch_assoc($result)) {
                    // ตรวจสอบว่ามี 'description' และ 'category_id' หรือไม่
                    $description = isset($row['category_description']) ? $row['category_description'] : 'ไม่มีคำอธิบาย';
                    $category_id = isset($row['category_id']) ? $row['category_id'] : null; // ใช้ 'category_id' แทน 'id'

                    echo "<div class='col-md-4'>"; // ใช้ Bootstrap เพื่อจัดเรียงคอลัมน์
                    echo "<div class='card' style='margin: 10px;'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($row['category_name']) . "</h5>"; // แสดงชื่อหมวดหมู่
                    echo "<p class='card-text'>" . htmlspecialchars($description) . "</p>"; // แสดงคำอธิบายหมวดหมู่

                    if ($category_id) { // ถ้า category_id มีค่า แสดงปุ่มลิงก์
                        echo "<a href='products.php?category_id=" . htmlspecialchars($category_id) . "' class='btn btn-primary'>ดูสินค้า</a>"; // ปุ่มดูสินค้า
                    } else {
                        echo "<button class='btn btn-secondary' disabled>ไม่พบหมวดหมู่</button>"; // ถ้าไม่มี category_id แสดงปุ่มที่ไม่สามารถคลิกได้
                    }

                    echo "</div></div></div>";
                }
            } else {
                echo "<p>ไม่มีหมวดหมู่สินค้าในขณะนี้</p>";
            }
            ?>
        </div>
    </section>

    <br>

    <!-- Footer -->
    <footer>
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
