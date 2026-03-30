document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');

    // 1. Logic trượt cơ bản
    if (registerBtn && loginBtn && container) {
        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
            // Khi trượt sang Đăng ký, reset lại các form ẩn bên phía Đăng nhập
            showLogin();
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });
    }

    // 2. Logic bổ sung: Nếu có lỗi Validation (như trùng mail),
    // JS phải tự động trượt sang bên đúng để người dùng thấy lỗi
    if (document.querySelector('.sign-up .error')) {
        container.classList.add("active");
    }
});

// 3. Hàm bổ trợ để điều khiển các Form ẩn (Dùng cho các nút Quay lại)
function showLogin() {
    const loginForm = document.getElementById('login-form');
    const forgotForm = document.getElementById('forgot-form');
    const resetForm = document.getElementById('reset-form');

    if(loginForm) loginForm.classList.remove('hidden-form');
    if(forgotForm) forgotForm.classList.add('hidden-form');
    if(resetForm) resetForm.classList.add('hidden-form');
}
