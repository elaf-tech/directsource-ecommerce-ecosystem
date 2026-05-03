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
            --light-color: #f9f9f9;
            --border-color: #e9ecef;
            --shadow: 0 8px 25px rgba(0, 0, 0, 0.08 );
            --transition: all 0.3s ease-in-out;
            --gradient: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        /* --- تصميم الصفحة العام --- */
        .order-confirmation-page {
            background-color: var(--light-color);
            padding: 60px 0;
            font-family: 'Cairo', sans-serif;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            text-align: center;
            margin-bottom: 40px;
        }

        /* --- البطاقة الرئيسية --- */
        .main-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
        }

        .card-section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .card-section-title i {
            color: var(--primary-color);
        }

        /* --- جدول المنتجات --- */
        .products-table {
            width: 100%;
            border-collapse: collapse;
        }

        .products-table th, .products-table td {
            padding: 15px;
            text-align: right;
            vertical-align: middle;
        }

        .products-table thead {
            border-bottom: 2px solid var(--primary-color);
        }

        .products-table th {
            font-weight: 600;
            color: var(--dark-color);
        }

        .products-table tbody tr {
            border-bottom: 1px solid var(--border-color);
        }
        
        .products-table tbody tr:last-child {
            border-bottom: none;
        }

        .product-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
        }

        .product-name {
            font-weight: 600;
            color: var(--dark-color);
        }

        /* --- بطاقة ملخص الطلب --- */
        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px dashed var(--border-color);
        }
        
        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            font-weight: 600;
            color: #555;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-value {
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .total-summary {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid var(--primary-color);
        }
        
        .total-summary .summary-label, .total-summary .summary-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        /* --- زر تأكيد الطلب --- */
        .btn-confirm-order {
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
            margin-top: 15px;
        }

        .btn-confirm-order:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(78, 107, 58, 0.25);
        }
    </style>

    <div class="order-confirmation-page">
        <div class="container">
            <h2 class="page-title">مراجعة وتأكيد الطلب</h2>

            <div class="row">
                <!-- العمود الأيمن: تفاصيل الطلب -->
                <div class="col-lg-8">
                    <div class="main-card">
                        <h3 class="card-section-title"><i class="fas fa-shopping-basket"></i> محتويات الطلب</h3>
                        <table class="products-table">
                            <thead>
                                <tr>
                                    <th>المنتج</th>
                                    <th>الكمية</th>
                                    <th>السعر</th>
                                    <th>الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr>
                                        <td>
                                            <div class="product-item">
                                                <div class="product-item">
                                                    <span class="product-name">{{ $item->product->name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->product->price, 2 ) }} $</td>
                                        <td>{{ number_format($item->product->price * $item->quantity, 2) }} $</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- العمود الأيسر: ملخص الدفع والعنوان -->
                <div class="col-lg-4">
                    <div class="main-card">
                        <h3 class="card-section-title"><i class="fas fa-file-invoice-dollar"></i> ملخص الطلب</h3>
                        
                        <div class="summary-item">
                            <span class="summary-label"><i class="fas fa-map-marker-alt"></i> العنوان</span>
                            <span class="summary-value">{{ session('checkout.address.city') }}, {{ session('checkout.address.region') }}</span>
                        </div>
                        
                        <div class="summary-item">
                            <span class="summary-label"><i class="fas fa-credit-card"></i> طريقة الدفع</span>
                            <span class="summary-value">{{ session('checkout.payment_method') }}</span>
                        </div>

                        <div class="summary-item total-summary">
                            <span class="summary-label">الإجمالي الكلي</span>
                            <span class="summary-value">{{ number_format($total, 2) }} $</span>
                        </div>

                        <form action="{{ route('checkout.confirm') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-confirm-order">
                                <i class="fas fa-check-circle"></i> تأكيد وإتمام الطلب
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
