<?php
// เริ่มต้นเซสชัน
session_start();

// เชื่อมต่อฐานข้อมูล
require_once 'db_connect.php';

// ดึงข้อมูลช่องทางติดต่อจากฐานข้อมูล
$sql = "SELECT * FROM ContactInfo"; // คำสั่ง SQL ดึงข้อมูลช่องทางติดต่อ
$result = mysqli_query($conn, $sql);

$isLoggedIn = isset($_SESSION['username']) ? 'true' : 'false'; // เช็คสถานะการล็อกอิน
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ช่องทางติดต่อ - Cosmetic Store</title>
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


    <!-- Section: Contact Information -->
    <section class="contact">
        <h2>ข้อมูลช่องทางติดต่อ</h2>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <ul class="list-unstyled">
                <?php
                // วนลูปเพื่อแสดงข้อมูลช่องทางติดต่อ
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<li><strong>" . $row['type'] . ":</strong> " . $row['details'] . "</li>"; // แสดงประเภทและรายละเอียด
                }
                ?>
            </ul>
        <?php else: ?>
            <p>ไม่มีข้อมูลช่องทางติดต่อในขณะนี้</p>
        <?php endif; ?>
    </section>

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
