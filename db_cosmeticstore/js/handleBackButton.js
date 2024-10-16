function handleBackButton() {
    const referrer = document.referrer; // ตรวจสอบ URL ของหน้าก่อนหน้า
    
    // ตรวจสอบว่าหน้าก่อนหน้านี้คือ checkout.php หรือไม่
    if (referrer.includes('checkout.php')) {
        // ถ้าหน้าก่อนหน้าเป็น checkout.php, ส่งไปยังหน้าหมวดหมู่หรือหน้าที่ต้องการเอง
        window.location.href = "categories.php?category_id=<?php echo isset($categoryId) ? $categoryId : ''; ?>";
    } else {
        // ถ้าไม่ใช่ checkout.php, ใช้ฟังก์ชันย้อนกลับปกติ
        window.history.back();
    }
}