@extends('Users.home')

@section('content')
    <!-- === مكتبات الأنماط والأيقونات === -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- المتغيرات والألوان الأساسية --- */
        :root {
            --primary-color: #4e6b3a;
            --secondary-color: #8aa57c;
            --dark-color: #2c3e50;
            --light-color: #f8f9fa;
            --border-color: #e9ecef;
            --shadow: 0 8px 25px rgba(0, 0, 0, 0.08 );
            --transition: all 0.3s ease-in-out;
        }
        .my-orders-page { background-color: var(--light-color); padding: 50px 0; font-family: 'Cairo', sans-serif; min-height: 100vh; }
        .page-header { font-size: 2.5rem; font-weight: 700; color: var(--dark-color); text-align: center; margin-bottom: 40px; }
        
        /* --- تصميم بطاقة الطلب --- */
        .order-card {
            background: #ffffff;
            border-radius: 18px;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
        }
        .order-summary-header {
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            border-bottom: 1px solid var(--border-color);
        }
        .order-info { display: flex; flex-wrap: wrap; align-items: center; gap: 20px; }
        .order-id { font-size: 1.2rem; font-weight: 700; color: var(--dark-color); }
        .order-id span { color: var(--primary-color); }
        .order-date, .order-total { font-size: 1rem; color: #555; display: flex; align-items: center; gap: 8px; }
        .order-total { font-weight: 600; }
        .toggle-icon { font-size: 1.2rem; color: var(--secondary-color); transition: transform 0.3s ease; }
        .toggle-icon.expanded { transform: rotate(90deg); }

        /* --- **الجديد: تصميم واجهة التتبع (Timeline)** --- */
        .timeline-container {
            padding: 25px 15px;
            border-bottom: 1px solid var(--border-color);
        }
        .timeline {
            display: flex;
            justify-content: space-between;
            position: relative;
        }
        /* الخط الرمادي الخلفي */
        .timeline::before {
            content: '';
            position: absolute;
            top: 20px; /* في منتصف الأيقونة */
            left: 0;
            right: 0;
            height: 4px;
            background-color: #e0e0e0;
            z-index: 1;
        }
        .timeline-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 25%;
            position: relative;
            z-index: 2;
        }
        .timeline-step .icon-wrapper {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e0e0e0; /* اللون الافتراضي */
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            border: 4px solid var(--light-color);
            transition: background-color 0.3s;
        }
        .timeline-step .step-label {
            margin-top: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #888; /* اللون الافتراضي */
            transition: color 0.3s;
        }
        /* تنسيق الخطوات المكتملة */
        .timeline-step.completed .icon-wrapper {
            background-color: var(--primary-color);
        }
        .timeline-step.completed .step-label {
            color: var(--dark-color);
        }

        /* --- تفاصيل الطلب (مخفية) --- */
        .order-details-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease-in-out, padding 0.5s ease;
            background-color: #fdfdfd;
            padding: 0 25px;
        }
        .order-details-content.expanded { padding: 25px; }
        .details-section { margin-bottom: 20px; }
        .details-section h5 { font-weight: 700; color: var(--dark-color); margin-bottom: 10px; }
        .product-list-item { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border-color); }
        .product-list-item:last-child { border-bottom: none; }
        .product-info img { width: 50px; height: 50px; border-radius: 8px; object-fit: cover; }
        .product-name-qty { font-weight: 600; }
        .product-name-qty span { color: #777; font-size: 0.9rem; }
        .product-price { font-weight: 600; color: var(--primary-color); }
        .no-orders-message { text-align: center; padding: 50px; background: #fff; border-radius: 15px; box-shadow: var(--shadow); }
    </style>

    <div class="my-orders-page">
        <div class="container">
            <h2 class="page-header">طلباتي</h2>

            @forelse($orders as $order)
                @php
                    // **الجديد: تعريف منطق التتبع هنا**
                    $statusLevels = ['pending' => 1, 'processing' => 2, 'shipped' => 3, 'completed' => 4];
                    $currentLevel = $statusLevels[$order->status] ?? 0;
                @endphp
                <div class="order-card">
                    <!-- رأس البطاقة (يظهر دائماً) -->
                    <div class="order-summary-header" data-target="#details-{{ $order->id }}">
                        <div class="order-info">
                            <div class="order-id">طلب <span>#{{ $order->id }}</span></div>
                            <div class="order-date"><i class="fas fa-calendar-alt"></i> {{ $order->created_at->format('Y-m-d') }}</div>
                            <div class="order-total"><i class="fas fa-wallet"></i> {{ number_format($order->total_price, 2) }} ريال</div>
                        </div>
                        <div class="order-actions">
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </div>
                    </div>

                    <!-- **الجديد: قسم واجهة التتبع** -->
                    <div class="timeline-container">
                        <div class="timeline">
                            <div class="timeline-step {{ $currentLevel >= 1 ? 'completed' : '' }}">
                                <div class="icon-wrapper"><i class="fas fa-receipt"></i></div>
                                <div class="step-label">قيد المراجعة</div>
                            </div>
                            <div class="timeline-step {{ $currentLevel >= 2 ? 'completed' : '' }}">
                                <div class="icon-wrapper"><i class="fas fa-box-open"></i></div>
                                <div class="step-label">قيد التجهيز</div>
                            </div>
                            <div class="timeline-step {{ $currentLevel >= 3 ? 'completed' : '' }}">
                                <div class="icon-wrapper"><i class="fas fa-truck"></i></div>
                                <div class="step-label">تم الشحن</div>
                            </div>
                            <div class="timeline-step {{ $currentLevel >= 4 ? 'completed' : '' }}">
                                <div class="icon-wrapper"><i class="fas fa-check-circle"></i></div>
                                <div class="step-label">تم التوصيل</div>
                            </div>
                        </div>
                    </div>

                    <!-- تفاصيل الطلب (مخفية) -->
                    <div class="order-details-content" id="details-{{ $order->id }}">
                        <div class="row">
                            <div class="col-md-6 details-section">
                                <h5><i class="fas fa-shipping-fast"></i> تفاصيل الشحن</h5>
                                <p><strong>العنوان:</strong>
                                    {{ optional($order->address)->full_address ?? 'لم يحدد عنوان' }}
                                </p>
                                <p><strong>طريقة الدفع:</strong> {{ $order->payment_method }}</p>
                            </div>
                            <div class="col-md-6 details-section">
                                <h5><i class="fas fa-receipt"></i> ملخص الفاتورة</h5>
                                <p><strong>الإجمالي:</strong> {{ number_format($order->total_price, 2) }} ريال</p>
                            </div>
                        </div>
                        
                        <div class="details-section">
                            <h5><i class="fas fa-box-open"></i> المنتجات</h5>
                            @foreach($order->orderItems as $item)
                                <div class="product-list-item">
                                    <div class="product-info">
                                        <img src="{{ $item->product->image_url ?? 'https://via.placeholder.com/60' }}" alt="{{ $item->product->name }}">
                                        <div class="product-name-qty">
                                            {{ $item->product->name }}  
                                            <span>(الكمية: {{ $item->quantity }} )</span>
                                        </div>
                                    </div>
                                    <div class="product-price">{{ number_format($item->price * $item->quantity, 2 ) }} ريال</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="no-orders-message">
                    <h3>لا توجد طلبات حالياً</h3>
                    <p>لم تقم بأي طلبات بعد. ابدأ التسوق الآن!</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.order-summary-header').forEach(header => {
            header.addEventListener('click', function () {
                const targetId = this.dataset.target;
                const content = document.querySelector(targetId);
                const icon = this.querySelector('.toggle-icon');

                if (content.classList.contains('expanded')) {
                    content.classList.remove('expanded');
                    icon.classList.remove('expanded');
                    content.style.maxHeight = '0px';
                } else {
                    content.classList.add('expanded');
                    icon.classList.add('expanded');
                    content.style.maxHeight = content.scrollHeight + 'px';
                }
            });
        });
    });
    </script>
@endsection
