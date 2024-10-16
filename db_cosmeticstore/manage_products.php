<?php
session_start();
require_once 'db_connect.php'; // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่าผู้ใช้เป็นแอดมินหรือไม่
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// กำหนดจำนวนสินค้าที่จะแสดงต่อหน้า
$productsPerPage = 8;

// ตรวจสอบว่าผู้ใช้คลิกหน้าที่เท่าไหร่ ถ้าไม่กำหนดจะเริ่มจากหน้า 1
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $productsPerPage; // คำนวณ offset

// ดึงข้อมูลจำนวนสินค้าทั้งหมดเพื่อนับจำนวนหน้า
$totalProductsQuery = "SELECT COUNT(*) as total FROM Products";
$totalProductsResult = mysqli_query($conn, $totalProductsQuery);
$totalProducts = mysqli_fetch_assoc($totalProductsResult)['total'];

// คำนวณจำนวนหน้าทั้งหมด
$totalPages = ceil($totalProducts / $productsPerPage);

// ดึงข้อมูลสินค้าสำหรับหน้านี้
$sql = "SELECT * FROM Products LIMIT $offset, $productsPerPage";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการสินค้า</title>
    <link rel="stylesheet" href="css/styles3.css">
    <link rel="stylesheet" href="css/styles4.css">
</head>
<body>
    <div class="card-container">
        <h2>รายการสินค้า</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>ราคา</th>
                        <th>คำอธิบาย</th>
                        <th>จำนวนในสต็อก</th>
                        <th>แบรนด์</th>
                        <th>หมวดหมู่</th>
                        <th>ภาพ</th>
                        <th>แก้ไข</th>
                        <th>ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['price']); ?></td>
                            <td class="description-cell"><?php echo htmlspecialchars($product['description']); ?></td>
                            <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                            <td><?php echo htmlspecialchars($product['brand_id']); ?></td>
                            <td><?php echo htmlspecialchars($product['category_id']); ?></td>
                            <td class="image-cell">
                                <?php if (!empty($product['image_url'])): ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image_url']); ?>" alt="Product Image">
                                <?php else: ?>
                                    <span>ไม่มีภาพ</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_product.php?product_id=<?php echo $product['product_id']; ?>" class="edit-button">แก้ไข</a>
                            </td>
                            <td>
                                <form method="post" action="delete_product.php" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <input type="submit" value="ลบ" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?');">
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?php echo $currentPage - 1; ?>">หน้าก่อนหน้า</a>
            <?php else: ?>
                <a class="disabled">หน้าก่อนหน้า</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $currentPage ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?php echo $currentPage + 1; ?>">หน้าถัดไป</a>
            <?php else: ?>
                <a class="disabled">หน้าถัดไป</a>
            <?php endif; ?>
        </div>
        
        <div class="button-container">
            <a href="admin_dashboard.php" class="back-btn">ย้อนกลับ</a>
            <a href="add_product.php" class="add-product-btn">เพิ่มสินค้าใหม่</a>
        </div>
    </div>

</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
