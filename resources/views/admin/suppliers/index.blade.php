@extends('admin.layouts.app')

@section('title', 'إدارة الموردين')

@section('content')
<style>
    /* ... (نفس الأنماط السابقة) ... */
    .page-content { background: linear-gradient(120deg, #f7f9f6 0%, #f0f5eb 100%); border-radius: 20px; padding: 30px !important; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .page-title { font-size: 2.2rem; font-weight: 800; color: var(--dark-color); display: flex; align-items: center; gap: 15px; }
    .btn-add-supplier { background: var(--gradient); color: white; padding: 12px 25px; border-radius: 12px; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(78, 107, 58, 0.2); }
    .suppliers-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 25px; }
    .supplier-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); border-radius: 20px; box-shadow: 0 8px 32px 0 rgba(44, 62, 80, 0.1); display: flex; flex-direction: column; transition: all 0.3s ease; }
    .supplier-card:hover { transform: translateY(-8px); box-shadow: 0 12px 40px 0 rgba(44, 62, 80, 0.15); }
    .card-header { padding: 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); }
    .header-info { display: flex; align-items: center; gap: 15px; }
    .supplier-logo { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary-color); flex-shrink: 0; }
    .company-info h4 { font-size: 1.2rem; font-weight: 700; color: var(--dark-color); margin: 0; }
    .company-info p { font-size: 0.9rem; color: #6c757d; margin: 0; }
    
    /* --- شارة الحالة (Status Badge) --- */
    .status-badge { padding: 5px 12px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; text-transform: capitalize; }
    .status-pending { background-color: #fffbeb; color: #b45309; }
    .status-confirmed { background-color: #d1fae5; color: #065f46; }

    .card-body { padding: 20px; flex-grow: 1; }
    .details-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px; }
    .detail-item { display: flex; align-items: flex-start; gap: 12px; font-size: 0.95rem; }
    .detail-item i { color: var(--secondary-color); margin-top: 3px; width: 16px; text-align: center; }
    
    .card-footer { padding: 15px 20px; background: rgba(249, 250, 251, 0.8); border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; gap: 10px; }
    .actions-group { display: flex; gap: 10px; }
    .btn-action { padding: 8px 18px; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: all 0.2s ease; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
    .btn-edit { background-color: #e0e7ff; color: #4338ca; }
    .btn-delete { background-color: #fee2e2; color: #b91c1c; }
    .btn-approve { background-color: #d1fae5; color: #065f46; }
    .btn-approve:hover { background-color: #a7f3d0; }
    .page-content { background: linear-gradient(120deg, #f7f9f6 0%, #f0f5eb 100%); border-radius: 20px; padding: 30px !important; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .page-title { font-size: 2.2rem; font-weight: 800; color: var(--dark-color); display: flex; align-items: center; gap: 15px; }
    .btn-add-supplier { background: var(--gradient); color: white; padding: 12px 25px; border-radius: 12px; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(78, 107, 58, 0.2); }
    
    /* --- **الجديد: شريط أدوات الفلترة** --- */
    .filter-toolbar {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 8px 32px 0 rgba(44, 62, 80, 0.08);
    }
    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-end;
    }
    .filter-group { flex-grow: 1; }
    .filter-group label { font-weight: 600; color: var(--dark-color); display: block; margin-bottom: 8px; font-size: 0.9rem; }
    .filter-control {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }
    .filter-control:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(78, 107, 58, 0.1); }
    .btn-filter {
        padding: 10px 25px;
        border-radius: 10px;
        border: none;
        background: var(--primary-color);
        color: white;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-filter:hover { background-color: #3e552e; }
    .btn-clear-filter {
        padding: 10px 25px;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #4b5563;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
    }

    .suppliers-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 25px; }

</style>
<div class="page-header">
    <h2 class="page-title"><i class="fas fa-truck-loading"></i> إدارة الموردين</h2>
    <a href="{{ route('supplierad.create') }}" class="btn-add-supplier"><i class="fas fa-plus"></i> إضافة مورد جديد</a>
</div>

<!-- **الجديد: قسم الفلترة** -->
<div class="filter-toolbar">
    <form action="{{ route('supplierad.index') }}" method="GET" class="filter-form">
        <div class="filter-group" style="min-width: 300px;">
            <label for="q">بحث عام</label>
            <input type="text" id="q" name="q" class="filter-control" placeholder="ابحث بالاسم, الشركة, السجل..." value="{{ request('q') }}">
        </div>
        <div class="filter-group">
            <label for="status">الحالة</label>
            <select id="status" name="status" class="filter-control">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>الكل</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>معتمد</option>
            </select>
        </div>
        <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> فلترة</button>
        <a href="{{ route('supplierad.index') }}" class="btn-clear-filter">مسح الفلتر</a>
    </form>
</div>

<div class="suppliers-grid">
    @forelse($suppliers as $supplier)
        {{-- كود بطاقة المورد يبقى كما هو بدون تغيير --}}
        <div class="supplier-card">
            <div class="card-header">
                <div class="header-info">
                    <img src="{{ $supplier->logo ? asset('storage/'.$supplier->logo) : 'https://ui-avatars.com/api/?name=' . urlencode($supplier->company_name ) . '&background=8aa57c&color=fff' }}" alt="شعار الشركة" class="supplier-logo">
                    <div class="company-info">
                        <h4>{{ $supplier->company_name }}</h4>
                        <p>{{ $supplier->business_type }}</p>
                    </div>
                </div>
                <span class="status-badge status-{{ $supplier->status }}">
                    {{ $supplier->status == 'confirmed' ? 'معتمد' : 'قيد المراجعة' }}
                </span>
            </div>
            <div class="card-body">
                <ul class="details-list">
                    <li class="detail-item"><i class="fas fa-user-tie fa-fw"></i><span><strong>المستخدم:</strong> {{ $supplier->user->name ?? 'غير مرتبط' }}</span></li>
                    <li class="detail-item"><i class="fas fa-map-marker-alt fa-fw"></i><span><strong>العنوان:</strong> {{ $supplier->address }}</span></li>
                    <li class="detail-item"><i class="fas fa-id-card fa-fw"></i><span><strong>السجل التجاري:</strong> {{ $supplier->commercial_registration_number }}</span></li>
                    <li class="detail-item"><i class="fas fa-credit-card fa-fw"></i><span><strong>الحساب البنكي:</strong> {{ $supplier->bank_account }}</span></li>
                </ul>
            </div>
            <div class="card-footer">
                @if($supplier->status == 'pending')
                    <form action="{{ route('suppliers.approve', $supplier->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-action btn-approve"><i class="fas fa-check-circle"></i> اعتماد</button>
                    </form>
                @else
                    <div></div>
                @endif
                <div class="actions-group">
                    <a href="{{ route('supplierad.edit', $supplier->id) }}" class="btn-action btn-edit"><i class="fas fa-pen"></i> تعديل</a>
                    <form action="{{ route('supplierad.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete"><i class="fas fa-trash"></i> حذف</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="no-suppliers-message">
            <h3>لا توجد نتائج مطابقة</h3>
            <p>حاول تغيير كلمات البحث أو الفلترة.</p>
        </div>
    @endforelse
</div>

<!-- **الجديد: عرض روابط الـ Pagination** -->
<div class="mt-4">
    {{ $suppliers->links() }}
</div>

@endsection
