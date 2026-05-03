<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Company;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplier=Supplier::all() ->where('status', 'confirmed') ;
        return view('Users.Supplier.index',compact('supplier'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('Users.Supplier.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

    
        return redirect()->route('suppliers.index')->with('success', 'تم إضافة المورد بنجاح ✅');
    }
    public function me(Request $request)
    {
        $user = $request->user(); // المستخدم الحالي
        $user = auth()->user(); // المستخدم الحالي
        $suppliers = Supplier::where('user_id', $user->id)
                             ->where('status', 'confirmed') // جلب اللي حالته pending فقط
                             ->get();
            
        return view('Users.Supplier.details', compact('suppliers'));

    }

    public function sup_orders(){
        $userId = auth()->id(); // المستخدم الحالي

    $orders = Order::whereHas('orderItems.product.supplier', function($q) use ($userId) {
        $q->where('user_id', $userId);
    })
    ->with(['orderItems.product.supplier', 'user', 'address'])
    ->get();

        return view('Users.Supplier.sup_orders',compact('orders','userId'));
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
