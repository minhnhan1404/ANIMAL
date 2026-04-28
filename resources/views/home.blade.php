<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animalia - Thế Giới Động Vật</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logotgdvv.png') }}">
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
            <a href="{{ route('social.index') }}" class="btn-outline">Góc chia sẻ</a>
            <a href="{{ route('ai.nhandien') }}" class="btn-ai-hero"><i class="fas fa-microscope"></i> Khám phá Công nghệ AI</a>
        </div>
    </div>
</header>

<main id="explore" class="container py-5">
    <div class="filter-main-box">
        <div class="filter-container">
            <div class="filter-section">
                <span class="filter-label"><i class="fas fa-th-large" style="color: #27ae60;"></i> DANH MỤC</span>
                <div class="btn-group-custom desktop-filter">
                    <a href="{{ route('home') }}" class="nav-filter-btn {{ !request('category') ? 'active' : '' }}">Tất cả</a>
                    <a href="{{ route('home', ['category' => 'Thú']) }}" class="nav-filter-btn {{ request('category') == 'Thú' ? 'active' : '' }}">Thú</a>
                    <a href="{{ route('home', ['category' => 'Chim']) }}" class="nav-filter-btn {{ request('category') == 'Chim' ? 'active' : '' }}">Chim</a>
                    <a href="{{ route('home', ['category' => 'Bò sát']) }}" class="nav-filter-btn {{ request('category') == 'Bò sát' ? 'active' : '' }}">Bò sát</a>
                    <a href="{{ route('home', ['category' => 'Cá']) }}" class="nav-filter-btn {{ request('category') == 'Cá' ? 'active' : '' }}">Cá</a>
                    <a href="{{ route('home', ['category' => 'Lưỡng cư']) }}" class="nav-filter-btn {{ request('category') == 'Lưỡng cư' ? 'active' : '' }}">Lưỡng cư</a>
                    <a href="{{ route('home', ['category' => 'Côn trùng']) }}" class="nav-filter-btn {{ request('category') == 'Côn trùng' ? 'active' : '' }}">Côn trùng</a>
                </div>
                <select class="custom-select category-dropdown mobile-filter" onchange="window.location.href=this.value">
                    <option value="{{ route('home') }}" {{ !request('category') ? 'selected' : '' }}>Tất cả danh mục</option>
                    <option value="{{ route('home', ['category' => 'Thú']) }}" {{ request('category') == 'Thú' ? 'selected' : '' }}>Thú (Mammals)</option>
                    <option value="{{ route('home', ['category' => 'Chim']) }}" {{ request('category') == 'Chim' ? 'selected' : '' }}>Chim (Birds)</option>
                    <option value="{{ route('home', ['category' => 'Bò sát']) }}" {{ request('category') == 'Bò sát' ? 'selected' : '' }}>Bò sát (Reptiles)</option>
                    <option value="{{ route('home', ['category' => 'Cá']) }}" {{ request('category') == 'Cá' ? 'selected' : '' }}>Cá (Fishes)</option>
                    <option value="{{ route('home', ['category' => 'Lưỡng cư']) }}" {{ request('category') == 'Lưỡng cư' ? 'selected' : '' }}>Lưỡng cư (Amphibians)</option>
                    <option value="{{ route('home', ['category' => 'Côn trùng']) }}" {{ request('category') == 'Côn trùng' ? 'selected' : '' }}>Côn trùng (Insects)</option>
                </select>
            </div>

            @if(request('category'))
