@extends('Users.home')

@section('content')
    <!-- === مكتبات الأنماط والأيقونات === -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- متغيرات الألوان والأنماط --- */
        :root {
            --primary-color: #4e6b3a;
            --secondary-color: #8aa57c;
            --dark-color: #2c3e50;
            --light-color: #f8f9fa;
            --border-color: #e9ecef;
            --shadow: 0 8px 25px rgba(0, 0, 0, 0.08 );
            --transition: all 0.3s ease-in-out;
        }

        /* --- تصميم الصفحة العام --- */
        .supplier-orders-page {
            background-color: var(--light-color);
            padding: 50px 0;
            font-family: 'Cairo', sans-serif;
            min-height: 100vh;
        }

        .page-header {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            text-align: center;
            margin-bottom: 40px;
        }

        /* --- بطاقة الطلب --- */
        .order-card {
            background: #ffffff;
            border-radius: 18px;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
        }

        /* --- رأس البطاقة (الملخص) --- */
        .order-summary-header {
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            flex-wrap: wrap; /* للسماح بالالتفاف على الشاشات الصغيرة */
            gap: 15px;
        }

        .order-id-date {
            flex-grow: 1;
        }
        .order-id { font-size: 1.3rem; font-weight: 700; color: var(--dark-color); }
        .order-id span { color: var(--primary-color); }
        .order-date { font-size: 0.95rem; color: #6c757d; }

        /* --- نظام تحديث الحالة (مُحسَّن) --- */
        .status-update-form {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0; /* لمنع النموذج من التقلص */
        }
        .status-select {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-weight: 600;
            background-color: #f8f9fa;
            -webkit-appearance: none; /* لإزالة النمط الافتراضي للمتصفح */
            -moz-appearance: none;
            appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007bff%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E' );
            background-repeat: no-repeat;
            background-position: left 10px center;
            background-size: .65em auto;
            padding-left: 30px; /* مساحة للسهم */
        }
        .btn-save-status {
            padding: 8px 15px;
            border-radius: 8px;
            border: none;
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }
        .btn-save-status:hover { background-color: #3e552e; }

        /* --- جسم البطاقة (التفاصيل) --- */
        .order-card-body { padding: 25px; }
        .details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 25px; }
        .details-section h5 { font-size: 1.1rem; font-weight: 700; color: var(--dark-color); margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
        .details-section h5 i { color: var(--secondary-color); }
        .details-section p { margin-bottom: 5px; color: #495057; }
        .details-section p strong { color: var(--dark-color); }
        .products-section h5 { border-top: 1px solid var(--border-color); padding-top: 25px; }
        .product-list-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border-color); }
        .product-list-item:last-child { border-bottom: none; }
        .product-info { display: flex; align-items: center; gap: 15px; }
        .product-info img { width: 50px; height: 50px; border-radius: 8px; object-fit: cover; }
        .product-name-qty { font-weight: 600; }
        .product-name-qty span { color: #777; font-size: 0.9rem; }
        .product-price { font-weight: 600; color: var(--primary-color); }
        .no-orders-message { text-align: center; padding: 50px; background: #fff; border-radius: 15px; box-shadow: var(--shadow); }
    </style>

    <div class="supplier-orders-page">
        <div class="container">
            <h2 class="page-header"><i class="fas fa-receipt"></i> إدارة الطلبات الواردة</h2>

            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @forelse($orders as $order)
                <div class="order-card">
                    <!-- رأس البطاقة: رقم الطلب ونظام تحديث الحالة -->
                    <div class="order-summary-header">
                        <div class="order-id-date">
                            <div class="order-id">طلب <span>#{{ $order->id }}</span></div>
                            <div class="order-date">تاريخ الطلب: {{ $order->created_at->format('Y-m-d') }}</div>
                        </div>
                        
                        <!-- ================================================== -->
                        <!-- هذا هو الجزء الخاص بتحديث الحالة الذي يجب أن يظهر -->
                        <!-- ================================================== -->
                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="status-update-form">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="status-select" onchange="this.form.submit()">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>قيد التجهيز</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                            {{-- تم إزالة زر الحفظ لجعل التحديث تلقائياً عند التغيير --}}
                            {{-- <button type="submit" class="btn-save-status">حفظ</button> --}}
                        </form>
                        <!-- ================================================== -->
                        <!--             نهاية الجزء الخاص بالتحديث             -->
                        <!-- ================================================== -->
                    </div>

                    <!-- جسم البطاقة: تفاصيل العميل والشحن والمنتجات -->
                    <div class="order-card-body">
                        <div class="details-grid">
                            <!-- قسم معلومات العميل -->
                            <div class="details-section">
                                <h5><i class="fas fa-user-circle"></i> معلومات العميل</h5>
                                <p><strong>الاسم:</strong> {{ optional($order->user)->name ?? 'غير مسجل' }}</p>
                                <p><strong>البريد الإلكتروني:</strong> {{ optional($order->user)->email ?? 'غير متوفر' }}</p>
                            </div>

                            <!-- قسم تفاصيل الشحن -->
                            <div class="details-section">
                                <h5><i class="fas fa-shipping-fast"></i> تفاصيل الشحن</h5>
                                <p><strong>العنوان:</strong> {{ optional($order->address)->full_address ?? 'لم يحدد' }}</p>
                                <p><strong>طريقة الدفع:</strong> {{ $order->payment_method }}</p>
                            </div>

                            <!-- قسم ملخص الفاتورة -->
                            <div class="details-section">
                                <h5><i class="fas fa-file-invoice-dollar"></i> ملخص الفاتورة</h5>
                                <p><strong>الإجمالي:</strong> {{ number_format($order->total_price, 2) }} ريال</p>
                                <p><strong>الحالة الحالية:</strong> <span class="fw-bold text-capitalize">{{ $order->status }}</span></p>
                            </div>
                        </div>

                        <!-- قسم المنتجات -->
                        <div class="details-section products-section">
                            <h5><i class="fas fa-box-open"></i> المنتجات المطلوبة</h5>
                            @foreach($order->orderItems as $item)
                                <div class="product-list-item">
                                    <div class="product-info">
                                        <img src="{{ $item->product->image_url ?? 'https://via.placeholder.com/60' }}" alt="{{ $item->product->name }}">
                                        <div class="product-name-qty">
                                            {{ $item->product->name }}  

                                            <span>الكمية: {{ $item->quantity }}</span>
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
                    <h3>لا توجد طلبات واردة حالياً</h3>
                    <p>سيتم عرض الطلبات الجديدة هنا عند وصولها.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
