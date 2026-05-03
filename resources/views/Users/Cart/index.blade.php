@extends('Users.home')

@section('content')

{{-- تم دمج الـ CSS هنا لتسهيل التطبيق --}}
<style>
    /* ... نفس كود CSS السابق ... */
    .shopping-cart-page { background: linear-gradient(to bottom, #ffffff, #f8f9fa); padding: 50px 0; }
    .cart-header { text-align: center; margin-bottom: 40px; }
    .cart-header h2 { font-size: 2.8rem; font-weight: 800; color: var(--dark-color); display: flex; align-items: center; justify-content: center; gap: 15px; }
    .cart-header h2 i { color: var(--primary-color); }
    .cart-layout { display: grid; grid-template-columns: 1fr 380px; gap: 30px; align-items: flex-start; }
    .cart-items-list { display: flex; flex-direction: column; gap: 20px; }
    .cart-item-card { background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.07); padding: 20px; display: flex; align-items: center; gap: 20px; transition: var(--transition); }
    .cart-item-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .cart-item-image { width: 100px; height: 100px; border-radius: 12px; overflow: hidden; flex-shrink: 0; }
    .cart-item-image img { width: 100%; height: 100%; object-fit: cover; }
    .cart-item-details { flex-grow: 1; }
    .cart-item-details .product-name { font-size: 1.2rem; font-weight: 700; color: var(--dark-color); margin-bottom: 5px; }
    .cart-item-details .product-meta { font-size: 0.9rem; color: #6c757d; }
    .cart-item-price { font-size: 1.1rem; font-weight: 600; color: var(--primary-color); min-width: 100px; text-align: center; }
    .cart-item-actions { display: flex; align-items: center; gap: 15px; }
    .btn-remove-item { background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding: 0; transition: var(--transition); cursor: pointer; }
    .btn-remove-item:hover { background-color: #dc3545; color: white; }
    .order-summary-card { background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.07); padding: 30px; position: sticky; top: 100px; }
    .order-summary-card h3 { font-size: 1.5rem; font-weight: 700; color: var(--dark-color); margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #e9ecef; }
    .summary-line { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 1rem; }
    .summary-line .label { color: #6c757d; }
    .summary-line .value { color: var(--dark-color); font-weight: 600; }
    .summary-total { border-top: 1px solid #e9ecef; padding-top: 20px; margin-top: 20px; }
    .summary-total .label { font-size: 1.2rem; font-weight: 700; }
    .summary-total .value { font-size: 1.5rem; font-weight: 700; color: var(--primary-color); }
    .btn-checkout { width: 100%; padding: 15px; margin-top: 25px; font-size: 1.1rem; font-weight: 700; border-radius: 50px; border: none; cursor: pointer; transition: var(--transition); background: var(--gradient); color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; gap: 10px; }
    .btn-checkout:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
    .empty-cart-container { text-align: center; padding: 80px 20px; background-color: #ffffff; border-radius: 16px; }
    .empty-cart-container i { font-size: 5rem; color: var(--primary-color); opacity: 0.5; }
    .empty-cart-container h3 { font-size: 2rem; color: var(--dark-color); margin-top: 20px; }
    .empty-cart-container p { color: #6c757d; margin-bottom: 30px; }
    .btn-back-to-shop { padding: 12px 30px; text-decoration: none; font-weight: 700; border-radius: 50px; background: var(--gradient); color: white; transition: var(--transition); }
    .btn-back-to-shop:hover { transform: translateY(-3px); }

    /* === CSS الجديد لعداد الكمية === */
    .quantity-counter-cart {
        display: flex;
        align-items: center;
        border: 1px solid #dde2e7;
        border-radius: 50px;
        width: fit-content;
        overflow: hidden;
    }
    .quantity-btn-cart {
        background-color: #f8f9fa;
        border: none;
        width: 35px;
        height: 35px;
        font-size: 1.2rem;
        font-weight: 400;
        cursor: pointer;
        color: var(--dark-color);
        transition: background-color 0.2s;
    }
    .quantity-btn-cart:hover { background-color: #e9ecef; }
    .quantity-btn-cart:disabled { color: #ced4da; cursor: not-allowed; }
    .quantity-input-cart {
        width: 50px;
        height: 35px;
        text-align: center;
        border: none;
        font-size: 1rem;
        font-weight: 600;
        color: var(--primary-color);
        -moz-appearance: textfield;
    }
    .quantity-input-cart::-webkit-outer-spin-button,
    .quantity-input-cart::-webkit-inner-spin-button {
        -webkit-appearance: none; margin: 0;
    }

    @media (max-width: 992px) { .cart-layout { grid-template-columns: 1fr; } .order-summary-card { position: static; margin-top: 30px; } }
    @media (max-width: 768px) { .cart-item-card { flex-direction: column; align-items: stretch; } .cart-item-actions { position: absolute; top: 15px; left: 15px; } .cart-item-price { text-align: left; margin-top: 10px; } }
</style>

<div class="shopping-cart-page">
    <div class="container">
        <div class="cart-header">
            <h2><i class="fas fa-shopping-cart"></i>عربة التسوق الخاصة بك</h2>
        </div>

        @if($cartItems->count() > 0)
            <div class="cart-layout">
                <!-- قائمة المنتجات -->
                <div class="cart-items-list">
                    @foreach($cartItems as $item)
                        <div class="cart-item-card" data-item-id="{{ $item->id }}">
                            <div class="cart-item-image">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                @else
                                    <img src="https://via.placeholder.com/100x100.png/f8f9fa/6c757d?text=No+Image" alt="No Image">
                                @endif
                            </div>
                            <div class="cart-item-details">
                                <h5 class="product-name">{{ $item->product->name }}</h5>
                                <p class="product-meta">
                                    السعر الفردي: <span class="single-price" data-price="{{ $item->product->price }}">{{ number_format($item->product->price, 2 ) }}</span> ر.س
                                </p>
                                <!-- === عداد الكمية الجديد === -->
                                <div class="quantity-counter-cart mt-2">
                                    <button type="button" class="quantity-btn-cart decrease-btn" data-item-id="{{ $item->id }}">-</button>
                                    <input type="number" class="quantity-input-cart" value="{{ $item->quantity }}" min="1" max="{{ $item->product->quantity }}">
                                    <button type="button" class="quantity-btn-cart increase-btn" data-item-id="{{ $item->id }}">+</button>
                                </div>
                            </div>
                            <div class="cart-item-price subtotal-price">
                                {{ number_format($item->product->price * $item->quantity, 2) }} ر.س
                            </div>
                            <div class="cart-item-actions">
                                <form action="{{ route('carts.destroy', $item->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-remove-item" title="حذف المنتج"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- ملخص الطلب -->
                <div class="order-summary-card">
                    <h3>ملخص الطلب</h3>
                    <div class="summary-line">
                        <span class="label">إجمالي المنتجات</span>
                        <span class="value" id="summary-subtotal">{{ number_format($cartItems->sum(fn($item) => $item->product->price * $item->quantity), 2) }} ر.س</span>
                    </div>
                    <div class="summary-line">
                        <span class="label">رسوم الشحن</span>
                        <span class="value">سيتم تحديدها</span>
                    </div>
                    <div class="summary-line summary-total">
                        <span class="label">الإجمالي الكلي</span>
                        <span class="value" id="summary-total">{{ number_format($cartItems->sum(fn($item) => $item->product->price * $item->quantity), 2) }} ر.س</span>
                    </div>
                    <a href="{{ route('checkout.payment') }}" class="btn-checkout">
                        <i class="fas fa-shield-alt"></i>
                        <span>تأكيد الطلب والدفع</span>
                    </a>
                    
                    
                </div>
            </div>
       
        @else
            <div class="empty-cart-container">
                <i class="fas fa-shopping-bag"></i>
                <h3>عربة التسوق فارغة!</h3>
                <p>لم تقم بإضافة أي منتجات بعد. تصفح منتجاتنا وأضف ما يعجبك.</p>
                <a href="{{ route('products.index') }}" class="btn-back-to-shop">
                    <i class="fas fa-arrow-left"></i> العودة للتسوق
                </a>
            </div>
        @endif
    </div>
</div>

{{-- كود JavaScript لتفعيل عداد الكمية والتحديث عبر AJAX --}}


<script>
document.addEventListener('DOMContentLoaded', function() {
    // دالة لتحديث الكمية عبر AJAX
    function updateCartQuantity(itemId, newQuantity) {
        // === التعديل هنا: استخدام اسم المسار الجديد ===
        fetch('{{ route("cart.updateQuantity") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                item_id: itemId,
                quantity: newQuantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // تحديث الإجماليات في الصفحة
                updateTotalsOnPage();
            } else {
                alert(data.message || 'حدث خطأ أثناء تحديث العربة.');
                // يمكنك إعادة الكمية إلى قيمتها السابقة هنا إذا أردت
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // ... (بقية كود JavaScript يبقى كما هو) ...
    function updateTotalsOnPage() {
        let subtotal = 0;
        document.querySelectorAll('.cart-item-card').forEach(card => {
            const price = parseFloat(card.querySelector('.single-price').dataset.price);
            const quantity = parseInt(card.querySelector('.quantity-input-cart').value, 10);
            const itemSubtotal = price * quantity;
            card.querySelector('.subtotal-price').textContent = itemSubtotal.toFixed(2) + ' ر.س';
            subtotal += itemSubtotal;
        });

        document.getElementById('summary-subtotal').textContent = subtotal.toFixed(2) + ' ر.س';
        document.getElementById('summary-total').textContent = subtotal.toFixed(2) + ' ر.س';
    }

    document.querySelectorAll('.increase-btn').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.cart-item-card');
            const input = card.querySelector('.quantity-input-cart');
            const max = parseInt(input.max, 10);
            let currentValue = parseInt(input.value, 10);
            if (currentValue < max) {
                input.value = currentValue + 1;
                updateCartQuantity(this.dataset.itemId, input.value);
            }
        });
    });

    document.querySelectorAll('.decrease-btn').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.cart-item-card');
            const input = card.querySelector('.quantity-input-cart');
            const min = parseInt(input.min, 10);
            let currentValue = parseInt(input.value, 10);
            if (currentValue > min) {
                input.value = currentValue - 1;
                updateCartQuantity(this.dataset.itemId, input.value);
            }
        });
    });
});
</script>


@stop