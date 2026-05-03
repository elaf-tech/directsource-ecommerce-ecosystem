<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller; // << هذا مفقود
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function orderCount(Request $request)
{
    $userId = $request->user()->id; // المستخدم الحالي

    // جلب الطلبات التي منتجاتها تابعة للمورد الحالي
    $orders = Order::whereHas('orderItems.product.supplier', function($q) use ($userId) {
        $q->where('user_id', $userId);
    })->get();

    return response()->json([
        'orders' => $orders,
        'order_count' => $orders->count(),
    ]);
}


    public function checkout(Request $request) {
        $user = Auth::user();
        
        // التحقق من وجود طريقة دفع وعنوان
        $request->validate([
            'payment_method' => 'required|string',
            'address_id' => 'required|exists:addresses,id'
        ]);
    
        DB::transaction(function() use ($user, $request) {
            // 1️⃣ جلب عناصر السلة
            $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
            
            if ($cartItems->isEmpty()) {
                throw new \Exception('العربة فارغة');
            }
    
            // 2️⃣ حساب المجموع الكلي
            $totalPrice = $cartItems->sum(function($item) {
                return $item->quantity * $item->product->price;
            });
    
            // 3️⃣ إنشاء الطلب
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'address_id' => $request->address_id
            ]);
    
            // 4️⃣ إضافة العناصر للطلب وتحديث المخزون
            foreach ($cartItems as $item) {
                // إضافة عنصر للطلب
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
    
                // تحديث كمية المنتج الفعلية
                $item->product->decrement('quantity', $item->quantity);
                // أو بدلاً من decrement:
                // $item->product->quantity -= $item->quantity;
                // $item->product->save();
            }
    
            // 5️⃣ حذف عناصر السلة
            Cart::where('user_id', $user->id)->delete();
        });
    
        return response()->json(['message' => 'تم إنشاء الطلب بنجاح']);
    }
    
// public function checkout(Request $request)
// {
//     $user = auth()->user(); // المستخدم الحالي
//     $paymentMethod = $request->payment_method; // طريقة الدفع من الفورم

//     // نستخدم transaction لضمان عدم فقد البيانات إذا حصل خطأ
//     DB::transaction(function() use ($user, $paymentMethod) {
        
//         // 1️⃣ نجمع كل عناصر الكارت
//         $cartItems = Cart::where('user_id', $user->id)->get();

//         if ($cartItems->isEmpty()) {
//             throw new \Exception('العربة فارغة');
//         }

//         // 2️⃣ نحسب السعر الكلي
//         $totalPrice = $cartItems->sum(function($item) {
//             return $item->quantity * $item->product->price;
//         });

//         // 3️⃣ ننشئ الطلب الجديد
//         $order = Order::create([
//             'user_id' => $user->id,
//             'total_price' => $totalPrice,
//             'status' => 'pending', // ممكن 'pending' أول ما ينشأ
//             'payment_method' => $paymentMethod,
//             'address_id'=>$address_id
//         ]);

//         // 4️⃣ ننقل كل عنصر في الـ Cart إلى OrderItem
//         foreach ($cartItems as $item) {
//             OrderItem::create([
//                 'order_id' => $order->id,
//                 'product_id' => $item->product_id,
//                 'quantity' => $item->quantity,
//                 'price' => $item->product->price,
//             ]);
//         }

//         // 5️⃣ نحذف عناصر الكارت بعد النقل
//         Cart::where('user_id', $user->id)->delete();
//     });

//     return response()->json(['message' => 'تم إنشاء الطلب بنجاح']);
// }
public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,delivered,cancelled',
        ]);

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الطلب بنجاح',
            'status' => $order->status,
        ]);
    } /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    public function sup_orders()
{
    $userId = Auth::id(); // المستخدم الحالي

    $orders = Order::whereHas('orderItems.product.supplier', function($q) use ($userId) {
        $q->where('user_id', $userId);
    })
    ->with(['orderItems.product.supplier', 'user', 'address'])
    ->get();

    return response()->json($orders);
}

    public function userOrders()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    $orders = Order::with(['orderItems.product', 'address'])
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($orders);
}

     public function show($id)
{
    $user = Auth::user();

    // جلب الطلب مع العناصر والعنوان
    $order = Order::with(['orderItems.product', 'address'])
        ->where('id', $id)
        ->where('user_id', $user->id) // للتأكد أن المستخدم صاحب الطلب
        ->first();

    if (!$order) {
        return response()->json(['message' => 'الطلب غير موجود'], 404);
    }

    return response()->json([
        'order' => $order
    ]);
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
