<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa loài vật - Animalia</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logotgdvv.png') }}">
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
        <header class="admin-header">
            <div class="search-bar" style="position: relative;">
                <input type="text" id="adminSearchInput" placeholder="Tìm kiếm trang hệ thống..." style="padding-right: 40px; width: 250px;" onkeyup="filterAdminSearch()">
                <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #7f8c8d; cursor: default;"></i>
                <div id="adminSearchSuggestions" class="search-suggestions">
                    <a href="{{ route('admin.dashboard') }}" class="search-suggestion-item"><i class="fas fa-tachometer-alt"></i> Tổng quan (Dashboard)</a>
                    <a href="{{ route('admin.animals') }}" class="search-suggestion-item"><i class="fas fa-hippo"></i> Quản lý loài vật</a>
                    <a href="{{ route('admin.users.index') }}" class="search-suggestion-item"><i class="fas fa-users"></i> Quản lý người dùng</a>
                    <a href="{{ route('admin.post.index') }}" class="search-suggestion-item"><i class="fas fa-newspaper"></i> Quản lý bài đăng</a>
                    <a href="{{ url('/profile/edit') }}" class="search-suggestion-item"><i class="fas fa-user-cog"></i> Cài đặt tài khoản</a>
                </div>
            </div>
            <script>
            function filterAdminSearch() {
                let input = document.getElementById('adminSearchInput').value.toLowerCase();
                let suggestionsBox = document.getElementById('adminSearchSuggestions');
                let items = suggestionsBox.getElementsByTagName('a');
                if (input.length > 0) {
                    suggestionsBox.style.display = 'block';
                    let hasMatch = false;
                    for (let i = 0; i < items.length; i++) {
                        if ((items[i].textContent || items[i].innerText).toLowerCase().indexOf(input) > -1) {
                            items[i].style.display = 'flex';
                            hasMatch = true;
                        } else {
                            items[i].style.display = 'none';
                        }
                    }
                    if(!hasMatch) suggestionsBox.style.display = 'none';
                } else {
                    suggestionsBox.style.display = 'none';
                }
            }
            document.addEventListener('click', function(e){
                let input = document.getElementById('adminSearchInput');
                let box = document.getElementById('adminSearchSuggestions');
                if(input && box && !input.contains(e.target) && !box.contains(e.target)) box.style.display = 'none';
            });
            document.getElementById('adminSearchInput')?.addEventListener('focus', filterAdminSearch);
            </script>
            <div class="user-tools" style="margin-left: auto;">
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

        <section class="content-body" style="padding: 30px;">
            <div class="admin-form-container" style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                <h1 class="page-title" style="margin-bottom: 25px; color: #2c3e50;">
                    <i class="fas fa-edit"></i> Chỉnh sửa: {{ $animal->name }}
                </h1>

                <form action="{{ route('admin.animals.update', $animal->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Tên loài vật (VD: Hổ Bengal)</label>
                            <input type="text" name="name" class="form-control" value="{{ $animal->name }}" required placeholder="VD: Hổ Bengal">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Tên khoa học (VD: Panthera tigris)</label>
                            <input type="text" name="scientific_name" class="form-control" value="{{ $animal->scientific_name }}" required placeholder="VD: Panthera tigris">
                        </div>
                    </div>

                    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group" style="flex: 1;">
                            <label><i class="fas fa-th-large"></i> Danh mục chính</label>
                            <select name="category" class="form-control" required>
                                <option value="Thú" {{ $animal->category == 'Thú' ? 'selected' : '' }}>Thú</option>
                                <option value="Chim" {{ $animal->category == 'Chim' ? 'selected' : '' }}>Chim</option>
                                <option value="Bò sát" {{ $animal->category == 'Bò sát' ? 'selected' : '' }}>Bò sát</option>
                                <option value="Cá" {{ $animal->category == 'Cá' ? 'selected' : '' }}>Cá</option>
                                <option value="Lưỡng cư" {{ $animal->category == 'Lưỡng cư' ? 'selected' : '' }}>Lưỡng cư</option>
                                <option value="Côn trùng" {{ $animal->category == 'Côn trùng' ? 'selected' : '' }}>Côn trùng</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label><i class="fas fa-shield-alt"></i> Tình trạng bảo tồn</label>
                            <select name="status" class="form-control">
                                <option value="Ít lo ngại" {{ $animal->status == 'Ít lo ngại' ? 'selected' : '' }}>Ít lo ngại</option>
                                <option value="Sắp nguy cấp" {{ $animal->status == 'Sắp nguy cấp' ? 'selected' : '' }}>Sắp nguy cấp</option>
                                <option value="Nguy cấp" {{ $animal->status == 'Nguy cấp' ? 'selected' : '' }}>Nguy cấp</option>
                                <option value="Cực kỳ nguy cấp" {{ $animal->status == 'Cực kỳ nguy cấp' ? 'selected' : '' }}>Cực kỳ nguy cấp</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
                        <div class="form-group" style="flex: 1; min-width: 200px;">
                            <label>Lớp (Class)</label>
                            <input type="text" name="animal_class" class="form-control" value="{{ $animal->animal_class }}" placeholder="VD: Mammalia">
                        </div>

                        <div class="form-group" style="flex: 1; min-width: 250px;">
    <label style="color: #27ae60; font-weight: bold;"><i class="fas fa-sitemap"></i> Bộ (Order) - Cần khớp để lọc</label>
    <select name="animal_order" class="form-control" required style="border: 2px solid #27ae60;">
        <option value="">-- Chọn Bộ để lọc --</option>

        <optgroup label="Nhóm Thú">
            <option value="Ăn thịt">Bộ Ăn thịt</option>
            <option value="Vòi">Bộ Vòi</option>
            <option value="Linh trưởng">Bộ Linh trưởng</option>
            <option value="Guốc chẵn">Bộ Guốc chẵn</option>
            <option value="Guốc lẻ">Bộ Guốc lẻ</option>
            <option value="Gặm nhấm">Bộ Gặm nhấm</option>
            <option value="Dơi">Bộ Dơi</option>
            <option value="Cá voi">Bộ Cá voi (Thú biển)</option>
        </optgroup>

        <optgroup label="Nhóm Chim">
            <option value="Ưng">Bộ Ưng</option>
            <option value="Sẻ">Bộ Sẻ</option>
            <option value="Vẹt">Bộ Vẹt</option>
            <option value="Gà">Bộ Gà</option>
            <option value="Cú">Bộ Cú</option>
            <option value="Cánh cụt">Bộ Cánh cụt</option>
        </optgroup>

        <optgroup label="Nhóm Bò sát">
            <option value="Cá sấu">Bộ Cá sấu</option>
            <option value="Rùa">Bộ Rùa</option>
            <option value="Có vảy">Bộ Có vảy</option>
        </optgroup>

        <optgroup label="Nhóm Cá">
            <option value="Cá mập">Cá mập / Cá nhám</option>
            <option value="Cá xương">Cá xương (Cá vược...)</option>
            <option value="Cá đuối">Cá đuối</option>
            <option value="Chân đầu">Lớp Chân đầu (Mực/Bạch tuộc)</option>
        </optgroup>

        <optgroup label="Nhóm Lưỡng cư">
            <option value="Không đuôi">Không đuôi (Ếch/Nhái)</option>
            <option value="Có đuôi">Có đuôi (Kỳ giông)</option>
        </optgroup>

        <optgroup label="Nhóm Côn trùng">
            <option value="Cánh cứng">Bộ Cánh cứng</option>
            <option value="Cánh phấn">Bộ Cánh phấn (Bướm)</option>
            <option value="Cánh màng">Bộ Cánh màng (Ong/Kiến)</option>
        </optgroup>
    </select>
