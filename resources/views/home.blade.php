<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animalia - Thế Giới Động Vật</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                </div>
            </div>

            @if(request('category'))
            <div class="sub-filter-section animate__animated animate__fadeIn">
                <span class="filter-label" style="font-size: 0.8rem; color: #7f8c8d;">
                    <i class="fas fa-level-down-alt"></i> CHI TIẾT THEO BỘ ({{ request('category') }})
                </span>
                <div class="sub-menu-scroll">
                    @if(request('category') == 'Thú')
                        <a href="{{ route('home', ['category' => 'Thú', 'order' => 'Ăn thịt']) }}" class="sub-btn {{ request('order') == 'Ăn thịt' ? 'active' : '' }}">Bộ Ăn thịt (Hổ, Sư tử)</a>
                        <a href="{{ route('home', ['category' => 'Thú', 'order' => 'Vòi']) }}" class="sub-btn {{ request('order') == 'Vòi' ? 'active' : '' }}">Bộ Vòi (Voi)</a>
                    @elseif(request('category') == 'Bò sát')
                        <a href="{{ route('home', ['category' => 'Bò sát', 'order' => 'Cá sấu']) }}" class="sub-btn {{ request('order') == 'Cá sấu' ? 'active' : '' }}">Bộ Cá sấu</a>
                        <a href="{{ route('home', ['category' => 'Bò sát', 'order' => 'Rùa']) }}" class="sub-btn {{ request('order') == 'Rùa' ? 'active' : '' }}">Bộ Rùa</a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="animal-grid">
        @forelse($animals as $animal)
            <div class="animal-card">
                <div class="card-img-wrapper">
                    <img src="{{ str_contains($animal->image_url, 'http') ? $animal->image_url : asset($animal->image_url) }}" alt="{{ $animal->name }}">
                    <span class="category-tag">{{ $animal->category }}</span>
                </div>
                <div class="card-body">
                    <h3>{{ $animal->name }}</h3>
                    <p class="latin">{{ $animal->scientific_name }}</p>
                    <div class="taxonomy-mini">
                        <span><strong>Bộ:</strong> {{ $animal->animal_order ?? 'Đang cập nhật' }}</span>
                    </div>
                    <a href="{{ route('animal.detail', $animal->id) }}" class="btn-detail">Chi tiết</a>
                </div>
            </div>
        @empty
            <p style="text-align: center; width: 100%; grid-column: 1/-1;">Không tìm thấy loài vật nào phù hợp.</p>
        @endforelse
    </div>
</main>

<footer>
    <div class="container"><p>&copy; {{ date('Y') }} Animalia World.</p></div>
</footer>

<div id="chat-circle" class="btn btn-raised">
    <i class="fas fa-paw"></i>
</div>

<div class="chat-box">
    <div class="chat-box-header">
        <i class="fas fa-robot"></i> Trợ lý Animalia
        <span class="chat-box-toggle"><i class="fas fa-times"></i></span>
    </div>
    <div class="chat-box-body">
        <div class="chat-logs">
            <div class="chat-msg bot">
                <div class="cm-msg-text">
                    Xin chào! Tui là chuyên gia động vật. Bạn muốn hỏi gì về thế giới hoang dã nè? 🐾
                </div>
            </div>
        </div>
    </div>
    <div class="chat-input">
        <form id="chat-form">
            <input type="text" id="chat-input-field" placeholder="Hỏi về loài vật..."/>
            <button type="submit" class="chat-submit" id="chat-submit">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="{{ asset('js/search.js') }}"></script>
<script src="{{ asset('js/home.js') }}"></script>
<script src="{{ asset('js/chatbot.js') }}"></script>
</body>
</html>
