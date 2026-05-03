@extends('Users.home')

{{-- هذا هو الكود الصحيح الذي سيتم تطبيقه الآن --}}
<style>
    /* === الحل النهائي مع خاصية !important === */
    .hero-section {
        position: relative !important; 
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        color: white !important;
        min-height: 450px !important; 
        padding: 40px 20px !important;
    
        /* 
        استخدام !important لإجبار المتصفح على تطبيق هذه الخلفية
        */
        background: 
            linear-gradient(90deg, rgba(44, 62, 80, 0.75) 0%, rgba(78, 107, 58, 0.65) 100%), 
            url("{{ asset('assets/images/sh.jpg') }}") !important;
    
        background-size: cover !important;
        background-position: center center !important;
        
        border: none !important;
        border-radius: 20px !important; 
        margin: 30px 0 !important; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    }
    
    /* بقية كود CSS يبقى كما هو */
    .hero-content {
        max-width: 800px; 
    }
    
    .hero-content h1 {
        font-size: 3.2rem;
        font-weight: 800;
        margin-bottom: 15px;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
    }
    
    .hero-content p {
        font-size: 1.4rem;
        margin-bottom: 30px;
        opacity: 0.95;
    }
    </style>
    

@section('content')
    <!-- القسم الرئيسي في الصفحة -->
    {{-- لقد قمنا بحذف الـ style المباشر من هنا --}}
    <div class="hero-section">
        <div class="hero-content">
            <h1>تسوق مباشرة من المورد بثقة وسهولة</h1>
            <p>اكتشف عالمًا من المنتجات عالية الجودة بأفضل الأسعار مباشرة من موردينا المعتمدين.</p>
            <a href="#products" class="btn btn-primary">ابدأ التسوق الآن</a>
        </div>
    </div>
    <div class="container">

    
    {{-- ... بقية محتوى الصفحة ... --}}



    <!-- قسم المميزات -->
    <div class="features">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-truck"></i>
            </div>
            <h3 class="feature-title">شحن سريع</h3>
            <p class="feature-desc">خدمة توصيل سريعة لجميع أنحاء المملكة</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3 class="feature-title">دفع آمن</h3>
            <p class="feature-desc">أنظمة دفع إلكتروني آمنة ومحمية</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-headset"></i>
            </div>
            <h3 class="feature-title">دعم فني</h3>
            <p class="feature-desc">خدمة عملاء متاحة 24/7 لمساعدتك</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-undo"></i>
            </div>
            <h3 class="feature-title">إرجاع سهل</h3>
            <p class="feature-desc">إمكانية إرجاع المنتجات خلال 14 يوم</p>
        </div>
    </div>

    <!-- قسم الفئات -->
    <div class="section">
        <div class="section-header">
            <h2 class="section-title"><i class="fas fa-list"></i> تصفح الفئات</h2>
            <a href="{{ route('categories.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> إضافة فئة
            </a>
    
            <a href="{{route('products.index')}}" class="view-all"><i class="fas fa-arrow-left"></i> عرض الكل</a>
        </div>
        <div class="categories-container">
            @foreach ($cat as $c)
            <a href="{{route('products.index')}}" class="category-card">
                <div class="category-image-container">
                    <span class="category-products">120 منتج</span>
                    <img src="{{ asset('storage/' . $c->image) }}" alt="{{ $c->name }}" class="category-image">
                </div>
                <span class="category-name">{{ $c->name }}</span>
            </a>
            @endforeach
        </div>
    </div>

    <!-- قسم أشهر الماركات -->
    <div class="section">
        <div class="section-header">
            <h2 class="section-title"><i class="fas fa-star"></i> أشهر الماركات</h2>
            <a href="{{ route('brands.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> إضافة ماركة
            </a>
            <a href="{{route('products.index')}}" class="view-all"><i class="fas fa-arrow-left"></i> عرض الكل</a>
        </div>
        <div class="horizontal-scroll">
            @foreach ($brand as $b)
            <a href="{{route('products.index')}}" class="brand-card">
                <div class="brand-icon">
                    <img src="{{ asset('storage/' . $b->image) }}" alt="{{ $b->name }}" class="brand-image">
                </div>
                <span class="brand-name">{{ $b->name }}</span>
            </a>
            @endforeach
        </div>
    </div>

    <!-- قسم المنتجات -->
    <div class="section">
        <div class="section-header">
            <h2 class="section-title"><i class="fas fa-box-open"></i> أحدث المنتجات</h2>
            <a href="{{ route('products.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> إضافة منتج
            </a>
            <a href="{{route('products.index')}}" class="view-all"><i class="fas fa-arrow-left"></i> عرض الكل</a>
        </div>
        <div class="cards-container">
            @foreach ($product as $p)
            <div class="card">
                <div class="card-img" style="background-image: url('{{ asset('storage/' . $p->image) }}')">
                    <span class="card-badge">جديد</span>
                </div>
                <div class="card-content">
                    <h3 class="card-title">{{ $p->name }}</h3>
                    <p class="card-desc">{{ $p->description }}</p>
                    <div class="card-price-container">
                        <span class="card-price">{{ $p->price }} ر.س</span>
                    </div>
                    <div class="card-actions">
                        <a href="{{ route('products.show', $p->id) }}"  class="btn btn-primary"><i class="fas fa-shopping-cart"></i> إضافة إلى السلة</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- قسم الموردين -->
    <div class="section">
        <div class="section-header">
            <h2 class="section-title"><i class="fas fa-store"></i> موردونا المعتمدون</h2>
            <a href="{{ route('suppliers.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> إضافة مورد
            </a>
            <a href="{{route('suppliers.index')}}" class="view-all"><i class="fas fa-arrow-left"></i> عرض الكل</a>
        </div>
        <div class="horizontal-scroll">
            @foreach ($sup as $s)
            <div class="brand-card">
                <div class="brand-icon">
                    <img src="{{ asset('storage/' . $s->logo) }}" alt="{{ $s->company_name }}" class="brand-image">
                </div>
                <span class="brand-name">{{ $s->company_name }}</span>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- قسم العروض الخاصة -->
    <div class="offer-section" id="offers">
        <div class="offer-content">
            <h2 class="offer-title">عروض نهاية الموسم</h2>
            <p class="offer-desc">خصومات تصل إلى 50% على مجموعة واسعة من المنتجات. عروض محدودة لفترة محدودة!</p>
            
            <div class="offer-timer">
                <div class="timer-item">
                    <span class="timer-value">02</span>
                    <span class="timer-label">أيام</span>
                </div>
                <div class="timer-item">
                    <span class="timer-value">12</span>
                    <span class="timer-label">ساعات</span>
                </div>
                <div class="timer-item">
                    <span class="timer-value">45</span>
                    <span class="timer-label">دقائق</span>
                </div>
                <div class="timer-item">
                    <span class="timer-value">30</span>
                    <span class="timer-label">ثواني</span>
                </div>
            </div>
            
            <a href="#" class="btn btn-primary">استفد من العروض الآن</a>
        </div>
    </div>
</div>

@endsection