<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller; 

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $addresses = Address::where('user_id', Auth::id())->get();
        return response()->json($addresses);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'country' => 'required|string',
            'city' => 'required|string',
            'street_address' => 'required|string',
            'region' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
    
        $address = Address::create([
            'user_id' => Auth::id(),  // 🟢 يجيب المستخدم من التوكن
            'country' => $request->country,
            'region' => $request->region,
            'city' => $request->city,
            'street_address' => $request->street_address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
    
        return response()->json([
            'message' => 'تم إضافة العنوان بنجاح',
            'address' => $address
        ], 201);
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
   

    // 🔹 جلب عنوان محدد
    public function show($id)
    {
        $address = Address::findOrFail($id);
        return response()->json($address);
    }

    // 🔹 تحديث عنوان
    public function update(Request $request, $id)
    {
        $address = Address::findOrFail($id);

        $address->update($request->all());

        return response()->json([
            'message' => 'تم تحديث العنوان بنجاح',
            'address' => $address
        ]);
    }

    // 🔹 حذف عنوان
   

    /**
     * Display the specified resource.
     */
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Address $address)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();

        return response()->json(['message' => 'تم حذف العنوان']);
    }
}
