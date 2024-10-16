<?php
session_start();
require_once 'db_connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบการส่งแบบฟอร์ม
if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // ค้นหาว่ามีผู้ใช้งานอยู่ในระบบที่มีอีเมลนี้หรือไม่
    $sql = "SELECT * FROM Users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // หากมีผู้ใช้งานที่มีอีเมลนี้ในระบบ
        $user = mysqli_fetch_assoc($result);
        
        // ตรวจสอบว่าผู้ใช้ได้กรอกข้อมูลรหัสผ่านใหม่และยืนยันรหัสผ่านหรือไม่
        if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
            $newPassword = mysqli_real_escape_string($conn, $_POST['new_password']);
            $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);

            // ตรวจสอบว่ารหัสผ่านใหม่ตรงกับการยืนยันรหัสผ่านหรือไม่
            if ($newPassword === $confirmPassword) {
                // เข้ารหัสรหัสผ่านใหม่
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // อัปเดตรหัสผ่านใหม่ในฐานข้อมูล
                $updateSql = "UPDATE Users SET password = '$hashedPassword' WHERE email = '$email'";
                if (mysqli_query($conn, $updateSql)) {
                    $success = "เปลี่ยนรหัสผ่านสำเร็จแล้ว"; // เก็บข้อความสำเร็จ
                } else {
                    $error = "เกิดข้อผิดพลาดในการอัปเดตรหัสผ่าน";
                }
            } else {
                $error = "รหัสผ่านใหม่และการยืนยันรหัสผ่านไม่ตรงกัน";
            }
        }
    } else {
        // หากไม่พบผู้ใช้งานที่มีอีเมลนี้
        $error = "ไม่พบผู้ใช้ที่มีอีเมลนี้ในระบบ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลืมรหัสผ่าน</title>
    <link rel="stylesheet" href="css/styles1.css?v=9999"> <!-- เชื่อมโยงกับไฟล์ CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4e1c1;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
<div class="forgot-password-container">
    <h2>ลืมรหัสผ่าน</h2>
    <form method="post" action="forgot_password.php">
        <input type="email" name="email" placeholder="กรอกอีเมลของคุณ" required>
        
        <!-- เพิ่มฟิลด์สำหรับกรอกรหัสผ่านใหม่และยืนยันรหัสผ่าน -->
        <input type="password" name="new_password" placeholder="รหัสผ่านใหม่" required>
        <input type="password" name="confirm_password" placeholder="ยืนยันรหัสผ่านใหม่" required>

        <input type="submit" name="submit" value="เปลี่ยนรหัสผ่าน">

        <!-- แสดงข้อความสำเร็จหรือข้อผิดพลาดหลังจากปุ่ม -->
        <?php if (isset($success)): ?>
            <p class="message success-message"><?php echo $success; ?></p>
            <!-- เพิ่ม JavaScript สำหรับการ redirect หลัง 3 นาที -->
            <script>
                setTimeout(function() {
                    window.location.href = 'login.php'; // redirect ไปหน้า login
                }, 3000); // 3000 มิลลิวินาที = 3 วินาที
            </script>
        <?php elseif (isset($error)): ?>
            <p class="message error-message"><?php echo $error; ?></p>
        <?php endif; ?>
    </form>
</div>
</body>
</html>
