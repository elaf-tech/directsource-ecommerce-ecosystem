<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Supplier;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // تحميل كل العلاقات المطلوبة لتجنب مشكلة N+1
        $query = Order::with(['user', 'address', 'orderItems.product.category', 'orderItems.product.brand', 'orderItems.product.supplier']);

        // 1. الفلترة بالبحث النصي (تبقى كما هي)
        if ($request->filled('q')) {
            // ... كود البحث النصي ...
        }

        // 2. الفلترة بحالة الطلب (تبقى كما هي)
        if ($request->filled('status') && $request->input('status') != 'all') {
            // ... كود فلترة الحالة ...
        }

        // 3. الفلترة بالفئة (تبقى كما هي)
        if ($request->filled('category_id') && is_numeric($request->input('category_id'))) {
            // ... كود فلترة الفئة ...
        }
        
        // 4. الفلترة بالماركة (تبقى كما هي)
        if ($request->filled('brand_id') && is_numeric($request->input('brand_id'))) {
            // ... كود فلترة الماركة ...
        }

        // 5. **الجديد: الفلترة بالمورد**
        if ($request->filled('supplier_id') && is_numeric($request->input('supplier_id'))) {
            $query->whereHas('orderItems.product', function ($productQuery) use ($request) {
                $productQuery->where('supplier_id', $request->input('supplier_id'));
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        // جلب بيانات الفلاتر
        $categories = Category::all();
        $brands = Brand::all();
        $suppliers = Supplier::all(); // **الجديد: جلب كل الموردين**

        return view('admin.Order.index', compact('orders', 'categories', 'brands', 'suppliers')); // **الجديد: تمرير الموردين للواجهة**
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
        //
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
