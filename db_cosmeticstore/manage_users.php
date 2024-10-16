<?php
session_start();
require_once 'db_connect.php'; // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่าผู้ใช้เป็นแอดมินหรือไม่
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// กำหนดจำนวนผู้ใช้ที่จะแสดงต่อหน้า
$usersPerPage = 8;

// ตรวจสอบว่าผู้ใช้คลิกหน้าที่เท่าไหร่ ถ้าไม่กำหนดจะเริ่มจากหน้า 1
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $usersPerPage; // คำนวณ offset

// ดึงข้อมูลจำนวนผู้ใช้ทั้งหมดเพื่อนับจำนวนหน้า
$totalUsersQuery = "SELECT COUNT(*) as total FROM users"; // เปลี่ยนเป็นตารางผู้ใช้
$totalUsersResult = mysqli_query($conn, $totalUsersQuery);
$totalUsers = mysqli_fetch_assoc($totalUsersResult)['total'];

// คำนวณจำนวนหน้าทั้งหมด
$totalPages = ceil($totalUsers / $usersPerPage);

// ดึงข้อมูลผู้ใช้สำหรับหน้านี้
$sql = "SELECT * FROM users LIMIT $offset, $usersPerPage"; // เปลี่ยนเป็นตารางผู้ใช้
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการผู้ใช้</title>
    <link rel="stylesheet" href="css/styles3.css">
    <link rel="stylesheet" href="css/styles4.css">
</head>
<body>
    <div class="card-container">
        <h2>รายการผู้ใช้</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>รหัสผู้ใช้</th>
                        <th>ชื่อ</th>
                        <th>นามสกุล</th>
                        <th>อีเมล</th>
                        <th>โทรศัพท์</th>
                        <th>ที่อยู่</th>
                        <th>รหัสผ่าน</th>
                        <th>บทบาท</th>
                        <th>แก้ไข</th>
                        <th>ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['phone']); ?></td>
                            <td><?php echo htmlspecialchars($user['address']); ?></td>
                            <td><?php echo htmlspecialchars($user['PASSWORD']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <a href="edit_user.php?user_id=<?php echo $user['user_id']; ?>" class="edit-button">แก้ไข</a>
                            </td>
                            <td>
                                <form method="post" action="delete_user.php" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <input type="submit" value="ลบ" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบผู้ใช้นี้?');">
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
        </div>
    </div>

</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
