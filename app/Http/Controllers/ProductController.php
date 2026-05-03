<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Services\OpenAIService;
use App\Services\GroqService; // <-- استيراد الخدمة

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
// public function index(Request $request)
// {
//     $searchTerm = $request->input('search');
    
//     $query = Product::query()->with(['category', 'brand', 'supplier.user']);

//     // 1. فلترة البحث باستخدام Scout/TNTSearch
//     if ($request->filled('search')) {
//         $query = Product::search($searchTerm);
//     }

//     // 2. فلترة حسب الفئة
//     if ($request->filled('category')) {
//         $query->where('category_id', $request->input('category'));
//     }

//     // 3. فلترة حسب الماركة
//     if ($request->filled('brand')) {
//         $query->where('brand_id', $request->input('brand'));
//     }

//     // 4. فلترة حسب المورد
//     if ($request->filled('supplier')) {
//         $query->where('supplier_id', $request->input('supplier'));
//     }

//     // 5. تنفيذ الاستعلام مع الترتيب والتصفح
//     $products = $query->paginate(12);

//     // 6. جلب البدائل الذكية إذا لم توجد نتائج
//     $alternatives = collect();
//     if ($request->filled('search') && $products->isEmpty()) {
//         // البدائل حسب الفئة أولاً
//         $altQuery = Product::query()->with(['category', 'brand', 'supplier.user']);
//         if ($request->filled('category')) {
//             $altQuery->where('category_id', $request->input('category'));
//         }

//         // إذا هناك نتائج، نستخدمها
//         $alternatives = $altQuery->take(6)->get();

//         // إذا لم يكفِ العدد، نضيف البحث بالكلمة المفتاحية
//         if ($alternatives->count() < 6) {
//             $needed = 6 - $alternatives->count();
//             $more = Product::search($searchTerm)
//                 ->take($needed)
//                 ->get();
//             $alternatives = $alternatives->merge($more);
//         }
//     }

//     // 7. جلب بيانات الفلاتر
//     $categories = Category::all();
//     $brands = Brand::all();
//     $suppliers = Supplier::all();

//     return view('Users.Product.index', compact('products', 'categories', 'brands', 'suppliers', 'alternatives'));
// }



//     public function index(Request $request, OpenAIService $openAI)
//     {
//         // --- بداية منطق الفلترة ---
    
//         // 1. ابدأ ببناء الاستعلام الأساسي للمنتجات مع العلاقات المطلوبة
//         $query = Product::with(['category', 'brand', 'supplier.user'])
//             ->select(['id', 'name', 'description', 'price', 'quantity', 'image', 'category_id', 'brand_id', 'supplier_id', 'created_at']);
    
//         // 2. فلترة البحث العام
//         if ($request->filled('search')) {
//             $searchTerm = $request->input('search');
//             $query->where(function($q) use ($searchTerm) {
//                 $q->where('name', 'like', '%' . $searchTerm . '%')
//                   ->orWhereHas('category', fn($subQ) => $subQ->where('name', 'like', '%' . $searchTerm . '%'))
//                   ->orWhereHas('brand', fn($subQ) => $subQ->where('name', 'like', '%' . $searchTerm . '%'))
//                   ->orWhereHas('supplier', fn($subQ) => $subQ->where('company_name', 'like', '%' . $searchTerm . '%'));
//             });
//         }
    
//         // 3. فلترة حسب الفئة المحددة
//         if ($request->filled('category')) {
//             $query->where('category_id', $request->input('category'));
//         }
    
//         // 4. فلترة حسب الماركة المحددة
//         if ($request->filled('brand')) {
//             $query->where('brand_id', $request->input('brand'));
//         }
    
//         // 5. فلترة حسب المورد المحدد
//         if ($request->filled('supplier')) {
//             $query->where('supplier_id', $request->input('supplier'));
//         }
    
//         // --- نهاية منطق الفلترة ---
    
