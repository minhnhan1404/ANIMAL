<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    /**
     * Hiển thị danh sách bài viết trên trang mạng xã hội
     */
    public function index()
    {
        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->where('posts.status', 1)
            // Lấy đúng cột likes_count để hiển thị số tim
            ->select('posts.*', 'users.name as user_name', 'users.avatar as user_avatar', 'posts.likes_count')
            ->orderBy('posts.created_at', 'desc')
            ->get();

        return view('social.index', compact('posts'));
    }

    /**
     * Xử lý đăng bài viết mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/posts'), $imageName);
            $imagePath = 'uploads/posts/' . $imageName;
        }

        DB::table('posts')->insert([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'image_url' => $imagePath,
            'status' => 0, // Chờ Admin phê duyệt
            'likes_count' => 0, // Bài mới mặc định 0 like
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Bài viết đang chờ Admin phê duyệt!');
    }

    /**
     * Xử lý thả tim (Like/Unlike) bài viết
     */
    public function like($id)
    {
        // 1. Chặn khách vãng lai để tránh lỗi 500
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập!'
            ], 401);
        }

        $userId = Auth::id();
        $postId = $id;

        // 2. Chỉ kiểm tra user_id và post_id trong bảng likes
        $like = DB::table('likes')
            ->where('user_id', $userId)
            ->where('post_id', $postId)
            ->first();

        if ($like) {
            // Đã like rồi thì xóa (Unlike)
            DB::table('likes')
                ->where('user_id', $userId)
                ->where('post_id', $postId)
                ->delete();
            $action = 'unliked';
        } else {
            // Chưa có thì thêm mới (Bỏ qua type và animal_id theo ý Nhan)
            DB::table('likes')->insert([
                'user_id' => $userId,
                'post_id' => $postId,
                'created_at' => now()
            ]);
            $action = 'liked';
        }

        // 3. Đếm lại tổng số tim từ bảng likes
        $newLikesCount = DB::table('likes')->where('post_id', $postId)->count();

        // 4. Cập nhật con số tổng vào bảng posts để hiển thị nhanh
        DB::table('posts')->where('id', $postId)->update([
            'likes_count' => $newLikesCount,
            'updated_at' => now()
        ]);

        // 5. Trả số liệu về cho JavaScript nhảy số trên màn hình
        return response()->json([
            'success' => true,
            'action' => $action,
            'new_likes' => $newLikesCount
        ]);
    }
}
