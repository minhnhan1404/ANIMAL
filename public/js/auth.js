// Chờ cho trang web tải xong hoàn toàn
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');

    if (registerBtn && loginBtn && container) {
        // Khi bấm nút Đăng ký (bên phải)
        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });

        // Khi bấm nút Đăng nhập (bên trái)
        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });
    } else {
        console.error("Không tìm thấy các thành phần ID: container, register hoặc login");
    }
});
