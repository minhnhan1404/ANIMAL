<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Animal</title>
    {{-- Meta tag này cực quan trọng để chạy Like bằng AJAX sau này --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Gọi file CSS duy nhất đã gom tất cả code --}}
    <link rel="stylesheet" href="{{ asset('css/social.css') }}">
</head>
<body>

    {{-- Sidebar bên trái --}}
    <div class="sidebar">
        <div class="sidebar-logo">
            <h2>Animal</h2>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ url('/') }}"><i class="fas fa-home"></i> <span>Trang chủ</span></a>
            <a href="#"><i class="fas fa-compass"></i> <span>Khám phá</span></a>
            <a href="#" onclick="document.getElementById('postModal').style.display='flex'">
                <i class="far fa-plus-square"></i> <span>Tạo bài viết</span>
            </a>
        </nav>
    </div>

    {{-- Nội dung chính --}}
    <div class="instagram-container">
        {{-- Card gợi ý đăng bài --}}
        <div class="create-post-card" onclick="document.getElementById('postModal').style.display='flex'">
            <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" class="avatar-small">
            <div class="open-modal-btn">{{ Auth::user()->name }} ơi, bạn đang nghĩ gì thế?</div>
        </div>

        {{-- Danh sách bài viết --}}
        <div class="post-feed">
            @foreach($posts as $post)
            <div class="post-card" data-id="{{ $post->id }}">
                <div class="ins-header">
                    <img src="{{ asset('images/default-avatar.png') }}" class="avatar-small">
                    <span class="username">{{ $post->user_name }}</span>
                    <i class="fas fa-ellipsis-h ms-auto"></i>
                </div>

                <div class="post-image-container">
                    @if($post->image_url)
                        <img src="{{ asset($post->image_url) }}" class="post-image">
                    @else
                        <div class="no-image-placeholder">Không có ảnh</div>
                    @endif
                    <div class="big-heart-overlay"><i class="fas fa-heart"></i></div>
                </div>

                <div class="ins-footer">
                    <div class="post-actions">
                        <button class="action-btn-ins"><i class="far fa-heart fa-lg"></i></button>
                        <button class="action-btn-ins"><i class="far fa-comment fa-lg"></i></button>
                    </div>
                    <div class="ins-content" style="padding: 0 15px 15px;">
                        <p><strong>0 lượt thích</strong></p>
                        <p><strong>{{ $post->user_name }}</strong> {{ $post->content }}</p>
                        <span class="post-time">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Modal Tạo bài viết mới --}}
   {{-- Modal Tạo bài viết mới --}}
<div class="modal" id="postModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tạo bài viết mới</h3>
            <span class="close-btn" style="position: absolute; right: 15px; top: 12px; cursor: pointer; font-size: 20px;" onclick="document.getElementById('postModal').style.display='none'">&times;</span>
        </div>

        <form action="{{ route('social.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body" style="padding: 15px;">
                <textarea name="content" placeholder="Viết chú thích..." style="width: 100%; border: none; outline: none; height: 100px; resize: none; font-size: 15px;" required></textarea>

                <div class="upload-section">
                    {{-- Ảnh preview hiện ở đây --}}
                    <img id="imagePreview" src="#">

                    <label for="file-upload" id="uploadPlaceholder" style="cursor: pointer;">
                        <i class="fas fa-images" style="font-size: 40px; color: #262626; margin-bottom: 10px; display: block;"></i>
                        <span style="color: #0095f6; font-weight: bold;">Chọn từ máy tính</span>
                    </label>
                    <input type="file" name="image" id="file-upload" hidden onchange="previewImage(event)">
                </div>
            </div>

            {{-- Nút nằm sát đáy Modal --}}
            <button type="submit" class="submit-post-btn">Chia sẻ</button>
        </form>
    </div>
</div>


    <script src="{{ asset('js/social.js') }}"></script>


    <script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('imagePreview');
            const placeholder = document.getElementById('uploadPlaceholder');
            output.src = reader.result;
            output.style.display = "block";
            placeholder.style.display = "none";
        };
        reader.readAsDataURL(event.target.files[0]);
    }
    </script>
</body>
</html>
