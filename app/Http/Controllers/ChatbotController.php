<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $userMsg = $request->input('message');

        // Lấy API key từ .env
        $apiKey = env('OPENAI_API_KEY');

        if (!$apiKey) {
            return response()->json(['reply' => 'Thiếu API key 😢'], 200);
        }

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Bạn là trợ lý động vật của Animalia, trả lời ngắn gọn, dễ hiểu.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $userMsg
                    ]
                ],
            ]);

            $data = $response->json();

            // Lấy nội dung trả về
            if ($response->successful() && isset($data['choices'][0]['message']['content'])) {
                $botReply = $data['choices'][0]['message']['content'];
                return response()->json(['reply' => $botReply], 200);
            }

            // Bắt lỗi từ OpenAI
            if (isset($data['error'])) {
                $msg = $data['error']['message'] ?? 'Lỗi không xác định';
                return response()->json(['reply' => "Lỗi GPT: " . $msg], 200);
            }

            return response()->json(['reply' => "Bot đang bận, thử lại sau nha!"], 200);

        } catch (\Exception $e) {
            return response()->json(['reply' => "Lỗi kết nối: " . $e->getMessage()], 200);
        }
    }
}
