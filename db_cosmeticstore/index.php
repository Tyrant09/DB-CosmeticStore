<?php
// เริ่มต้นเซสชัน
session_start();

// เชื่อมต่อฐานข้อมูล
require_once 'db_connect.php';

// ดึงข้อมูลสินค้าจากฐานข้อมูล
$sql = "SELECT product_name, price, image_url FROM Products LIMIT 6"; // ดึงข้อมูลสินค้าจำนวน 6 ชิ้น
$result = mysqli_query($conn, $sql);

$isLoggedIn = isset($_SESSION['username']) ? 'true' : 'false'; // เช็คสถานะการล็อกอิน


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosmetic Store - Home</title>
    <!-- เชื่อมต่อ Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="css/styles1.css"> <!-- เชื่อมต่อไฟล์ CSS -->
    <!-- เชื่อมต่อไฟล์ script.js -->
    <script src="js/script.js"></script>
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


    <!-- Carousel -->
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="image/bannerdesktop_02072024-142808.png" class="d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
            </div>
        </div>
        <div class="carousel-item">
            <img src="image/bannerdesktop_02072024-143404.jpeg" class="d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
            </div>
        </div>
        <div class="carousel-item">
            <img src="image/bannerdesktop_04102024-163232.png" class="d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
    </div>

    <!-- Section: Products -->
    <section class="products">
        <h2>Featured Products</h2>
        <div class="product-list">
            <?php
            require_once 'db_connect.php'; // เชื่อมต่อกับฐานข้อมูล

            // ดึงข้อมูลสินค้าทั้งหมดจากฐานข้อมูล
            $result = mysqli_query($conn, "SELECT * FROM products");

            if ($result && mysqli_num_rows($result) > 0) {
                // วนลูปเพื่อแสดงสินค้าที่ดึงจากฐานข้อมูล
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='product'>";
                    
                    // สร้างลิงก์สำหรับแต่ละสินค้า
                    echo "<a href='product_detail.php?product_id=" . $row['product_id'] . "'>";

                    // แปลง BLOB เป็น base64
                    $imageData = base64_encode($row['image_url']);
                    echo "<img src='data:image/jpeg;base64," . $imageData . "' alt='" . htmlspecialchars($row['product_name']) . "'>";
                    
                    echo "<h3>" . htmlspecialchars($row['product_name']) . "</h3>";
                    echo "<p>Price: $" . number_format($row['price'], 2) . "</p>";

                    echo "</a>"; // ปิดลิงก์
                    echo "</div>";
                }
            } else {
                echo "<p>No products found.</p>";
            }

            mysqli_close($conn);
            ?>
        </div>
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
