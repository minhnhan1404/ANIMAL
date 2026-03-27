<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $userMsg = $request->input('message');

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
