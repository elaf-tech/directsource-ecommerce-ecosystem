@extends('Users.home')

@section('content')

{{-- تم دمج الـ CSS هنا لتسهيل التطبيق --}}
<style>
    /* :root variables are already defined in your main layout, so we can use them directly */

    /* خلفية الصفحة وتنسيقها العام */
    .categories-page-container {
        background: linear-gradient(to bottom, #ffffff, #f4f7f6); /* تدرج أبيض خفيف */
        padding: 50px 0;
    }

    /* تصميم العنوان الرئيسي للصفحة */
    .page-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .page-header h2 {
        font-size: 2.8rem;
        font-weight: 800;
        color: var(--dark-color);
        position: relative;
        display: inline-block;
        padding-bottom: 15px;
    }

    .page-header h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        border-radius: 2px;
        background: var(--gradient); /* استخدام التدرج اللوني للخط السفلي */
    }

    /* شبكة عرض البطاقات */
    .categories-grid {
        display: grid;
        /* إنشاء 4 أعمدة في الشاشات الكبيرة، مع تصغيرها تلقائيًا في الشاشات الأصغر */
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 30px; /* المسافة بين البطاقات */
    }

    /* تصميم بطاقة الفئة - تم تصغيرها وتحسينها */
    .category-card-v2 {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: var(--shadow);
        transition: var(--transition);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        text-decoration: none; /* لإزالة الخط تحت النص عند جعل البطاقة رابطًا */
    }

    .category-card-v2:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
    }

    /* حاوية الصورة المصغرة */
    .category-card-v2 .img-container {
        width: 100%;
        height: 180px; /* تصغير ارتفاع الصورة */
        overflow: hidden;
    }

    .category-card-v2 .category-img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* لضمان ملء الصورة للحاوية دون تشويه */
        transition: transform 0.4s ease;
    }

    .category-card-v2:hover .category-img {
        transform: scale(1.1); /* تأثير تكبير بسيط للصورة عند مرور الماوس */
    }

    /* محتوى البطاقة */
    .category-card-v2 .card-content {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .category-card-v2 .category-title {
        font-size: 1.2rem; /* تصغير حجم الخط للعنوان */
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 8px;
    }

    .category-card-v2 .category-desc {
        font-size: 0.9rem; /* تصغير حجم الخط للوصف */
        color: #6c757d;
        line-height: 1.6;
        flex-grow: 1; /* لجعل الوصف يملأ المساحة المتاحة */
        margin-bottom: 15px;
    }

    /* زر "عرض المنتجات" */
    .category-card-v2 .view-products-btn {
        display: inline-block;
        text-align: center;
        padding: 8px 15px;
        border-radius: 50px;
        background: var(--primary-color);
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
        transition: background-color 0.3s ease;
        align-self: flex-start; /* محاذاة الزر لبداية السطر */
    }

    .category-card-v2:hover .view-products-btn {
        background-color: var(--accent-color);
        color: var(--dark-color);
    }

</style>

<div class="categories-page-container">
    <div class="container">
        <div class="page-header">
            <h2>تصفح جميع الفئات</h2>
        </div>

        <div class="categories-grid">
            @forelse($cat as $category)
                {{-- جعل البطاقة بأكملها رابطًا يؤدي إلى منتجات الفئة --}}
                <a href="#" class="category-card-v2">
                    <div class="img-container">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" class="category-img" alt="{{ $category->name }}">
                        @else
                            {{-- صورة افتراضية أفضل --}}
                            <img src="https://via.placeholder.com/300x180.png/f4f7f6/6c757d?text={{ urlencode($category->name ) }}" class="category-img" alt="{{ $category->name }}">
                        @endif
                    </div>
                    <div class="card-content">
                        <h5 class="category-title">{{ $category->name }}</h5>
                        <p class="category-desc">
                            {{-- استخدام Str::limit لضمان عدم تجاوز الوصف للطول المطلوب --}}
                            {{ Str::limit($category->description ?? 'وصف موجز لهذه الفئة المميزة.', 80) }}
                        </p>
                        <span class="view-products-btn">
                            <i class="fas fa-shopping-bag me-2"></i>عرض المنتجات
                        </span>
                    </div>
                </a>
            @empty
                <div class="col-12 text-center">
                    <p class="fs-4 text-muted">عفوًا، لا توجد فئات لعرضها حاليًا.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@stop
