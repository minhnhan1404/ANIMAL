<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animalia - Thế Giới Động Vật</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<nav class="navbar">
    <div class="container">
        <a href="{{ url('/') }}" class="logo"><i class="fas fa-paw"></i> Animalia</a>
        <div class="nav-tools">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="animal-search" placeholder="Tìm loài vật..." autocomplete="off">
                <div id="suggestion-list" class="suggestion-results"></div>
            </div>

            <a href="{{ route('ai.nhandien') }}" class="btn-ai-nav">
                <div class="ai-icon-wrapper">
                    <i class="fas fa-robot"></i>
                    <span class="badge-notify">HOT</span>
                </div>
                <span>Nhận diện AI</span>
            </a>

            @guest
                <a href="{{ route('login') }}" class="btn-outline-sm">Đăng nhập</a>
                <a href="{{ route('register') }}" class="btn-primary-sm">Đăng ký</a>
            @else
                <div class="user-dropdown">
                    <button class="dropdown-toggle">
                        <img src="{{ asset(Auth::user()->avatar ?? 'images/default-avatar.png') }}" class="user-avatar-nav">
                        Xin chào, <strong>{{ Auth::user()->name }}</strong>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu">
                        @if(Auth::user()->role == 'admin')
                            <li><a href="{{ url('/admin/dashboard') }}"><i class="fas fa-chart-line"></i> Dashboard Admin</a></li>
                        @endif
                        <li><a href="{{ url('/profile/edit') }}"><i class="fas fa-user-edit"></i> Chỉnh sửa thông tin</a></li>
                        <li class="divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">@csrf</form>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                        </li>
                    </ul>
                </div>
            @endguest
        </div>
    </div>
</nav>

<header class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="display-title">Thế Giới Động Vật</h1>
        <p class="hero-subtitle">Khám phá vẻ đẹp hoang dã và chung tay bảo tồn những giống loài quý hiếm trên hành tinh.</p>
        <div class="hero-buttons">
            <a href="#explore" class="btn-primary">Khám phá ngay</a>
            <a href="{{ route('social.index') }}" class="btn-outline">Tham gia bảo tồn</a>
            <a href="{{ route('ai.nhandien') }}" class="btn-ai-hero"><i class="fas fa-microscope"></i> Khám phá Công nghệ AI</a>
        </div>
    </div>
</header>

<main id="explore" class="container py-5">
    <div class="filter-main-box">
        <div class="filter-container">
            <div class="filter-section">
                <span class="filter-label"><i class="fas fa-th-large" style="color: #27ae60;"></i> DANH MỤC</span>
                <div class="btn-group-custom">
                    <a href="{{ route('home') }}" class="nav-filter-btn {{ !request('category') ? 'active' : '' }}">Tất cả</a>
                    <a href="{{ route('home', ['category' => 'Thú']) }}" class="nav-filter-btn {{ request('category') == 'Thú' ? 'active' : '' }}">Thú</a>
                    <a href="{{ route('home', ['category' => 'Chim']) }}" class="nav-filter-btn {{ request('category') == 'Chim' ? 'active' : '' }}">Chim</a>
                    <a href="{{ route('home', ['category' => 'Đại dương']) }}" class="nav-filter-btn {{ request('category') == 'Đại dương' ? 'active' : '' }}">Đại dương</a>
                    <a href="{{ route('home', ['category' => 'Bò sát']) }}" class="nav-filter-btn {{ request('category') == 'Bò sát' ? 'active' : '' }}">Bò sát</a>
                    <a href="{{ route('home', ['category' => 'Côn trùng']) }}" class="nav-filter-btn {{ request('category') == 'Côn trùng' ? 'active' : '' }}">Côn trùng</a>
                    <a href="{{ route('home', ['category' => 'Linh trưởng']) }}" class="nav-filter-btn {{ request('category') == 'Linh trưởng' ? 'active' : '' }}">Linh trưởng</a>
                </div>
            </div>
        </div>
    </div>

    <div class="animal-grid">
        @foreach($animals as $animal)
        <div class="animal-card">
            <div class="card-img-wrapper">
                <img src="{{ str_contains($animal->image_url, 'http') ? $animal->image_url : asset($animal->image_url) }}" alt="{{ $animal->name }}">

                <form action="{{ route('animal.like', $animal->id) }}" method="POST" class="like-form-container">
                    @csrf
                    <button type="submit" class="like-badge-btn">
                        <i class="{{ (Auth::check() && DB::table('likes')->where('user_id', Auth::id())->where('animal_id', $animal->id)->exists()) ? 'fas' : 'far' }} fa-heart" style="color: #ff4757;"></i>
                        <span class="like-count">{{ $animal->likes_count ?? 0 }}</span>
                    </button>
                </form>

                <span class="category-tag">{{ $animal->category }}</span>
            </div>
            <div class="card-body">
                <h3>{{ $animal->name }}</h3>
                <p class="latin">{{ $animal->scientific_name }}</p>
                <div class="taxonomy-mini">
                    <span><strong>Lớp:</strong> {{ $animal->animal_class ?? 'Đang cập nhật' }}</span>
                    <span><strong>Bộ:</strong> {{ $animal->animal_order ?? 'Đang cập nhật' }}</span>
                </div>

                {{-- ĐOẠN TỰ ĐỘNG CẬP NHẬT MÀU VÀ ICON TỪ DB --}}
                <div class="stats" style="margin-top: 10px;">
                    @php
                        $statusMap = [
                            'Ít lo ngại' => ['color' => '#28a745', 'icon' => 'fa-leaf'],
                            'Sắp nguy cấp' => ['color' => '#ffc107', 'icon' => 'fa-exclamation-triangle'],
                            'Nguy cấp' => ['color' => '#fd7e14', 'icon' => 'fa-book'],
                            'Cực kỳ nguy cấp' => ['color' => '#dc3545', 'icon' => 'fa-skull-crossbones'],
                        ];
                        $current = $statusMap[trim($animal->status)] ?? ['color' => '#28a745', 'icon' => 'fa-leaf'];
                    @endphp
                    <p style="color: {{ $current['color'] }}; font-weight: bold; font-size: 0.9rem;">
                        <i class="fas {{ $current['icon'] }}"></i> {{ $animal->status }}
                    </p>
                </div>

                <a href="{{ Auth::check() ? route('animal.detail', $animal->id) : route('login') }}" class="btn-detail" style="margin-top: 10px; display: inline-block;">
                    {{ Auth::check() ? 'Chi tiết' : 'Đăng nhập để xem' }} <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</main>

<footer>
    <div class="container"><p>&copy; {{ date('Y') }} Animalia World.</p></div>
</footer>

<script src="{{ asset('js/search.js') }}"></script>
<script src="{{ asset('js/home.js') }}"></script>
</body>
</html>