//         // 6. تنفيذ الاستعلام مع الترتيب والتصفح
//         $products = $query->latest()->paginate(12);
    
//         // 7. تطبيق transform على النتائج بعد جلبها
//         $products->getCollection()->transform(function($product) {
//             return [
//                 'id' => $product->id,
//                 'name' => $product->name,
//                 'description' => $product->description,
//                 'price' => $product->price,
//                 'quantity' => $product->quantity,
//                 'image' => $product->image,
//                 'category' => ['name' => $product->category->name ?? 'غير محدد'],
//                 'brand' => ['name' => $product->brand->name ?? 'غير محدد'],
//                 'supplier' => ['company_name' => $product->supplier->company_name ?? 'غير محدد'],
//             ];
//         });
    
//         // 8. جلب كل البيانات اللازمة لقوائم الفلترة المنسدلة
//         $categories = Category::all();
//         $brands = Brand::all();
//         $suppliers = Supplier::all();
    
//         // 9. إرسال كل البيانات (المنتجات المفلترة + بيانات الفلاتر) إلى الواجهة
//     //  $chatResponse = null;
//     // if ($request->filled('search')) {
//     //     $searchTerm = $request->input('search');
//     //     try {
//     //         $chatResponse = $openAI->askChatGPT("المستخدم بحث عن: '$searchTerm'. أعطه إجابة مختصرة بالعربية عن هذا البحث وأقترح منتجات مشابهة إذا أمكن.");
//     //     } catch (\Exception $e) {
//     //         $chatResponse = "تعذر الحصول على رد من ChatGPT: " . $e->getMessage();
//     //     }
//     // }

//     return view('Users.Product.index', compact('products', 'categories', 'brands', 'suppliers'));
// }
 public function index(Request $request, GroqService $groq)
    {
        $searchTerm = $request->input('search');
        $alternatives = collect();

        // ابدأ ببناء استعلام فارغ
        $query = Product::query();

        if ($searchTerm) {
            // --- **الاستراتيجية الجديدة** ---
            // 1. ابدأ بالبحث السريع والضبابي باستخدام Scout
            $productIds = Product::search($searchTerm)->keys();

            if ($productIds->isNotEmpty()) {
                // إذا وجد Scout نتائج، استخدمها
                $query->whereIn('id', $productIds)
                      ->orderByRaw('FIELD(id, ' . $productIds->implode(',') . ')');
            } else {
                // 2. إذا فشل Scout، الجأ إلى Groq لفهم النية واقتراح البدائل
                $categoryNames = Category::pluck('name')->toArray();
                $keywords = $groq->getIntelligentSearchResults($searchTerm, $categoryNames);

                // ابحث عن منتجات في الفئات المقترحة
                $query->whereHas('category', function ($q) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $q->orWhere('name', 'like', '%' . $keyword . '%');
                    }
                });
            }
        }

        // تطبيق الفلاتر التقليدية (الفئة، الماركة)
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->input('brand'));
        }

        // جلب المنتجات
        $products = $query->with(['category', 'brand', 'supplier'])->latest()->paginate(12)->withQueryString();

        // منطق عرض البدائل إذا فشل البحث تماماً
        if ($searchTerm && $products->isEmpty()) {
            // كحل أخير، اعرض أحدث المنتجات
            $alternatives = Product::with(['category', 'brand', 'supplier'])->latest()->take(4)->get();
        }

        // جلب بيانات الفلاتر
        $categories = Category::all();
        $brands = Brand::all();
        $suppliers = Supplier::all();

        return view('Users.Product.index', compact('products', 'categories', 'brands', 'suppliers', 'alternatives', 'searchTerm'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
$brands = Brand::all();
$suppliers = Supplier::all();
return view('Users.Product.add', compact('categories','brands','suppliers'));
        
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
        return redirect()->route('products.index')->with('success', 'تم إضافة المورد بنجاح ✅');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $selectedProduct = \App\Models\Product::findOrFail($id);
        return view('Users.Cart.add', compact('selectedProduct'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
