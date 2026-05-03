<?php
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::get('/categories', [App\Http\Controllers\Api\CategoryController::class, 'index']);
Route::post('/categories', [App\Http\Controllers\Api\CategoryController::class, 'store']);

Route::get('/brands', [App\Http\Controllers\Api\BrandController::class, 'index']);
Route::post('/brands', [App\Http\Controllers\Api\BrandController::class, 'store']);

Route::middleware('auth:sanctum')->post('/suppliers', [App\Http\Controllers\Api\SupplierController::class, 'store']);
Route::middleware('auth:sanctum')->get('/suppliers/me', [App\Http\Controllers\Api\SupplierController::class, 'me']);
Route::get('/suppliers', [App\Http\Controllers\Api\SupplierController::class, 'index']);
            
Route::middleware('auth:sanctum')->get('/suppliers/check-status', [App\Http\Controllers\Api\SupplierController::class, 'checkStatus']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [App\Http\Controllers\Api\AuthController::class, 'user']);
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::put('/user/update', [App\Http\Controllers\Api\AuthController::class, 'update']);

});


//Route::post('/products', [App\Http\Controllers\Api\ProductController::class, 'store']);
//Route::get('/products', [App\Http\Controllers\Api\ProductController::class, 'index']);

Route::get('/products', [App\Http\Controllers\Api\ProductController::class, 'index'])->name('api.products.index');
Route::post('/products', [App\Http\Controllers\Api\ProductController::class, 'store'])->name('api.products.store');
Route::get('/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'show'])->name('api.products.show');
Route::post('/products/generate-description', [App\Http\Controllers\Api\ProductController::class, 'generateDescription'])->name('api.products.generateDescription');


Route::middleware('auth:sanctum')->post('/cart/add', [App\Http\Controllers\Api\CartController::class, 'addToCart']);
Route::middleware('auth:sanctum')->get('/cart', [App\Http\Controllers\Api\CartController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/cart', [CartController::class, 'index']);         // عرض سلة التسوق
    Route::put('/cart/{id}', [App\Http\Controllers\Api\CartController::class, 'update']);  // تحديث الكمية
    Route::delete('/cart/{id}', [App\Http\Controllers\Api\CartController::class, 'destroy']); // حذف المنتج
});
// routes/api.php

Route::middleware('auth')->group(function () {
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkout', [App\Http\Controllers\Api\OrderController::class, 'checkout'])->name('checkout');

Route::get('/addresses', [App\Http\Controllers\Api\AddressController::class, 'index']);
Route::post('/addresses', [App\Http\Controllers\Api\AddressController::class, 'store']);
Route::get('/addresses/{id}', [App\Http\Controllers\Api\AddressController::class, 'show']);
Route::put('/addresses/{id}', [App\Http\Controllers\Api\AddressController::class, 'update']);
Route::delete('/addresses/{id}', [App\Http\Controllers\Api\AddressController::class, 'destroy']);
Route::get('/orders/{id}', [App\Http\Controllers\Api\OrderController::class, 'show']);

});
Route::middleware('auth:sanctum')->
get('/user-orders', [App\Http\Controllers\Api\OrderController::class, 'userOrders']);

Route::middleware('auth:sanctum')->get('/supplier/orders', [App\Http\Controllers\Api\OrderController::class, 'sup_orders']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/odercount', [App\Http\Controllers\Api\OrderController::class, 'orderCount']);

    Route::post('supplier/orders/{order}/status', [App\Http\Controllers\Api\OrderController::class, 'updateStatus']);
});

// use App\Http\Controllers\Api\OpenAIController;
// Route::post('/chat', [OpenAIController::class, 'chat']);
// Route::get('/test-openai', [OpenAIController::class, 'test']);
use Illuminate\Http\Request;
Route::post('/chat', function (Request $request) {
    return response()->json([
        'message' => 'تم استلام الطلب',
        'prompt' => $request->input('prompt')
    ]);
});