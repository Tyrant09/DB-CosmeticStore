<?php
session_start();
require_once 'db_connect.php';

// ตรวจสอบว่าผู้ใช้เป็นแอดมินหรือไม่
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// ตรวจสอบว่ามีการ submit ฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $stock_quantity = (int)$_POST['stock_quantity'];
    $brand_id = (int)$_POST['brand_id'];
    $category_id = (int)$_POST['category_id'];

    // อัปโหลดรูปภาพ
    $image = $_FILES['image_url']['tmp_name'];
    $image_data = addslashes(file_get_contents($image));

    // เพิ่มข้อมูลสินค้าเข้าในฐานข้อมูล
    $sql = "INSERT INTO Products (product_name, description, price, stock_quantity, brand_id, category_id, image_url) 
            VALUES ('$product_name', '$description', '$price', '$stock_quantity', '$brand_id', '$category_id', '$image_data')";
    
    if (mysqli_query($conn, $sql)) {
        // แสดงข้อความเพิ่มสินค้าสำเร็จ
        echo "<div class='success-message'>เพิ่มสินค้าสำเร็จ!</div>";
        
        // ปิดการเชื่อมต่อฐานข้อมูล
        mysqli_close($conn);

        // JavaScript ตั้งเวลาย้อนกลับไปยังหน้า manage_products.php
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'manage_products.php';
                }, 3000);
              </script>";
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มสินค้าใหม่</title>
    <link rel="stylesheet" href="css/styles3.css">
</head>
<body class="add-product-page">
    <h2 class="form-title">เพิ่มสินค้าใหม่</h2>
    <div class="add-product-container">
        <form method="post" enctype="multipart/form-data" class="add-product-form">
            <div class="form-group">
                <label for="product_name" class="form-label">ชื่อสินค้า:</label>
                <input type="text" name="product_name" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="price" class="form-label">ราคา:</label>
                <input type="text" name="price" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="description" class="form-label">คำอธิบาย:</label>
                <textarea name="description" class="form-textarea" required></textarea>
            </div>
            <div class="form-group">
                <label for="stock_quantity" class="form-label">จำนวนสต็อก:</label>
                <input type="number" name="stock_quantity" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="brand_id" class="form-label">แบรนด์ ID:</label>
                <input type="number" name="brand_id" class="form-input">
            </div>
            <div class="form-group">
                <label for="category_id" class="form-label">หมวดหมู่ ID:</label>
                <input type="number" name="category_id" class="form-input">
            </div>
            <div class="form-group">
                <label for="image_url" class="form-label">รูปภาพสินค้า:</label>
                <input type="file" name="image_url" class="form-input" required>
            </div>
            <input type="submit" value="เพิ่มสินค้า" class="form-submit-btn">
        </form>
    </div>
</body>
</html>
