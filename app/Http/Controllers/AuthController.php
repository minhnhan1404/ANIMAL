<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // XỬ LÝ ĐĂNG KÝ
    public function register(Request $request)
    {
        // 1. Kiểm tra dữ liệu (Validation)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users', // Kiểm tra email không được trùng trong localhost
            'password' => 'required|min:6',
        ]);

        // 2. Tự động thêm dữ liệu vào bảng 'users' trong localhost
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Mã hóa mật khẩu trước khi lưu
            'role' => 'user', // Gán quyền mặc định là user theo file SQL của bạn
        ]);

        // 3. Đăng nhập ngay sau khi tạo tài khoản thành công
        Auth::login($user);

        // 4. Chuyển hướng về trang chủ
        return redirect()->route('home')->with('success', 'Đăng ký tài khoản thành công!');
    }

    // XỬ LÝ ĐĂNG NHẬP
    public function login(Request $request)
    {
        // Lấy email và mật khẩu từ form
        $credentials = $request->only('email', 'password');

        // Kiểm tra xem thông tin có khớp với dữ liệu trong localhost không
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Phân quyền dựa trên cột 'role' trong database
            if (Auth::user()->role == 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/'); // Chuyển về trang chủ nếu là user
        }

        // Nếu sai tài khoản/mật khẩu
        return back()->with('error', 'Email hoặc mật khẩu không chính xác!');
    }

    // XỬ LÝ ĐĂNG XUẤT
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
