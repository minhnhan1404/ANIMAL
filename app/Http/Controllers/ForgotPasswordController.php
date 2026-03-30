<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm() {
        return view('auth.login');
    }

    // 1. Xử lý gửi mã Quên mật khẩu
    public function sendResetCode(Request $request) {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email này chưa gia nhập rừng xanh!');
        }

        $code = rand(100000, 999999);
        $user->update(['reset_code' => $code]);

        // SỬA: Truyền thêm chữ 'Mã xác nhận khôi phục mật khẩu' vào tham số thứ 3
        Mail::to($user->email)->send(new ForgotPasswordMail($code, $user->name, 'Mã xác nhận khôi phục mật khẩu'));

        return back()->with([
            'success' => 'Mã xác nhận đã bay thẳng vào Gmail của ông! 🐾',
            'show_reset' => true,
            'reset_email' => $user->email
        ]);
    }

    // 2. Kiểm tra mã và Cập nhật mật khẩu mới (Quên mật khẩu)
    public function resetPassword(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)
                    ->where('reset_code', $request->code)
                    ->first();

        if (!$user) {
            return back()->with([
                'error' => 'Mã xác nhận sai rồi Nhan ơi!',
                'show_reset' => true,
                'reset_email' => $request->email
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'reset_code' => null
        ]);

        return redirect()->route('login')->with('success', 'Mật khẩu đã đổi thành công! Đăng nhập thôi! 🐾');
    }

    // 3. XÁC THỰC ĐĂNG KÝ (Xử lý khi người dùng nhập mã kích hoạt)
    public function verifyRegister(Request $request) {
        $user = User::where('email', $request->email)
                    ->where('reset_code', $request->code)
                    ->first();

        if ($user) {
            $user->update([
                'is_verified' => 1,
                'reset_code' => null
            ]);
            return redirect()->route('login')->with('success', 'Tài khoản đã kích hoạt! Đăng nhập đi Nhan ơi! 🐾');
        }

        return back()->with([
            'error' => 'Mã xác thực không đúng!',
            'show_verify_form' => true,
            'verify_email' => $request->email
        ]);
    }
}
