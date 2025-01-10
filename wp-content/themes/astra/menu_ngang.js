// Lắng nghe sự kiện cuộn trang
window.addEventListener('scroll', function() {
    const menu = document.querySelector('.custom-horizontal-menu');

    if (window.scrollY > 1) {
        menu.style.top = '25px'; // Hiển thị menu
    } 
    else if (window.scrollY <= 100) {
        menu.style.top = '170px'; // Đặt lại vị trí ban đầu
    }
});
window.addEventListener('scroll', function () {
    const menu = document.querySelector('.custom-horizontal-menu');
    if (window.scrollY > 0) {
        menu.classList.add('scrolled'); // Thêm class khi cuộn
    } else {
        menu.classList.remove('scrolled'); // Xóa class khi quay lại đầu trang
    }
});
