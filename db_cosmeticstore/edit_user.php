<?php
session_start();
require_once 'db_connect.php'; // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่าผู้ใช้เป็นแอดมินหรือไม่
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// ตรวจสอบว่ามีการส่งค่า user_id มาหรือไม่
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if ($user_id) {
    // Fetch the user to edit
    $sql = "SELECT * FROM users WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Prepare the data for update
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $role = mysqli_real_escape_string($conn, $_POST['role']);

        // Prepare the SQL update statement
        $sql_update = "UPDATE users SET 
            first_name=?, 
            last_name=?, 
            email=?, 
            phone=?, 
            address=?, 
            PASSWORD=?, 
            role=? 
            WHERE user_id=?";

        // Prepare the statement
        $stmt_update = $conn->prepare($sql_update);

        // Bind the parameters
        $stmt_update->bind_param('sssssssi', $first_name, $last_name, $email, $phone, $address, $password, $role, $user_id);

        // Execute the update
        if ($stmt_update->execute()) {
            echo "<p class='success-message'>อัปเดตข้อมูลผู้ใช้สำเร็จ!</p>";
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
    <title>แก้ไขข้อมูลผู้ใช้</title>
    <link rel="stylesheet" href="css/styles3.css?v=999">
</head>
<body class="edit-user-page">
    <h2 class="form-title">แก้ไขข้อมูลผู้ใช้</h2>
    <div class="edit-user-container1">
        <div class="edit-user-container">
            <?php if ($user): ?>
                <form method="post" action="" class="edit-user-form">
                    <div class="form-group">
                        <label for="first_name" class="form-label">ชื่อ:</label>
                        <input type="text" name="first_name" class="form-input" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name" class="form-label">นามสกุล:</label>
                        <input type="text" name="last_name" class="form-input" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">อีเมล:</label>
                        <input type="email" name="email" class="form-input" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone" class="form-label">โทรศัพท์:</label>
                        <input type="text" name="phone" class="form-input" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="address" class="form-label">ที่อยู่:</label>
                        <input type="text" name="address" class="form-input" value="<?php echo htmlspecialchars($user['address']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">รหัสผ่าน:</label>
                        <input type="password" name="password" class="form-input" value="<?php echo htmlspecialchars($user['PASSWORD']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="role" class="form-label">บทบาท:</label>
                        <input type="text" name="role" class="form-input" value="<?php echo htmlspecialchars($user['role']); ?>" required>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="back-button" onclick="window.location.href='manage_users.php'">ย้อนกลับ</button>
                        <input type="submit" class="form-submit-btn" value="บันทึกการเปลี่ยนแปลง">
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
