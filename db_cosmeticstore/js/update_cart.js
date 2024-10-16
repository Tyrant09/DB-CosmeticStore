document.querySelectorAll('.quantity-form').forEach(form => {
    form.addEventListener('submit', function (event) {
        event.preventDefault(); // ป้องกันการโหลดหน้าใหม่

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // อัปเดตข้อมูลใน DOM ตามผลลัพธ์ที่ได้รับ
            document.querySelector('.cart-main').innerHTML = data; // อัปเดตตะกร้าสินค้า
        })
        .catch(error => console.error('Error:', error));
    });
});
