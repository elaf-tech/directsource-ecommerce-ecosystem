<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

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
    
        return view('Users.Cart.index',compact('cartItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    
       
    }
    
    

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);


        $productId = $request->product_id;
        $quantity = $request->quantity;

        // تحقق إذا كان المنتج موجود بالفعل بالعربة
        $cartItem = Cart::where('user_id', Auth::id())
        ->where('product_id', $productId)
        ->first();


        if ($cartItem) {
            // زيادة الكمية
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // إنشاء صف جديد
        Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }
        return redirect()->route('carts.index')->with('success', 'تم إضافة المورد بنجاح ✅');


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
    public function updateQuantity(Request $request)
{
    // التحقق من صحة البيانات المرسلة
    $request->validate([
        'item_id' => 'required|exists:carts,id',
        'quantity' => 'required|integer|min:1',
    ]);

    // البحث عن المنتج في عربة المستخدم الحالي
    $cartItem = Cart::where('id', $request->item_id)
                    ->where('user_id', auth()->id())
                    ->first();

    if ($cartItem) {
        // التحقق من أن الكمية المطلوبة لا تتجاوز الكمية المتاحة في المخزون
        if ($request->quantity > $cartItem->product->quantity) {
            return response()->json(['success' => false, 'message' => 'الكمية المطلوبة تتجاوز المخزون المتاح.']);
        }

        // تحديث الكمية
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        // إرجاع استجابة نجاح
        return response()->json(['success' => true]);
    }

    // إرجاع استجابة فشل إذا لم يتم العثور على المنتج
    return response()->json(['success' => false, 'message' => 'لم يتم العثور على المنتج في العربة.'], 404);
}
    
        // إرجاع استجابة فشل إذا لم يتم العثور على المنتج
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cartItem = Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
    
        if (!$cartItem) {
            return redirect()->back()->with('error', 'العنصر غير موجود في العربة');
        }
    
        $cartItem->delete();
    
        return redirect()->back()->with('success', 'تم حذف المنتج من العربة بنجاح');
    }
    
}
