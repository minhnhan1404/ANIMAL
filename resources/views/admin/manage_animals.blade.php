<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý loài vật - Animalia</title>
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
                                <option value="Bò sát">Bò sát</option>
                                <option value="Cá">Cá</option>
                                <option value="Lưỡng cư">Lưỡng cư</option>
                                <option value="Côn trùng">Côn trùng</option>
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
