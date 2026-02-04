<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Thế Giới Động Vật</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container" id="container">
        {{-- FORM ĐĂNG KÝ --}}
        <div class="form-container sign-up">
            <form method="POST" action="{{ route('register.post') }}">
                @csrf {{-- Bắt buộc phải có thẻ này để Laravel chấp nhận form --}}
                <h1>Tạo tài khoản</h1>
                <span>Tham gia cộng đồng yêu động vật</span>
                <input type="text" name="name" placeholder="Tên người dùng" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>
                <button type="submit">Đăng ký ngay</button>
            </form>
        </div>

        {{-- FORM ĐĂNG NHẬP --}}
        <div class="form-container sign-in">
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <h1>Chào mừng trở lại!</h1>
                <div class="animal-icon">
                    <i class="fas fa-paw"></i>
                </div>
                <span>Đăng nhập để khám phá tự nhiên</span>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>

                @if (session('error'))
                    <div class="error">{{ session('error') }}</div>
                @endif

                <a href="#">Quên mật khẩu?</a>
                <button type="submit">Đăng nhập</button>
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
</body>
</html>
