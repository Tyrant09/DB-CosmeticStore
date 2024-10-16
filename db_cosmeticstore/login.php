<?php
session_start();
require_once 'db_connect.php'; // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบการ submit
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']); // ใช้ email
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // ค้นหาผู้ใช้จากฐานข้อมูลโดยใช้ email
    $sql = "SELECT * FROM Users WHERE email = '$email'"; // เปลี่ยนเป็น email
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // ตรวจสอบรหัสผ่าน (เปรียบเทียบกับรหัสที่ถูกเข้ารหัสในฐานข้อมูล)
        if (password_verify($password, $user['password'])) {
            // เก็บข้อมูลผู้ใช้ในเซสชัน
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['first_name']; // ใช้ first_name แทน username
            $_SESSION['role'] = $user['role']; // ตรวจสอบว่า role มีอยู่ใน Users หรือไม่ (หากไม่มีให้ลบออก)

            // Redirect ไปหน้าหลักหรือหน้า dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "รหัสผ่านไม่ถูกต้อง!";
        }
    } else {
        $error = "ชื่อผู้ใช้ (อีเมล) ไม่ถูกต้อง!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="css/styles1.css"> <!-- เชื่อมโยงกับไฟล์ CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4e1c1;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>เข้าสู่ระบบ</h2>
    <form method="post" action="login_process.php">
        <input type="text" name="email" placeholder="อีเมล" required> <!-- เปลี่ยน name เป็น email -->
        <input type="password" name="password" placeholder="รหัสผ่าน" required>
        <input type="submit" name="login" value="เข้าสู่ระบบ"> <!-- เพิ่ม name ให้กับ submit -->
    </form>

    <?php if (isset($error)): ?>
        <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>

    <div class="social-login">
        <!-- <button class="facebook">Facebook</button> -->
        <button class="google" onclick="window.location.href='forgot_password.php'">Forgot Password</button>
    </div>

    <p>หรือ</p>

    <button class="yves-member-btn" onclick="window.location.href='register.php'">สมัคร cosmeticstore Member เพื่อรับสิทธิพิเศษ</button>
</div>
</body>
</html>
