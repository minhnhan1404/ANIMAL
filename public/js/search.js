document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('animal-search');
    const suggestionList = document.getElementById('suggestion-list');

    // Chỉ chạy logic khi tìm thấy cả ô nhập và nơi hiển thị gợi ý
    if (searchInput && suggestionList) {

        searchInput.addEventListener('input', function() {
            let query = this.value.trim();

            if (query.length < 1) {
                suggestionList.innerHTML = '';
                suggestionList.style.display = 'none';
                return;
            }

            // Gọi đến Route đã tạo trong web.php
            fetch(`/search-suggestions?term=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    suggestionList.innerHTML = ''; // Dọn dẹp danh sách cũ

                    if (data.length > 0) {
                        data.forEach(name => {
                            let div = document.createElement('div');
                            div.classList.add('suggestion-item');
                            // Thêm icon search cho đẹp
                            div.innerHTML = `<i class="fas fa-search" style="margin-right:10px; color:#999; font-size:12px;"></i> ${name}`;

                            // Xử lý khi click vào một gợi ý
                            div.onclick = function() {
                                searchInput.value = name;
                                suggestionList.innerHTML = '';
                                suggestionList.style.display = 'none';

                                // Chuyển hướng trang để lọc theo tên con vật đã chọn
                                window.location.href = `/?search=${encodeURIComponent(name)}`;
                            };
                            suggestionList.appendChild(div);
                        });
                        suggestionList.style.display = 'block';
                    } else {
                        suggestionList.style.display = 'none';
                    }
                })
                .catch(error => console.error('Lỗi lấy gợi ý từ database:', error));
        });

        // Đóng danh sách gợi ý khi click ra vùng ngoài
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionList.contains(e.target)) {
                suggestionList.style.display = 'none';
            }
        });
    } else {
        // Thông báo lỗi nhỏ trong Console để bạn biết mình quên đặt ID ở đâu
        console.warn("Lưu ý: Bạn cần kiểm tra ID 'animal-search' và 'suggestion-list' trong file Blade.");
    }
});
