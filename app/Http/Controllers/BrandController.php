<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;


class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $brand=Brand::all();
       return view('Users.Brand.index',compact('brand'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Users.Brand.addBrand');
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
            $imagePath = $request->file('image')->store('categories', 'public'); 
            // هذا يخزن الصورة في storage/app/public/categories
        }
    
        $category = Brand::create([
            'name' => $request->name,
            'image' => $imagePath, // الآن هذا مسار الصورة
            'description' => $request->description,
        ]);
    
        return redirect('/')->with('success', 'تمت إضافة الفئة بنجاح ✅');

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
