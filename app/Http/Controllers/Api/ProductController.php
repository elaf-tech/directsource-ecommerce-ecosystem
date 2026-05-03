<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; //

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\GroqService; // استيراد الخدمة
use App\Models\Brand;
use App\Models\Supplier;
use App\Models\Category;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // ProductController.php
    public function index(Request $request, GroqService $groq)
    {
        $searchTerm = $request->input('search');
        $alternatives = collect();

        // ابدأ ببناء استعلام فارغ
        $query = Product::query();

        if ($searchTerm) {
            // 1. ابدأ بالبحث السريع والضبابي باستخدام Scout
            $productIds = Product::search($searchTerm)->keys();

            if ($productIds->isNotEmpty()) {
                $query->whereIn('id', $productIds)
                      ->orderByRaw('FIELD(id, ' . $productIds->implode(',') . ')');
            } else {
                // 2. إذا فشل Scout، الجأ إلى Groq لفهم النية
                $categoryNames = Category::pluck('name')->toArray();
                $keywords = $groq->getIntelligentSearchResults($searchTerm, $categoryNames);
                
                $query->where(function ($q) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $q->orWhere('name', 'like', '%' . $keyword . '%')
                          ->orWhereHas('category', fn($subQ) => $subQ->where('name', 'like', '%' . $keyword . '%'));
                    }
                });
            }
        }

        // تطبيق الفلاتر التقليدية
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->input('supplier_id'));
        }

        // جلب المنتجات مع العلاقات والتصفح (Pagination)
        $products = $query->with(['category', 'brand', 'supplier.user'])->latest()->paginate(10);

        // منطق البدائل
        if ($searchTerm && $products->isEmpty()) {
            $alternatives = Product::with(['category', 'brand', 'supplier.user'])->latest()->take(4)->get();
        }

        // تنسيق البيانات للرد النهائي
        $products->getCollection()->transform(function($product) {
            return $this->formatProductForApi($product);
        });

        $alternatives = $alternatives->map(function($product) {
            return $this->formatProductForApi($product);
        });

        return response()->json([
            'success' => true,
            'products' => $products,
            'alternatives' => $alternatives,
        ]);
    }

    /**
     * دالة مساعدة لتنسيق شكل المنتج في الرد
     */
    private function formatProductForApi(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'size' => $product->size,
            'unit' => $product->unit,
            'image' => $product->image,
            'image_url' => $product->image ? asset('storage/' . $product->image) : null,
            'category_id' => $product->category_id,
            'brand_id' => $product->brand_id,
            'supplier_id' => $product->supplier_id,
            'category_name' => $product->category->name ?? null,
            'brand_name' => $product->brand->name ?? null,
            'supplier_name' => $product->supplier->company_name ?? null,
        ];
    }

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'size'        => 'nullable|string|max:50',
            'unit'        => 'nullable|string|max:50',
            'quantity'    => 'required|numeric',
            'price'       => 'required|numeric',
            'supplier_id' => 'required|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id',
            'brand_id'    => 'required|exists:brands,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // تحويل القيم العددية و IDs لضمان نوع البيانات الصحيح
        $validated['quantity']    = (int) $validated['quantity'];
        $validated['price']       = (float) $validated['price'];
        $validated['supplier_id'] = (int) $validated['supplier_id'];
        $validated['category_id'] = (int) $validated['category_id'];
        $validated['brand_id']    = (int) $validated['brand_id'];
    
        // حفظ الصورة إن وجدت
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }
    
        $product = Product::create($validated);
    
        return response()->json([
            'message' => 'تمت إضافة المنتج بنجاح',
            'product' => $product,
        ], 201);
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with(['supplier', 'category', 'brand'])->findOrFail($id);
        return response()->json($product);
    }
    public function generateDescription(Request $request, GroqService $groq)
    {
        // 1. التحقق من صحة البيانات القادمة من Flutter
        $validated = $request->validate([
            'product_name' => 'required|string|max:100',
            'category_name' => 'required|string|max:100',
        ]);

        try {
            // 2. استدعاء الدالة الموجودة في خدمتنا الذكية
            $description = $groq->generateProductDescription(
                $validated['product_name'],
                $validated['category_name']
            );

            // 3. التحقق من الرد وإرسال استجابة ناجحة
            if ($description) {
                return response()->json([
                    'success' => true,
                    'description' => $description,
                ]);
            }

            // في حال لم يرجع النموذج أي وصف
            throw new \Exception('Failed to generate description from AI service.');

        } catch (\Exception $e) {
            // 4. في حال حدوث أي خطأ، أرجع رسالة خطأ واضحة
            // يمكنك تسجيل الخطأ هنا باستخدام Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'عذراً، حدث خطأ أثناء إنشاء الوصف. يرجى المحاولة لاحقاً.',
            ], 500); // 500 تعني خطأ في الخادم
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
