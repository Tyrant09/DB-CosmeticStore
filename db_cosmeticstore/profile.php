<?php
session_start(); // เริ่มต้นเซสชัน

require_once 'db_connect.php'; // เชื่อมต่อฐานข้อมูล

// เช็คว่าผู้ใช้ได้เข้าสู่ระบบหรือยัง
if (isset($_SESSION['username'])) {
    // ดึงข้อมูลผู้ใช้จากฐานข้อมูลโดยใช้ username จากเซสชันและเทียบกับ first_name
    $sql = "SELECT first_name, last_name, email, phone, address FROM users WHERE first_name = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']); // ใช้ username จาก session ไปเทียบกับ first_name
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // ตรวจสอบว่าพบข้อมูลผู้ใช้หรือไม่
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result); // ดึงข้อมูลผู้ใช้
    } else {
        echo "ไม่พบข้อมูลผู้ใช้";
        exit();
    }
} else {
    // ถ้าไม่พบ session username ให้เปลี่ยนไปหน้า login
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosmetic Store - Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles1.css"> <!-- เชื่อมต่อไฟล์ CSS -->
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <h1 class="store-title">Profile - Cosmetic Store</h1>
            <div class="user-options">
                <?php if (isset($_SESSION['username'])): ?>
                    <!-- แสดงข้อมูลผู้ใช้ -->
                    <p class="user-greeting">Hello, <?php echo htmlspecialchars($user['first_name']); ?>! <a href="logout.php" class="logout-btn">Logout</a></p>
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


    <!-- Profile Section -->
    <section class="profile-info">
        <div class="profile-container">
            <h2>Profile Information</h2>
            <p><strong class="s1">First Name:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
            <p><strong class="s2">Last Name:</strong> <?php echo htmlspecialchars($user['last_name']); ?></p>
            <p><strong class="s3">Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong class="s4">Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
            <p><strong class="s5">Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Cosmetic Store. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script> <!-- Popper.js -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> <!-- Bootstrap JS -->
</body>
</html>
