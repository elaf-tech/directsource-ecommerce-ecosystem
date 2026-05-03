<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function index(){
        $user = Auth::user();

    // جلب الطلب مع العناصر والعنوان
    $orders = Order::with(['orderItems.product', 'address'])
    ->where('user_id', $user->id)
    ->orderBy('created_at', 'desc')
    ->get();
        return view('Users.Cart.user_orders',compact('orders','user'));
    }
    // CheckoutController.php
public function payment()
{
    return view('Users.Cart.paymentMethod');
}

public function storePayment(Request $request)
{
    $request->validate([
        'payment_method' => 'required'
    ]);

    session(['checkout.payment_method' => $request->payment_method]);

    return redirect()->route('checkout.address');
}
public function address()
{ 
    $addresses = Address::where('user_id',  Auth::id())->get(); 
   
    return view('Users.Cart.location',compact('addresses'));
}
public function storeAddress(Request $request)
{
    $request->validate([
        'address_id' => 'required|exists:addresses,id',
    ]);

    $address = Address::find($request->address_id);

    // حفظ العنوان في السيشن ليتم استخدامه عند إنشاء الطلب
    session(['checkout.address' => [
        'id' => $address->id,
        'country' => $address->country,
        'region' => $address->region,
        'city' => $address->city,
        'street_address' => $address->street_address,
        'latitude' => $address->latitude,
        'longitude' => $address->longitude,
    ]]);

    // التوجه مباشرة إلى صفحة تأكيد الطلب
    return redirect()->route('checkout.confirm');
}

public function create()
{
    return view('Users.Cart.addAddress'); // تأكدي أن الملف موجود
}


public function confirm()
{
    $user = Auth::user();

    $cartItems = \App\Models\Cart::with('product')
        ->where('user_id', $user->id)
        ->get();

    $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

    // جلب العنوان من السيشن
    $address = session('checkout.address');

    return view('Users.Cart.confirm', compact('cartItems', 'total', 'address'));
}



public function placeOrder()
{
    $user = Auth::user();
    $cartItems = \App\Models\Cart::with('product')
        ->where('user_id', $user->id)->get();

    // جلب العنوان من السيشن
    $address = session('checkout.address');

    $order = \App\Models\Order::create([
        'user_id' => $user->id,
        'status' => 'pending',
        'payment_method' => session('checkout.payment_method'),
        'address_id' => $address['id'], // حفظ الـ ID هنا
        'city' => $address['city'],
        'region' => $address['region'],
        'total_price' => $cartItems->sum(fn($item) => $item->product->price * $item->quantity),
    ]);

    foreach ($cartItems as $item) {
        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->product->price,
        ]);
    }

    // مسح العربة
    \App\Models\Cart::where('user_id', $user->id)->delete();

    // مسح السيشن
    session()->forget('checkout');

    return redirect()->route('orders.show', $order->id)->with('success', 'تم إنشاء الطلب بنجاح!');
}


//     public function checkout()
// {
//     $user = auth()->user();

//     // جلب محتويات العربة
//     $cartItems = \App\Models\Cart::with('product')
//         ->where('user_id', $user->id)
//         ->get();

//     if ($cartItems->isEmpty()) {
//         return redirect()->back()->with('error', 'العربة فارغة!');
//     }

//     // إنشاء الطلب
//     $order = \App\Models\Order::create([
//         'user_id' => $user->id,
//         'status' => 'pending', // بانتظار الدفع
//         'total_price' => $cartItems->sum(fn($item) => $item->product->price * $item->quantity),
//     ]);

//     // إضافة تفاصيل الطلب
//     foreach ($cartItems as $item) {
//         \App\Models\OrderItem::create([
//             'order_id' => $order->id,
//             'product_id' => $item->product_id,
//             'quantity' => $item->quantity,
//             'price' => $item->product->price,
//         ]);
//     }

//     // بعد الحفظ امسح العربة
//     \App\Models\Cart::where('user_id', $user->id)->delete();

//     // تحويل المستخدم لصفحة الدفع
//     return redirect()->route('orders.payment', $order->id);
// }
// public function payment($orderId)
// {
//     $order = \App\Models\Order::findOrFail($orderId);
//     return view('Users.Cart.paymentMethod', compact('order'));
// }

    /**
     * Display a listing of the resource.
     */
   

    /**
     * Show the form for creating a new resource.
     */
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'country' => 'required|string|max:255',
        'region' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'street_address' => 'required|string|max:255',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ]);

    $address = Address::create([
        'user_id' => Auth::id(),
        'country' => $request->country,
        'region' => $request->region,
        'city' => $request->city,
        'street_address' => $request->street_address,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
    ]);

    // تخزين العنوان في السيشن
    session(['checkout.address' => [
        'id' => $address->id,
        'country' => $address->country,
        'region' => $address->region,
        'city' => $address->city,
        'street_address' => $address->street_address,
        'latitude' => $address->latitude,
        'longitude' => $address->longitude,
    ]]);

    // إعادة التوجيه مباشرة لصفحة تأكيد الطلب
    return redirect()->route('checkout.confirm');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    $user = Auth::user();

    // جلب الطلب مع العناصر والعنوان
    $orders = Order::with(['orderItems.product', 'address'])
    ->where('user_id', $user->id)
    ->orderBy('created_at', 'desc')
    ->get();
        return view('Users.Cart.user_orders',compact('orders','user'));
    }
    public function cancelOrder(\App\Models\Order $order)
    {
        // تحقق أن الطلب يخص المستخدم وأن حالته pending
        if ($order->user_id != auth()->id || $order->status != 'pending') {
            return redirect()->back()->with('error', 'لا يمكن إلغاء هذا الطلب.');
        }
    
        $order->update(['status' => 'cancelled']);
    
        return redirect()->back()->with('success', 'تم إلغاء الطلب بنجاح.');
    }
    
    public function sup_orders(){
        $userId = auth()->id; // المستخدم الحالي

    $orders = Order::whereHas('orderItems.product.supplier', function($q) use ($userId) {
        $q->where('user_id', $userId);
    })
    ->with(['orderItems.product.supplier', 'user', 'address'])
    ->get();

        return view('Users.Supplier.sup_orders',compact('orders','userId'));
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,delivered,cancelled',
        ]);

        $order->status = $request->status;
        $order->save();

        return  redirect()->back();
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