<div class="sub-filter-section animate__animated animate__fadeIn">
    <span class="filter-label" style="font-size: 0.8rem; color: #7f8c8d;">
        <i class="fas fa-level-down-alt"></i> CHI TIẾT THEO BỘ ({{ request('category') }})
    </span>
    <div class="sub-menu-scroll desktop-filter">
        @php $category = request('category'); @endphp
        @if($category == 'Thú')
            <a href="{{ route('home', ['category' => 'Thú', 'order' => 'Ăn thịt']) }}" class="sub-btn {{ request('order') == 'Ăn thịt' ? 'active' : '' }}">Bộ Ăn thịt</a>
            <a href="{{ route('home', ['category' => 'Thú', 'order' => 'Vòi']) }}" class="sub-btn {{ request('order') == 'Vòi' ? 'active' : '' }}">Bộ Vòi</a>
            <a href="{{ route('home', ['category' => 'Thú', 'order' => 'Linh trưởng']) }}" class="sub-btn {{ request('order') == 'Linh trưởng' ? 'active' : '' }}">Bộ Linh trưởng</a>
            <a href="{{ route('home', ['category' => 'Thú', 'order' => 'Guốc chẵn']) }}" class="sub-btn {{ request('order') == 'Guốc chẵn' ? 'active' : '' }}">Bộ Guốc chẵn</a>
            <a href="{{ route('home', ['category' => 'Thú', 'order' => 'Guốc lẻ']) }}" class="sub-btn {{ request('order') == 'Guốc lẻ' ? 'active' : '' }}">Bộ Guốc lẻ</a>
            <a href="{{ route('home', ['category' => 'Thú', 'order' => 'Gặm nhấm']) }}" class="sub-btn {{ request('order') == 'Gặm nhấm' ? 'active' : '' }}">Bộ Gặm nhấm</a>
            <a href="{{ route('home', ['category' => 'Thú', 'order' => 'Dơi']) }}" class="sub-btn {{ request('order') == 'Dơi' ? 'active' : '' }}">Bộ Dơi</a>
            <a href="{{ route('home', ['category' => 'Thú', 'order' => 'Cá voi']) }}" class="sub-btn {{ request('order') == 'Cá voi' ? 'active' : '' }}">Bộ Cá voi (Thú biển)</a>
        @elseif($category == 'Chim')
            <a href="{{ route('home', ['category' => 'Chim', 'order' => 'Ưng']) }}" class="sub-btn {{ request('order') == 'Ưng' ? 'active' : '' }}">Bộ Ưng</a>
            <a href="{{ route('home', ['category' => 'Chim', 'order' => 'Sẻ']) }}" class="sub-btn {{ request('order') == 'Sẻ' ? 'active' : '' }}">Bộ Sẻ</a>
            <a href="{{ route('home', ['category' => 'Chim', 'order' => 'Vẹt']) }}" class="sub-btn {{ request('order') == 'Vẹt' ? 'active' : '' }}">Bộ Vẹt</a>
            <a href="{{ route('home', ['category' => 'Chim', 'order' => 'Gà']) }}" class="sub-btn {{ request('order') == 'Gà' ? 'active' : '' }}">Bộ Gà</a>
            <a href="{{ route('home', ['category' => 'Chim', 'order' => 'Cú']) }}" class="sub-btn {{ request('order') == 'Cú' ? 'active' : '' }}">Bộ Cú</a>
            <a href="{{ route('home', ['category' => 'Chim', 'order' => 'Cánh cụt']) }}" class="sub-btn {{ request('order') == 'Cánh cụt' ? 'active' : '' }}">Bộ Cánh cụt</a>
        @elseif($category == 'Bò sát')
            <a href="{{ route('home', ['category' => 'Bò sát', 'order' => 'Cá sấu']) }}" class="sub-btn {{ request('order') == 'Cá sấu' ? 'active' : '' }}">Bộ Cá sấu</a>
            <a href="{{ route('home', ['category' => 'Bò sát', 'order' => 'Rùa']) }}" class="sub-btn {{ request('order') == 'Rùa' ? 'active' : '' }}">Bộ Rùa</a>
            <a href="{{ route('home', ['category' => 'Bò sát', 'order' => 'Có vảy']) }}" class="sub-btn {{ request('order') == 'Có vảy' ? 'active' : '' }}">Bộ Có vảy</a>
        @elseif($category == 'Cá')
            <a href="{{ route('home', ['category' => 'Cá', 'order' => 'Cá mập']) }}" class="sub-btn {{ request('order') == 'Cá mập' ? 'active' : '' }}">Cá mập / Cá nhám</a>
            <a href="{{ route('home', ['category' => 'Cá', 'order' => 'Cá xương']) }}" class="sub-btn {{ request('order') == 'Cá xương' ? 'active' : '' }}">Cá xương (Cá vược...)</a>
            <a href="{{ route('home', ['category' => 'Cá', 'order' => 'Cá đuối']) }}" class="sub-btn {{ request('order') == 'Cá đuối' ? 'active' : '' }}">Cá đuối</a>
            <a href="{{ route('home', ['category' => 'Cá', 'order' => 'Chân đầu']) }}" class="sub-btn {{ request('order') == 'Chân đầu' ? 'active' : '' }}">Lớp Chân đầu (Mực/Bạch tuộc)</a>
        @elseif($category == 'Lưỡng cư')
            <a href="{{ route('home', ['category' => 'Lưỡng cư', 'order' => 'Không đuôi']) }}" class="sub-btn {{ request('order') == 'Không đuôi' ? 'active' : '' }}">Không đuôi (Ếch/Nhái)</a>
            <a href="{{ route('home', ['category' => 'Lưỡng cư', 'order' => 'Có đuôi']) }}" class="sub-btn {{ request('order') == 'Có đuôi' ? 'active' : '' }}">Có đuôi (Kỳ giông)</a>
        @elseif($category == 'Côn trùng')
            <a href="{{ route('home', ['category' => 'Côn trùng', 'order' => 'Cánh cứng']) }}" class="sub-btn {{ request('order') == 'Cánh cứng' ? 'active' : '' }}">Bộ Cánh cứng</a>
            <a href="{{ route('home', ['category' => 'Côn trùng', 'order' => 'Cánh phấn']) }}" class="sub-btn {{ request('order') == 'Cánh phấn' ? 'active' : '' }}">Bộ Cánh phấn (Bướm)</a>
            <a href="{{ route('home', ['category' => 'Côn trùng', 'order' => 'Cánh màng']) }}" class="sub-btn {{ request('order') == 'Cánh màng' ? 'active' : '' }}">Bộ Cánh màng (Ong/Kiến)</a>
        @endif
    </div>

    <select class="custom-select sub-category-dropdown mobile-filter" onchange="window.location.href=this.value">
        @php $category = request('category'); @endphp
        <option value="{{ route('home', ['category' => $category]) }}">Tất cả các bộ trong {{ $category }}</option>

        {{-- NHÓM THÚ --}}
        @if($category == 'Thú')
            <option value="{{ route('home', ['category' => 'Thú', 'order' => 'Ăn thịt']) }}" {{ request('order') == 'Ăn thịt' ? 'selected' : '' }}>Bộ Ăn thịt</option>
            <option value="{{ route('home', ['category' => 'Thú', 'order' => 'Vòi']) }}" {{ request('order') == 'Vòi' ? 'selected' : '' }}>Bộ Vòi</option>
            <option value="{{ route('home', ['category' => 'Thú', 'order' => 'Linh trưởng']) }}" {{ request('order') == 'Linh trưởng' ? 'selected' : '' }}>Bộ Linh trưởng</option>
            <option value="{{ route('home', ['category' => 'Thú', 'order' => 'Guốc chẵn']) }}" {{ request('order') == 'Guốc chẵn' ? 'selected' : '' }}>Bộ Guốc chẵn</option>
            <option value="{{ route('home', ['category' => 'Thú', 'order' => 'Guốc lẻ']) }}" {{ request('order') == 'Guốc lẻ' ? 'selected' : '' }}>Bộ Guốc lẻ</option>
            <option value="{{ route('home', ['category' => 'Thú', 'order' => 'Gặm nhấm']) }}" {{ request('order') == 'Gặm nhấm' ? 'selected' : '' }}>Bộ Gặm nhấm</option>
            <option value="{{ route('home', ['category' => 'Thú', 'order' => 'Dơi']) }}" {{ request('order') == 'Dơi' ? 'selected' : '' }}>Bộ Dơi</option>
            <option value="{{ route('home', ['category' => 'Thú', 'order' => 'Cá voi']) }}" {{ request('order') == 'Cá voi' ? 'selected' : '' }}>Bộ Cá voi (Thú biển)</option>

        {{-- NHÓM CHIM --}}
        @elseif($category == 'Chim')
            <option value="{{ route('home', ['category' => 'Chim', 'order' => 'Ưng']) }}" {{ request('order') == 'Ưng' ? 'selected' : '' }}>Bộ Ưng</option>
            <option value="{{ route('home', ['category' => 'Chim', 'order' => 'Sẻ']) }}" {{ request('order') == 'Sẻ' ? 'selected' : '' }}>Bộ Sẻ</option>
            <option value="{{ route('home', ['category' => 'Chim', 'order' => 'Vẹt']) }}" {{ request('order') == 'Vẹt' ? 'selected' : '' }}>Bộ Vẹt</option>
            <option value="{{ route('home', ['category' => 'Chim', 'order' => 'Gà']) }}" {{ request('order') == 'Gà' ? 'selected' : '' }}>Bộ Gà</option>
            <option value="{{ route('home', ['category' => 'Chim', 'order' => 'Cú']) }}" {{ request('order') == 'Cú' ? 'selected' : '' }}>Bộ Cú</option>
            <option value="{{ route('home', ['category' => 'Chim', 'order' => 'Cánh cụt']) }}" {{ request('order') == 'Cánh cụt' ? 'selected' : '' }}>Bộ Cánh cụt</option>

        {{-- NHÓM BÒ SÁT --}}
        @elseif($category == 'Bò sát')
            <option value="{{ route('home', ['category' => 'Bò sát', 'order' => 'Cá sấu']) }}" {{ request('order') == 'Cá sấu' ? 'selected' : '' }}>Bộ Cá sấu</option>
            <option value="{{ route('home', ['category' => 'Bò sát', 'order' => 'Rùa']) }}" {{ request('order') == 'Rùa' ? 'selected' : '' }}>Bộ Rùa</option>
            <option value="{{ route('home', ['category' => 'Bò sát', 'order' => 'Có vảy']) }}" {{ request('order') == 'Có vảy' ? 'selected' : '' }}>Bộ Có vảy</option>

        {{-- NHÓM CÁ --}}
        @elseif($category == 'Cá')
            <option value="{{ route('home', ['category' => 'Cá', 'order' => 'Cá mập']) }}" {{ request('order') == 'Cá mập' ? 'selected' : '' }}>Cá mập / Cá nhám</option>
            <option value="{{ route('home', ['category' => 'Cá', 'order' => 'Cá xương']) }}" {{ request('order') == 'Cá xương' ? 'selected' : '' }}>Cá xương (Cá vược...)</option>
            <option value="{{ route('home', ['category' => 'Cá', 'order' => 'Cá đuối']) }}" {{ request('order') == 'Cá đuối' ? 'selected' : '' }}>Cá đuối</option>
            <option value="{{ route('home', ['category' => 'Cá', 'order' => 'Chân đầu']) }}" {{ request('order') == 'Chân đầu' ? 'selected' : '' }}>Lớp Chân đầu (Mực/Bạch tuộc)</option>

        {{-- NHÓM LƯỠNG CƯ --}}
        @elseif($category == 'Lưỡng cư')
            <option value="{{ route('home', ['category' => 'Lưỡng cư', 'order' => 'Không đuôi']) }}" {{ request('order') == 'Không đuôi' ? 'selected' : '' }}>Không đuôi (Ếch/Nhái)</option>
            <option value="{{ route('home', ['category' => 'Lưỡng cư', 'order' => 'Có đuôi']) }}" {{ request('order') == 'Có đuôi' ? 'selected' : '' }}>Có đuôi (Kỳ giông)</option>

        {{-- NHÓM CÔN TRùng --}}
        @elseif($category == 'Côn trùng')
            <option value="{{ route('home', ['category' => 'Côn trùng', 'order' => 'Cánh cứng']) }}" {{ request('order') == 'Cánh cứng' ? 'selected' : '' }}>Bộ Cánh cứng</option>
            <option value="{{ route('home', ['category' => 'Côn trùng', 'order' => 'Cánh phấn']) }}" {{ request('order') == 'Cánh phấn' ? 'selected' : '' }}>Bộ Cánh phấn (Bướm)</option>
            <option value="{{ route('home', ['category' => 'Côn trùng', 'order' => 'Cánh màng']) }}" {{ request('order') == 'Cánh màng' ? 'selected' : '' }}>Bộ Cánh màng (Ong/Kiến)</option>
        @endif
    </select>
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

    {{-- Đổi class thành fav-btn --}}
    <button class="fav-btn {{ $animal->is_liked ? 'active' : '' }}" data-id="{{ $animal->id }}">
        <i class="{{ $animal->is_liked ? 'fas' : 'far' }} fa-heart"></i>
    </button>
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

