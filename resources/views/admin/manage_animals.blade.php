<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý loài vật - Animalia</title>

    {{-- Nhúng file CSS gốc và file CSS Form mới --}}
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-form.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="dashboard-container">
    <aside class="sidebar">
        <div class="sidebar-brand">
        <i class="fas fa-paw"></i> <span>Animalia Admin</span>
    </div>
    <ul class="sidebar-menu">
        {{-- Nút quay về Dashboard tổng quan --}}
        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Tổng quan
            </a>
        </li>

        {{-- Nút trang Quản lý (Đăng bài) hiện tại --}}
        <li class="{{ request()->routeIs('admin.animals') ? 'active' : '' }}">
            <a href="{{ route('admin.animals') }}">
                <i class="fas fa-fish"></i> Quản lý loài vật
            </a>
        </li>

        <li><a href="#"><i class="fas fa-users"></i> Người dùng</a></li>
        <li><a href="#"><i class="fas fa-clipboard-list"></i> Bài đăng</a></li>

        {{-- Nút quay lại trang chủ người dùng --}}
        <li>
            <a href="{{ url('/') }}">
                <i class="fas fa-home"></i> Xem trang chủ
            </a>
        </li>
    </ul>
        </aside>

    <main class="main-content">
        <section class="content-body" style="padding: 30px;">
            <h1 class="page-title"><i class="fas fa-plus-circle"></i> Đăng bài lên trang chủ</h1>

            @if(session('success'))
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="admin-form">
                <form action="{{ route('admin.animals.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Tên loài vật (Khớp với Label AI - Ví dụ: Eagle)</label>
                        <input type="text" name="name" class="form-control" placeholder="Ví dụ: Eagle" required>
                    </div>

                    <div class="form-group">
                        <label>Tên khoa học (Tên hiển thị phụ)</label>
                        <input type="text" name="scientific_name" class="form-control" placeholder="Ví dụ: Haliaeetus leucocephalus" required>
                    </div>

                    <div style="display: flex; gap: 20px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Danh mục</label>
                            <select name="category" class="form-control">
                                <option value="Thú">Thú</option>
                                <option value="Chim">Chim</option>
                                <option value="Đại dương">Đại dương</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
    <label>Tình trạng bảo tồn</label>
    <select name="status" class="form-control">
        <option value="Ít lo ngại" selected>Ít lo ngại</option>
        <option value="Sắp nguy cấp">Sắp nguy cấp</option>
        <option value="Nguy cấp">Nguy cấp</option>
        <option value="Cực kỳ nguy cấp">Cực kỳ nguy cấp</option>
    </select>
</div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-paw"></i> Tập tính & Đặc điểm</label>
                        <textarea name="behavior" class="form-control" rows="4" placeholder="Nhập tập tính săn mồi, sinh sản..."></textarea>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-info-circle"></i> Mô tả chi tiết</label>
                        <textarea name="description" class="form-control" rows="6" placeholder="Nhập mô tả chi tiết bách khoa toàn thư..."></textarea>
                    </div>
                            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
    <div class="form-group" style="flex: 1; min-width: 200px;">
        <label>Lớp (Ví dụ: Mammalia)</label>
        <input type="text" name="animal_class" class="form-control">
    </div>
    <div class="form-group" style="flex: 1; min-width: 200px;">
        <label>Bộ (Ví dụ: Carnivora)</label>
        <input type="text" name="animal_order" class="form-control">
    </div>
    <div class="form-group" style="flex: 1; min-width: 200px;">
        <label>Chế độ ăn</label>
        <select name="diet_type" class="form-control">
            <option value="Ăn cỏ">Ăn cỏ</option>
            <option value="Ăn thịt">Ăn thịt</option>
            <option value="Ăn tạp">Ăn tạp</option>
        </select>
    </div>
</div>

                    <div class="form-group">
                        <label>Chọn ảnh sạch (Từ folder Dataset Kaggle)</label>
                        <input type="file" name="image" class="form-control" required style="border: none; padding-left: 0;">
                        <small style="color: #e67e22;"><i class="fas fa-info-circle"></i> Mẹo: Chọn ảnh gốc không có khung nhận diện AI để trang chủ đẹp hơn.</small>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-cloud-upload-alt"></i> Đăng lên trang chủ
                    </button>
                </form>
            </div>
        </section>
    </main>
</div>
</body>
</html>
