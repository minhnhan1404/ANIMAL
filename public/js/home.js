document.addEventListener('DOMContentLoaded', function() {
    // 1. Phải dùng đúng tên Class mới: .dropbtn-modern và .dropdown-content-horizontal
    const dropBtn = document.querySelector('.dropbtn-modern');
    const dropdownContent = document.querySelector('.dropdown-content-horizontal');

    if (dropBtn && dropdownContent) {
        // 2. Xử lý click mở menu
        dropBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownContent.classList.toggle('show');

            // Hiệu ứng xoay mũi tên chuẩn
            const arrow = this.querySelector('.fa-chevron-down');
            if (arrow) {
                arrow.style.transition = '0.3s';
                arrow.style.transform = dropdownContent.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        });

        // 3. Click ra ngoài để đóng menu
        document.addEventListener('click', function(e) {
            if (!dropBtn.contains(e.target) && !dropdownContent.contains(e.target)) {
                dropdownContent.classList.remove('show');
                const arrow = dropBtn.querySelector('.fa-chevron-down');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        });
    }
});
