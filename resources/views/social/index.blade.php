<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="user-id" content="{{ Auth::id() }}">
    <title>Social Animal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/social.css') }}">
</head>
<body>
    {{-- SIDEBAR --}}
    <div class="sidebar">
        <div class="sidebar-logo"><h2>Animal</h2></div>
        <nav class="sidebar-nav">
            <a href="{{ url('/') }}"><i class="fas fa-home"></i> <span>Trang chủ</span></a>
            <a href="{{ route('ai.nhandien') }}" class="btn-ai-hero"><i class="fas fa-compass"></i> <span>Khám phá</span></a>
            <a href="{{ route('profile.edit') }}"><i class="fas fa-user-edit"></i> <span>Hồ sơ</span></a>
            <a href="#" onclick="document.getElementById('postModal').style.display='flex'"><i class="far fa-plus-square"></i> <span>Tạo</span></a>
        </nav>
    </div>

    {{-- NỘI DUNG CHÍNH --}}
    <div class="instagram-container">
        @auth
            <div class="create-post-card" onclick="document.getElementById('postModal').style.display='flex'">
                <img src="{{ asset(Auth::user()->avatar ?? 'images/default-avatar.png') }}" class="avatar-small">
                <div class="open-modal-btn">{{ Auth::user()->name }} ơi, bạn đang nghĩ gì thế?</div>
            </div>
        @else
            <div class="create-post-card">
                <a href="{{ route('login') }}" class="open-modal-btn" style="text-decoration: none; color: #0095f6; font-weight: bold; width: 100%; text-align: center;">Đăng nhập để chia sẻ...</a>
            </div>
        @endauth

        <div class="post-feed">
            @foreach($posts as $post)
            <div class="post-card" data-id="{{ $post->id }}">
                <div class="ins-header">
                    <img src="{{ asset($post->user_avatar ?? 'images/default-avatar.png') }}" class="avatar-small">
                    <span class="username">{{ $post->user_name }}</span>
                </div>
                <div class="post-image-container">
                    @if($post->image_url)
                        <img src="{{ asset($post->image_url) }}" class="post-image">
                    @endif
                    <div class="big-heart-overlay" id="heart-{{ $post->id }}"><i class="fas fa-heart"></i></div>
                </div>
                <div class="ins-footer">
                    <div class="post-actions">
                        <button class="action-btn-ins" onclick="handleLike({{ $post->id }})"><i class="far fa-heart fa-lg"></i></button>
                        <button class="action-btn-ins" onclick="openCommentModal({{ $post->id }}, '{{ asset($post->image_url) }}', '{{ $post->user_name }}', '{{ asset($post->user_avatar ?? 'images/default-avatar.png') }}')"><i class="far fa-comment fa-lg"></i></button>
                    </div>
                    <div class="ins-content">
                        <p><strong id="likes-count-{{ $post->id }}">{{ $post->likes_count ?? 0 }}</strong> lượt thích</p>
                        <p><strong>{{ $post->user_name }}</strong> {{ $post->content }}</p>
                        <span class="post-time">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- MODAL BÌNH LUẬN --}}
    <div class="modal" id="commentModal">
        <div class="modal-content" style="max-width: 900px; display: flex; height: 600px; padding: 0; flex-direction: row;">
            <div style="flex: 1.2; background: #000; display: flex; align-items: center; justify-content: center;">
                <img id="modalPostImage" src="" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>
            <div style="flex: 0.8; display: flex; flex-direction: column; background: #fff;">
                <div style="padding: 15px; border-bottom: 1px solid #efefef; display: flex; align-items: center; gap: 10px;">
                    <img id="modalUserAvatar" src="" class="avatar-small">
                    <strong id="modalUserName"></strong>
                    <span style="margin-left: auto; cursor: pointer; font-size: 24px;" onclick="document.getElementById('commentModal').style.display='none'">&times;</span>
                </div>
                <div id="modalCommentList" style="flex: 1; overflow-y: auto; padding: 15px;"></div>
                <div style="padding: 15px; border-top: 1px solid #efefef;">
                    <form id="commentForm" onsubmit="submitComment(event)">
                        <input type="hidden" id="modalPostId">
                        <div style="display: flex; gap: 10px;">
                            <input type="text" id="commentInput" placeholder="Thêm bình luận..." style="flex: 1; border: none; outline: none;" required>
                            <button type="submit" style="background: none; border: none; color: #0095f6; font-weight: 600; cursor: pointer;">Đăng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ĐĂNG BÀI --}}
    <div class="modal" id="postModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tạo bài viết mới</h3>
                <span class="close-btn" onclick="document.getElementById('postModal').style.display='none'">&times;</span>
            </div>
            <form action="{{ route('social.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <textarea name="content" placeholder="Viết chú thích..." required></textarea>
                    <div class="upload-section">
                        <input type="file" name="image" required>
                    </div>
                </div>
                <button type="submit" class="submit-post-btn">Chia sẻ</button>
            </form>
        </div>
    </div>

    {{-- CÁC THÔNG BÁO VÀ CONFIRM (QUAN TRỌNG: ĐỂ DƯỚI CÙNG) --}}
    <div id="customConfirm" class="modal" style="display: none; align-items: center; justify-content: center; background: rgba(0,0,0,0.6); z-index: 100000;">
        <div style="background: white; border-radius: 12px; width: 260px; text-align: center; overflow: hidden;">
            <div style="padding: 20px;">
                <h3 style="margin: 0 0 10px; font-size: 18px;">Xóa bình luận?</h3>
                <button id="btnDeleteConfirm" onclick="executeDelete()" style="width: 100%; padding: 10px; color: red; border: none; background: none; font-weight: bold; cursor: pointer;">Xóa</button>
                <button onclick="document.getElementById('customConfirm').style.display='none'" style="width: 100%; padding: 10px; border: none; background: none; cursor: pointer;">Hủy</button>
            </div>
        </div>
    </div>

    {{-- THÔNG BÁO ĐỎ PHẢI NẰM ĐÂY --}}
    <div id="auth-toast" style="position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%); z-index: 999999 !important; display: none;"></div>

    <script src="{{ asset('js/social.js') }}"></script>
    <script>
        window.addEventListener('pageshow', (event) => {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>
