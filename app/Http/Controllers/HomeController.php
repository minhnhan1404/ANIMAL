<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ với bộ lọc: Danh mục, Chế độ ăn và BỘ (Order)
     */
    public function index(Request $request)
    {
        // 1. Lấy tất cả các tham số lọc từ URL
        $category = $request->query('category');
        $diet     = $request->query('diet');
        $order    = $request->query('order'); // <--- THÊM DÒNG NÀY ĐỂ LỌC THEO BỘ
        $search   = $request->query('search');

        $query = DB::table('animals');

        // 2. Lọc theo Danh mục (Thú, Bò sát...)
        if ($category && $category !== 'Tất cả') {
            $query->where('category', $category);
        }

        // 3. Lọc theo Chế độ ăn
        if ($diet) {
            $query->where('diet_type', $diet);
        }

        // 4. Lọc theo BỘ (Rùa, Cá sấu, Ăn thịt, Vòi...)
        // Đây chính là chỗ ông đang thiếu khiến nó không lọc được!
        if ($order) {
            $query->where('animal_order', $order);
        }

        // 5. Tìm kiếm từ khóa
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('animal_class', 'LIKE', "%$search%")
                  ->orWhere('animal_order', 'LIKE', "%$search%")
                  ->orWhere('scientific_name', 'LIKE', "%$search%");
            });
        }

        // 6. Sắp xếp: Nhiều tim hiện trước, mới đăng hiện sau
        $animals = $query->orderBy('likes_count', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->get();

        return view('home', compact('animals'));
    }

    /**
     * Xử lý Thích/Hủy thích
     */
    public function likeAnimal($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thả tim!');
        }

        $userId = Auth::id();
        $existingLike = DB::table('likes')
            ->where('user_id', $userId)
            ->where('animal_id', $id)
            ->first();

        if ($existingLike) {
            DB::table('likes')->where('id', $existingLike->id)->delete();
            DB::table('animals')->where('id', $id)->decrement('likes_count');
            $message = 'Đã hủy yêu thích.';
        } else {
            DB::table('likes')->insert([
                'user_id' => $userId,
                'animal_id' => $id,
                'created_at' => now()
            ]);
            DB::table('animals')->where('id', $id)->increment('likes_count');
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
            ->limit(5)
            ->pluck('name');

        return response()->json($suggestions);
    }
}
