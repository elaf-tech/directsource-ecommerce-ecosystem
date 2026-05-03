@extends('admin.layouts.app')

@section('title', 'إدارة الطلبات')

@section('content')
<style>
    /* --- تصميم الصفحة العام --- */
    .page-content { background: linear-gradient(120deg, #f7f9f6 0%, #f0f5eb 100%); border-radius: 20px; padding: 30px !important; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .page-title { font-size: 2.2rem; font-weight: 800; color: var(--dark-color); display: flex; align-items: center; gap: 15px; }
    .page-title i { color: var(--primary-color); }

    /* --- شريط أدوات الفلترة --- */
    .filter-toolbar { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); border-radius: 16px; padding: 20px; margin-bottom: 25px; box-shadow: 0 8px 32px 0 rgba(44, 62, 80, 0.08); }
    .filter-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: flex-end; }
    .filter-group label { font-weight: 600; color: var(--dark-color); display: block; margin-bottom: 8px; font-size: 0.9rem; }
    .filter-control { width: 100%; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 15px; transition: all 0.3s ease; }
    .filter-control:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(78, 107, 58, 0.1); }
    .filter-buttons { display: flex; gap: 10px; }
    .btn-filter { padding: 10px 20px; border-radius: 10px; border: none; font-weight: 700; cursor: pointer; transition: all 0.2s ease; }
    .btn-apply-filter { background: var(--primary-color); color: white; }
    .btn-clear-filter { background: #fff; color: #4b5563; border: 1px solid #d1d5db; text-decoration: none; display: flex; align-items: center; justify-content: center; }

    /* --- تصميم بطاقة الطلب --- */
    .order-card { background: #fff; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 20px; border-left: 5px solid; transition: all 0.3s ease; }
    .order-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    
    /* ألوان الحالة */
    .order-card.status-pending { border-left-color: #f8a31b; }
    .order-card.status-processing { border-left-color: #3b82f6; }
    .order-card.status-shipped { border-left-color: #8b5cf6; }
    .order-card.status-completed { border-left-color: #10b981; }
    .order-card.status-cancelled { border-left-color: #ef4444; }

    .card-header { padding: 20px; display: flex; justify-content: space-between; align-items: center; cursor: pointer; }
    .order-summary h5 { font-weight: 700; color: var(--dark-color); }
    .order-summary p { color: #6c757d; font-size: 0.9rem; margin: 0; }
    .order-meta { text-align: right; }
    .total-price { font-size: 1.3rem; font-weight: 800; color: var(--primary-color); }
    .toggle-icon { font-size: 1.2rem; color: #9ca3af; transition: transform 0.3s ease; }
    .toggle-icon.expanded { transform: rotate(180deg); }

    .card-body { padding: 0 20px 20px 20px; }
    .order-details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; border-top: 1px solid #e5e7eb; padding-top: 15px; }
    .details-section h6 { font-weight: 700; margin-bottom: 10px; }
    .product-list { list-style: none; padding: 0; }
    .product-item { display: flex; justify-content: space-between; font-size: 0.95rem; padding: 5px 0; }
    .product-item .name { color: var(--dark-color); }
    .product-item .price { color: #6c757d; }
    .filter-form {
        /* تعديل بسيط ليدعم المزيد من الفلاتر */
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    }
    .no-orders-message { text-align: center; padding: 50px; background: rgba(255, 255, 255, 0.9); border-radius: 20px; }
</style>
<div class="page-header">
    <h2 class="page-title"><i class="fas fa-receipt"></i> إدارة الطلبات</h2>
</div>

<!-- قسم الفلترة -->
<div class="filter-toolbar">
    <form action="{{ route('orderad.index') }}" method="GET" class="filter-form">
        <div class="filter-group" style="grid-column: 1 / -1;">
            <label for="q">بحث عام (العميل, المنتج)</label>
            <input type="text" id="q" name="q" class="filter-control" placeholder="اكتب اسم العميل, بريده, أو اسم المنتج..." value="{{ request('q') }}">
        </div>
        <div class="filter-group">
            <label for="status">حالة الطلب</label>
            <select id="status" name="status" class="filter-control">
                {{-- ... خيارات الحالة ... --}}
            </select>
        </div>
        <div class="filter-group">
            <label for="category_id">الفئة</label>
            <select id="category_id" name="category_id" class="filter-control">
                {{-- ... خيارات الفئات ... --}}
            </select>
        </div>
        <div class="filter-group">
            <label for="brand_id">الماركة</label>
            <select id="brand_id" name="brand_id" class="filter-control">
                {{-- ... خيارات الماركات ... --}}
            </select>
        </div>

        <!-- **الجديد: فلتر المورد** -->
        <div class="filter-group">
            <label for="supplier_id">المورد</label>
            <select id="supplier_id" name="supplier_id" class="filter-control">
                <option value="">الكل</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="filter-buttons">
            <button type="submit" class="btn-filter btn-apply-filter">فلترة</button>
            <a href="{{ route('orderad.index') }}" class="btn-filter btn-clear-filter" title="مسح الفلتر"><i class="fas fa-times"></i></a>
        </div>
    </form>
</div>


@forelse($orders as $order)
    <div class="order-card status-{{ $order->status }}">
        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#orderDetails{{ $order->id }}">
            <div class="order-summary">
                <h5>طلب #{{ $order->id }}</h5>
                <p>العميل: {{ $order->user->name ?? 'غير مسجل' }} - تاريخ الطلب: {{ $order->created_at->format('Y-m-d') }}</p>
            </div>
            <div class="order-meta">
                <div class="total-price">{{ number_format($order->total_price, 2) }} ر.س</div>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
        </div>
        <div class="collapse" id="orderDetails{{ $order->id }}">
            <div class="card-body">
                <div class="order-details-grid">
                    <div class="details-section">
                        <h6>تفاصيل العميل والشحن</h6>
                        <p><strong>البريد الإلكتروني:</strong> {{ $order->user->email ?? '-' }}</p>
                        <p><strong>طريقة الدفع:</strong> {{ $order->payment_method }}</p>
                        <p><strong>العنوان:</strong> {{ $order->address->full_address ?? 'لا يوجد عنوان' }}</p>
                    </div>
                    <div class="details-section">
                        <h6>المنتجات ({{ $order->orderItems->count() }})</h6>
                        <ul class="product-list">
                            @foreach($order->orderItems as $item)
                                <li class="product-item">
                                    <span class="name">{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                    <span class="price">{{ number_format($item->price * $item->quantity, 2) }} ر.س</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="no-orders-message">
        <h3>لا توجد طلبات تطابق بحثك</h3>
        <p>حاول تغيير كلمات البحث أو الفلاتر.</p>
    </div>
@endforelse

<div class="mt-4">
    {{ $orders->links() }}
</div>

@endsection
