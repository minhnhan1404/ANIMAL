<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Lấy ID người dùng (Dùng Facade Auth::id() để VS Code không báo lỗi đỏ)
        $userId = Auth::id();

        // 2. Lấy các tham số lọc từ URL
        $category = $request->query('category');
        $diet     = $request->query('diet');
        $order    = $request->query('order');
        $search   = $request->query('search');

        // 3. Khởi tạo Query builder
        $query = DB::table('animals');

        // 4. Thực hiện các bộ lọc (Filter)
        if ($category && $category !== 'Tất cả') {
            $query->where('category', $category);
        }

        if ($diet) {
            $query->where('diet_type', $diet);
        }

        if ($order) {
            $query->where('animal_order', $order);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('animal_class', 'LIKE', "%$search%")
                  ->orWhere('animal_order', 'LIKE', "%$search%")
                  ->orWhere('scientific_name', 'LIKE', "%$search%");
            });
        }

        // 5. Lấy danh sách kết quả (Sắp xếp theo Tim nhiều nhất và mới nhất)
        $animals = $query->orderBy('likes_count', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->get();

        // 6. XỬ LÝ TRẠNG THÁI THẢ TIM (Vòng lặp này phải nằm SAU khi đã lấy được danh sách $animals)
        foreach ($animals as $animal) {
            if ($userId) {
                $animal->is_liked = DB::table('likes')
                    ->where('user_id', $userId)
                    ->where('animal_id', $animal->id)
                    ->exists();
            } else {
                $animal->is_liked = false;
            }
        }

        // 7. Trả về view duy nhất ở cuối hàm
        return view('home', compact('animals'));
    }

    public function likeAnimal($id)
{
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $userId = Auth::id();
    $existingLike = DB::table('likes')
        ->where('user_id', $userId)
        ->where('animal_id', $id)
        ->first();

    if ($existingLike) {
        // Nếu đã like rồi thì xóa (Unlike)
        DB::table('likes')->where('id', $existingLike->id)->delete();
        DB::table('animals')->where('id', $id)->decrement('likes_count');
        $status = 'unliked';
    } else {
        // Nếu chưa like thì thêm mới (Like)
        DB::table('likes')->insert([
            'user_id' => $userId,
            'animal_id' => $id,
            'type' => 'like',
            'created_at' => now()
        ]);
        DB::table('animals')->where('id', $id)->increment('likes_count');
        $status = 'liked';
    }

    // Lấy số lượng like mới nhất để gửi về cho JS
    $newCount = DB::table('animals')->where('id', $id)->value('likes_count');

    return response()->json([
        'status' => $status,
        'new_count' => $newCount
    ]);
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
