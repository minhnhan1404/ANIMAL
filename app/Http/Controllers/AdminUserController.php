<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function indexUser() {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function updateUserRole(Request $request, $id) {
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();
        return back()->with('success', 'Đã cập nhật quyền thành công!');
    }

    public function storeUser(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:user,admin'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'is_verified' => 1 // Account created by admin is considered verified
        ]);

        return back()->with('success', 'Đã thêm tài khoản mới thành công!');
    }

public function deleteUser($id) {
    $user = User::findOrFail($id);

    if (Auth::id() == $user->id) {
        return back()->with('error', 'Bạn không thể tự xóa tài khoản của chính mình!');
    }

    // Xóa các bản ghi liên quan để tránh lỗi khóa ngoại (Foreign Key Constraint 1451)
    \Illuminate\Support\Facades\DB::table('comments')->where('user_id', $user->id)->delete();
    \Illuminate\Support\Facades\DB::table('likes')->where('user_id', $user->id)->delete();

    // Lấy tất cả bài viết của user này
    $postIds = \Illuminate\Support\Facades\DB::table('posts')->where('user_id', $user->id)->pluck('id');
    if ($postIds->isNotEmpty()) {
        // Xóa tất cả bình luận và lượt thích thuộc về các bài viết này trước
        \Illuminate\Support\Facades\DB::table('comments')->whereIn('post_id', $postIds)->delete();
        \Illuminate\Support\Facades\DB::table('likes')->whereIn('post_id', $postIds)->delete();
        // Cuối cùng mới xóa bài viết
        \Illuminate\Support\Facades\DB::table('posts')->where('user_id', $user->id)->delete();
    }

    $user->delete();
    return back()->with('success', 'Đã xóa người dùng thành công!');
}
}
