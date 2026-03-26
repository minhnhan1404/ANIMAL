<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý loài vật - Animalia</title>
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="dashboard-container">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-paw"></i> <span>Animalia Admin</span>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Tổng quan</a>
            </li>
            <li class="{{ request()->routeIs('admin.animals') ? 'active' : '' }}">
                <a href="{{ route('admin.animals') }}"><i class="fas fa-fish"></i> Quản lý loài vật</a>
            </li>
            <li><a href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i> Người dùng</a></li>
            <li><a href="{{ route('admin.post.index') }}"><i class="fas fa-clipboard-list"></i> Bài đăng</a></li>
            <li><a href="{{ url('/') }}"><i class="fas fa-home"></i> Xem trang chủ</a></li>
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
                    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Tên loài vật (VD: Hổ Bengal)</label>
                            <input type="text" name="name" class="form-control" placeholder="Tên gọi phổ biến..." required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Tên khoa học (VD: Panthera tigris)</label>
                            <input type="text" name="scientific_name" class="form-control" placeholder="Tên Latinh..." required>
                        </div>
                    </div>

                    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group" style="flex: 1;">
                            <label><i class="fas fa-th-large"></i> Danh mục</label>
                            <select name="category" class="form-control" required>
                                <option value="Thú">Thú</option>
                                <option value="Chim">Chim</option>
                                <option value="Đại dương">Đại dương</option>
                                <option value="Bò sát">Bò sát</option>
                                <option value="Côn trùng">Côn trùng</option>
                                <option value="Linh trưởng">Linh trưởng</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label><i class="fas fa-shield-alt"></i> Tình trạng bảo tồn</label>
                            <select name="status" class="form-control" style="font-weight: bold;">
                                <option value="Ít lo ngại" selected style="color: #28a745;">🌿 Ít lo ngại</option>
                                <option value="Sắp nguy cấp" style="color: #ffc107;">⚠️ Sắp nguy cấp</option>
                                <option value="Nguy cấp" style="color: #fd7e14;">📕 Nguy cấp</option>
                                <option value="Cực kỳ nguy cấp" style="color: #dc3545;">💀 Cực kỳ nguy cấp</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 20px;">
                        <div class="form-group" style="flex: 1; min-width: 200px;">
                            <label>Lớp (VD: Mammalia)</label>
                            <input type="text" name="animal_class" class="form-control" placeholder="Lớp động vật...">
                        </div>

                        <div class="form-group" style="flex: 1; min-width: 250px;">
                            <label style="color: #27ae60; font-weight: bold;"><i class="fas fa-sitemap"></i> Bộ (Order) - Cần khớp để lọc</label>
                            <select name="animal_order" class="form-control" required style="border: 2px solid #27ae60;">
                                <option value="">-- Chọn Bộ để lọc --</option>
                                <optgroup label="Nhóm Thú">
                                    <option value="Ăn thịt">Bộ Ăn thịt (Hổ, Sư tử)</option>
                                    <option value="Vòi">Bộ Vòi (Voi)</option>
                                    <option value="Linh trưởng">Bộ Linh trưởng (Khỉ, Vượn)</option>
                                    <option value="Móng guốc">Bộ Móng guốc (Hươu, Nai)</option>
                                </optgroup>
                                <optgroup label="Nhóm Bò sát">
                                    <option value="Cá sấu">Bộ Cá sấu</option>
                                    <option value="Rùa">Bộ Rùa</option>
                                    <option value="Có vảy">Bộ Có vảy (Rắn, Kỳ đà)</option>
                                </optgroup>
                                <optgroup label="Nhóm Chim">
                                    <option value="Chim ưng">Bộ Chim ưng</option>
                                    <option value="Vẹt">Bộ Vẹt</option>
                                </optgroup>
                                <optgroup label="Khác">
                                    <option value="Cá mập">Họ Cá mập</option>
                                    <option value="Cá voi">Bộ Cá voi</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="form-group" style="flex: 1; min-width: 200px;">
                            <label><i class="fas fa-utensils"></i> Chế độ ăn</label>
                            <select name="diet_type" class="form-control">
                                <option value="Ăn cỏ">Ăn cỏ</option>
                                <option value="Ăn thịt">Ăn thịt</option>
                                <option value="Ăn tạp">Ăn tạp</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-paw"></i> Tập tính & Đặc điểm</label>
                        <textarea name="behavior" class="form-control" rows="3" placeholder="Nhập tập tính săn mồi, sinh sản..."></textarea>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-info-circle"></i> Mô tả chi tiết</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Nhập mô tả chi tiết bách khoa toàn thư..."></textarea>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Chọn ảnh loài vật</label>
                        <input type="file" name="image" class="form-control" required style="border: none; padding-left: 0;">
                        <small style="color: #e67e22;"><i class="fas fa-info-circle"></i> Mẹo: Chọn ảnh chất lượng cao để trang chủ đẹp hơn.</small>
                    </div>

                    <button type="submit" class="btn-submit" style="background: #27ae60; color: white; padding: 15px; border: none; border-radius: 8px; width: 100%; font-weight: bold; font-size: 1.1rem; cursor: pointer; margin-top: 20px;">
                        <i class="fas fa-cloud-upload-alt"></i> ĐĂNG BÀI LÊN TRANG CHỦ
                    </button>
                </form>
            </div>
        </section>
    </main>
</div>
</body>
</html>
