<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    protected $apiKey;
    protected $apiUrl;
    protected $model;

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
        $this->apiUrl = config('services.groq.api_url');
        $this->model = config('services.groq.model');

        if (!$this->apiKey) {
            throw new \Exception('Groq API key is not set in your environment file.');
        }
    }

    public function ask(string $prompt): ?string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content');
            }

            Log::error('Groq API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return "عذراً، حدث خطأ أثناء الاتصال بالخدمة الذكية.";

        } catch (\Exception $e) {
            Log::error('Exception caught in GroqService', [
                'message' => $e->getMessage(),
            ]);
            return "عذراً، حدث خطأ فني. يرجى المحاولة لاحقاً.";
        }
    }

    /**
     *  الجديد: تم وضع هذه الدالة داخل الكلاس
     */
    public function generateProductDescription(string $productName, string $categoryName): ?string
    {
        $prompt = "أنا أبيع منتجاً في متجر زراعي. اسم المنتج هو '{$productName}' وهو ينتمي إلى فئة '{$categoryName}'.
                   اكتب لي وصفاً تسويقياً جذاباً ومختصراً لهذا المنتج باللغة العربية، لا يتجاوز 50 كلمة.
                   ركز على الفوائد التي سيحصل عليها العميل. لا تضف أي عناوين أو مقدمات، فقط الوصف مباشرة.";

        // استدعاء دالة ask الأساسية
        return $this->ask($prompt);
    }
public function getIntelligentSearchResults(string $searchTerm, array $availableCategories): array
    {
        // تحويل مصفوفة الفئات إلى نص ليفهمه النموذج
        $categoriesList = implode(', ', $availableCategories);

        $prompt = "أنا مساعد بحث في متجر زراعي. المستخدم بحث عن '{$searchTerm}'.
                   مهمتي هي تحليل هذا البحث حتى لو كان به أخطاء إملائية أو كان عاماً جداً.
                   الفئات المتوفرة في متجري هي: [{$categoriesList}].

                   قم بالآتي:
                   1. صحح أي أخطاء إملائية في بحث المستخدم.
                   2. استخرج الكلمات المفتاحية الرئيسية من البحث المصحح.
                   3. إذا كان البحث يطابق إحدى الفئات، أرجع اسم الفئة.
                   4. إذا كان البحث عن منتج معين، أرجع اسم المنتج.

                   أرجع لي قائمة بالكلمات المفتاحية المقترحة للبحث في قاعدة البيانات، مفصولة بفاصلة فقط، بدون أي شرح إضافي.
                   مثال 1: لو المستخدم بحث عن 'صماد حشري'، يجب أن ترجع: سماد, حشري
                   مثال 2: لو المستخدم بحث عن 'افضل شتلات الطماطم', يجب أن ترجع: شتلات, طماطم
                   مثال 3: لو المستخدم بحث عن 'مبيدات', يجب أن ترجع: مبيدات";

        $response = $this->ask($prompt);

        if ($response) {
            // تنظيف الرد وتحويله إلى مصفوفة من الكلمات المفتاحية
            $keywords = array_map('trim', explode(',', $response));
            return array_filter($keywords); // إزالة أي عناصر فارغة
        }

        // في حال فشل النموذج، نرجع الكلمة الأصلية للبحث كحل احتياطي
        return [$searchTerm];
    }
} // <-- القوس النهائي للكلاس