</div>

                        <div class="form-group" style="flex: 1; min-width: 200px;">
                            <label><i class="fas fa-utensils"></i> Chế độ ăn</label>
                            <select name="diet_type" class="form-control">
                                <option value="Ăn cỏ" {{ $animal->diet_type == 'Ăn cỏ' ? 'selected' : '' }}>Ăn cỏ</option>
                                <option value="Ăn thịt" {{ $animal->diet_type == 'Ăn thịt' ? 'selected' : '' }}>Ăn thịt</option>
                                <option value="Ăn tạp" {{ $animal->diet_type == 'Ăn tạp' ? 'selected' : '' }}>Ăn tạp</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label><i class="fas fa-paw"></i> Tập tính & Đặc điểm</label>
                        <textarea name="behavior" class="form-control" rows="3" placeholder="Nhập tập tính săn mồi, sinh sản...">{{ $animal->behavior }}</textarea>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label><i class="fas fa-info-circle"></i> Mô tả chi tiết</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Nhập thông tin chi tiết về loài vật...">{{ $animal->description }}</textarea>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label><i class="fas fa-image"></i> Thay đổi ảnh (Bỏ trống nếu giữ ảnh cũ)</label>
                        <input type="file" name="image" class="form-control" style="border: none; padding-left: 0;">
                        @if($animal->image_url)
                            <p style="font-size: 0.8rem; color: #7f8c8d; margin-top: 5px;">Ảnh hiện tại: {{ $animal->image_url }}</p>
                        @endif
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 30px;">
                        <button type="submit" class="btn-submit" style="background: #27ae60; color: white; padding: 12px 25px; border: none; border-radius: 8px; flex: 1; font-weight: bold; cursor: pointer; transition: 0.3s;">
                            <i class="fas fa-save"></i> Cập nhật thay đổi
                        </button>
                        <a href="{{ route('admin.animals') }}" style="background: #95a5a6; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; text-align: center; flex: 1; font-weight: bold;">
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
