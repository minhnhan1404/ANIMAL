<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // KHAI BÁO THƯ VIỆN AUTH (Rất quan trọng)

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ với bộ lọc và sắp xếp theo độ HOT
     */
    public function index(Request $request)
    {
        $category = $request->query('category');
        $diet = $request->query('diet');
        $search = $request->query('search');

        $query = DB::table('animals');

        if ($category && $category !== 'Tất cả') {
            $query->where('category', $category);
        }

        if ($diet) {
            $query->where('diet_type', $diet);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('animal_class', 'LIKE', "%$search%")
                  ->orWhere('animal_order', 'LIKE', "%$search%")
                  ->orWhere('animal_family', 'LIKE', "%$search%")
                  ->orWhere('scientific_name', 'LIKE', "%$search%");
            });
        }

        // Ưu tiên loài nhiều Tim đứng đầu
        $animals = $query->orderBy('likes_count', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->get();

        return view('home', compact('animals'));
    }

    /**
     * Xử lý Thích/Hủy thích cho từng tài khoản
     */
    public function likeAnimal($id)
    {
        // 1. Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thả tim!');
        }

        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        // 2. Kiểm tra xem đã tồn tại lượt thích này chưa
        $existingLike = DB::table('likes')
            ->where('user_id', $userId)
            ->where('animal_id', $id)
            ->first();

        if ($existingLike) {
            // NẾU ĐÃ LIKE -> HỦY LIKE
            DB::table('likes')->where('id', $existingLike->id)->delete();
            DB::table('animals')->where('id', $id)->decrement('likes_count'); // Trừ 1 tim
            $message = 'Đã hủy yêu thích.';
        } else {
            // NẾU CHƯA LIKE -> THÊM LIKE
            DB::table('likes')->insert([
                'user_id' => $userId,
                'animal_id' => $id,
                'created_at' => now()
            ]);
            DB::table('animals')->where('id', $id)->increment('likes_count'); // Cộng 1 tim
            $message = 'Đã thêm vào yêu thích!';
        }

        return redirect()->back()->with('success', $message);
    }

    public function detail($id)
    {
        $animal = DB::table('animals')->where('id', $id)->first();
        if (!$animal) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin!');
        }
        return view('animal_detail', compact('animal'));
    }

    public function suggestions(Request $request)
    {
        $term = $request->get('term');
        $suggestions = DB::table('animals')
            ->where('name', 'LIKE', "%$term%")
            ->orWhere('animal_class', 'LIKE', "%$term%")
            ->limit(5)
            ->pluck('name');

        return response()->json($suggestions);
    }
}
