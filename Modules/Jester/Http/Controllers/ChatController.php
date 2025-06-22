<?php

namespace Modules\Jester\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function index()
    {
        return view('jester::chat.index');
    }

    public function send(Request $request)
    {
        $input = $request->input('message');
    
        $model = 'gemini-1.5-flash-latest';
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . env('GOOGLE_AI_API_KEY');
    
        $body = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $input]
                    ],
                ]
            ],
            'generationConfig' => [
                'maxOutputTokens' => 1024,
                'temperature' => 0.4,
            ],
        ];
    
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, $body);
    
        if (!$response->successful()) {
            \Log::error('AI API Error: ' . $response->body());
            return response()->json(['reply' => 'AI API returned an error: ' . $response->body()], 500);
        }
    
        $json = $response->json();
        $reply = $json['candidates'][0]['content']['parts'][0]['text'] ?? 'Sorry, no response.';
    
        return response()->json(['reply' => $reply]);
    }        
}