<footer class="site-footer">
    <div class="footer-container">
        <!-- Cột 1: Giới thiệu -->
        <div class="footer-col footer-brand">
            <a href="{{ url('/') }}" class="logo"><i class="fas fa-paw"></i> Animalia</a>
            <p class="footer-desc">Nền tảng bách khoa toàn thư thế giới động vật. Chung tay bảo tồn sự đa dạng sinh học và lan tỏa tình yêu thiên nhiên qua công nghệ AI nhận diện hiện đại.</p>
            <div class="social-links">
                <a href="https://www.facebook.com/profile.php?id=61572031791404" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-tiktok"></i></a>
            </div>
        </div>

        <!-- Cột 2: Khám phá -->
        <div class="footer-col">
            <h3 class="footer-heading">Khám phá</h3>
            <ul class="footer-links">
                <li><a href="{{ route('home', ['category' => 'Thú']) }}"><i class="fas fa-angle-right"></i> Thế giới Thú</a></li>
                <li><a href="{{ route('home', ['category' => 'Chim']) }}"><i class="fas fa-angle-right"></i> Thế giới Chim</a></li>
                <li><a href="{{ route('home', ['category' => 'Cá']) }}"><i class="fas fa-angle-right"></i> Đại dương bao la</a></li>
                <li><a href="{{ route('ai.nhandien') }}"><i class="fas fa-angle-right"></i> AI Nhận diện</a></li>
            </ul>
        </div>

        <!-- Cột 3: Liên kết nhanh -->
        <div class="footer-col">
            <h3 class="footer-heading">Hỗ trợ & Thông tin</h3>
            <ul class="footer-links">
                <li><a href="{{ route('social.index') }}"><i class="fas fa-angle-right"></i> Góc chia sẻ cộng đồng</a></li>
                <li><a href="#"><i class="fas fa-angle-right"></i> Giới thiệu về Animalia</a></li>
                <li><a href="#"><i class="fas fa-angle-right"></i> Chính sách bảo mật</a></li>
                <li><a href="#"><i class="fas fa-angle-right"></i> Điều khoản sử dụng</a></li>
            </ul>
        </div>

        <!-- Cột 4: Liên hệ -->
        <div class="footer-col">
            <h3 class="footer-heading">Thông tin liên hệ</h3>
            <ul class="footer-contact">
                <li>
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Đại Học Cần Thơ Khu 2</span>
                </li>
                <li>
                    <i class="fas fa-phone-alt"></i>
                    <span>+84 (0) 123 456 789</span>
                </li>
                <li>
                    <i class="fas fa-envelope"></i>
                    <span>animalaidongvat@gmail.com</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} Animalia World. Mọi quyền được bảo lưu. Phát triển với dành cho thiên nhiên.</p>
    </div>
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
