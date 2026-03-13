<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng - Animalia Admin</title>

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
            <i class="fas fa-hippo"></i> Quản lý loài vật
        </a>

        <a href="{{ route('admin.users.index') }}" class="menu-item active">
            <i class="fas fa-users"></i> Người dùng
        </a>

        <a href="{{ route('admin.post.index') }}" class="menu-item">
            <i class="fas fa-newspaper"></i> Bài đăng
        </a>

        <a href="{{ route('home') }}" class="menu-item" style="margin-top: auto; border-top: 1px solid #1a252f;">
            <i class="fas fa-home"></i> Xem trang chủ
        </a>
    </div>

    <div class="main-content">
        <h2>Quản lý người dùng</h2>

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

</body>
</html>
