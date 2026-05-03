<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\User;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   
    public function index(Request $request)
    {
        // استعلام أساسي مع تحميل علاقة المستخدم لتجنب مشكلة N+1
        $query = Supplier::with('user');

        // 1. الفلترة بالبحث النصي
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function($subQuery) use ($q) {
                $subQuery->where('company_name', 'like', "%{$q}%")
                         ->orWhere('business_type', 'like', "%{$q}%")
                         ->orWhere('address', 'like', "%{$q}%")
                         ->orWhere('commercial_registration_number', 'like', "%{$q}%")
                         ->orWhereHas('user', function ($userQuery) use ($q) {
                             $userQuery->where('name', 'like', "%{$q}%");
                         });
            });
        }

        // 2. الفلترة بالحالة
        if ($request->filled('status') && $request->input('status') != 'all') {
            $query->where('status', $request->input('status'));
        }

        // جلب النتائج مع الترتيب الأحدث وتنسيق الـ pagination
        $suppliers = $query->latest()->paginate(9)->withQueryString();

        return view('admin.suppliers.index', compact('suppliers'));
    }


    public function approve(Supplier $supplier)
    {
        $supplier->status = 'confirmed';
        $supplier->save();
    
        return redirect()->route('supplierad.index')->with('success', 'تم اعتماد المورد بنجاح.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $users = User::all(); // علشان تختار المستخدم اللي بينضاف له المورد
    return view('admin.suppliers.add', compact('users'));
}

public function store(Request $request)
{
    $request->validate([
        'company_name' => 'required|string|max:255',
        'business_type' => 'required|string|max:255',
        'commercial_registration_number' => 'required|string|max:255',
        'identity_number' => 'required|string|max:255',
        'logo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
    ]);

    $data = $request->all();

    if ($request->hasFile('logo')) {
        $data['logo'] = $request->file('logo')->store('suppliers', 'public');
    }

    Supplier::create($data);

    return redirect()->route('supplierad.index')->with('success', 'تم إضافة المورد بنجاح');
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
    $supplier = Supplier::findOrFail($id);
    return view('admin.suppliers.edit', compact('supplier'));
}

public function update(Request $request, $id)
{
    $supplier = Supplier::findOrFail($id);

    $request->validate([
        'company_name' => 'required|string|max:255',
        'business_type' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        'commercial_registration_number' => 'required|string|max:255',
        'identity_number' => 'required|string|max:255',
        'bank_account' => 'required|string|max:255',
    ]);

    $data = $request->all();

    if ($request->hasFile('logo')) {
        $data['logo'] = $request->file('logo')->store('suppliers', 'public');
    }

    $supplier->update($data);

    return redirect()->route('supplierad.index')->with('success', 'تم تحديث بيانات المورد بنجاح.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return redirect()->route('supplierad.index')->with('success', 'تم حذف المورد بنجاح.');
    }
}
