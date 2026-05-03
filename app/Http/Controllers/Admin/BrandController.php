<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Supplier;
class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brand=Brand::all();
        return view('admin.brands.index',compact('brand'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cat=Category::all();
        $brand=Brand::all();
         $product = Product::paginate(4);
        $sup=Supplier::all();
       return view('admin.brands.add',compact('cat','brand','product','sup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image', // تعديل هنا لقبول الملفات
            'description' => 'nullable|string',
        ]);
    
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('brands', 'public'); 
            // هذا يخزن الصورة في storage/app/public/categories
        }
    
        $brand = Brand::create([
            'name' => $request->name,
            'image' => $imagePath, // الآن هذا مسار الصورة
            'description' => $request->description,
        ]);
    
        return redirect()->route('brandad.index')->with('success', 'تمت إضافة الفئة بنجاح ✅');

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
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
    
        return view('admin.brands.edit', compact('brand'));
    }
    // تحديث البيانات
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
    
        $data = $request->all();
    
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('brands', 'public');
        }
    
        $brand->update($data);
    
        return redirect()->route('brandad.index')->with('success','تم تعديل المنتج بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = brand::findOrFail($id);
        $brand->delete();
        return redirect()->route('brandad.index')->with('success','تم حذف المنتج بنجاح');
    
    }
}
