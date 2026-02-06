<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý bài đăng - Animalia Admin</title>

    {{-- Nhúng CSS chuẩn của Dashboard để giữ Sidebar luôn đẹp --}}
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-post.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="dashboard-container">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-paw"></i> <span>Animalia Admin</span>
        </div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Tổng quan</a></li>
            <li><a href="{{ route('admin.animals') }}"><i class="fas fa-fish"></i> Quản lý loài vật</a></li>
            <li><a href="#"><i class="fas fa-users"></i> Người dùng</a></li>
            <li class="active"><a href="{{ route('admin.post.index') }}"><i class="fas fa-clipboard-list"></i> Bài đăng</a></li>
            <li><a href="{{ url('/') }}"><i class="fas fa-home"></i> Xem trang chủ</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="container-fluid py-4">
            <h2 class="mb-4 fw-bold text-dark"><i class="fas fa-tasks"></i> Phê duyệt bài đăng người dùng</h2>

            @if(session('status_msg'))
                <div class="alert alert-success border-0 shadow-sm mb-4 p-3" style="border-radius: 10px;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('status_msg') }}
                </div>
            @endif

            <div class="post-table-card shadow-sm border-0 bg-white p-4" style="border-radius: 15px;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr class="text-secondary">
                                <th class="ps-3">Người đăng</th>
                                <th>Nội dung</th>
                                <th>Hình ảnh</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingPosts as $post)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-bold text-primary">{{ $post->user_name }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ Str::limit($post->content, 60) }}</span>
                                </td>
                                <td>
                                    @if($post->image_url)
                                        <img src="{{ asset($post->image_url) }}" class="post-img shadow-sm"
                                             style="width: 70px; height: 70px; object-fit: cover; border-radius: 10px; border: 2px solid #f8f9fa;">
                                    @else
                                        <span class="text-secondary small italic">Không có ảnh</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- Nút Chấp nhận (Accept) --}}
                                        <form action="{{ route('admin.post.update', $post->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="1">
                                            <button type="submit" class="btn btn-sm btn-success px-3 fw-bold" style="border-radius: 8px;">
                                                <i class="fas fa-check"></i> Accept
                                            </button>
                                        </form>

                                        {{-- Nút Từ chối và Xóa (Denied) --}}
                                        <form action="{{ route('admin.post.update', $post->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="2">
                                            <button type="submit" class="btn btn-sm btn-danger px-3 fw-bold" style="border-radius: 8px;">
                                                <i class="fas fa-trash-alt"></i> Denied
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>
