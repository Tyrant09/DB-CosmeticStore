<?php
session_start();
require_once 'db_connect.php'; // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่าผู้ใช้เป็นแอดมินหรือไม่
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// ตรวจสอบว่ามีการส่งค่า product_id มาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);

    // Prepare the SQL delete statement
    $sql_delete = "DELETE FROM Products WHERE product_id=?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param('i', $product_id);

    if ($stmt->execute()) {
        echo "<p class='success-message'>ลบสินค้าสำเร็จ!</p>";
    } else {
        echo "<p class='error-message'>เกิดข้อผิดพลาด: " . $stmt->error . "</p>";
    }
}

// Redirect back to the manage products page
header('Location: manage_products.php');
exit();
?>
