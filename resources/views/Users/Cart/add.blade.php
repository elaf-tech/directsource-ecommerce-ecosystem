@extends('Users.home')

@section('content')

{{-- تم دمج الـ CSS هنا لتسهيل التطبيق --}}
<style>
    /* === تصميم صفحة إضافة منتج للعربة الجديدة === */

    /* خلفية الصفحة وتنسيقها العام */
    .add-to-cart-page {
        background: linear-gradient(to bottom, #ffffff, #f8f9fa);
        padding: 60px 0;
    }

    /* بطاقة المنتج الرئيسية */
    .product-details-card {
        background-color: #ffffff;
        border-radius: 24px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        display: grid;
        grid-template-columns: 1fr 1.2fr; /* عمود للصورة وعمود للتفاصيل */
        max-width: 1100px;
        margin: 0 auto;
        overflow: hidden;
    }

    /* عمود صورة المنتج */
    .product-image-column {
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px;
    }
    .product-image-column img {
        max-width: 100%;
        max-height: 450px;
        object-fit: contain;
        border-radius: 16px;
    }

    /* عمود تفاصيل المنتج */
    .product-info-column {
        padding: 40px 50px;
        display: flex;
        flex-direction: column;
    }

    /* اسم المنتج */
    .product-title-main {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--dark-color);
        line-height: 1.3;
        margin-bottom: 15px;
    }

    /* وصف المنتج */
    .product-description-main {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 25px;
        line-height: 1.7;
    }

    /* سعر المنتج */
    .product-price-main {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 25px;
    }
    .product-price-main small {
        font-size: 1rem;
        font-weight: 500;
        color: #6c757d;
    }

    /* عداد الكمية الاحترافي */
    .quantity-selector {
        margin-bottom: 30px;
    }
    .quantity-selector .form-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 10px;
        display: block;
    }
    .quantity-counter {
        display: flex;
        align-items: center;
        border: 1px solid #dde2e7;
        border-radius: 50px;
        width: fit-content;
        overflow: hidden;
    }
    .quantity-btn {
        background-color: #f8f9fa;
        border: none;
        width: 45px;
        height: 45px;
        font-size: 1.5rem;
        font-weight: 300;
        cursor: pointer;
        color: var(--dark-color);
        transition: background-color 0.2s;
    }
    .quantity-btn:hover {
        background-color: #e9ecef;
    }
    .quantity-btn:disabled {
        color: #ced4da;
        cursor: not-allowed;
    }
    #quantity {
        width: 70px;
        height: 45px;
        text-align: center;
        border: none;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary-color);
        -moz-appearance: textfield; /* لإخفاء أسهم المتصفح الافتراضية */
    }
    #quantity::-webkit-outer-spin-button,
    #quantity::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* زر الإضافة للعربة */
    .btn-add-to-cart-main {
        width: 100%;
        padding: 15px;
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 50px;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        background: #f8a31b;
        color: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .btn-add-to-cart-main:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    /* التجاوب مع الشاشات الصغيرة */
    @media (max-width: 992px) {
        .product-details-card {
            grid-template-columns: 1fr; /* عمود واحد فقط */
        }
        .product-info-column {
            padding: 30px;
        }
        .product-title-main {
            font-size: 2rem;
        }
    }
</style>

<div class="add-to-cart-page">
    <div class="container">
        <form action="{{ route('carts.store') }}" method="POST">
            @csrf
            {{-- إرسال معرّف المنتج مع الفورم --}}
            <input type="hidden" name="product_id" value="{{ $selectedProduct->id }}">

            <div class="product-details-card">
                <!-- عمود الصورة -->
                <div class="product-image-column">
                    <img src="{{ asset('storage/' . $selectedProduct->image) }}" alt="{{ $selectedProduct->name }}">
                </div>

                <!-- عمود التفاصيل والإجراءات -->
                <div class="product-info-column">
                    <h2 class="product-title-main">{{ $selectedProduct->name }}</h2>
                    <p class="product-description-main">{{ $selectedProduct->description }}</p>
                    
                    <div class="product-price-main">
                        {{ number_format($selectedProduct->price, 2) }} ر.س
                        <small>/ لل{{ $selectedProduct->unit ?? 'قطعة' }}</small>
                    </div>

                    <div class="quantity-selector">
                        <label for="quantity" class="form-label">اختر الكمية</label>
                        <div class="quantity-counter">
                            <button type="button" id="decrease-btn" class="quantity-btn">-</button>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="{{ $selectedProduct->quantity }}">
                            <button type="button" id="increase-btn" class="quantity-btn">+</button>
                        </div>
                        <small class="text-muted mt-2 d-block">الكمية المتاحة في المخزون: {{ $selectedProduct->quantity }}</small>
                    </div>

                    <button type="submit" class="btn-add-to-cart-main">
                        <i class="fas fa-shopping-cart"></i>
                        <span>إضافة إلى العربة</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- كود JavaScript لتفعيل عداد الكمية --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const decreaseBtn = document.getElementById('decrease-btn');
    const increaseBtn = document.getElementById('increase-btn');
    const quantityInput = document.getElementById('quantity');
    
    const minQuantity = parseInt(quantityInput.min, 10);
    const maxQuantity = parseInt(quantityInput.max, 10);

    function updateButtons() {
        const currentValue = parseInt(quantityInput.value, 10);
        decreaseBtn.disabled = currentValue <= minQuantity;
        increaseBtn.disabled = currentValue >= maxQuantity;
    }

    decreaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(quantityInput.value, 10);
        if (currentValue > minQuantity) {
            quantityInput.value = currentValue - 1;
            updateButtons();
        }
    });

    increaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(quantityInput.value, 10);
        if (currentValue < maxQuantity) {
            quantityInput.value = currentValue + 1;
            updateButtons();
        }
    });

    quantityInput.addEventListener('change', function() {
        let currentValue = parseInt(quantityInput.value, 10);
        if (isNaN(currentValue) || currentValue < minQuantity) {
            quantityInput.value = minQuantity;
        } else if (currentValue > maxQuantity) {
            quantityInput.value = maxQuantity;
        }
        updateButtons();
    });

    // التحديث الأولي عند تحميل الصفحة
    updateButtons();
});
</script>

@stop
