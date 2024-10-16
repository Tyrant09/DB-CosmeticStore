document.addEventListener('DOMContentLoaded', function() {
    // ตรวจสอบสถานะการล็อกอินที่ส่งมาจาก PHP
    console.log("isLoggedIn: ", isLoggedIn); // ตรวจสอบใน console

    // Function สำหรับตรวจสอบการล็อกอินแล้ว redirect ไปหน้า login ถ้ายังไม่ได้ล็อกอิน
    function checkLoginAndRedirect(event, href) {
        if (!isLoggedIn) {
            event.preventDefault(); // ป้องกันไม่ให้ลิงก์ทำงาน
            window.location.href = 'login.php'; // redirect ไปหน้า login
        }
    }

    // ดักจับการคลิกใน navbar ยกเว้น about.php และ contact.php
    document.querySelectorAll('.navbar a').forEach(function(link) {
        link.addEventListener('click', function(event) {
            var href = link.getAttribute('href');
            
            if (href !== 'index.php' && href !== 'about.php' && href !== 'contact.php' ) {
                checkLoginAndRedirect(event, href);
            }
        });
    });

    // ดักจับการคลิกที่ carousel
    document.querySelectorAll('.carousel-item').forEach(function(item) {
        item.addEventListener('click', function(event) {
            checkLoginAndRedirect(event);
        });
    });

    // ดักจับการคลิกที่ products
    document.querySelectorAll('.product a').forEach(function(link) {
        link.addEventListener('click', function(event) {
            checkLoginAndRedirect(event);
        });
    });
});
