// social.js - Phiên bản Instagram cho Nhan

// 1. Xử lý đóng mở Modal (Tạo bài viết)
const modal = document.getElementById("postModal");
const openBtn = document.getElementById("openModal");
const closeBtn = document.getElementById("closeModal");

if (openBtn) {
    openBtn.onclick = () => modal.style.display = "block";
}

if (closeBtn) {
    closeBtn.onclick = () => modal.style.display = "none";
}

window.onclick = (e) => {
    if (e.target == modal) modal.style.display = "none";
}

// 2. Xử lý tương tác Like kiểu Instagram
const likeButtons = document.querySelectorAll(".action-btn-ins .fa-heart");
const imageContainers = document.querySelectorAll(".post-image-container");

// Hàm thực hiện hiệu ứng Like
function doLike(heartIcon) {
    heartIcon.classList.remove('far');
    heartIcon.classList.add('fas', 'active');
    // Có thể thêm code AJAX gửi lên Database ở đây sau
}

// Hàm thực hiện hiệu ứng Unlike
function doUnlike(heartIcon) {
    heartIcon.classList.remove('fas', 'active');
    heartIcon.classList.add('far');
}

// Click vào icon Tim bên dưới ảnh
likeButtons.forEach(heart => {
    heart.addEventListener("click", function() {
        if (this.classList.contains('active')) {
            doUnlike(this);
        } else {
            doLike(this);
        }
    });
});

// Nhấn đúp (Double Click) vào ảnh để thả tim
imageContainers.forEach(container => {
    container.addEventListener("dblclick", function() {
        const heartIcon = this.closest('.post-card').querySelector('.fa-heart');
        const bigHeart = this.querySelector('.big-heart-overlay');

        // Thả tim cho nút bên dưới
        doLike(heartIcon);

        // Hiện hiệu ứng trái tim trắng giữa ảnh
        if (bigHeart) {
            bigHeart.classList.add('animate');
            setTimeout(() => {
                bigHeart.classList.remove('animate');
            }, 800);
        }
    });

    document.querySelectorAll('.action-icon.fa-heart').forEach(btn => {
    btn.onclick = function() {
        const postId = this.closest('.ins-post').dataset.id; // Lấy ID bài viết
        const heartIcon = this;

        fetch("{{ route('social.like') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ post_id: postId })
        })
        .then(res => res.json())
        .then(data => {
            if(data.action === 'liked') {
                heartIcon.classList.replace('far', 'fas');
                heartIcon.style.color = '#ed4956'; // Màu đỏ Insta
            } else {
                heartIcon.classList.replace('fas', 'far');
                heartIcon.style.color = 'black';
            }
        });
    }
});
});

function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('uploadPlaceholder');

    // Kiểm tra xem có file được chọn không
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            // Gán dữ liệu ảnh vào thẻ img
            preview.src = e.target.result;
            // Hiển thị thẻ img
            preview.style.display = 'block';

            // Ẩn cái icon và chữ "Chọn từ máy tính" đi cho đẹp
            if (placeholder) {
                placeholder.style.display = 'none';
            }
        }

        // Đọc file dưới dạng URL
        reader.readAsDataURL(input.files[0]);
    }
}

// Gán sự kiện cho input file (ID phải khớp với id="fileInput" trong HTML)
const fileInput = document.getElementById('fileInput');
if (fileInput) {
    fileInput.addEventListener('change', previewImage);
}
