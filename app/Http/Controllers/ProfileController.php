<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    // Hiển thị trang chỉnh sửa hồ sơ
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
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

        $user->name = $request->name;

        // Xử lý lưu Avatar vào thư mục uploads/avatars
        if ($request->hasFile('avatar')) {
            $imageName = time().'.'.$request->avatar->extension();
            $request->avatar->move(public_path('uploads/avatars'), $imageName);
            $user->avatar = 'uploads/avatars/' . $imageName;
        }

        // Nếu Nhan có nhập mật khẩu mới thì mới đổi
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // Lưu trực tiếp bằng Query Builder vì Nhan đang dùng DB
        \DB::table('users')->where('id', $user->id)->update([
            'name' => $user->name,
            'avatar' => $user->avatar,
            'password' => $user->password,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật thông tin thành công!');
    }
}
