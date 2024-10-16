<?php
// เริ่มต้นเซสชัน
session_start();

// เชื่อมต่อฐานข้อมูล
require_once 'db_connect.php';

// ดึงข้อมูลเกี่ยวกับเราจากฐานข้อมูล
$sql = "SELECT * FROM AboutUs"; // คำสั่ง SQL ดึงข้อมูลเกี่ยวกับเรา
$result = mysqli_query($conn, $sql);
$about_data = mysqli_fetch_assoc($result); // ดึงข้อมูลแค่แถวเดียว

$isLoggedIn = isset($_SESSION['username']) ? 'true' : 'false'; // เช็คสถานะการล็อกอิน
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เกี่ยวกับเรา - Cosmetic Store</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- เชื่อมต่อ Bootstrap CSS -->
    <link rel="stylesheet" href="css/styles1.css"> <!-- เชื่อมต่อไฟล์ CSS -->
    <script src="js/script.js"></script>
</head>
<body>
    <!-- Header -->
    <header class="main-header">
    <div class="container">
        <h1 class="store-title">Welcome to Cosmetic Store</h1>
        <div class="user-options">
            <?php if (isset($_SESSION['username'])): ?>
                <p class="user-greeting">Hello, <?php echo $_SESSION['username']; ?>! <a href="logout.php" class="logout-btn">Logout</a></p>
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


    <!-- Section: About Us -->
    <section class="about">
        <h2>เกี่ยวกับเรา</h2>
        <?php if ($about_data): ?>
            <p><?php echo $about_data['description']; ?></p> <!-- แสดงคำอธิบายเกี่ยวกับเรา -->
        <?php else: ?>
            <p>ข้อมูลเกี่ยวกับเราไม่สามารถแสดงได้ในขณะนี้</p>
        <?php endif; ?>
    </section>

    <br>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Cosmetic Store. All rights reserved.</p>
    </footer>

    <!-- ส่งค่าการล็อกอินไปยัง JavaScript -->
    <script>
        // ส่งค่า isLoggedIn จาก PHP ไป JavaScript
        var isLoggedIn = <?php echo $isLoggedIn; ?>;
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script> <!-- Popper.js -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> <!-- Bootstrap JS -->
</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
