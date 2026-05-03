@extends('admin.layouts.app')

@section('title', 'إدارة الماركات')

@section('content')
<style>
    /* --- تصميم الصفحة العام --- */
    .page-content { background: linear-gradient(120deg, #f7f9f6 0%, #f0f5eb 100%); border-radius: 20px; padding: 30px !important; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .page-title { font-size: 2.2rem; font-weight: 800; color: var(--dark-color); display: flex; align-items: center; gap: 15px; }
    .page-title i { color: var(--primary-color); }
    .btn-add-category { background: var(--gradient); color: white; padding: 12px 25px; border-radius: 12px; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(78, 107, 58, 0.2); }
    .btn-add-category:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(78, 107, 58, 0.3); }

    /* --- شبكة عرض بطاقات الماركات --- */
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
    }

    /* --- تصميم بطاقة الماركة --- */
    .category-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px);
        border-radius: 20px;
        box-shadow: 0 8px 32px 0 rgba(44, 62, 80, 0.1);
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px 0 rgba(44, 62, 80, 0.15);
    }

    .category-image-wrapper {
        height: 200px;
        overflow: hidden;
    }
    .category-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .category-card:hover .category-image {
        transform: scale(1.1);
    }

    .card-content {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .category-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 8px;
    }
    .category-desc {
        font-size: 0.95rem;
        color: #6c757d;
        line-height: 1.6;
        flex-grow: 1;
        margin-bottom: 15px;
    }

    /* --- **الجديد: قسم أزرار الإجراءات** --- */
    .card-footer {
        padding: 15px 20px;
        background: rgba(249, 250, 251, 0.8);
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    .btn-action {
        padding: 8px 18px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-edit { background-color: #e0e7ff; color: #4338ca; }
    .btn-edit:hover { background-color: #c7d2fe; }
    .btn-delete { background-color: #fee2e2; color: #b91c1c; }
    .btn-delete:hover { background-color: #fecaca; }

    .no-categories-message {
        grid-column: 1 / -1;
        text-align: center;
        padding: 50px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 20px;
    }
</style>

<div class="page-header">
    <h2 class="page-title"><i class="fas fa-sitemap"></i> إدارة الماركات</h2>
    <a href="{{ route('brandad.create') }}" class="btn-add-category">
        <i class="fas fa-plus"></i> إضافة ماركة جديدة
    </a>
</div>

<div class="categories-grid">
    @forelse($brand as $brand)
        <div class="category-card">
            <div class="category-image-wrapper">
                @if($brand->image)
                    <img src="{{ asset('storage/' . $brand->image) }}" class="category-image" alt="{{ $brand->name }}">
                @else
                    <img src="https://via.placeholder.com/300x200.png/f0f5eb/4e6b3a?text={{ urlencode($brand->name ) }}" class="category-image" alt="{{ $brand->name }}">
                @endif
            </div>
            <div class="card-content">
                <h5 class="category-title">{{ $brand->name }}</h5>
                <p class="category-desc">
                    {{ Str::limit($brand->description ?? 'لا يوجد وصف لهذه الماركة.', 90) }}
                </p>
            </div>
            
            <!-- **الجديد: تذييل البطاقة مع أزرار الإجراءات** -->
            <div class="card-footer">
                <a href="{{ route('brandad.edit', $brand->id) }}" class="btn-action btn-edit">
                    <i class="fas fa-pen"></i> تعديل
                </a>
                <form action="{{ route('brandad.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الماركة؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-delete">
                        <i class="fas fa-trash"></i> حذف
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="no-categories-message">
            <h3>لا توجد فئات حالياً</h3>
            <p>يمكنك إضافة ماركة جديدة من الزر في الأعلى.</p>
        </div>
    @endforelse
</div>

@endsection
