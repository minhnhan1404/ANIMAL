// social.js - Bản chuẩn hóa cho Nhan: Chống nhảy số ảo

// 1. Xử lý đóng mở Modal (Giữ nguyên của Nhan)
const modal = document.getElementById("postModal");
if (modal) {
    window.onclick = (e) => {
        if (e.target == modal) modal.style.display = "none";
    }
}

// 2. Hàm xử lý Like gửi lên Database
function handleLike(postId) {
    const heartOverlay = document.getElementById(`heart-${postId}`);
    const likesText = document.getElementById(`likes-count-${postId}`);
    const heartIcon = document.querySelector(`.post-card[data-id="${postId}"] .fa-heart`);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Hiệu ứng tim trắng bay giữa ảnh
    if (heartOverlay) {
        heartOverlay.classList.add('animate');
        setTimeout(() => heartOverlay.classList.remove('animate'), 800);
    }

    fetch(`/post/${postId}/like`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            "Accept": "application/json"
        }
    })
    .then(res => {
        if (!res.ok) return res.text().then(text => { throw new Error(text) });
        return res.json();
    })
    .then(data => {
        if(data.success) {
            // Cập nhật số tim thực tế từ Database trả về
            // Không dùng phép cộng/trừ ảo ở đây để tránh nhảy số sai
            if (likesText) {
                likesText.innerText = data.new_likes;
            }

            if (heartIcon) {
                if (data.action === 'liked') {
                    heartIcon.classList.replace('far', 'fas');
                    heartIcon.classList.add('active');
                    heartIcon.style.color = '#ed4956'; // Ép màu đỏ cho xịn
                } else {
                    heartIcon.classList.replace('fas', 'far');
                    heartIcon.classList.remove('active');
                    heartIcon.style.color = '#262626'; // Trả về màu đen
                }
            }
        }
    })
    .catch(err => console.error("Lỗi gửi Like cụ thể:", err));
}

// 3. Gán sự kiện Nhấn đúp vào ảnh (Dblclick)
document.querySelectorAll(".post-image-container").forEach(container => {
    container.addEventListener("dblclick", function(e) {
        e.preventDefault(); // Ngăn chặn các sự kiện mặc định
        const postId = this.closest('.post-card').dataset.id;
        handleLike(postId);
    });
});

// 4. Gán sự kiện Click vào icon tim nhỏ (Chỉ dùng click, không dùng dblclick ở đây)
document.querySelectorAll(".action-btn-ins .fa-heart").forEach(heart => {
    heart.addEventListener("click", function(e) {
        e.preventDefault();
        const postId = this.closest('.post-card').dataset.id;
        handleLike(postId);
    });
});

// 5. Preview ảnh (Giữ nguyên của Nhan)
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('imagePreview');
        const placeholder = document.getElementById('uploadPlaceholder');
        if (output) {
            output.src = reader.result;
            output.style.display = "block";
        }
        if (placeholder) placeholder.style.display = "none";
    };
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
