<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng - Animalia Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logotgdvv.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
</head>
<body>

    <div class="sidebar-admin">
        <a href="{{ route('admin.dashboard') }}" class="logo">
            <i class="fas fa-paw"></i> Animalia Admin
        </a>

        <a href="{{ route('admin.dashboard') }}" class="menu-item">
            <i class="fas fa-tachometer-alt"></i> Tổng quan
        </a>

        <a href="{{ route('admin.animals') }}" class="menu-item">
            <i class="fas fa-fish"></i> Quản lý loài vật
        </a>

        <a href="{{ route('admin.users.index') }}" class="menu-item active">
            <i class="fas fa-users"></i> Người dùng
        </a>

        <a href="{{ route('admin.post.index') }}" class="menu-item">
            <i class="fas fa-newspaper"></i> Bài đăng
        </a>

        <a href="{{ route('home') }}" class="menu-item">
            <i class="fas fa-home"></i> Xem trang chủ
        </a>
    </div>

    <div class="main-content">
        <div class="admin-header-flex">
            <h2 style="margin: 0;">Quản lý người dùng</h2>
            <button class="btn-create-account" onclick="toggleAddUserForm()">
                <i class="fas fa-plus"></i> Thêm tài khoản mới
            </button>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-error">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error-box">
                <ul class="error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Thêm Tài Khoản --}}
        <div id="addUserForm" class="post-card form-card" style="display: {{ $errors->any() ? 'block' : 'none' }};">
            <div class="form-header-row">
                <h3 class="form-title"><i class="fas fa-user-plus" style="color: #27ae60; margin-right: 8px;"></i>Tạo tài khoản hệ thống</h3>
                <button type="button" class="btn-close-form" onclick="toggleAddUserForm()">&times;</button>
            </div>
            
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Tên người dùng <span style="color: #e53e3e;">*</span></label>
                        <input type="text" name="name" required placeholder="Nhập họ và tên..." class="form-input">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Địa chỉ Email <span style="color: #e53e3e;">*</span></label>
                        <input type="email" name="email" required placeholder="ví dụ: admin@gmail.com" class="form-input">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Mật khẩu <span style="color: #e53e3e;">*</span></label>
                        <input type="password" name="password" required placeholder="Tối thiểu 6 ký tự..." class="form-input">
                    </div>
                    <div class="form-col small-col">
                        <label class="form-label">Vai trò hệ thống <span style="color: #e53e3e;">*</span></label>
                        <select name="role" required class="form-input">
                            <option value="user">Người dùng (Member)</option>
                            <option value="admin">Quản trị viên (Admin)</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-save-user">
                        <i class="fas fa-save"></i> Lưu thông tin
                    </button>
                </div>
            </form>
        </div>

        <div class="post-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <form action="{{ route('admin.users.update_role', $user->id) }}" method="POST">
                                @csrf
                                <select name="role" class="role-select" onchange="this.form.submit()">
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Người dùng</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.users.delete', $user->id) }}"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?')"
                               class="btn-delete">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleAddUserForm() {
            const form = document.getElementById('addUserForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
                // Cuộn mượt mà đến form
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</body>
</html>
