<?php
session_start();
require_once 'db_connect.php'; // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่าผู้ใช้เป็นแอดมินหรือไม่
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// ตรวจสอบว่ามีการส่งค่า product_id มาหรือไม่
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;

if ($product_id) {
    // Fetch the product to edit
    $sql = "SELECT * FROM Products WHERE product_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Prepare the data for update
        $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $stock_quantity = mysqli_real_escape_string($conn, $_POST['stock_quantity']);
        $brand_id = mysqli_real_escape_string($conn, $_POST['brand_id']);
        $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
        
        // Handle image upload
        $image = null;
        if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
            $image = file_get_contents($_FILES['image_url']['tmp_name']);
        }

        // Prepare the SQL update statement
        $sql_update = "UPDATE Products SET 
            product_name=?, 
            price=?, 
            description=?, 
            stock_quantity=?, 
            brand_id=?, 
            category_id=?";

        // Include the image if it's provided
        if ($image !== null) {
            $sql_update .= ", image_url=?";
        }

        $sql_update .= " WHERE product_id=?";

        // Prepare the statement
        $stmt_update = $conn->prepare($sql_update);
        
        // Bind the parameters
        if ($image !== null) {
            $stmt_update->bind_param('sdssiisi', $product_name, $price, $description, $stock_quantity, $brand_id, $category_id, $image, $product_id);
        } else {
            $stmt_update->bind_param('sdssssi', $product_name, $price, $description, $stock_quantity, $brand_id, $category_id, $product_id);
        }

        // Execute the update
        if ($stmt_update->execute()) {
            echo "<p class='success-message'>อัปเดตข้อมูลสินค้าสำเร็จ!</p>";
        } else {
            echo "<p class='error-message'>เกิดข้อผิดพลาด: " . $stmt_update->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขสินค้า</title>
    <link rel="stylesheet" href="css/styles3.css?v=999">
</head>
<body class="edit-product-page">
    <h2 class="form-title">แก้ไขสินค้า</h2>
    <div class="edit-product-container1">
        <div class="edit-product-container">
            
            <?php if ($product): ?>
                <form method="post" action="" class="edit-product-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="product_name" class="form-label">ชื่อสินค้า:</label>
                        <input type="text" name="product_name" class="form-input" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="price" class="form-label">ราคา:</label>
                        <input type="text" name="price" class="form-input" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">คำอธิบาย:</label>
                        <textarea name="description" class="form-textarea" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="stock_quantity" class="form-label">จำนวนในสต็อก:</label>
                        <input type="number" name="stock_quantity" class="form-input" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="brand_id" class="form-label">แบรนด์:</label>
                        <input type="text" name="brand_id" class="form-input" value="<?php echo htmlspecialchars($product['brand_id']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="category_id" class="form-label">หมวดหมู่:</label>
                        <input type="text" name="category_id" class="form-input" value="<?php echo htmlspecialchars($product['category_id']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="image" class="form-label">อัพโหลดรูปภาพ:</label>
                        <input type="file" name="image_url" class="form-input" accept="image/*">
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="back-button" onclick="window.location.href='manage_products.php'">ย้อนกลับ</button>
                        <input type="submit" class="form-submit-btn" value="บันทึกการเปลี่ยนแปลง">
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
    
    