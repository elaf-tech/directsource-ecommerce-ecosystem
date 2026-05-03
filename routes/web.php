<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Auth;
// Route::get('/', function () {
//     return view('welcome');
// });


Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
	/** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
	Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
	Route::resource('admin', App\Http\Controllers\AdminController::class);
	Route::resource('user', App\Http\Controllers\Admin\UserController::class);
	Route::resource('supplierad', App\Http\Controllers\Admin\SupplierController::class);
	Route::patch('/suppliers/{supplier}/approve', [ App\Http\Controllers\Admin\SupplierController::class, 'approve'])->name('suppliers.approve');
	Route::resource('productad', App\Http\Controllers\Admin\ProductController::class);
	Route::resource('categoryad', App\Http\Controllers\Admin\CategoryController::class);
	Route::resource('brandad', App\Http\Controllers\Admin\BrandController::class);
	Route::resource('orderad', App\Http\Controllers\Admin\OrderController::class);



	Route::resource('categories', App\Http\Controllers\CategoryController::class);
	Route::resource('brands', App\Http\Controllers\BrandController::class);
	Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
	Route::resource('products', App\Http\Controllers\ProductController::class);
	Route::resource('orders', App\Http\Controllers\OrderController::class);
	Route::resource('carts', App\Http\Controllers\CartController::class);
	Route::post('/cart/update-quantity', [App\Http\Controllers\CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
	Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');
	Route::get('/editprofile', [App\Http\Controllers\HomeController::class, 'editprofile'])->name('editprofile');
	Route::put('/profileupdate', [App\Http\Controllers\HomeController::class, 'profileupdate'])->name('profileupdate');
	Route::get('/supplier/me', [App\Http\Controllers\SupplierController::class, 'me'])->name('suppliers.me');
	// Route::get('/orders/{order}/payment', [OrderController::class, 'payment'])->name('orders.payment');
	// Route::get('/checkout', [App\Http\Controllers\OrderController::class, 'me'])->name('orders.checkout');
// web.php
Route::get('/checkout/payment', [App\Http\Controllers\OrderController::class, 'payment'])->name('checkout.payment');
Route::post('/checkout/payment', [App\Http\Controllers\OrderController::class, 'storePayment']);
// web.php
Route::get('/checkout/address', [App\Http\Controllers\OrderController::class, 'address'])->name('checkout.address');
Route::post('/checkout/address', [App\Http\Controllers\OrderController::class, 'storeAddress'])
    ->name('checkout.storeAddress');
// web.php
Route::get('/checkout/confirm', [App\Http\Controllers\OrderController::class, 'confirm'])->name('checkout.confirm');
Route::post('/checkout/confirm', [App\Http\Controllers\OrderController::class, 'placeOrder']);

Route::get('/addresses/create', [App\Http\Controllers\OrderController::class, 'create'])->name('addresses.create');
Route::post('/addresses', [App\Http\Controllers\OrderController::class, 'store'])->name('addresses.store');
Route::post('/orders/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancelOrder'])
->name('orders.cancel');

Route::get('/supplier/sup_orders', [App\Http\Controllers\SupplierController::class, 'sup_orders'])->name('suppliers.sup_orders');



Route::get('/orders/sup_orders', [App\Http\Controllers\OrderController::class, 'sup_orders'])
->name('orders.sup_orders');
Route::patch('/orders/{order}/update-status', [App\Http\Controllers\OrderController::class, 'updateStatus'])->name('orders.updateStatus');

});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Services\GroqService;
use Illuminate\Http\Request;

Route::post('/admin/products/generate-description', function(Request $request, GroqService $groq) {
    $request->validate([
        'product_name' => 'required|string|max:100',
        'category_name' => 'required|string|max:100',
    ]);

    $description = $groq->generateProductDescription(
        $request->input('product_name'),
        $request->input('category_name')
    );

    return response()->json(['description' => $description]);
})->name('admin.products.generateDescription'); // الاسم 