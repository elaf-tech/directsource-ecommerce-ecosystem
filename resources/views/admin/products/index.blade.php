@extends('admin.layouts.app')

@section('title', 'المنتجات')

@section('content')

{{-- تم دمج الـ CSS هنا لتسهيل التطبيق --}}
<style>
    /* === تصميم صفحة جميع المنتجات مع الفلترة === */
    .products-page-wrapper { background: linear-gradient(to bottom, #ffffff, #f8f9fa); padding: 50px 0; position: relative; overflow: hidden; }
    .products-page-wrapper::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 150px; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3e%3cpath fill='%23ffffff' fill-opacity='1' d='M0,128L80,138.7C160,149,320,171,480,170.7C640,171,800,149,960,133.3C1120,117,1280,107,1360,101.3L1440,96L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z'%3e%3c/path%3e%3c/svg%3e"  ); background-repeat: no-repeat; background-size: cover; z-index: 0; }
    .container { position: relative; z-index: 1; }
    .page-header-products { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e9ecef; }
    .page-header-products .section-title { font-size: 2.5rem; font-weight: 800; color: var(--dark-color); display: flex; align-items: center; gap: 15px; }
    .page-header-products .section-title i { color: var(--primary-color); }
    .page-header-products .btn-add-product { display: inline-flex; align-items: center; gap: 8px; padding: 12px 25px; border-radius: 50px; text-decoration: none; font-weight: 700; color: white; background: var(--gradient); transition: var(--transition); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .page-header-products .btn-add-product:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
    .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px; }
    .product-card { background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.07); border: 1px solid transparent; transition: var(--transition); display: flex; flex-direction: column; overflow: hidden; }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-color: var(--primary-color); }
    .product-image-wrapper { height: 220px; overflow: hidden; position: relative; }
    .product-image-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
    .product-card:hover .product-image-wrapper img { transform: scale(1.1); }
    .product-quantity-badge { position: absolute; top: 15px; right: 15px; background-color: rgba(44, 62, 80, 0.8); color: white; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; backdrop-filter: blur(5px); }
    .product-details { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
    .product-details .product-name { font-size: 1.2rem; font-weight: 700; color: var(--dark-color); margin-bottom: 10px; }
    .product-details .product-info { font-size: 0.9rem; color: #6c757d; margin-bottom: 5px; }
    .product-details .product-info strong { color: #495057; }
    .product-price-actions-wrapper { margin-top: auto; padding-top: 15px; border-top: 1px solid #f1f1f1; display: flex; justify-content: space-between; align-items: center; }
    .product-price { font-size: 1.5rem; font-weight: 800; color: var(--primary-color); }
    .btn-add-to-cart { flex-grow: 1; padding: 10px 15px; border-radius: 50px; border: none; background-color: var(--accent-color); color: white; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-add-to-cart:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.15); }

    /* === CSS لقسم الفلترة === */
    .filter-section { background-color: #ffffff; padding: 25px 30px; border-radius: 16px; box-shadow: 0 8px 25px rgba(0,0,0,0.08); margin-bottom: 40px; }
    .filter-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; align-items: flex-end; }
    .filter-group .form-label { font-weight: 600; color: var(--dark-color); margin-bottom: 8px; font-size: 0.9rem; }
    .filter-group .form-control, .filter-group .form-select { width: 100%; padding: 10px 15px; border: 1px solid #dde2e7; border-radius: 10px; font-size: 0.95rem; transition: var(--transition); }
    .filter-group .form-control:focus, .filter-group .form-select:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(78, 107, 58, 0.1); }
    .filter-buttons { display: flex; gap: 10px; }
    .btn-filter { width: 100%; padding: 10px; font-weight: 700; border-radius: 10px; border: none; cursor: pointer; transition: var(--transition); }
    .btn-apply-filter { background-color: var(--primary-color); color: white; }
    .btn-apply-filter:hover { background-color: var(--dark-color); }
    .btn-clear-filter { background-color: #e9ecef; color: #6c757d; text-decoration: none; display: flex; align-items: center; justify-content: center; }
    .btn-clear-filter:hover { background-color: #ced4da; }
    .pagination {
    display: flex;
    justify-content: center;
    padding: 1rem 0;
}

.page-item {
    margin: 0 5px;
}

.page-link {
    color: var(--light-text);
    background: var(--gradient);
    border: 1px solid var(--primary-color);
    border-radius: 0.25rem;
    padding: 0.5rem 1rem;
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.page-link:hover {
    background: var(--accent-color);
    border-color: var(--accent-color);
    color: var(--light-text);
}

.page-item.active .page-link {
    background: var(--secondary-color);
    color: var(--light-text);
    border: 1px solid var(--secondary-color);
    box-shadow: var(--shadow);
}

.page-item.disabled .page-link {
    color: #6c757d; /* لون للروابط المعطلة */
    background-color: var(--light-color); /* خلفية بيضاء */
    border-color: #dee2e6; /* لون الحدود */
}
.products-page-wrapper { background: linear-gradient(to bottom, #ffffff, #f8f9fa); padding: 50px 0; }
    .page-header-products { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e9ecef; }
    .page-header-products .section-title { font-size: 2.5rem; font-weight: 800; color: var(--dark-color); }
    .page-header-products .btn-add-product { display: inline-flex; align-items: center; gap: 8px; padding: 12px 25px; border-radius: 50px; text-decoration: none; font-weight: 700; color: white; background: var(--gradient); transition: var(--transition); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .filter-section { background-color: #ffffff; padding: 25px 30px; border-radius: 16px; box-shadow: 0 8px 25px rgba(0,0,0,0.08); margin-bottom: 40px; }
    .filter-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; align-items: flex-end; }
    /* ... (بقية أنماط الفلترة) ... */

    /* --- تصميم شبكة وبطاقات المنتجات --- */
    .products-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; }
    .product-card { background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.07); border: 1px solid transparent; transition: var(--transition); display: flex; flex-direction: column; overflow: hidden; }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-color: var(--primary-color); }
    .product-image-wrapper { height: 220px; overflow: hidden; position: relative; }
    .product-image-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
    .product-card:hover .product-image-wrapper img { transform: scale(1.1); }
    .product-quantity-badge { position: absolute; top: 15px; right: 15px; background-color: rgba(44, 62, 80, 0.8); color: white; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; backdrop-filter: blur(5px); }
    .product-details { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
    .product-details .product-name { font-size: 1.2rem; font-weight: 700; color: var(--dark-color); margin-bottom: 10px; }
    .product-details .product-info { font-size: 0.9rem; color: #6c757d; margin-bottom: 5px; }
    
    /* --- **الجديد: تصميم قسم السعر والأزرار** --- */
    .product-footer {
        margin-top: auto; /* يدفع هذا القسم لأسفل البطاقة دائماً */
        padding: 20px;
        border-top: 1px solid #f1f1f1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .product-price {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary-color);
    }
    .product-actions {
        display: flex;
        gap: 8px;
    }
    .btn-action {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        transition: all 0.2s ease;
    }
    .btn-edit { background-color: #e0e7ff; color: #4338ca; }
    .btn-edit:hover { background-color: #c7d2fe; transform: translateY(-2px); }
    .btn-delete { background-color: #fee2e2; color: #b91c1c; }
    .btn-delete:hover { background-color: #fecaca; transform: translateY(-2px); }

</style>

<div class="products-page-wrapper">
    <div class="container">
        <div class="page-header-products">
            <h2 class="section-title"><i class="fas fa-cubes"></i>جميع المنتجات</h2>
            <a href="{{ route('productad.create') }}" class="btn-add-product">
                <i class="fas fa-plus-circle"></i> إضافة منتج جديد
            </a>
        </div>

        <!-- === قسم الفلترة الجديد === -->
        <div class="filter-section">
            <form action="{{ route('productad.index') }}" method="GET" class="filter-form">
                <div class="filter-group" style="grid-column: 1 / -1; margin-bottom: -10px;">
                    <label for="search" class="form-label">ابحث عن منتج</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="اسم المنتج، ماركة، فئة..." value="{{ request('search') }}">
                </div>
                <div class="filter-group">
                    <label for="category" class="form-label">الفئة</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">الكل</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="brand" class="form-label">الماركة</label>
                    <select name="brand" id="brand" class="form-select">
                        <option value="">الكل</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="supplier" class="form-label">المورد</label>
                    <select name="supplier" id="supplier" class="form-select">
                        <option value="">الكل</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group filter-buttons">
                    <button type="submit" class="btn-filter btn-apply-filter">تطبيق</button>
                    <a href="{{ route('productad.index') }}" class="btn-filter btn-clear-filter" title="إلغاء الفلترة">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>

        @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                <div class="product-card">
                    <div class="product-image-wrapper">
                        @if($product['image'])
                            <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}">
                        @else
                            <img src="https://via.placeholder.com/300x220.png/f8f9fa/6c757d?text=No+Image" alt="No Image">
                        @endif
                        <span class="product-quantity-badge">الكمية: {{ $product['quantity'] }}</span>
                    </div>
                    <div class="product-details">
                        <h5 class="product-name">{{ $product['name'] }}</h5>
                        <p class="product-info"><strong>الفئة:</strong> {{ $product['category']['name'] }}</p>
                        <p class="product-info"><strong>الماركة:</strong> {{ $product['brand']['name'] }}</p>
                        <p class="product-info"><strong>المورد:</strong> {{ $product['supplier']['company_name'] }}</p>
                        
                    
                        
                    </div>
                    <div class="product-footer">
                        <p class="product-price">{{ number_format($product['price'], 2 ) }} ر.س</p>
                        <div class="product-actions">
                            <a href="{{route('productad.edit',$product['id'])}}" class="btn-action btn-edit" title="تعديل المنتج">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{route('productad.destroy',$product['id'])}}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="حذف المنتج">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-5 d-flex justify-content-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
            
                        @for ($i = 1; $i <= $products->lastPage(); $i++)
                            <li class="page-item {{ $i == $products->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
            
                       
                    </ul>
                </nav>
            </div>
            
        @else
            <div class="text-center py-5">
                <i class="fas fa-search-minus fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">لا توجد منتجات تطابق بحثك.</h4>
                <p class="text-muted">جرّب تغيير كلمات البحث أو إلغاء الفلاتر.</p>
                <a href="{{ route('productad.index') }}" class="btn btn-primary mt-3">عرض كل المنتجات</a>
            </div>
        @endif
    </div>
</div>
@endsection
