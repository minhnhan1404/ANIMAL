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
                <h3 style="margin-top: 0;">Chỉnh sửa hồ sơ</h3>
                <hr style="border: 0; border-top: 1px solid #dbdbdb; margin: 20px 0;">

                {{-- Hiển thị thông báo thành công --}}
                @if(session('success'))
                    <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                {{-- Hiển thị lỗi nếu có --}}
                @if ($errors->any())
                    <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Khu vực ảnh đại diện --}}
                    <div style="text-align: center; margin-bottom: 25px;">
                        <div style="position: relative; display: inline-block;">
                            <img id="avatarPreview"
                                 src="{{ Auth::user()->avatar ? asset(Auth::user()->avatar) : asset('images/default-avatar.png') }}"
                                 onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff';"
                                 style="width: 130px; height: 130px; border-radius: 50%; object-fit: cover; border: 3px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">

                            <label for="avatar-input" style="position: absolute; bottom: 5px; right: 5px; background: #0095f6; color: #fff; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid #fff;">
                                <i class="fas fa-camera"></i>
                            </label>
                        </div>
                        <br>
                        <span style="color: #8e8e8e; font-size: 0.9rem; margin-top: 10px; display: block;">Nhấn vào biểu tượng camera để đổi ảnh</span>
                        <input type="file" name="avatar" id="avatar-input" hidden onchange="previewAvatar(event)" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 600; margin-bottom: 5px; display: block;">Tên người dùng</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-input" required>
                    </div>

                    <button type="submit" class="submit-btn" style="width: 100%; margin-top: 25px; background: #0095f6; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 600; cursor: pointer;">
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
