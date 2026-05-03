@extends('Users.home')

@section('content')

{{-- تم دمج الـ CSS هنا لتسهيل التطبيق --}}
<style>
    /* === تصميم صفحة اختيار طريقة الدفع الجديدة === */

    /* خلفية الصفحة وتنسيقها العام */
    .checkout-page-wrapper {
        background: linear-gradient(to bottom, #ffffff, #f8f9fa);
        padding: 60px 0;
        min-height: 90vh;
    }

    /* مؤشر خطوات عملية الشراء */
    .checkout-steps {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 50px;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }
    .step {
        display: flex;
        align-items: center;
        flex: 1;
        position: relative;
    }
    .step:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 50%;
        top: 20px;
        width: 100%;
        height: 2px;
        background-color: #e9ecef;
        transform: translateX(50%);
        z-index: 0;
    }
    .step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #fff;
        border: 2px solid #e9ecef;
        color: #adb5bd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        z-index: 1;
        transition: 0.3s ease;
    }
    .step-label {
        margin-left: 10px;
        color: #adb5bd;
        font-weight: 600;
        transition: 0.3s ease;
    }
    .step.active .step-icon {
        border-color: var(--primary-color);
        background-color: var(--primary-color);
        color: white;
    }
    .step.active .step-label { color: var(--primary-color); }
    .step.completed .step-icon {
        border-color: var(--secondary-color);
        background-color: var(--secondary-color);
        color: white;
    }
    .step.completed .step-label { color: var(--secondary-color); }
    .step.completed:not(:last-child)::after { background-color: var(--secondary-color); }


    /* تقسيم الصفحة إلى عمودين */
    .payment-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 30px;
        align-items: flex-start;
    }

    /* بطاقة خيارات الدفع */
    .payment-options-card {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.07);
        padding: 30px 40px;
    }
    .payment-options-card h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 30px;
    }
    .payment-options-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
    }

    /* بطاقة خيار الدفع الواحد */
    .payment-option-label {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        cursor: pointer;
        transition: var(--transition);
    }
    .payment-option-label:hover {
        border-color: var(--secondary-color);
        background-color: #f8f9fa;
    }
    .payment-option-label.selected {
        border-color: var(--primary-color);
        background-color: rgba(78, 107, 58, 0.05);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .payment-option-label input[type="radio"] { display: none; }
    .payment-option-icon {
        font-size: 2.5rem;
        color: var(--primary-color);
        width: 50px;
        text-align: center;
    }
    .payment-option-details h5 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-color);
        margin: 0;
    }
    .payment-option-details p {
        font-size: 0.9rem;
        color: #6c757d;
        margin: 5px 0 0 0;
    }

    /* بطاقة ملخص الطلب (نفس تصميم صفحة العربة) */
    .order-summary-card {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.07);
        padding: 30px;
        position: sticky;
        top: 100px;
    }
    .order-summary-card h3 { font-size: 1.5rem; font-weight: 700; color: var(--dark-color); margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #e9ecef; }
    .summary-line { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 1rem; }
    .summary-line .label { color: #6c757d; }
    .summary-line .value { color: var(--dark-color); font-weight: 600; }
    .summary-total { border-top: 1px solid #e9ecef; padding-top: 20px; margin-top: 20px; }
    .summary-total .label { font-size: 1.2rem; font-weight: 700; }
    .summary-total .value { font-size: 1.5rem; font-weight: 700; color: var(--primary-color); }

    /* زر المتابعة */
    .btn-proceed {
        width: 100%;
        padding: 15px;
        margin-top: 30px;
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 50px;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        background: var(--gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .btn-proceed:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    @media (max-width: 992px) {
        .payment-layout { grid-template-columns: 1fr; }
        .order-summary-card { position: static; margin-top: 30px; }
    }
</style>

<div class="checkout-page-wrapper">
    <div class="container">
        <!-- مؤشر خطوات الشراء -->
        <div class="checkout-steps">
            <div class="step completed">
                <div class="step-icon"><i class="fas fa-map-marker-alt"></i></div>
                <span class="step-label">العنوان</span>
            </div>
            <div class="step active">
                <div class="step-icon"><i class="fas fa-credit-card"></i></div>
                <span class="step-label">الدفع</span>
            </div>
            <div class="step">
                <div class="step-icon"><i class="fas fa-check"></i></div>
                <span class="step-label">التأكيد</span>
            </div>
        </div>

        <form action="{{ route('checkout.payment') }}" method="POST">
            @csrf
            <div class="payment-layout">
                <!-- عمود خيارات الدفع -->
                <div class="payment-options-card">
                    <h2>اختر طريقة الدفع</h2>
                    <div class="payment-options-grid">
                        <label class="payment-option-label">
                            <input type="radio" name="payment_method" value="credit_card" required>
                            <div class="payment-option-icon"><i class="fas fa-credit-card"></i></div>
                            <div class="payment-option-details">
                                <h5>البطاقة الائتمانية</h5>
                                <p>Visa, MasterCard, Mada</p>
                            </div>
                        </label>
                        <label class="payment-option-label">
                            <input type="radio" name="payment_method" value="paypal" required>
                            <div class="payment-option-icon"><i class="fab fa-paypal"></i></div>
                            <div class="payment-option-details">
                                <h5>PayPal</h5>
                                <p>الدفع الآمن عبر حساب باي بال الخاص بك.</p>
                            </div>
                        </label>
                        <label class="payment-option-label">
                            <input type="radio" name="payment_method" value="cash" required>
                            <div class="payment-option-icon"><i class="fas fa-money-bill-wave"></i></div>
                            <div class="payment-option-details">
                                <h5>الدفع عند الاستلام</h5>
                                <p>ادفع نقدًا لمندوب التوصيل عند استلام طلبك.</p>
                            </div>
                        </label>
                    </div>
                    <button type="submit" class="btn-proceed">
                        متابعة إلى التأكيد <i class="fas fa-arrow-left"></i>
                    </button>
                </div>

                <!-- عمود ملخص الطلب -->
                <div class="order-summary-card">
                    <h3>ملخص الطلب</h3>
                    <div class="summary-line">
                        <span class="label">إجمالي المنتجات</span>
                        <span class="value">1,250.00 ر.س</span>
                    </div>
                    <div class="summary-line">
                        <span class="label">رسوم الشحن</span>
                        <span class="value">25.00 ر.س</span>
                    </div>
                    <div class="summary-total">
                        <span class="label">الإجمالي الكلي</span>
                        <span class="value">1,275.00 ر.س</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.payment-option-label').forEach(label => {
    label.addEventListener('click', function() {
        // إزالة كلاس 'selected' من جميع الخيارات
        document.querySelectorAll('.payment-option-label').forEach(el => el.classList.remove('selected'));
        // إضافة كلاس 'selected' للخيار المختار
        this.classList.add('selected');
        // تحديد الراديو المقابل (للتأكيد)
        this.querySelector('input[type="radio"]').checked = true;
    });
});
</script>

@endsection
