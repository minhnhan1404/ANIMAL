document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let currentDeleteId = null;

    const showToast = (message) => {
    const toast = document.getElementById("auth-toast");
    if (toast) {
        toast.innerText = message; // Điền tin nhắn khiếm nhã vào đây
        toast.classList.add("show"); // Thêm class để hiện theo CSS trên

        // Sau 3 giây tự động ẩn đi
        setTimeout(() => {
            toast.classList.remove("show");
        }, 3000);
    }
};

    window.onclick = (e) => {
        const postModal = document.getElementById("postModal");
        const commentModal = document.getElementById("commentModal");
        const customConfirm = document.getElementById("customConfirm");

        if (e.target === postModal) {
            postModal.style.display = "none";
            resetPostModal();
        }
        if (e.target === commentModal) commentModal.style.display = "none";
        if (e.target === customConfirm) closeConfirm();

        if (!e.target.classList.contains('fa-ellipsis-h')) {
            document.querySelectorAll('.delete-menu').forEach(m => m.style.display = 'none');
        }
    };

    window.handleLike = async (postId) => {
        const heartOverlay = document.getElementById(`heart-${postId}`);
        const likesText = document.getElementById(`likes-count-${postId}`);
        const heartIcon = document.querySelector(`.post-card[data-id="${postId}"] .fa-heart`);

        try {
            const response = await fetch(`/post/${postId}/like`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                }
            });

            if (response.status === 401) {
                showToast("Bạn cần đăng nhập để like bài viết!");
                setTimeout(() => window.location.href = "/login", 2000);
                return;
            }

            const data = await response.json();
            if (data.success) {
                if (heartOverlay) {
                    heartOverlay.classList.add('animate');
                    setTimeout(() => heartOverlay.classList.remove('animate'), 800);
                }
                if (likesText) likesText.innerText = data.new_likes;
                if (heartIcon) {
                    const isLiked = data.action === 'liked';
                    heartIcon.classList.toggle('fas', isLiked);
                    heartIcon.classList.toggle('far', !isLiked);
                    heartIcon.style.color = isLiked ? '#ed4956' : '#262626';
                }
            }
        } catch (err) { console.error(err); }
    };

    window.openCommentModal = async (postId, imageUrl, userName, userAvatar) => {
        const modal = document.getElementById('commentModal');
        document.getElementById('modalPostImage').src = imageUrl;
        document.getElementById('modalUserName').innerText = userName;
        document.getElementById('modalUserAvatar').src = userAvatar;
        document.getElementById('modalPostId').value = postId;
        const list = document.getElementById('modalCommentList');
        list.innerHTML = 'Đang tải...';

        modal.style.display = 'flex';

        try {
            const response = await fetch(`/post/${postId}/comments`);
            const data = await response.json();
            const currentUserId = document.querySelector('meta[name="user-id"]')?.content;

            let html = '';
            data.comments.forEach(cmt => {
                const isOwner = currentUserId && String(cmt.user_id) === String(currentUserId);
                html += `
                    <div class="comment-item" id="comment-${cmt.id}">
                        <div class="comment-content-wrapper">
                            <img src="${cmt.user_avatar || '/images/default-avatar.png'}" class="avatar-small">
                            <p><strong>${cmt.user_name}</strong> ${cmt.content}</p>
                        </div>
                        ${isOwner ? `
                            <div class="comment-actions-container">
                                <i class="fas fa-ellipsis-h" onclick="event.stopPropagation(); toggleDeleteMenu(${cmt.id})"></i>
                                <div id="delete-menu-${cmt.id}" class="delete-menu">
                                    <button onclick="confirmDeleteComment(${cmt.id})">Xóa</button>
                                </div>
                            </div>
                        ` : ''}
                    </div>`;
            });
            list.innerHTML = html || 'Chưa có bình luận nào.';
        } catch (err) { console.error(err); }
    };

    window.toggleDeleteMenu = (cmtId) => {
        const menu = document.getElementById(`delete-menu-${cmtId}`);
        document.querySelectorAll('.delete-menu').forEach(m => {
            if (m.id !== `delete-menu-${cmtId}`) m.style.display = 'none';
        });
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    };

    window.confirmDeleteComment = (cmtId) => {
        currentDeleteId = cmtId;
        document.getElementById('customConfirm').style.display = 'flex';
    };

    window.closeConfirm = () => {
        document.getElementById('customConfirm').style.display = 'none';
    };

    window.executeDelete = async () => {
        if (!currentDeleteId) return;
        const cmtId = currentDeleteId;
        closeConfirm();

        try {
            const response = await fetch(`/comment/${cmtId}/delete`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            if (data.success) {
                const el = document.getElementById(`comment-${cmtId}`);
                if (el) el.remove();
                showToast("Đã xóa xong rồi nhé!");
            } else {
                alert("Lỗi: " + data.message);
            }
        } catch (err) { console.error("Lỗi:", err); }
    };

// --- HÀM SUBMIT COMMENT ĐÃ SỬA LẠI ĐÚNG GIAO DIỆN ---
    window.submitComment = async (e) => {
        e.preventDefault();
        const postId = document.getElementById('modalPostId').value;
        const inputField = document.getElementById('commentInput');
        const content = inputField.value;

        if (!content.trim()) return;

        try {
            const response = await fetch(`/post/${postId}/comment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    content: content,
                    post_id: postId
                })
            });

            if (!response.ok) {
            const data = await response.json();

            // Gọi hàm showToast để hiện tin nhắn "khiếm nhã" từ Controller trả về
            showToast(data.message || "Bình luận không hợp lệ!");

            inputField.value = ''; // Xóa nội dung xấu
            return; // Dừng lại không cho hiện lên danh sách
        }

            const data = await response.json();
            // 2. Nếu thành công (status success từ Controller)
            if (data.status === 'success' || data.success) {
                const newList = document.getElementById('modalCommentList');
                if (newList.innerText === "Chưa có bình luận nào.") newList.innerHTML = '';

                // TRẢ LẠI ĐÚNG STYLE CŨ ĐỂ KHÔNG BỊ MẤT CSS
                newList.innerHTML += `
                    <div class="comment-item" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;" id="comment-${data.comment_id}">
                        <div style="display: flex; gap: 10px;">
                            <img src="${data.user_avatar || '/images/default-avatar.png'}" class="avatar-small">
                            <p style="margin: 0;"><strong>${data.user_name}</strong> ${content}</p>
                        </div>
                        <div style="position: relative;">
                            <i class="fas fa-ellipsis-h" style="cursor: pointer; color: #8e8e8e; padding: 5px;" onclick="event.stopPropagation(); toggleDeleteMenu(${data.comment_id})"></i>
                            <div id="delete-menu-${data.comment_id}" class="delete-menu" style="display: none; position: absolute; right: 0; background: white; border: 1px solid #ddd; border-radius: 4px; z-index: 10; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <button onclick="confirmDeleteComment(${data.comment_id})" style="color: #ed4956; border: none; background: none; padding: 8px 15px; cursor: pointer; font-weight: bold; white-space: nowrap;">Xóa</button>
                            </div>
                        </div>
                    </div>`;

                inputField.value = '';
                newList.scrollTop = newList.scrollHeight;

                const latestDiv = document.getElementById(`latest-comment-${postId}`);
                if (latestDiv) {
                    latestDiv.innerHTML = `<p style="font-size: 14px; margin-top: 5px;"><strong>${data.user_name}</strong> ${content}</p>`;
                }
            }
        } catch (err) { console.error("Lỗi gửi bình luận:", err); }
    };

    document.querySelectorAll(".post-image-container").forEach(container => {
        container.addEventListener("dblclick", function(e) {
            e.preventDefault();
            const postId = this.closest('.post-card').dataset.id;
            window.handleLike(postId);
        });
    });
});

function previewImage(event) {
    const reader = new FileReader();
    const output = document.getElementById('imagePreview');
    const placeholder = document.getElementById('uploadPlaceholder');
    reader.onload = () => {
        if (output) {
            output.src = reader.result;
            output.style.display = "block";
        }
        if (placeholder) placeholder.style.display = "none";
    };
    if (event.target.files[0]) reader.readAsDataURL(event.target.files[0]);
}

window.resetPostModal = () => {
    const textarea = document.querySelector('#postModal textarea');
    if (textarea) textarea.value = '';
    const fileInput = document.getElementById('file-upload');
    if (fileInput) fileInput.value = '';
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('uploadPlaceholder');
    if (preview) {
        preview.src = '#';
        preview.style.display = 'none';
    }
    if (placeholder) placeholder.style.display = 'block';
};

