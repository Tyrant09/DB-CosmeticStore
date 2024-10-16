function goBack() {
    // ตรวจสอบประวัติการเข้าชมหน้าเว็บ
    if (document.referrer.indexOf('checkout.php') === -1) {
        window.history.back(); // ย้อนกลับไปยังหน้าก่อนหน้า
    } else {
        window.location.href = "categories.php?category_id=<?php echo isset($categoryId) ? $categoryId : ''; ?>"; // ถ้าหน้าก่อนหน้าเป็น checkout.php ให้ไปที่หน้า categories
    }
}