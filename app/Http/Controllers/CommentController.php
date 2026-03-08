<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        // 1. Cập nhật mảng từ cấm "thật" (Thêm các từ Nhan muốn chặn vào đây)
        $badWords = [
    'đụ', 'mẹ', 'mày', 'tao', 'chửi', 'vô văn hóa', 'ngu', 'cút', 'đếch', 'đéo',
    'giết', 'thịt', 'ăn thịt', 'săn bắn', 'bắn chết', 'ngược đãi', 'hành hạ',
    'đánh đập', 'buôn bán lậu', 'tận diệt', 'bẫy', 'kích điện', 'lột da',
    'đâm', 'chém', 'đốt', 'phóng hỏa', 'bạo lực', 'tàn sát', 'thảm sát'
];

        $content = $request->content;

        // Kiểm tra từng từ trong danh sách cấm
        foreach ($badWords as $word) {
            if (str_contains(mb_strtolower($content), $word)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không được bình luận những từ ngữ thô tục!'
                ], 400);
            }
        }

        // 2. Lưu vào Database nếu nội dung sạch sẽ
        DB::table('comments')->insert([
            'user_id' => Auth::id(),
            'post_id' => $request->post_id,
            'content' => $content,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Bình luận thành công!'
        ]);
    }
}
