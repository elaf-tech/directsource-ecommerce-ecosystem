@extends('Users.home')

@section('content')

{{-- تم دمج الـ CSS هنا لتسهيل التطبيق --}}
<style>
    /* === تصميم صفحة إدارة شركات المورد === */

    /* خلفية الصفحة وتنسيقها العام */
    .supplier-portfolio-wrapper {
        background: linear-gradient(to bottom, #ffffff, #f8f9fa);
        padding: 60px 0;
        min-height: 90vh;
    }

    /* رأس الصفحة */
    .portfolio-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 50px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e9ecef;
    }
    .portfolio-header h2 {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--dark-color);
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .portfolio-header h2 i { color: var(--primary-color); }
    .btn-add-company {
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
    .btn-add-company:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    /* شبكة عرض بطاقات الشركات */
    .companies-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
    }

    /* تصميم بطاقة الشركة */
    .company-card {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.07);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .company-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.1);
    }
    .company-card-header { padding: 25px; display: flex; align-items: center; gap: 20px; border-bottom: 1px solid #f1f1f1; }
    .company-logo { width: 60px; height: 60px; border-radius: 50%; flex-shrink: 0; overflow: hidden; border: 2px solid #f0f0f0; }
    .company-logo img { width: 100%; height: 100%; object-fit: cover; }
    .company-name-type h5 { font-size: 1.3rem; font-weight: 700; color: var(--dark-color); margin: 0 0 5px 0; }
    .company-name-type p { font-size: 0.9rem; color: #6c757d; margin: 0; }
    .company-card-body { padding: 25px; flex-grow: 1; }
    .company-details-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 15px; }
    .company-detail-item { display: flex; align-items: flex-start; gap: 15px; font-size: 0.95rem; }
    .company-detail-item i { color: var(--primary-color); margin-top: 4px; width: 16px; text-align: center; }
    .company-detail-item span { color: #495057; }
    .company-card-footer { padding: 20px 25px; background-color: #f8f9fa; display: flex; justify-content: flex-end; gap: 10px; }
    .btn-card-action { padding: 8px 20px; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: var(--transition); }
    .btn-card-action.edit { background-color: var(--secondary-color); color: white; }
    .btn-card-action.edit:hover { background-color: var(--primary-color); }
    .btn-card-action.view { background-color: #e9ecef; color: #495057; }
    .btn-card-action.view:hover { background-color: #ced4da; }

    /* === CSS الجديد لقسم الإجراءات العامة === */
    .general-actions-section {
        margin-top: 60px;
        padding-top: 40px;
        border-top: 1px solid #e9ecef;
    }
    .general-actions-header {
        text-align: center;
        margin-bottom: 40px;
    }
    .general-actions-header h3 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark-color);
    }
    .general-actions-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
        max-width: 800px;
        margin: 0 auto;
    }
    .general-action-card {
        background-color: #fff;
        border-radius: 16px;
        padding: 25px;
        display: flex;
        align-items: center;
        gap: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: var(--transition);
    }
    .general-action-card:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }
    .general-action-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: var(--gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        flex-shrink: 0;
    }
    .general-action-details {
        flex-grow: 1;
    }
    .general-action-details h5 {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0 0 5px 0;
    }
    .general-action-details p {
        font-size: 0.95rem;
        color: #6c757d;
        margin: 0;
    }
    .btn-go-to-action {
        padding: 10px 25px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        background-color: var(--accent-color);
        color: white;
        transition: var(--transition);
        flex-shrink: 0;
    }
    .btn-go-to-action:hover {
        background-color: #e0931a;
        transform: scale(1.05);
    }

</style>

<div class="supplier-portfolio-wrapper">
    <div class="container">
        <div class="portfolio-header">
            <h2><i class="fas fa-briefcase"></i>شركاتي المدارة</h2>
            @if($suppliers->isNotEmpty())
            <a href="{{ route('suppliers.create') }}" class="btn-add-company">
                <i class="fas fa-plus-circle"></i> إضافة شركة جديدة
            </a>
        </div>

       
            <div class="companies-grid">
                @foreach($suppliers as $supplier)
                    <div class="company-card">
                        {{-- ... محتوى بطاقة الشركة يبقى كما هو ... --}}
                        <div class="company-card-header">
                            <div class="company-logo">
                                <img src="{{ $supplier->logo ? asset('storage/' . $supplier->logo) : 'https://ui-avatars.com/api/?name=' . urlencode($supplier->company_name ) . '&background=random&size=60' }}" alt="{{ $supplier->company_name }}">
                            </div>
                            <div class="company-name-type">
                                <h5>{{ $supplier->company_name }}</h5>
                                <p>{{ $supplier->business_type }}</p>
                            </div>
                        </div>
                        <div class="company-card-body">
                            <ul class="company-details-list">
                                <li class="company-detail-item"><i class="fas fa-map-marker-alt fa-fw"></i><span>{{ $supplier->address }}</span></li>
                                <li class="company-detail-item"><i class="fas fa-id-card fa-fw"></i><span>رقم السجل: {{ $supplier->commercial_registration_number }}</span></li>
                                <li class="company-detail-item"><i class="fas fa-credit-card fa-fw"></i><span>الحساب البنكي: {{ $supplier->bank_account }}</span></li>
                            </ul>
                        </div>
                        <div class="company-card-footer">
                            <a href="#" class="btn-card-action view">عرض المنتجات</a>
                            <a href="#" class="btn-card-action edit">تعديل</a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="general-actions-section">
                <div class="general-actions-header">
                    <h3>أدوات الإدارة العامة</h3>
                </div>
                <div class="general-actions-list">
                    <div class="general-action-card">
                        <div class="general-action-icon"><i class="fas fa-sitemap"></i></div>
                        <div class="general-action-details">
                            <h5> استعرض الطلبات</h5>
                            <p> استعرض طلبات عملائك</p>
                        </div>
                        <a href="{{ route('suppliers.sup_orders') }}" class="btn-go-to-action">اذهب الآن <i class="fas fa-arrow-left"></i></a>
                    </div>
                    <!-- إضافة منتج -->
                    <div class="general-action-card">
                        <div class="general-action-icon"><i class="fas fa-box-open"></i></div>
                        <div class="general-action-details">
                            <h5>إدارة المنتجات</h5>
                            <p>إضافة منتج جديد أو تعديل المنتجات الحالية لجميع شركاتك.</p>
                        </div>
                        <a href="{{ route('products.create') }}" class="btn-go-to-action">اذهب الآن <i class="fas fa-arrow-left"></i></a>
                    </div>
                    <!-- إضافة فئة -->
                    <div class="general-action-card">
                        <div class="general-action-icon"><i class="fas fa-sitemap"></i></div>
                        <div class="general-action-details">
                            <h5>إدارة الفئات</h5>
                            <p>إضافة فئات جديدة لتصنيف المنتجات في المتجر.</p>
                        </div>
                        <a href="{{ route('categories.create') }}" class="btn-go-to-action">اذهب الآن <i class="fas fa-arrow-left"></i></a>
                    </div>
                    <!-- إضافة ماركة -->
                    <div class="general-action-card">
                        <div class="general-action-icon"><i class="fas fa-tags"></i></div>
                        <div class="general-action-details">
                            <h5>إدارة الماركات</h5>
                            <p>إضافة ماركات تجارية جديدة لربطها بالمنتجات.</p>
                        </div>
                        <a href="{{ route('brands.create') }}" class="btn-go-to-action">اذهب الآن <i class="fas fa-arrow-left"></i></a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">لم تقم بإضافة أي شركات بعد.</h4>
                <p class="text-muted">ابدأ بإضافة أول شركة تديرها من الزر أعلاه.</p>
            </div>
        @endif

        <!-- === القسم الجديد: الإجراءات العامة === -->
       
    </div>
</div>
@endsection
