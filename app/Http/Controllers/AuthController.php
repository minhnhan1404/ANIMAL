<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail; // Thêm dòng này để gửi mail
use App\Models\User;
use App\Mail\ForgotPasswordMail; // Dùng lại mailable này cho lẹ

class AuthController extends Controller
{
    // XỬ LÝ ĐĂNG KÝ (Đã thêm xác thực Mail)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $code = rand(100000, 999999); // Tạo mã 6 số

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'reset_code' => $code, // Lưu mã vào cột reset_code
            'is_verified' => 0,    // Mặc định là chưa xác thực
        ]);

        // Gửi Mail ngay lập tức
        Mail::to($user->email)->send(new ForgotPasswordMail($code, $user->name));

        // Trả về trang cũ và báo cho Blade hiện Form nhập mã OTP
        return back()->with([
            'success' => 'Mã xác thực đã gửi vào Gmail của ông! 🐾',
            'show_verify_form' => true,
            'verify_email' => $user->email
        ]);
    }

    // XỬ LÝ ĐĂNG NHẬP (Đã thêm chặn nếu chưa xác thực)
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Tìm user trước để kiểm tra trạng thái xác thực
        $user = User::where('email', $request->email)->first();

        if ($user && Auth::attempt($credentials)) {
            // Kiểm tra xem đã xác thực chưa
            if ($user->is_verified == 0) {
                Auth::logout(); // Đăng xuất ngay nếu chưa xác thực
                return back()->with([
                    'error' => 'Tài khoản chưa xác thực Gmail! Vui lòng nhập mã để kích hoạt.',
                    'show_verify_form' => true,
                    'verify_email' => $user->email
                ]);
            }

            $request->session()->regenerate();

            if (Auth::user()->role == 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/');
        }

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
