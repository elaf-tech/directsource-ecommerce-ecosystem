<?php

namespace App\Http\Controllers\Api;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $suppliers = Supplier::all(); // جلب كل الموردين

    return response()->json([
        'success' => true,
        'suppliers' => $suppliers
    ]);
}
public function checkStatus(Request $request)
{
    $user = $request->user();

    $supplier = Supplier::where('user_id', $user->id)
        ->where('status', 'pending')
        ->first();

    if ($supplier) {
        return response()->json([
            'exists' => true,
            'status' => $supplier->status,
        ]);
    }

    return response()->json([
        'exists' => false,
    ]);
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function me(Request $request)
    {
        $user = $request->user(); // المستخدم الحالي
        $user = auth()->user(); // المستخدم الحالي
        $suppliers = Supplier::where('user_id', $user->id)
                             ->where('status', 'confirmed') // جلب اللي حالته pending فقط
                             ->get();
            
        return response()->json([
            'success' => true,
            'suppliers' => $suppliers
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1️⃣ تحقق من صحة البيانات
        $request->validate([
            'company_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'commercial_registration_number' => 'required|string|max:50',
            'identity_number' => 'required|string|max:50',
            'bank_account' => 'required|string|max:50',
        ]);

        // 2️⃣ رفع شعار الشركة إذا تم إرساله
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // 3️⃣ إنشاء الشركة مؤقتًا (مورد مرتبط بشركة واحدة)
        $company = Company::create([
            'name' => $request->company_name,
            'type' => $request->business_type,
            'address' => $request->address,
            'logo' => $logoPath,
            'commercial_registration_number' => $request->commercial_registration_number,
            'bank_account' => $request->bank_account,
        ]);

        // 4️⃣ إنشاء المورد وربطه بالمستخدم والشركة
        $supplier = Supplier::create([
            'user_id' => Auth::id(), // مهم جداً
            'company_name' => $request->company_name,
            'business_type' => $request->business_type,
            'address' => $request->address,
            'logo' => $logoPath,
            'commercial_registration_number' => $request->commercial_registration_number,
            'identity_number' => $request->identity_number,
            'bank_account' => $request->bank_account,
            'company_id' => $company->id,
        ]);

        return response()->json([
            'message' => 'تم تسجيل المورد بنجاح!',
            'supplier' => $supplier,
            'company' => $company,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
