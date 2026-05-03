<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OpenAIService;

class OpenAIController extends Controller
{
    protected $openAI;

    public function __construct(OpenAIService $openAI)
    {
        $this->openAI = $openAI;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        try {
            $answer = $this->openAI->askChatGPT($request->prompt);
            return response()->json([
                'prompt' => $request->prompt,
                'answer' => $answer,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
