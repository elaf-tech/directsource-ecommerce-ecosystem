<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

use App\Models\Supplier;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // --- بداية منطق الفلترة ---
    
        // 1. ابدأ ببناء الاستعلام الأساسي للمنتجات مع العلاقات المطلوبة
        $query = Product::with(['category', 'brand', 'supplier.user'])
            ->select(['id', 'name', 'description', 'price', 'quantity', 'image', 'category_id', 'brand_id', 'supplier_id', 'created_at']);
    
        // 2. فلترة البحث العام
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('category', fn($subQ) => $subQ->where('name', 'like', '%' . $searchTerm . '%'))
                  ->orWhereHas('brand', fn($subQ) => $subQ->where('name', 'like', '%' . $searchTerm . '%'))
                  ->orWhereHas('supplier', fn($subQ) => $subQ->where('company_name', 'like', '%' . $searchTerm . '%'));
            });
        }
    
        // 3. فلترة حسب الفئة المحددة
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }
    
        // 4. فلترة حسب الماركة المحددة
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->input('brand'));
        }
    
        // 5. فلترة حسب المورد المحدد
        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->input('supplier'));
        }
    
        // --- نهاية منطق الفلترة ---
    
        // 6. تنفيذ الاستعلام مع الترتيب والتصفح
        $products = $query->latest()->paginate(12);
    
        // 7. تطبيق transform على النتائج بعد جلبها
        $products->getCollection()->transform(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'image' => $product->image,
                'category' => ['name' => $product->category->name ?? 'غير محدد'],
                'brand' => ['name' => $product->brand->name ?? 'غير محدد'],
                'supplier' => ['company_name' => $product->supplier->company_name ?? 'غير محدد'],
            ];
        });
    
        // 8. جلب كل البيانات اللازمة لقوائم الفلترة المنسدلة
        $categories = Category::all();
        $brands = Brand::all();
        $suppliers = Supplier::all();
    
        // 9. إرسال كل البيانات (المنتجات المفلترة + بيانات الفلاتر) إلى الواجهة
        return view('admin.products.index', compact('products', 'categories', 'brands', 'suppliers'));
    }

    
public function edit($id)
{
    $product = Product::findOrFail($id);
    $suppliers = Supplier::all();
    $categories = Category::all();
    $brands = Brand::all();

    return view('admin.products.edit', compact('product','suppliers','categories','brands'));
}

public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $data = $request->all();

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    $product->update($data);

    return redirect()->route('productad.index')->with('success','تم تعديل المنتج بنجاح');
}

public function destroy($id)
{
    $product = Product::findOrFail($id);
    $product->delete();
    return redirect()->route('productad.index')->with('success','تم حذف المنتج بنجاح');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
$brands = Brand::all();
$suppliers = Supplier::all();
return view('admin.products.add', compact('categories','brands','suppliers'));
        
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
        return redirect()->route('productad.store')->with('success', 'تم إضافة المورد بنجاح ✅');

    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
   
}
