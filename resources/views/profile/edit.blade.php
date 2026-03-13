<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa hồ sơ - {{ Auth::user()->name }}</title>
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
                <h3 style="margin-top: 0;">Chỉnh sửa hồ sơ</h3>
                <hr style="border: 0; border-top: 1px solid #dbdbdb; margin: 20px 0;">

                @if(session('success'))
                    <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div style="text-align: center; margin-bottom: 25px;">
                        <img id="avatarPreview" src="{{ asset(Auth::user()->avatar ?? 'images/default-avatar.png') }}"
                             style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 1px solid #dbdbdb;">
                        <br>
                        <label for="avatar-input" style="color: #0095f6; cursor: pointer; font-weight: 600; margin-top: 10px; display: inline-block;">
                            Thay đổi ảnh đại diện
                        </label>
                        <input type="file" name="avatar" id="avatar-input" hidden onchange="previewAvatar(event)">
                    </div>

                    <div class="form-group">
                        <label>Tên người dùng</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-input">
                    </div>

                    <div class="form-group">
                        <label>Mật khẩu mới</label>
                        <input type="password" name="password" placeholder="Để trống nếu không muốn đổi" class="form-input">
                    </div>

                    <div class="form-group">
                        <label>Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" class="form-input">
                    </div>

                    <button type="submit" class="submit-btn">Gửi</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewAvatar(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('avatarPreview').src = reader.result;
            };
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</body>
</html>
