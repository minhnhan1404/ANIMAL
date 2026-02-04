<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Nhận Diện Động Vật - Animalia</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ai-style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="ai-body">

<a href="{{ url('/') }}" class="btn-back-home">
    <i class="fas fa-home"></i> Quay lại trang chủ
</a>

<div class="user-sidebar-right">
    @auth
        <div class="user-dropdown">
            <button class="dropdown-btn">
                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
            </button>
            <div class="dropdown-content">
                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-terminal"></i> Admin Panel</a>
                @endif
                <a href="#"><i class="fas fa-user-edit"></i> Hồ sơ</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-link"><i class="fas fa-power-off"></i> Đăng xuất</button>
                </form>
            </div>
        </div>
    @endauth
</div>

<div class="ai-container">
    <div class="header-section">
        <h2 class="display-title">AI RECOGNITION SYSTEM</h2>
        <p class="ai-subtitle">Nhận diện hình ảnh các loài động vật/p>
    </div>

    <div class="upload-box shadow-lg">
        <input type="file" id="ai-input" hidden accept="image/*">

        <div class="drop-area" id="drop-area">
            <div class="scan-frame"></div>
            <i class="fas fa-microscope"></i>
            <p>Kéo thả hình ảnh vào vùng quét</p>
        </div>

        <div id="preview-section" style="display: none;">
            <div class="img-wrapper">
                <img id="preview-img" src="" class="img-fluid">
                <div id="laser-line" class="laser"></div>
            </div>
            <br>
            <button id="btn-scan" class="btn-primary-ai neon-btn">
                <i class="fas fa-bolt"></i> KHỞI CHẠY PHÂN TÍCH
            </button>
        </div>
    </div>

    <div id="ai-result-card" class="result-card glass-morphism" style="display: none;">
        <div class="result-header">DỮ LIỆU TRÍ TUỆ NHÂN TẠO</div>
        <div class="result-body">
            <div class="result-flex">
                <div>
                    <h3 id="animal-name">---</h3>
                    <p id="animal-conf">Độ chính xác: 0%</p>
                </div>
                <div class="status-indicator animate-pulse">Phân tích xong</div>
            </div>
            <hr>
            <div id="animal-info">
                <p><i class="fas fa-info-circle"></i> Thông tin chi tiết đang được truy xuất...</p>
            </div>

            <button id="btn-reset" class="btn-reset-ai">
                <i class="fas fa-redo"></i> Nhận diện động vật khác
            </button>
        </div>
    </div>
</div>

<script src="{{ asset('js/ai-processor.js') }}"></script>
</body>
</html>
