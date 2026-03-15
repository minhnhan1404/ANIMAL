<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Giữ nguyên dòng này

class ProfileController extends Controller
{
    // Hiển thị trang chỉnh sửa hồ sơ
    public function edit()
    {
        $user = Auth::user();

        // SỬA: Bỏ dấu \ ở trước DB vì đã có 'use' ở trên đầu file
        $posts = DB::table('posts')->where('user_id', $user->id)->latest()->get();

        return view('profile.edit', compact('user', 'posts'));
    }

    // Xử lý cập nhật thông tin (Avatar & Mật khẩu)
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Xử lý lưu Avatar vào thư mục uploads/avatars
        $avatarPath = $user->avatar; // Giữ lại avatar cũ nếu không up mới
        if ($request->hasFile('avatar')) {
            $imageName = time().'.'.$request->avatar->extension();
            $request->avatar->move(public_path('uploads/avatars'), $imageName);
            $avatarPath = 'uploads/avatars/' . $imageName;
        }

        // Xử lý mật khẩu
        $newPassword = $user->password;
        if ($request->password) {
            $newPassword = Hash::make($request->password);
        }

        // SỬA: Dùng trực tiếp DB Builder cho đồng bộ
        DB::table('users')->where('id', $user->id)->update([
            'name' => $request->name,
            'avatar' => $avatarPath,
            'password' => $newPassword,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật thông tin thành công!');
    }
}
                    