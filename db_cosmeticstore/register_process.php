<?php
// เริ่มต้น session
session_start();

// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cosmeticstore";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = ''; // ตัวแปรสำหรับจัดเก็บข้อความผิดพลาด

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // ตรวจสอบว่ารหัสผ่านตรงกัน
    if ($password !== $confirmPassword) {
        $error = "รหัสผ่านไม่ตรงกัน!";
    } else {
        // ตรวจสอบว่ามีผู้ใช้ซ้ำหรือไม่
        $sql = "SELECT * FROM Users WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $error = "อีเมลนี้มีผู้ใช้งานแล้ว!";
        } else {
            // เข้ารหัสรหัสผ่าน
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // แทรกข้อมูลผู้ใช้ใหม่ลงในฐานข้อมูล
            $sql = "INSERT INTO Users (first_name, last_name, email, phone, address, password) 
                    VALUES ('$firstName', '$lastName', '$email', '$phone', '$address', '$hashedPassword')";

            if ($conn->query($sql) === TRUE) {
                // สมัครสมาชิกสำเร็จ เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
                header("Location: login.php");
                exit();
            } else {
                $error = "เกิดข้อผิดพลาด: " . $conn->error;
            }
        }
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - Cosmetic Store</title>
    <link rel="stylesheet" href="css/styles1.css">
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

        /* จัดแต่งข้อความแสดงสถานะ */
        .status-message {
            margin-top: 20px;
            padding: 15px;
            background-color: #ffdddd;
            color: #d8000c;
            border: 1px solid #d8000c;
            border-radius: 5px;
            font-size: 1.1rem;
            text-align: center;
        }

        .success-message {
            margin-top: 20px;
            padding: 15px;
            background-color: #ddffdd;
            color: #4caf50;
            border: 1px solid #4caf50;
            border-radius: 5px;
            font-size: 1.1rem;
            text-align: center;
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

                <!-- แสดงข้อความสถานะถ้ามีข้อผิดพลาด -->
        <?php if (!empty($error)): ?>
            <div class="status-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
    </div>



</body>
</html>
