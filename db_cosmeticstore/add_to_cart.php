<?php
session_start();

// เชื่อมต่อฐานข้อมูล
require_once 'db_connect.php'; // ตรวจสอบว่าการเชื่อมต่อฐานข้อมูลถูกต้อง

// ฟังก์ชันสำหรับดักจับข้อผิดพลาด
function handleError($message) {
    echo "<p>เกิดข้อผิดพลาด: $message</p>";
    exit();
}

// ฟังก์ชันสำหรับเพิ่มสินค้าลงในตะกร้า
function addToCart($productId, $quantity) {
    // ตรวจสอบให้แน่ใจว่า productId และ quantity เป็นตัวเลขที่ถูกต้อง
    if (!is_numeric($productId) || !is_numeric($quantity) || $quantity < 1) {
        handleError("ID ของผลิตภัณฑ์หรือจำนวนสินค้าไม่ถูกต้อง");
    }

    // ถ้าตะกร้าไม่มีสินค้านี้ ให้เพิ่มใหม่ ถ้ามีอยู่แล้วให้เพิ่มจำนวนตามที่เลือก
    try {
        if (isset($_SESSION['cart'][$productId])) {
            // ถ้ามีสินค้าในตะกร้าอยู่แล้ว ให้เพิ่มจำนวนตามที่ส่งมา
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            // ถ้ายังไม่มีสินค้าในตะกร้า ให้เพิ่มใหม่
            $_SESSION['cart'][$productId] = $quantity;
        }
    } catch (Exception $e) {
        handleError("ไม่สามารถเพิ่มสินค้าลงในตะกร้าได้: " . $e->getMessage());
    }
}

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์ม
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = intval($_POST['quantity']); // แปลงจำนวนสินค้าเป็นจำนวนเต็ม

    // เรียกฟังก์ชันเพื่อเพิ่มสินค้าในตะกร้า
    addToCart($productId, $quantity);
} else {
    handleError("ไม่มีข้อมูลผลิตภัณฑ์หรือจำนวนสินค้าที่ส่งมา");
}

// กลับไปที่หน้าสินค้า (ปรับ URL ตามที่ต้องการ)
header("Location: cart.php");
exit();
?>
