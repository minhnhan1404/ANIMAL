<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animalia - Thế Giới Động Vật</title>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

{{-- ================= NAVBAR ================= --}}
<nav class="navbar">
    <div class="container">
        <a href="{{ url('/') }}" class="logo">
            <i class="fas fa-paw"></i> Animalia
        </a>

        <div class="nav-tools">

            {{-- SEARCH --}}
            <div class="search-box">
                <i class="fas fa-search"></i>
                    <input type="text" id="animal-search" placeholder="Tìm loài vật..." autocomplete="off">

                <div id="suggestion-list" class="suggestion-results"></div>
            </div>

            {{-- Nút Nhận diện AI mới --}}
<a href="{{ route('ai.nhandien') }}" class="btn-ai-nav">
    <div class="ai-icon-wrapper">
        <i class="fas fa-robot"></i>
        <span class="badge-notify">HOT</span>
    </div>
    <span>Nhận diện AI</span>
</a>

            {{-- AUTH --}}
@guest
    <a href="{{ route('login') }}" class="btn-outline-sm">Đăng nhập</a>
    <a href="{{ route('register') }}" class="btn-primary-sm">Đăng ký</a>
@else
    <div class="user-dropdown">
        <button class="dropdown-toggle">
            Xin chào, <strong>{{ Auth::user()->name }}</strong>
            <i class="fas fa-chevron-down"></i>
        </button>

        <ul class="dropdown-menu">
            {{-- CHỈ HIỆN NẾU LÀ ADMIN --}}
            @if(Auth::user()->role == 'admin')
                <li>
                    <a href="{{ url('/admin/dashboard') }}">
                        <i class="fas fa-chart-line"></i> Dashboard Admin
                    </a>
                </li>
            @endif

            {{-- HIỆN CHO TẤT CẢ USER ĐÃ ĐĂNG NHẬP --}}
            <li>
                <a href="{{ url('/profile/edit') }}">
                    <i class="fas fa-user-edit"></i> Chỉnh sửa thông tin
                </a>
            </li>

            <li class="divider"></li>

            <li>
                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-danger">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                </form>
            </li>
        </ul>
    </div>
@endguest

        </div>
    </div>
</nav>

{{-- ================= HERO ================= --}}
<header class="hero">
    <div class="hero-overlay"></div>

    <div class="hero-content">
        <h1 class="display-title">Thế Giới Động Vật</h1>
        <p class="hero-subtitle">
            Khám phá vẻ đẹp hoang dã và chung tay bảo tồn những giống loài quý hiếm trên hành tinh.
        </p>

        <div class="hero-buttons">
            <a href="#explore" class="btn-primary">Khám phá ngay</a>
            <a href="#" class="btn-outline">Tham gia bảo tồn</a>
            <a href="{{ route('ai.nhandien') }}" class="btn-ai-hero">
    <i class="fas fa-microscope"></i> Khám phá Công nghệ AI
</a>
        </div>
    </div>
</header>
<main id="explore" class="container py-5">

    {{-- Nhóm bộ lọc --}}
    {{-- Nhóm bộ lọc mới: Gọn gàng, chuyên nghiệp như Admin --}}
<div class="filter-main-box" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">

        {{-- DANH MỤC --}}
        <div class="filter-section">
            <span style="font-weight: 700; color: #2c3e50; margin-right: 15px; font-size: 0.95rem;">
                <i class="fas fa-th-large" style="color: #27ae60;"></i> DANH MỤC
            </span>
            <div class="btn-group-custom" style="display: inline-flex; background: #f8f9fa; padding: 5px; border-radius: 30px; border: 1px solid #eee;">
                <a href="{{ route('home') }}" class="nav-filter-btn {{ !request('category') ? 'active' : '' }}">Tất cả</a>
                <a href="{{ route('home', ['category' => 'Thú']) }}" class="nav-filter-btn {{ request('category') == 'Thú' ? 'active' : '' }}">Thú</a>
                <a href="{{ route('home', ['category' => 'Chim']) }}" class="nav-filter-btn {{ request('category') == 'Chim' ? 'active' : '' }}">Chim</a>
                <a href="{{ route('home', ['category' => 'Đại dương']) }}" class="nav-filter-btn {{ request('category') == 'Đại dương' ? 'active' : '' }}">Đại dương</a>
            </div>
        </div>

        {{-- DROPDOWN CHẾ ĐỘ ĂN --}}
        <div class="diet-dropdown-container" style="position: relative;">
            <button class="dropbtn-modern" type="button">
                <span>{{ request('diet') ? 'Chế độ: ' . request('diet') : 'CHẾ ĐỘ ĂN' }}</span>
                <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 10px;"></i>
            </button>
            <div class="dropdown-content-horizontal">
                <div class="dropdown-inner">
                    <a href="{{ route('home', array_merge(request()->query(), ['diet' => 'Ăn cỏ'])) }}" class="opt {{ request('diet') == 'Ăn cỏ' ? 'active' : '' }}">Ăn cỏ</a>
                    <a href="{{ route('home', array_merge(request()->query(), ['diet' => 'Ăn thịt'])) }}" class="opt {{ request('diet') == 'Ăn thịt' ? 'active' : '' }}">Ăn thịt</a>
                    <a href="{{ route('home', array_merge(request()->query(), ['diet' => 'Ăn tạp'])) }}" class="opt {{ request('diet') == 'Ăn tạp' ? 'active' : '' }}">Ăn tạp</a>
                    <div class="divider-v"></div>
                    <a href="{{ route('home', array_diff_key(request()->query(), ['diet' => ''])) }}" class="opt-reset">Xóa</a>
                </div>
            </div>
        </div>

    </div>
