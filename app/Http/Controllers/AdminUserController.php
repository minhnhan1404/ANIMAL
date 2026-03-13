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

public function deleteUser($id) {
    $user = User::findOrFail($id);

    if (Auth::id() == $user->id) {
        return back()->with('error', 'Bạn không thể tự xóa tài khoản của chính mình!');
    }

    $user->delete();
    return back()->with('success', 'Đã xóa người dùng thành công!');
}
}
