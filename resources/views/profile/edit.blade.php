<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa hồ sơ - {{ Auth::user()->name }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logotgdvv.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/social.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>
<body>

    <div class="sidebar">
        <a href="/" class="logo">Animal</a>
        <a href="/" class="menu-item">
            <i class="fas fa-home"></i> <span>Trang chủ</span>
        </a>
        <a href="{{ route('ai.nhandien') }}" class="menu-item btn-ai-hero">
            <i class="fas fa-compass"></i> <span>Khám phá</span>
        </a>
        <a href="{{ route('social.index') }}" class="menu-item active">
            <i class="fas fa-user-edit"></i> <span>Animal Social</span>
        </a>
    </div>

    <div class="main-content">
        <div class="instagram-container">
            <div class="post-card">
                <h3 class="profile-title">Chỉnh sửa hồ sơ</h3>
                <hr class="profile-divider">

                {{-- Hiển thị thông báo thành công --}}
                @if(session('success'))
                    <div class="alert-success-box">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                {{-- Hiển thị lỗi nếu có --}}
                @if ($errors->any())
                    <div class="alert-error-box">
                        <ul class="error-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Khu vực ảnh đại diện --}}
                    <div class="avatar-section">
                        <div class="avatar-wrapper">
                            <img id="avatarPreview"
                                 src="{{ Auth::user()->avatar ? asset(Auth::user()->avatar) : asset('images/default-avatar.png') }}"
                                 onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff';"
                                 class="avatar-img">

                            <label for="avatar-input" class="avatar-upload-btn">
                                <i class="fas fa-camera"></i>
                            </label>
                        </div>
                        <br>
                        <span class="avatar-hint">Nhấn vào biểu tượng camera để đổi ảnh</span>
                        <input type="file" name="avatar" id="avatar-input" hidden onchange="previewAvatar(event)" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label>Tên người dùng</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-input" required>
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <label>Mật khẩu mới (bỏ trống nếu không đổi)</label>
                        <input type="password" name="password" class="form-input" placeholder="Nhập mật khẩu mới">
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <label>Xác nhận mật khẩu mới</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Nhập lại mật khẩu mới">
                    </div>

                    <button type="submit" class="profile-submit-btn">
                        Lưu thay đổi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewAvatar(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('avatarPreview');
                output.src = reader.result;
            };
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</body>
</html>
