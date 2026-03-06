<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    /**
     * Hiển thị danh sách bài viết kèm bình luận mới nhất
     */
    public function index()
    {
        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->leftJoin(DB::raw('(SELECT c1.post_id, c1.content as latest_comment_content, u.name as latest_comment_user
                                FROM comments c1
                                JOIN users u ON c1.user_id = u.id
                                WHERE c1.id = (SELECT MAX(id) FROM comments c2 WHERE c2.post_id = c1.post_id)
                               ) as latest_cmt'), 'posts.id', '=', 'latest_cmt.post_id')
            ->where('posts.status', 1)
            ->select(
                'posts.*',
                'users.name as user_name',
                'users.avatar as user_avatar',
                'latest_cmt.latest_comment_content',
                'latest_cmt.latest_comment_user'
            )
            ->orderBy('posts.created_at', 'desc')
            ->get();

        return view('social.index', compact('posts'));
    }

    /**
     * Lấy danh sách bình luận (Dùng cho Modal)
     */
    public function getComments($postId)
    {
        $comments = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->where('comments.post_id', $postId)
            ->select('comments.*', 'users.name as user_name', 'users.avatar as user_avatar')
            ->orderBy('comments.created_at', 'asc')
            ->get();

        return response()->json(['comments' => $comments]);
    }

    /**
     * Lưu bình luận mới
     */
    public function storeComment(Request $request, $postId)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        $request->validate(['content' => 'required']);

        // Dùng insertGetId để lấy ID phục vụ nút xóa ngay sau khi đăng
        $id = DB::table('comments')->insertGetId([
            'user_id' => Auth::id(),
            'post_id' => $postId,
            'content' => $request->content,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'user_name' => Auth::user()->name,
            'user_avatar' => asset(Auth::user()->avatar ?? 'images/default-avatar.png'),
            'comment_id' => $id
        ]);
    }

    /**
     * Xóa bình luận (Đã thêm log kiểm tra ID)
     */
    public function deleteComment($id)
{
    $comment = DB::table('comments')->where('id', $id)->first();

    if ($comment) {
        // KIỂM TRA: Chỉ cho xóa nếu đúng chủ nhân CMT
        if ($comment->user_id == Auth::id()) {
            DB::table('comments')->where('id', $id)->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Bạn không có quyền xóa!'], 403);
    }
    return response()->json(['success' => false, 'message' => 'Không tìm thấy CMT'], 404);
}
    /**
     * Xử lý đăng bài mới
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
            'status' => 1,
            'likes_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Đăng bài thành công!');
    }

    /**
     * Xử lý Like
     */
    public function like($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        $userId = Auth::id();
        $like = DB::table('likes')->where('user_id', $userId)->where('post_id', $id)->first();

        if ($like) {
            DB::table('likes')->where('user_id', $userId)->where('post_id', $id)->delete();
            $action = 'unliked';
        } else {
            DB::table('likes')->insert([
                'user_id' => $userId,
                'post_id' => $id,
                'created_at' => now()
            ]);
            $action = 'liked';
        }

        $newLikesCount = DB::table('likes')->where('post_id', $id)->count();
        DB::table('posts')->where('id', $id)->update(['likes_count' => $newLikesCount]);

        return response()->json([
            'success' => true,
            'action' => $action,
            'new_likes' => $newLikesCount
        ]);
    }
}
