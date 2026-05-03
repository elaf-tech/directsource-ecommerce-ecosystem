@extends('Users.home')

@section('content')

<style>
    /* --- متغيرات الألوان والأنماط --- */
    :root {
        --primary-color: #4e6b3a;
        --secondary-color: #8aa57c;
        --accent-color: #f8a31b;
        --dark-color: #2c3e50;
        --light-color: #f9f9f9;
        --text-color: #333;
        --light-text: #fff;
        --border-color: #e0e0e0;
        --shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease-in-out;
        --gradient: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    }

    /* --- تصميم الصفحة العام --- */
    .address-selection-page {
        background-color: var(--light-color);
        padding: 60px 0;
        font-family: 'Cairo', sans-serif; /* استخدام خط أفضل للغة العربية */
    }

    /* --- بطاقة اختيار العنوان --- */
    .address-selection-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 40px;
        box-shadow: var(--shadow);
        max-width: 800px;
        margin: auto;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 20px;
    }

    .card-header h2 {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--dark-color);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .card-header h2 i {
        color: var(--primary-color);
    }

    /* --- زر إضافة عنوان جديد --- */
    .btn-add-address {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 25px;
        border-radius: 50px;
        background: var(--gradient);
        color: var(--light-text);
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        box-shadow: 0 4px 10px rgba(78, 107, 58, 0.2);
    }

    .btn-add-address:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(78, 107, 58, 0.3);
    }

    /* --- قائمة العناوين --- */
    .address-list {
        display: grid;
        grid-template-columns: 1fr; /* عمود واحد لتصميم أفضل */
        gap: 15px;
        margin-bottom: 30px;
    }

    .address-option {
        border: 2px solid var(--border-color);
        border-radius: 15px;
        padding: 20px;
        cursor: pointer;
        transition: var(--transition);
        background: #fff;
        display: flex;
        align-items: center; /* محاذاة العناصر بشكل أفضل */
        gap: 20px;
    }

    .address-option:hover {
        border-color: var(--secondary-color);
        transform: translateY(-2px);
    }

    .address-option input[type="radio"] {
        display: none; /* إخفاء الراديو الأصلي */
    }

    .address-option.selected {
        border-color: var(--primary-color);
        background-color: #f7faf5; /* خلفية خفيفة عند الاختيار */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.07);
    }
    
    .radio-custom-icon {
        font-size: 1.5rem;
        color: var(--border-color);
        transition: var(--transition);
    }
    
    .address-option.selected .radio-custom-icon {
        color: var(--primary-color);
        transform: scale(1.1);
    }

    .address-details {
        flex-grow: 1;
    }

    .address-main {
        font-weight: 700;
        font-size: 1.2rem;
        color: var(--dark-color);
        margin-bottom: 5px;
    }

    .address-secondary {
        font-size: 1rem;
        color: #666;
    }

    /* --- زر المتابعة --- */
    .btn-submit-address {
        width: 100%;
        padding: 18px;
        font-size: 1.2rem;
        font-weight: 700;
        border-radius: 15px;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        background: var(--gradient);
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    .btn-submit-address:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(78, 107, 58, 0.25);
    }
    
    .btn-submit-address i {
        transition: transform 0.3s ease;
    }

    .btn-submit-address:hover i {
        transform: translateX(-5px);
    }
</style>

<div class="address-selection-page">
  <div class="container">
    <div class="address-selection-card">
      
      <div class="card-header">
        <h2><i class="fas fa-map-marked-alt"></i> اختر عنوان التوصيل</h2>
        <a href="{{route('addresses.create')}}" class="btn-add-address">
          <i class="fas fa-plus"></i> إضافة عنوان جديد
        </a>
      </div>
      
      <form action="{{route('checkout.storeAddress')}}" method="POST" id="addressForm">
        @csrf
        <div class="address-list">
          @forelse($addresses as $address)
            <label class="address-option" for="address-{{ $address->id }}">
              <i class="far fa-circle radio-custom-icon"></i> <!-- أيقونة بديلة للراديو -->
              <input type="radio" name="address_id" value="{{ $address->id }}" id="address-{{ $address->id }}" required>
              <div class="address-details">
                <div class="address-main">{{ $address->country }} - {{ $address->region }}</div>
                <div class="address-secondary">{{ $address->city }}, {{ $address->street_address }}</div>
              </div>
            </label>
          @empty
            <p>لم يتم العثور على عناوين. الرجاء إضافة عنوان جديد.</p>
          @endforelse
        </div>

        <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
        <button type="submit" class="btn-submit-address">
          <span>التالي</span>
          <i class="fas fa-arrow-left"></i>
        </button>
      </form>
      
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addressOptions = document.querySelectorAll('.address-option');

    addressOptions.forEach(option => {
        option.addEventListener('click', function() {
            // إزالة التحديد من جميع الخيارات
            addressOptions.forEach(el => {
                el.classList.remove('selected');
                el.querySelector('.radio-custom-icon').classList.replace('fa-check-circle', 'fa-circle');
            });

            // إضافة التحديد للخيار المختار
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
            this.querySelector('.radio-custom-icon').classList.replace('fa-circle', 'fa-check-circle');
        });
    });
});
</script>

@endsection
