<?php
session_start(); // เริ่มต้น session

// เชื่อมต่อฐานข้อมูล
$host = 'localhost';
$db = 'cosmeticstore'; // ชื่อฐานข้อมูลของคุณ
$user = 'root'; // ชื่อผู้ใช้ของฐานข้อมูล
$pass = ''; // รหัสผ่านของฐานข้อมูล

$conn = new mysqli($host, $user, $pass, $db);

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

$statusMessage = ''; // สร้างตัวแปรสำหรับเก็บข้อความสถานะ

// ตรวจสอบว่ามีการส่งข้อมูล POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม login
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // ตรวจสอบว่าไม่มีฟิลด์ใดเป็นค่าว่าง
    if (empty($email) || empty($password)) {
        $statusMessage = "กรุณากรอกข้อมูลให้ครบถ้วน";
    } else {
        // คำสั่ง SQL เพื่อค้นหาผู้ใช้ในฐานข้อมูล
        $sql = "SELECT * FROM Users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $statusMessage = "การเตรียมคำสั่ง SQL ล้มเหลว: " . $conn->error;
        } else {
            $stmt->bind_param("s", $email); // ผูกค่ากับคำสั่ง SQL
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // ตรวจสอบรหัสผ่าน (ซึ่งถูกเข้ารหัสด้วย password_hash)
                if (password_verify($password, $user['PASSWORD'])) {
                    // รหัสผ่านถูกต้อง
                    $_SESSION['user_id'] = $user['user_id']; // ปรับตามคอลัมน์ในฐานข้อมูล
                    $_SESSION['username'] = $user['first_name']; // ใช้ first_name แทน username
                    $_SESSION['role'] = $user['role']; // เก็บระดับผู้ใช้

                    // ตรวจสอบระดับผู้ใช้
                    if ($user['role'] === 'admin') {
                        // ถ้าเป็น admin เปลี่ยนเส้นทางไปยังหน้า admin_dashboard.php
                        header("Location: admin_dashboard.php");
                    } else {
                        // ถ้าเป็น user เปลี่ยนเส้นทางไปยังหน้า index.php
                        header("Location: index.php");
                    }
                    exit();
                } else {
                    // รหัสผ่านไม่ถูกต้อง
                    $statusMessage = "รหัสผ่านไม่ถูกต้อง!";
                }
            } else {
                // ไม่พบชื่อผู้ใช้
                $statusMessage = "ชื่อผู้ใช้ (อีเมล) ไม่ถูกต้อง!";
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
    <title>เข้าสู่ระบบ</title>
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
    </style>
</head>
<body>
    <div class="login-container">
        <h2>เข้าสู่ระบบ</h2>
        <form method="post" action="login_process.php">
            <input type="text" name="email" placeholder="อีเมล" required>
            <input type="password" name="password" placeholder="รหัสผ่าน" required>
            <input type="submit" name="login" value="เข้าสู่ระบบ">
        </form>

        <?php if (!empty($statusMessage)): ?>
            <div class="status-message">
                <?php echo $statusMessage; ?>
            </div>
        <?php endif; ?>

        <div class="social-login">
            <button class="google" onclick="window.location.href='forgot_password.php'">Forgot Password</button>
        </div>

        <p>หรือ</p>

        <button class="yves-member-btn" onclick="window.location.href='register.php'">สมัคร cosmeticstore Member เพื่อรับสิทธิพิเศษ</button>
    </div>

</body>
</html>
