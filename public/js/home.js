document.addEventListener('DOMContentLoaded', () => {
    const dropBtn = document.querySelector('.dropbtn-modern');
    const dropdownContent = document.querySelector('.dropdown-content-horizontal');

    // 1. QUẢN LÝ DROPDOWN BỘ LỌC
    if (dropBtn && dropdownContent) {
        dropBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isShow = dropdownContent.classList.toggle('show');
            const arrow = dropBtn.querySelector('.fa-chevron-down');

            if (arrow) {
                arrow.style.transition = '0.3s';
                arrow.style.transform = isShow ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        });

        document.addEventListener('click', (e) => {
            if (!dropBtn.contains(e.target) && !dropdownContent.contains(e.target)) {
                dropdownContent.classList.remove('show');
                const arrow = dropBtn.querySelector('.fa-chevron-down');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        });
    }

    // 2. XỬ LÝ THẢ TIM BẰNG AJAX
    $(document).on('click', '.fav-btn', function(e) {
        e.preventDefault();

        let btn = $(this);
        let animalId = btn.data('id');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        if (!csrfToken) {
            console.error('Lỗi: Không tìm thấy CSRF token meta tag.');
            return;
        }

        $.ajax({
            url: '/animal/like/' + animalId,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(res) {
                // res.status nên trả về 'liked' hoặc 'unliked' từ Controller
                if (res.status === 'liked') {
                    btn.addClass('active');
                    btn.find('i').removeClass('far').addClass('fas');
                } else {
                    btn.removeClass('active');
                    btn.find('i').removeClass('fas').addClass('far');
                }

                // Gợi ý: Nếu ông có thẻ hiện số like (vd: .like-count), hãy cập nhật nó ở đây
                // if(res.new_count !== undefined) {
                //    btn.closest('.animal-card').find('.like-count').text(res.new_count);
                // }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    alert('Nhan ơi, ông phải đăng nhập mới thả tim được nha! 🐾');
                    window.location.href = '/login';
                } else {
                    console.error('Lỗi AJAX:', xhr.responseText);
                }
            }
        });
    });

    // 3. XỬ LÝ QUAY LẠI TRANG
    window.addEventListener('pageshow', (event) => {
        const isBackNavigation = event.persisted ||
            (typeof window.performance !== "undefined" && window.performance.navigation.type === 2);

        if (isBackNavigation) {
            window.location.reload();
        }
    });
});
