<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
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

    // --- HÀM LƯU BÌNH LUẬN: ĐÃ THÊM BỘ LỌC TỪ CẤM ---
    public function storeComment(Request $request, $postId)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập!'], 401);
        }

        $request->validate(['content' => 'required']);

        // Danh sách từ cấm "thật" của Nhan
        $badWords = [
            'đụ', 'mẹ', 'mày', 'tao', 'chửi', 'vô văn hóa', 'ngu', 'cút', 'đếch', 'đéo',
            'giết', 'thịt', 'ăn thịt', 'săn bắn', 'bắn chết', 'ngược đãi', 'hành hạ',
            'đánh đập', 'buôn bán lậu', 'tận diệt', 'bẫy', 'kích điện', 'lột da',
            'đâm', 'chém', 'đốt', 'phóng hỏa', 'bạo lực', 'tàn sát', 'thảm sát'
        ];

        $content = $request->content;

        foreach ($badWords as $word) {
            if (str_contains(mb_strtolower($content), $word)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ko được bình luận những từ ngữ thô tục hoặc vi phạm chính sách bảo vệ động vật!'
                ], 422); // Trả về mã lỗi 422 để JS bắt được
            }
        }

        $id = DB::table('comments')->insertGetId([
            'user_id' => Auth::id(),
            'post_id' => $postId,
            'content' => $content,
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

    public function deleteComment($id)
    {
        $comment = DB::table('comments')->where('id', $id)->first();

        if ($comment) {
            if ($comment->user_id == Auth::id()) {
                DB::table('comments')->where('id', $id)->delete();
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền xóa!'], 403);
        }
        return response()->json(['success' => false, 'message' => 'Không tìm thấy CMT'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;
        $fileHash = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileHash = md5_file($image->getRealPath());

            $isDuplicate = DB::table('posts')
                ->where('user_id', Auth::id())
                ->where('image_hash', $fileHash)
                ->exists();

            if ($isDuplicate) {
                return redirect()->back()->with('error', 'Ảnh này bạn đã đăng rồi, đừng spam nhé!');
            }

            $imageName = time().'.'.$image->extension();
            $image->move(public_path('uploads/posts'), $imageName);
            $imagePath = 'uploads/posts/' . $imageName;
        }

        DB::table('posts')->insert([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'image_url' => $imagePath,
            'image_hash' => $fileHash,
            'status' => 1,
            'likes_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Đăng bài thành công!');
    }

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
