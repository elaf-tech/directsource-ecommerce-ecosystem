<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
    }

    public function askChatGPT(string $prompt): string
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo', // أو gpt-4 لو عندك صلاحية
            'messages' => [
                ['role' => 'system', 'content' => 'أنت مساعد ذكي ومتعاون يتحدث بالعربية.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
        ]);

        if ($response->failed()) {
            throw new \Exception("OpenAI API Error: " . $response->body());
        }

        return $response->json()['choices'][0]['message']['content'] ?? 'لا يوجد رد';
    }
}
