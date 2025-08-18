<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function handle(Request $request)
    {
        $userMessage = $request->input('message');

        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $userMessage]
                ]
            ]);

        return response()->json([
            'reply' => $response->json()['choices'][0]['message']['content'] ?? 'No entendí eso.'
        ]);
    }
}
