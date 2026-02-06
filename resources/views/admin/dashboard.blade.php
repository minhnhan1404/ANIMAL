<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Animalia</title>
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="dashboard-container">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-paw"></i> <span>Animalia Admin</span>
        </div>
        <ul class="sidebar-menu">
            <li class="active"><a href="#"><i class="fas fa-tachometer-alt"></i> Tổng quan</a></li>
            <li>
    <a href="{{ route('admin.animals') }}">
        <i class="fas fa-fish"></i> Quản lý loài vật
    </a>
</li>
            <li><a href="#"><i class="fas fa-users"></i> Người dùng</a></li>
            <li>
    <a href="{{ route('admin.post.index') }}">
        <i class="fas fa-clipboard-list"></i> Bài đăng
    </a>
</li>
            <li><a href="{{ url('/') }}"><i class="fas fa-home"></i> Xem trang chủ</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="admin-header">
            <div class="search-bar">
                <input type="text" placeholder="Tìm kiếm hệ thống...">
            </div>

            <div class="user-tools">
                <div class="user-dropdown">
                    <button class="dropdown-toggle">
                        <i class="fas fa-user-shield"></i> Admin: <strong>{{ Auth::user()->name }}</strong>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('/profile/edit') }}"><i class="fas fa-user-cog"></i> Cài đặt</a></li>
                        <li class="divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" id="admin-logout">
                                @csrf
                                <a href="#" onclick="event.preventDefault(); document.getElementById('admin-logout').submit();" class="text-danger">
                                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <section class="content-body">
            <h1 class="page-title">Bảng điều khiển hệ thống</h1>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon bg-green"><i class="fas fa-hippo"></i></div>
                    <div class="stat-info">
                        <h3>{{ $totalAnimals }}</h3>
                        <p>Loài động vật</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-blue"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <h3>{{ $totalUsers }}</h3>
                        <p>Thành viên</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-orange"><i class="fas fa-heart"></i></div>
                    <div class="stat-info">
                        <h3>{{ $totalLikes }}</h3>
                        <p>Lượt yêu thích</p>
                    </div>
                </div>
            </div>

            <div class="data-section">
                <div class="section-header">
                    <h2>Quản lý loài vật</h2>
                    <button class="btn-add">+ Thêm loài mới</button>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên loài</th>
                            <th>Danh mục</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                  <tbody>
    @foreach($animals as $animal)
    <tr>
        <td>#{{ $animal->id }}</td>
        <td><strong>{{ $animal->name }}</strong></td>
        <td>{{ $animal->category ?? 'N/A' }}</td>
        <td><span class="badge warning">{{ $animal->status }}</span></td>
        <td>
            {{-- NÚT EDIT: Truyền ID về trang quản lý để hiện lại thông tin để sửa --}}
            <a href="{{ route('admin.animals.edit', $animal->id) }}" class="btn-edit" title="Chỉnh sửa bài đăng">
                <i class="fas fa-edit"></i>
            </a>

            {{-- NÚT XÓA --}}
            <a href="{{ route('admin.animals.delete', $animal->id) }}"
               class="btn-delete"
               title="Xóa"
               onclick="return confirm('Bạn có chắc chắn muốn xóa loài {{ $animal->name }} không?')">
                <i class="fas fa-trash"></i>
            </a>
        </td>
    </tr>
    @endforeach
</tbody>
                </table>
            </div>

            <div class="data-section" style="margin-top: 30px;">
                <div class="section-header">
                    <h2>Lịch sử nhận diện AI (YOLOv8)</h2>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Người dùng</th>
                            <th>Kết quả dự đoán</th>
                            <th>Độ tin cậy</th>
                            <th>Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($aiHistory as $ai)
                        <tr>
                            <td><strong>{{ $ai->user_name }}</strong></td>
                            <td><span class="text-success">{{ $ai->detected_animal }}</span></td>
                            <td>{{ number_format($ai->confidence * 100, 1) }}%</td>
                            <td>{{ $ai->created_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

</body>
</html>
