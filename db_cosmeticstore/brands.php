<?php
// เริ่มต้นเซสชัน
session_start();

// เชื่อมต่อฐานข้อมูล
require_once 'db_connect.php';

// ดึงข้อมูลแบรนด์สินค้าจากฐานข้อมูล
$sql = "SELECT * FROM Brands"; // คำสั่ง SQL ดึงข้อมูลแบรนด์
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แบรนด์สินค้า - Cosmetic Store</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- เชื่อมต่อ Bootstrap CSS -->
    <link rel="stylesheet" href="css/styles1.css"> <!-- เชื่อมต่อไฟล์ CSS -->
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


    <!-- Section: Brands -->
    <section class="brands">
    <h2>รายการแบรนด์สินค้า</h2>
    <div class="row justify-content-center">
    <?php
        if (mysqli_num_rows($result) > 0) {
            // วนลูปเพื่อแสดงแบรนด์ที่ดึงจากฐานข้อมูล
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='col-md-4 d-flex justify-content-center'>"; // จัดการ์ดให้อยู่กลาง
                echo "<div class='card card-custom' style='margin: 10px;'>"; // ใช้ class 'card-custom' เพื่อควบคุมขนาดการ์ด

                // แปลง BLOB เป็น Base64
                $imageData = base64_encode($row['logo']); // 'logo' คือคอลัมน์ที่เก็บข้อมูล BLOB
                $imageSrc = "data:image/jpeg;base64," . $imageData; // สร้าง Base64 URL สำหรับรูปภาพ

                // แสดงผลรูปภาพแบรนด์จาก BLOB
                echo "<img src='" . $imageSrc . "' class='card-img-top' alt='" . htmlspecialchars($row['brand_name']) . "'>"; 

                echo "<div class='card-body d-flex flex-column'>"; // ทำให้ card-body เป็น flex container
                echo "<h5 class='card-title'>" . htmlspecialchars($row['brand_name']) . "</h5>"; // แสดงชื่อแบรนด์
                echo "<p class='card-text'>" . htmlspecialchars($row['brand_description']) . "</p>"; // แสดงคำอธิบายแบรนด์
                echo "<div class='mt-auto'>"; // เพิ่ม div นี้เพื่อใช้ margin-top:auto
                echo "<a href='products.php?brand_id=" . htmlspecialchars($row['brand_id']) . "' class='btn btn-primary'>ดูสินค้าของแบรนด์นี้</a>"; // ปุ่มดูสินค้า
                echo "</div>"; // ปิด div
                echo "</div></div></div>";
            }
        } else {
            echo "<p>ไม่มีแบรนด์สินค้าในขณะนี้</p>";
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
