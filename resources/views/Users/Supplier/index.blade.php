@extends('Users.home')

@section('content')

{{-- تم دمج الـ CSS هنا لتسهيل التطبيق --}}
<style>
    /* === تصميم صفحة جميع الموردين الجديدة === */

    /* خلفية الصفحة وتنسيقها العام */
    .suppliers-page-wrapper {
        background: linear-gradient(to bottom, #ffffff, #f8f9fa); /* تدرج أبيض خفيف */
        padding: 50px 0;
        position: relative;
        overflow: hidden;
    }

    /* تأثير الموجة في أسفل الصفحة */
    .suppliers-page-wrapper::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 150px;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3e%3cpath fill='%23ffffff' fill-opacity='1' d='M0,128L80,138.7C160,149,320,171,480,170.7C640,171,800,149,960,133.3C1120,117,1280,107,1360,101.3L1440,96L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z'%3e%3c/path%3e%3c/svg%3e" );
        background-repeat: no-repeat;
        background-size: cover;
        z-index: 0;
    }

    .container {
        position: relative;
        z-index: 1;
    }

    /* رأس الصفحة (العنوان والزر) */
    .page-header-suppliers {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .page-header-suppliers .section-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--dark-color);
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .page-header-suppliers .section-title i {
        color: var(--primary-color);
    }

    .page-header-suppliers .btn-add-supplier {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 25px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        color: white;
        background: var(--gradient);
        transition: var(--transition);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .page-header-suppliers .btn-add-supplier:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    /* شبكة عرض بطاقات الموردين */
    .suppliers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
    }

    /* تصميم بطاقة المورد الأنيقة */
    .supplier-card {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        border: 1px solid #e9ecef;
        transition: var(--transition);
        display: flex;
        padding: 20px;
        gap: 20px;
    }
    .supplier-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: var(--primary-color);
    }

    /* شعار المورد */
    .supplier-logo-wrapper {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
        border-radius: 50%;
        overflow: hidden;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #e9ecef;
        transition: var(--transition);
    }
    .supplier-card:hover .supplier-logo-wrapper {
        border-color: var(--accent-color);
    }
    .supplier-logo-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .supplier-logo-wrapper .fa-store {
        font-size: 2rem;
        color: #ced4da;
    }

    /* تفاصيل المورد */
    .supplier-details {
        flex-grow: 1;
    }
    .supplier-details .company-name {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 5px;
    }
    .supplier-details .business-type {
        font-size: 0.95rem;
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 12px;
    }
    .supplier-details .info-line {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 5px;
    }
    .supplier-details .info-line i {
        width: 15px;
        text-align: center;
        color: #909aa3;
    }

    /* أزرار الإجراءات */
    .supplier-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .btn-action {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: var(--transition);
    }
    .btn-edit { background-color: var(--secondary-color); }
    .btn-edit:hover { background-color: var(--accent-color); }
    .btn-delete { background-color: #dc3545; }
    .btn-delete:hover { background-color: #a71d2a; }

</style>

<div class="suppliers-page-wrapper">
    <div class="container">
        <div class="page-header-suppliers">
            <h2 class="section-title"><i class="fas fa-truck-loading"></i>موردينا المعتمدون</h2>
            <a href="{{ route('suppliers.create') }}" class="btn-add-supplier">
                <i class="fas fa-plus-circle"></i> إضافة مورد جديد
            </a>
        </div>

        <div class="suppliers-grid">
            @forelse($supplier as $supplier)
            <div class="supplier-card">
                <div class="supplier-logo-wrapper">
                    @if($supplier->logo)
                        <img src="{{ asset('storage/' . $supplier->logo) }}" alt="{{ $supplier->company_name }}">
                    @else
                        <i class="fas fa-store"></i>
                    @endif
                </div>
                <div class="supplier-details">
                    <h5 class="company-name">{{ $supplier->company_name }}</h5>
                    <p class="business-type">{{ $supplier->business_type }}</p>
                    <div class="info-line">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $supplier->address }}</span>
                    </div>
                    <div class="info-line">
                        <i class="fas fa-id-card"></i>
                        <span>{{ $supplier->identity_number }}</span>
                    </div>
                </div>
                <div class="supplier-actions">
                    {{-- <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn-action btn-edit" title="تعديل">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف المورد؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete" title="حذف">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form> --}}
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <p class="fs-4 text-muted">لا يوجد موردون لعرضهم حاليًا.</p>
                <p class="text-muted">يمكنك البدء بإضافة مورد جديد من الزر أعلاه.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        {{-- <div class="mt-5 d-flex justify-content-center">
            {{ $supplier->links() }}
        </div> --}}
    </div>
</div>
@endsection
