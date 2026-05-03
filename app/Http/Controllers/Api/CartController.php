<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Models\Cart;

use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cartItems = Cart::with([
            'product.category',
            'product.brand',
            'product.supplier.user'
        ])
        ->where('user_id', auth()->id())
        ->get();
    
        return response()->json([
            'cart' => $cartItems,
            'count' => $cartItems->count(), // عدد العناصر

        ]);
    }
    

    

    // إضافة منتج للعربة
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $userId = $request->user()->id;
        $productId = $request->product_id;
        $quantity = $request->quantity;

        // تحقق إذا كان المنتج موجود بالفعل بالعربة
        $cartItem = Cart::where('user_id', $userId)
                            ->where('product_id', $productId)
                            ->first();

        if ($cartItem) {
            // زيادة الكمية
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // إنشاء صف جديد
        Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة المنتج للعربة بنجاح',
        ]);
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
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('id', $id)->where('user_id', auth()->id())->first();

        if (!$cartItem) {
            return response()->json(['message' => 'المنتج غير موجود في السلة'], 404);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'message' => 'تم تحديث الكمية بنجاح',
            'cartItem' => $cartItem
        ]);
    }

    // حذف منتج من السلة
    public function destroy($id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', auth()->id())->first();

        if (!$cartItem) {
            return response()->json(['message' => 'المنتج غير موجود في السلة'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'تم حذف المنتج بنجاح']);
    }
}
