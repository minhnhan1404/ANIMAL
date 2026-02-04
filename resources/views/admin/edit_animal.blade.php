<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa loài vật - Animalia</title>
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-form.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="dashboard-container">
    {{-- Sidebar giống Dashboard --}}
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-paw"></i> <span>Animalia Admin</span>
        </div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Tổng quan</a></li>
            <li class="active"><a href="{{ route('admin.animals') }}"><i class="fas fa-fish"></i> Quản lý loài vật</a></li>
            <li><a href="{{ url('/') }}"><i class="fas fa-home"></i> Xem trang chủ</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="admin-header">
            <div class="user-tools">
                <i class="fas fa-user-shield"></i> Admin: <strong>{{ Auth::user()->name }}</strong>
            </div>
        </header>

        <section class="content-body" style="padding: 30px;">
            <div class="admin-form-container" style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                <h1 class="page-title" style="margin-bottom: 25px; color: #2c3e50;">
                    <i class="fas fa-edit"></i> Chỉnh sửa: {{ $animal->name }}
                </h1>

                <form action="{{ route('admin.animals.update', $animal->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Tên loài vật (Khớp với Label AI)</label>
                        <input type="text" name="name" class="form-control" value="{{ $animal->name }}" required>
                    </div>

                    <div class="form-group">
                        <label>Tên khoa học</label>
                        <input type="text" name="scientific_name" class="form-control" value="{{ $animal->scientific_name }}" required>
                    </div>

                    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Danh mục</label>
                            <select name="category" class="form-control">
                                <option value="Thú" {{ $animal->category == 'Thú' ? 'selected' : '' }}>Thú</option>
                                <option value="Chim" {{ $animal->category == 'Chim' ? 'selected' : '' }}>Chim</option>
                                <option value="Đại dương" {{ $animal->category == 'Đại dương' ? 'selected' : '' }}>Đại dương</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Tình trạng bảo tồn</label>
                            <select name="status" class="form-control">
                                <option value="Ít lo ngại" {{ $animal->status == 'Ít lo ngại' ? 'selected' : '' }}>Ít lo ngại</option>
                                <option value="Nguy cấp" {{ $animal->status == 'Nguy cấp' ? 'selected' : '' }}>Nguy cấp</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tập tính & Đặc điểm</label>
                        <textarea name="behavior" class="form-control" rows="4">{{ $animal->behavior }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Mô tả chi tiết</label>
                        <textarea name="description" class="form-control" rows="5">{{ $animal->description }}</textarea>
                    </div>

                    {{-- PHÂN LOẠI CHI TIẾT --}}
                    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
                        <div class="form-group" style="flex: 1; min-width: 200px;">
                            <label>Lớp (Class)</label>
                            <input type="text" name="animal_class" class="form-control" value="{{ $animal->animal_class }}">
                        </div>
                        <div class="form-group" style="flex: 1; min-width: 200px;">
                            <label>Bộ (Order)</label>
                            <input type="text" name="animal_order" class="form-control" value="{{ $animal->animal_order }}">
                        </div>
                        <div class="form-group" style="flex: 1; min-width: 200px;">
                            <label>Chế độ ăn</label>
                            <select name="diet_type" class="form-control">
                                <option value="Ăn cỏ" {{ $animal->diet_type == 'Ăn cỏ' ? 'selected' : '' }}>Ăn cỏ</option>
                                <option value="Ăn thịt" {{ $animal->diet_type == 'Ăn thịt' ? 'selected' : '' }}>Ăn thịt</option>
                                <option value="Ăn tạp" {{ $animal->diet_type == 'Ăn tạp' ? 'selected' : '' }}>Ăn tạp</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label>Thay đổi ảnh (Bỏ trống nếu giữ ảnh cũ)</label>
                        <input type="file" name="image" class="form-control" style="border: none;">
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 30px;">
                        <button type="submit" class="btn-submit" style="background: #27ae60; color: white; padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer; flex: 1; font-weight: bold;">
                            <i class="fas fa-save"></i> Cập nhật thay đổi
                        </button>
                        <a href="{{ route('admin.dashboard') }}" style="background: #95a5a6; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; text-align: center; flex: 1; font-weight: bold;">
                            Hủy bỏ
                        </a>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>

</body>
</html>
@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-form.css') }}">

<div class="admin-form-container" style="padding: 20px; background: #fff; border-radius: 10px;">
    <h1 class="page-title" style="margin-bottom: 20px;">
        <i class="fas fa-edit"></i> Chỉnh sửa thông tin: {{ $animal->name }}
    </h1>

    <form action="{{ route('admin.animals.update', $animal->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Tên loài vật (Khớp với Label AI)</label>
            <input type="text" name="name" class="form-control" value="{{ $animal->name }}" required>
        </div>

        <div class="form-group">
            <label>Tên khoa học (Tên hiển thị phụ)</label>
            <input type="text" name="scientific_name" class="form-control" value="{{ $animal->scientific_name }}" required>
        </div>

        <div style="display: flex; gap: 20px;">
            <div class="form-group" style="flex: 1;">
                <label>Danh mục</label>
                <select name="category" class="form-control">
                    <option value="Thú" {{ $animal->category == 'Thú' ? 'selected' : '' }}>Thú</option>
                    <option value="Chim" {{ $animal->category == 'Chim' ? 'selected' : '' }}>Chim</option>
                    <option value="Đại dương" {{ $animal->category == 'Đại dương' ? 'selected' : '' }}>Đại dương</option>
                </select>
            </div>
            <div class="form-group" style="flex: 1;">
                <label>Tình trạng bảo tồn</label>
                <select name="status" class="form-control">
                    <option value="Ít lo ngại" {{ $animal->status == 'Ít lo ngại' ? 'selected' : '' }}>Ít lo ngại</option>
                    <option value="Sắp nguy cấp" {{ $animal->status == 'Sắp nguy cấp' ? 'selected' : '' }}>Sắp nguy cấp</option>
                    <option value="Nguy cấp" {{ $animal->status == 'Nguy cấp' ? 'selected' : '' }}>Nguy cấp</option>
                    <option value="Cực kỳ nguy cấp" {{ $animal->status == 'Cực kỳ nguy cấp' ? 'selected' : '' }}>Cực kỳ nguy cấp</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label><i class="fas fa-paw"></i> Tập tính & Đặc điểm</label>
            <textarea name="behavior" class="form-control" rows="4">{{ $animal->behavior }}</textarea>
        </div>

        <div class="form-group">
            <label><i class="fas fa-info-circle"></i> Mô tả chi tiết</label>
            <textarea name="description" class="form-control" rows="6">{{ $animal->description }}</textarea>
        </div>

        {{-- HÀNG DỮ LIỆU PHÂN LOẠI (Giống hình 2 của Nhan) --}}
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 200px;">
                <label>Lớp (Ví dụ: Mammalia)</label>
                <input type="text" name="animal_class" class="form-control" value="{{ $animal->animal_class }}">
            </div>
            <div class="form-group" style="flex: 1; min-width: 200px;">
                <label>Bộ (Ví dụ: Carnivora)</label>
                <input type="text" name="animal_order" class="form-control" value="{{ $animal->animal_order }}">
            </div>
            <div class="form-group" style="flex: 1; min-width: 200px;">
                <label>Chế độ ăn</label>
                <select name="diet_type" class="form-control">
                    <option value="Ăn cỏ" {{ $animal->diet_type == 'Ăn cỏ' ? 'selected' : '' }}>Ăn cỏ</option>
                    <option value="Ăn thịt" {{ $animal->diet_type == 'Ăn thịt' ? 'selected' : '' }}>Ăn thịt</option>
                    <option value="Ăn tạp" {{ $animal->diet_type == 'Ăn tạp' ? 'selected' : '' }}>Ăn tạp</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Chọn ảnh mới (Bỏ trống nếu giữ ảnh cũ)</label>
            <input type="file" name="image" class="form-control" style="border: none; padding-left: 0;">
            @if($animal->image_url)
                <div style="margin-top: 10px;">
                    <p>Ảnh hiện tại:</p>
                    <img src="{{ asset($animal->image_url) }}" width="150" style="border-radius: 8px;">
                </div>
            @endif
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-submit" style="background: #27ae60; color: white; padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer; flex: 1;">
                <i class="fas fa-save"></i> Cập nhật bài đăng
            </button>
            <a href="{{ route('admin.dashboard') }}" style="background: #95a5a6; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; text-align: center;">
                Hủy bỏ
            </a>
        </div>
    </form>
</div>
@endsection
