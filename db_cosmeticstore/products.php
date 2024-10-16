<?php
// เริ่มต้นเซสชัน
session_start();

// เชื่อมต่อฐานข้อมูล
require_once 'db_connect.php';

$category_name = "";
$brand_name = "";
$sql = "";

// ตรวจสอบว่า category_id หรือ brand_id ถูกส่งมาหรือไม่
if (isset($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']); // แปลง category_id เป็น int เพื่อความปลอดภัย

    // ดึงชื่อหมวดหมู่จากฐานข้อมูล
    $category_sql = "SELECT category_name FROM Categories WHERE category_id = ?";
    $category_stmt = mysqli_prepare($conn, $category_sql);
    mysqli_stmt_bind_param($category_stmt, "i", $category_id);
    mysqli_stmt_execute($category_stmt);
    $category_result = mysqli_stmt_get_result($category_stmt);

    // ตรวจสอบว่ามีหมวดหมู่หรือไม่
    if (mysqli_num_rows($category_result) > 0) {
        $category_row = mysqli_fetch_assoc($category_result);
        $category_name = htmlspecialchars($category_row['category_name']);
    } else {
        $category_name = "หมวดหมู่ไม่พบ"; // กรณีไม่พบหมวดหมู่
    }

    // ดึงข้อมูลสินค้าตามหมวดหมู่
    $sql = "SELECT product_id, product_name, price, image_url FROM Products WHERE category_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $category_id);

} elseif (isset($_GET['brand_id'])) {
    $brand_id = intval($_GET['brand_id']); // แปลง brand_id เป็น int เพื่อความปลอดภัย

    // ดึงชื่อแบรนด์จากฐานข้อมูล
    $brand_sql = "SELECT brand_name FROM Brands WHERE brand_id = ?";
    $brand_stmt = mysqli_prepare($conn, $brand_sql);
    mysqli_stmt_bind_param($brand_stmt, "i", $brand_id);
    mysqli_stmt_execute($brand_stmt);
    $brand_result = mysqli_stmt_get_result($brand_stmt);

    // ตรวจสอบว่ามีแบรนด์หรือไม่
    if (mysqli_num_rows($brand_result) > 0) {
        $brand_row = mysqli_fetch_assoc($brand_result);
        $brand_name = htmlspecialchars($brand_row['brand_name']);
    } else {
        $brand_name = "แบรนด์ไม่พบ"; // กรณีไม่พบแบรนด์
    }

    // ดึงข้อมูลสินค้าตามแบรนด์
    $sql = "SELECT product_id, product_name, price, image_url FROM Products WHERE brand_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $brand_id);

} else {
    echo "<p>ไม่พบหมวดหมู่หรือแบรนด์สินค้า</p>";
    exit;
}

// Execute the query
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Cosmetic Store</title>
    <link rel="stylesheet" href="css/styles1.css"> <!-- เชื่อมต่อไฟล์ CSS -->
    <style>
        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="products-page"> <!-- ตั้งชื่อ class เฉพาะสำหรับหน้า products.php -->
        <header class="products-header">
            <?php if (!empty($category_name)): ?>
                <h1>สินค้าที่อยู่ในหมวดหมู่ "<?php echo $category_name; ?>"</h1> <!-- แสดงชื่อหมวดหมู่ -->
            <?php elseif (!empty($brand_name)): ?>
                <h1>สินค้าที่อยู่ในแบรนด์ "<?php echo $brand_name; ?>"</h1> <!-- แสดงชื่อแบรนด์ -->
            <?php endif; ?>
        </header>

        <section class="products-section">
            <div class="product-list">
                <?php
                if (isset($result) && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $imageData = base64_encode($row['image_url']);
                        $imageSrc = "data:image/jpeg;base64," . $imageData;
                        $productId = isset($row['product_id']) ? $row['product_id'] : '';
                ?>
                <div class="product-card">
                    <!-- ทำให้รูปภาพสามารถคลิกได้และลิงก์ไปยังหน้า product_detail.php -->
                    <a href="product_detail.php?product_id=<?php echo $productId; ?>">
                        <img src="<?php echo htmlspecialchars($imageSrc); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" class="product-image">
                    </a>

                    <!-- ทำให้ชื่อสินค้าสามารถคลิกได้และลิงก์ไปยังหน้า product_detail.php -->
                    <a href="product_detail.php?product_id=<?php echo $productId; ?>">
                        <h5 class="product-name"><?php echo htmlspecialchars($row['product_name']); ?></h5>
                    </a>

                    <p class="product-price">฿<?php echo number_format($row['price'], 2); ?></p>

                    <div class="button-group"> <!-- กลุ่มปุ่ม -->
                        <!-- ฟอร์มสำหรับเพิ่มในตะกร้า -->
                        <form action="add_to_cart.php" method="POST">
                            <input type="hidden" name="quantity" value="1"> <!-- กำหนดจำนวนเริ่มต้นเป็น 1 -->
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($productId); ?>"> <!-- ฟิลด์ซ่อนสำหรับ product_id -->
                            <button type="submit" class="add-to-cart">ใส่ตะกร้า</button> <!-- ปุ่มส่งข้อมูล -->
                        </form>
                    </div>
                </div>

                <?php
                    }
                } else {
                    echo "<p>ไม่มีสินค้าที่ตรงกับหมวดหมู่หรือแบรนด์นี้</p>";
                }
                ?>
            </div>
        </section>





        <!-- <script>
        function addToCart(productId) {
            // Create a form programmatically
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'add_to_cart.php';

            // Create a hidden input to store the product ID
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'product_id';
            input.value = productId;

            // Append the input to the form
            form.appendChild(input);

            // Append the form to the body
            document.body.appendChild(form);

            // Submit the form
            form.submit();
        }
        </script> -->

    </div>

    <br>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Cosmetic Store. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
