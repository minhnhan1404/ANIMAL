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
            ->where('posts.status', 1)
            ->select('posts.*', 'users.name as user_name')
            ->orderBy('posts.created_at', 'desc')
            ->get();

        return view('social.index', compact('posts'));
    }

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
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Bài viết đang chờ Admin phê duyệt!');
    }
    public function toggleLike(Request $request)
{
    $postId = $request->post_id;
    $userId = Auth::id();

    // Kiểm tra xem đã thả tim chưa
    $like = DB::table('reactions') // Hoặc bảng likes tùy Nhan đặt tên
        ->where('user_id', $userId)
        ->where('post_id', $postId)
        ->first();

    if ($like) {
        // Nếu có rồi thì bỏ tim (Unlike)
        DB::table('reactions')
            ->where('user_id', $userId)
            ->where('post_id', $postId)
            ->delete();
        return response()->json(['action' => 'unliked']);
    } else {
        // Nếu chưa có thì thêm tim (Like)
        DB::table('reactions')->insert([
            'user_id' => $userId,
            'post_id' => $postId,
            'type' => 'like', // Chỉ một loại duy nhất là like
            'created_at' => now()
        ]);
        return response()->json(['action' => 'liked']);
    }
}
}
