document.addEventListener('DOMContentLoaded', function() {
    // ค้นหาปุ่มเพิ่ม/ลดจำนวนสินค้า
    const quantityButtons = document.querySelectorAll('.quantity-button');

    quantityButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const action = this.getAttribute('data-action');

            // ส่งคำขอไปยัง cart.php ด้วย AJAX
            fetch('cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `product_id=${productId}&action=${action}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // อัปเดตจำนวนสินค้าและราคารวม
                    this.parentElement.querySelector('.quantity').textContent = data.newQuantity;
                    document.querySelector('.total-price').textContent = 'ราคาสุทธิ: ฿' + data.totalPrice.toFixed(2);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
