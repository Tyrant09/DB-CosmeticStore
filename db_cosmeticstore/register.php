<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost"; // ชื่อโฮสต์
$username = "root"; // ชื่อผู้ใช้ฐานข้อมูล
$password = ""; // รหัสผ่านฐานข้อมูล
$dbname = "cosmeticstore"; // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = ''; // ตัวแปรสำหรับจัดเก็บข้อความผิดพลาด

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // ตรวจสอบว่ามีผู้ใช้ซ้ำหรือไม่
    $sql = "SELECT * FROM Users WHERE username='$username' OR email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $error = "ชื่อผู้ใช้หรืออีเมลนี้มีผู้ใช้งานแล้ว";
    } else {
        // แทรกข้อมูลผู้ใช้ใหม่
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่าน
        $sql = "INSERT INTO Users (username, password, email) VALUES ('$username', '$hashedPassword', '$email')";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: login.php"); // เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
            exit();
        } else {
            $error = "เกิดข้อผิดพลาด: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
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
    <script>
        function validatePassword() {
            var password = document.getElementsByName("password")[0].value;
            var confirmPassword = document.getElementsByName("confirm_password")[0].value;

            if (password !== confirmPassword) {
                alert("รหัสผ่านไม่ตรงกัน กรุณาตรวจสอบอีกครั้ง!"); // Alert if passwords don't match
                return false; // Prevent form submission
            }
            return true; // Allow form submission if passwords match
        }
    </script>
</head>
<body>
    <div class="register-container">
        <h2>สมัครสมาชิก</h2>
        <form method="post" action="register_process.php">
            <input type="text" name="first_name" placeholder="ชื่อ" required>
            <input type="text" name="last_name" placeholder="นามสกุล" required>
            <input type="email" name="email" placeholder="อีเมล" required>
            <input type="text" name="phone" placeholder="เบอร์โทร" required>
            <textarea type="address" name="address" placeholder="ที่อยู่" required></textarea> <!-- Address field as a textarea -->
            <input type="password" name="password" placeholder="รหัสผ่าน" required>
            <input type="password" name="confirm_password" placeholder="ยืนยันรหัสผ่าน" required>
            <input type="submit" value="สมัครสมาชิก">
        </form>
        
        <p>มีบัญชีอยู่แล้ว? <a class="register-link" href="login.php">เข้าสู่ระบบที่นี่</a></p>
    </div>
</body>
</html>