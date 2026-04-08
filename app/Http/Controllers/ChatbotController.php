<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $userMsg = $request->input('message');

        // 1. TÌM TRONG DATABASE CỦA WEBSITE TRƯỚC
        $cleanMsg = preg_replace('/[^\p{L}\p{N}\s]/u', '', mb_strtolower($userMsg, 'UTF-8'));
        $words = explode(' ', $cleanMsg);
        $stopWords = ['con', 'loài', 'là', 'gì', 'hỏi', 'về', 'thế', 'nào', 'có', 'biết', 'không', 'cho', 'tôi', 'cái', 'tên', 'chi', 'tiết', 'thông', 'tin', 'động', 'vật', 'xin', 'chào', 'bạn', 'ơi', 'những', 'các', 'một'];
        
        $searchTerms = array_filter($words, function($word) use ($stopWords) {
            return mb_strlen($word) >= 2 && !in_array($word, $stopWords);
        });

        if (!empty($searchTerms)) {
            $query = \DB::table('animals');
            foreach ($searchTerms as $term) {
                $query->orWhere('name', 'LIKE', '%' . $term . '%')
                      ->orWhere('scientific_name', 'LIKE', '%' . $term . '%');
            }
            
            $matches = $query->get();
            
            if ($matches->isNotEmpty()) {
                // Ưu tiên tìm chính gốc (Original match)
                $bestMatch = $matches->first();
                foreach ($matches as $animal) {
                    $nameLower = mb_strtolower($animal->name, 'UTF-8');
                    if (strpos($cleanMsg, $nameLower) !== false) {
                        $bestMatch = $animal;
                        break;
                    }
                }
                $reply = "";
                
                if (!empty($bestMatch->image)) {
                    $imagePath = asset(ltrim($bestMatch->image, '/'));
                    $reply .= "<img src='{$imagePath}' style='width: 100%; border-radius: 8px; margin-bottom: 10px;'><br>";
                } elseif (!empty($bestMatch->image_url)) {
                    $imagePath = asset(ltrim($bestMatch->image_url, '/'));
                    $reply .= "<img src='{$imagePath}' style='width: 100%; border-radius: 8px; margin-bottom: 10px;'><br>";
                }
                
                $reply .= "<strong>{$bestMatch->name}</strong> <em>({$bestMatch->scientific_name})</em><br><br>";
                
                $metaFields = [
                    'category' => 'Danh mục',
                    'animal_class' => 'Lớp',
                    'animal_order' => 'Bộ',
                    'animal_family' => 'Họ',
                    'animal_genus' => 'Chi',
                    'habitat' => 'Môi trường sống',
                    'diet_type' => 'Thức ăn',
                    'status' => 'Bảo tồn'
                ];
                
                foreach ($metaFields as $key => $label) {
                    if (!empty($bestMatch->{$key})) {
                        $reply .= "▪ <strong>{$label}:</strong> " . $bestMatch->{$key} . "<br>";
                    }
                }
                $reply .= "<br>";
                
                if (!empty($bestMatch->description)) {
                    $reply .= "<strong>📖 Mô tả:</strong><br>" . nl2br($bestMatch->description) . "<br><br>";
                }
                if (!empty($bestMatch->behavior)) {
                    $reply .= "<strong>🌿 Tập tính:</strong><br>" . nl2br($bestMatch->behavior);
                }
                
                return response()->json(['reply' => $reply], 200);
            }
        }

        // 2. NẾU KHÔNG CÓ TRONG DB, SỬ DỤNG GOOGLE/LLAMA (GROQ)
        // 🔥 ĐỔI KEY Ở ĐÂY
        $apiKey = env('GROQ_API_KEY');

        if (!$apiKey) {
            return response()->json(['reply' => 'Thiếu API key 😢'], 200);
        }

        try {
            /** @var \Illuminate\Http\Client\Response $response */
     $response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $apiKey,
    'Content-Type' => 'application/json',
])
->timeout(30)
->post('https://api.groq.com/openai/v1/chat/completions', [
    'model' => 'llama-3.1-8b-instant',
    'messages' => [
        [
            'role' => 'system',
            'content' => 'Bạn là chuyên gia về động vật. Trả lời bằng tiếng Việt tự nhiên, dễ hiểu, không dùng từ "họ" để chỉ động vật, không dịch máy. Trả lời ngắn gọn và chính xác.'
        ],
        [
            'role' => 'user',
            'content' => $userMsg
        ]
    ],
]);

            $data = $response->json();

            if (isset($data['choices'][0]['message']['content'])) {
                $botReply = $data['choices'][0]['message']['content'];
                return response()->json(['reply' => $botReply], 200);
            }

            if (isset($data['error'])) {
                return response()->json(['reply' => 'Lỗi Groq: ' . $data['error']['message']], 200);
            }

            return response()->json(['reply' => 'Không có phản hồi 😢'], 200);

        } catch (\Exception $e) {
            return response()->json(['reply' => 'Lỗi kết nối: ' . $e->getMessage()], 200);
        }
    }
}
