<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết: {{ $animal->name }}</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animal-detail.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background-color: #f4f7f6; font-family: 'Inter', sans-serif;">

<div class="container detail-container">
    <div class="detail-wrapper">

        {{-- Hình ảnh bên trái --}}
        <div class="detail-img">
            <img src="{{ asset($animal->image_url) }}" alt="{{ $animal->name }}">
        </div>

        {{-- Thông tin bên phải --}}
        <div class="detail-info">
            <h1 style="font-size: 3.5rem; color: #27ae60; margin-bottom: 5px; font-weight: 800;">
                {{ $animal->name }}
            </h1>
            <p style="font-style: italic; color: #888; font-size: 1.3rem; margin-bottom: 25px;">
                {{ $animal->scientific_name }}
            </p>

            <div class="info-badges">
    @php
        // Bảng màu đồng bộ với trang chủ
        $statusMap = [
            'Ít lo ngại' => ['color' => '#28a745', 'icon' => 'fa-leaf', 'bg' => '#e8f5e9'],
            'Sắp nguy cấp' => ['color' => '#ffc107', 'icon' => 'fa-exclamation-triangle', 'bg' => '#fffde7'],
            'Nguy cấp' => ['color' => '#fd7e14', 'icon' => 'fa-book', 'bg' => '#fff3e0'],
            'Cực kỳ nguy cấp' => ['color' => '#dc3545', 'icon' => 'fa-skull-crossbones', 'bg' => '#ffebee'],
        ];
        $current = $statusMap[trim($animal->status)] ?? ['color' => '#28a745', 'icon' => 'fa-leaf', 'bg' => '#e8f5e9'];
    @endphp

    {{-- Badge Trạng thái tự động đổi màu --}}
    <span class="badge-status" style="background-color: {{ $current['bg'] }}; color: {{ $current['color'] }}; padding: 10px 20px; border-radius: 50px; font-weight: bold; margin-right: 10px; display: inline-block;">
        <i class="fas {{ $current['icon'] }}"></i> {{ $animal->status }}
    </span>

    {{-- Badge Danh mục --}}
    <span class="badge-category" style="background-color: #e3f2fd; color: #1976d2; padding: 10px 20px; border-radius: 50px; font-weight: bold; display: inline-block;">
        <i class="fas fa-tag"></i> {{ $animal->category }}
    </span>
</div>

            <h3 class="section-title">Tập tính & Đặc điểm</h3>
            <p style="line-height: 1.8; color: #555; margin-bottom: 30px; font-size: 1.05rem;">
                {{ $animal->behavior ?? 'Thông tin tập tính loài ' . $animal->name . ' đang được cập nhật...' }}
            </p>

            <h3 class="section-title">Mô tả chi tiết</h3>
            <p style="line-height: 1.8; color: #555; font-size: 1.05rem;">
                {{ $animal->description ?? 'Hiện chưa có mô tả chi tiết cho cá thể này.' }}
            </p>

            <div style="margin-top: 40px;">
                <a href="{{ url('/') }}" class="btn-primary" style="padding: 15px 35px; border-radius: 50px; text-decoration: none; font-weight: 700; display: inline-block;">
                    <i class="fas fa-arrow-left"></i> Quay lại trang chủ
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
