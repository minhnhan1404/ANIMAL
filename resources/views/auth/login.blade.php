<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Thế Giới Động Vật</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logotgdvv.png') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hidden-form { display: none !important; }
        .error { color: #e74c3c; font-size: 0.8rem; margin-bottom: 10px; text-align: center; }
        .success { color: #27ae60; font-size: 0.8rem; margin-bottom: 10px; text-align: center; }
    </style>
</head>
<body>
    {{-- Container tự động trượt active nếu đang trong luồng xác thực đăng ký --}}
    <div class="container {{ session('show_verify_form') ? 'active' : '' }}" id="container">

        {{-- FORM BÊN TRÁI (ĐĂNG KÝ) --}}
        <div class="form-container sign-up">
            {{-- 1. Form Đăng ký gốc --}}
            <form method="POST" action="{{ route('register.post') }}" id="register-form" class="{{ session('show_verify_form') ? 'hidden-form' : '' }}">
                @csrf
                <h1>Tạo tài khoản</h1>
                <span>Tham gia cộng đồng yêu động vật</span>
                <input type="text" name="name" placeholder="Tên người dùng" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>
                <button type="submit">Đăng ký ngay</button>
            </form>

            {{-- 2. Form Xác thực Đăng ký (Hiện sau khi nhấn Đăng ký thành công) --}}
            <form method="POST" action="{{ route('verify.register') }}" id="verify-reg-form" class="{{ session('show_verify_form') ? '' : 'hidden-form' }}">
                @csrf
                <h1>Xác thực Email 🐾</h1>
                <span>Nhập mã 6 số gửi tới Gmail của bạn</span>
                <input type="hidden" name="email" value="{{ session('verify_email') }}">
                <input type="text" name="code" placeholder="Mã xác thực" required maxlength="6" style="text-align: center; letter-spacing: 5px;">
                <button type="submit">Kích hoạt tài khoản</button>
            </form>
        </div>

        {{-- FORM BÊN PHẢI (LOGIN / FORGOT / RESET) --}}
        <div class="form-container sign-in">
            {{-- 1. FORM ĐĂNG NHẬP (Tự ẩn nếu đang Quên mật khẩu hoặc Xác thực) --}}
            <form method="POST" action="{{ route('login.post') }}" id="login-form" class="{{ (session('show_reset') || session('show_verify_form')) ? 'hidden-form' : '' }}">
                @csrf
                <h1>Chào mừng trở lại!</h1>
                <div class="animal-icon"><i class="fas fa-paw"></i></div>
                <span>Đăng nhập để khám phá tự nhiên</span>

                @if (session('error')) <div class="error">{{ session('error') }}</div> @endif
                @if (session('success')) <div class="success">{{ session('success') }}</div> @endif

                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>

                <a href="javascript:void(0)" onclick="showForgot()">Quên mật khẩu?</a>
                <button type="submit">Đăng nhập</button>
            </form>

            {{-- 2. FORM NHẬP EMAIL ĐỂ LẤY MÃ (ẨN) --}}
            <form method="POST" action="{{ route('password.email') }}" id="forgot-form" class="hidden-form">
                @csrf
                <h1>Khôi phục mã 🐾</h1>
                <span>Nhập email để nhận mã 6 số</span>
                <input type="email" name="email" placeholder="Email của bạn" required>
                <button type="submit">Gửi mã xác nhận</button>
                <a href="javascript:void(0)" onclick="showLogin()" style="margin-top: 15px;">Quay lại Đăng nhập</a>
            </form>

            {{-- 3. FORM ĐẶT LẠI MẬT KHẨU MỚI (Hiện sau khi gửi mail thành công) --}}
            <form method="POST" action="{{ route('password.update') }}" id="reset-form" class="{{ session('show_reset') ? '' : 'hidden-form' }}">
                @csrf
                <h1>Mật khẩu mới 🐾</h1>
                <input type="hidden" name="email" value="{{ session('reset_email') }}">

                @if (session('error')) <div class="error">{{ session('error') }}</div> @endif

                <input type="text" name="code" placeholder="Nhập mã 6 số từ Gmail" required maxlength="6" style="text-align: center;">
                <input type="password" name="password" placeholder="Mật khẩu mới" required>
                <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" required>
                <button type="submit">Cập nhật mật khẩu</button>
                <a href="javascript:void(0)" onclick="showLogin()" style="margin-top: 10px;">Hủy bỏ</a>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h2>Chào bạn mới!</h2>
                    <p>Đăng ký để cùng chúng mình bảo tồn động vật hoang dã nhé!</p>
                    <button class="hidden" id="login">Đăng nhập</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h2>Xin chào!</h2>
                    <p>Bạn đã có tài khoản rồi? Hãy quay lại rừng xanh cùng chúng mình.</p>
                    <button class="hidden" id="register">Đăng ký</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/auth.js') }}"></script>
    <script>
        function showForgot() {
            document.getElementById('login-form').classList.add('hidden-form');
            document.getElementById('forgot-form').classList.remove('hidden-form');
            document.getElementById('reset-form').classList.add('hidden-form');
            // Xóa thông báo cũ khi chuyển form
            const msg = document.querySelectorAll('.success, .error');
            msg.forEach(m => m.style.display = 'none');
        }

        function showLogin() {
            document.getElementById('login-form').classList.remove('hidden-form');
            document.getElementById('forgot-form').classList.add('hidden-form');
            document.getElementById('reset-form').classList.add('hidden-form');
        }
    </script>
</body>
</html>