</div>

    {{-- ANIMAL GRID --}}
    <div class="animal-grid">
        @foreach($animals as $animal)
        <div class="animal-card">
            <div class="card-img-wrapper">
    {{-- 1. Ảnh động vật --}}
    @if(str_contains($animal->image_url, 'http'))
        <img src="{{ $animal->image_url }}" alt="{{ $animal->name }}">
    @else
        <img src="{{ asset($animal->image_url) }}" alt="{{ $animal->name }}">
    @endif

    {{-- 2. LOGIC KIỂM TRA ĐÃ LIKE CHƯA --}}
    @php
        $isLiked = false;
        if(Auth::check()){
            $isLiked = DB::table('likes')
                ->where('user_id', Auth::id())
                ->where('animal_id', $animal->id)
                ->exists();
        }
    @endphp

    {{-- 3. NÚT TRÁI TIM (Bấm lần 1: Like, Bấm lần 2: Unlike) --}}
    <form action="{{ route('animal.like', $animal->id) }}" method="POST" style="position: absolute; top: 15px; right: 15px; z-index: 10;">
        @csrf
        <button type="submit" class="like-badge-btn" style="border: none; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(5px); padding: 5px 12px; border-radius: 20px; cursor: pointer; display: flex; align-items: center; gap: 5px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: 0.3s;">
            {{-- Đã thích hiện tim đỏ (fas), chưa thích hiện tim trống (far) --}}
            <i class="{{ $isLiked ? 'fas fa-heart' : 'far fa-heart' }}" style="color: #ff4757;"></i>
            <span style="font-weight: 800; color: #2c3e50; font-size: 0.9rem;">
                {{ $animal->likes_count ?? 0 }}
            </span>
        </button>
    </form>

    {{-- Nhãn Danh mục --}}
    <div class="tag-group" style="position: absolute; top: 10px; left: 10px; display: flex; gap: 5px;">
        <span class="category-tag">{{ $animal->category }}</span>
    </div>
</div>

            <div class="card-body">
                <h3>{{ $animal->name }}</h3>
                <p class="latin" style="margin-bottom: 10px;">{{ $animal->scientific_name }}</p>

                {{-- HIỂN THỊ PHÂN LOẠI HỌC (Góp ý của cô giáo) --}}
                <div class="taxonomy-mini">
    {{-- Sửa 'animal_class' thay vì 'class' và 'animal_order' thay vì 'family' --}}
    <span><strong>Lớp:</strong> {{ $animal->animal_class ?? 'Đang cập nhật' }}</span>
    <span><strong>Bộ:</strong> {{ $animal->animal_order ?? 'Đang cập nhật' }}</span>
</div>

                <div class="stats">
                    <p><i class="fas fa-leaf"></i> {{ $animal->status }}</p>
                </div>

                @auth
                    <a href="{{ route('animal.detail', $animal->id) }}" class="btn-detail">
                        Chi tiết <i class="fas fa-arrow-right"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-detail">
                        Đăng nhập để xem
                    </a>
                @endauth
            </div>
        </div>
        @endforeach
    </div>
</main>


{{-- ================= MODAL ================= --}}
<input type="checkbox" id="modal-lion" class="modal-toggle">
<div class="modal-overlay">
    <div class="modal-content">
        <label for="modal-lion" class="btn-close">&times;</label>

        <div class="modal-grid">
            <div class="modal-img">
                <img src="https://images.unsplash.com/photo-1614027126733-757680a45475?w=800" alt="Sư tử">
            </div>

            <div class="modal-info">

                <div class="info-badges">
                    <div class="badge">
                        <small>CHẾ ĐỘ ĂN</small>
                        <strong>Ăn thịt</strong>
                    </div>
                    <div class="badge">
                        <small>IUCN</small>
                        <strong style="color:#e67e22">Sắp nguy cấp</strong>
                    </div>
                </div>

                <h3><i class="fas fa-lightbulb text-warning"></i> Sự thật thú vị</h3>
                <p>
                    Sư tử là loài duy nhất trong họ Mèo có đời sống xã hội phức tạp.
                    Tiếng gầm của chúng có thể vang xa tới 8km.
                </p>

                <button class="btn-primary w-full">Chia sẻ thông điệp</button>
            </div>
        </div>
    </div>
</div>

{{-- ================= FOOTER ================= --}}
<footer>
    <div class="container">
        <p>&copy; {{ date('Y') }} Animalia World.</p>
    </div>
</footer>


    <script src="{{ asset('js/search.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>
