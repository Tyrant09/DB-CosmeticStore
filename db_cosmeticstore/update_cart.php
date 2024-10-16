<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['action'])) {
    $productId = $_POST['product_id'];
    $action = $_POST['action'];

    // ตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่
    if (isset($_SESSION['cart'][$productId])) {
        if ($action === 'increase') {
            // เพิ่มจำนวนสินค้า
            $_SESSION['cart'][$productId]++;
        } elseif ($action === 'decrease') {
            // ลดจำนวนสินค้า
            $_SESSION['cart'][$productId]--;
            // ลบสินค้าออกจากตะกร้าหากจำนวนลดลงเหลือ 0
            if ($_SESSION['cart'][$productId] <= 0) {
                unset($_SESSION['cart'][$productId]);
            }
        }
    }
}

// กลับไปที่หน้าตะกร้าสินค้า
header('Location: cart.php');
exit();
?>
