<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; // Thêm dòng này để xóa file cũ

class ProfileController extends Controller
{
    // Hiển thị trang chỉnh sửa hồ sơ
    public function edit()
    {
        $user = Auth::user();
        $posts = DB::table('posts')->where('user_id', $user->id)->latest()->get();
        return view('profile.edit', compact('user', 'posts'));
    }

    // Xử lý cập nhật thông tin (Avatar & Mật khẩu)
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // 1. Khởi tạo mảng dữ liệu cập nhật
        $updateData = [
            'name' => $request->name,
            'updated_at' => now(),
        ];

        // 2. Xử lý Avatar
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ trong thư mục nếu có (để tránh rác server)
            if ($user->avatar && File::exists(public_path($user->avatar))) {
                File::delete(public_path($user->avatar));
            }

            // Tạo tên file duy nhất
            $imageName = time() . '.' . $request->avatar->extension();

            // Di chuyển file vào public/uploads/avatars
            $request->avatar->move(public_path('uploads/avatars'), $imageName);

            // Lưu đường dẫn vào mảng update
            $updateData['avatar'] = 'uploads/avatars/' . $imageName;
        }

        // 3. Xử lý Mật khẩu
        if ($request->password) {
            $updateData['password'] = Hash::make($request->password);
        }

        // 4. Thực hiện Update vào Database
        DB::table('users')->where('id', $user->id)->update($updateData);

        return redirect()->back()->with('success', 'Đã cập nhật thông tin thành công! 🐾');
    }
}
