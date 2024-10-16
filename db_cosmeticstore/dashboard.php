<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// แสดงข้อมูลผู้ใช้ที่เข้าสู่ระบบ
echo "Welcome, " . $_SESSION['username'];
?>
<a href="logout.php">Logout</a>
