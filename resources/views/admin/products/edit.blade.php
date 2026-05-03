@extends('admin.layouts.app')

@section('title', 'تعديل المنتج')

@section('content')
<style>
    /* --- تصميم الصفحة العام --- */
    .page-content { background: linear-gradient(120deg, #f7f9f6 0%, #f0f5eb 100%); border-radius: 20px; padding: 30px !important; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .page-title { font-size: 2.2rem; font-weight: 800; color: var(--dark-color); display: flex; align-items: center; gap: 15px; }
    .page-title i { color: var(--primary-color); }
    .btn-back { background: #fff; color: var(--dark-color); padding: 10px 20px; border-radius: 12px; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; border: 1px solid var(--border-color); }
    .btn-back:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(44, 62, 80, 0.1); }

    /* --- بطاقة النموذج الرئيسية --- */
    .form-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); border-radius: 20px; box-shadow: 0 8px 32px 0 rgba(44, 62, 80, 0.1); overflow: hidden; }
    .form-header { padding: 25px 30px; background: var(--gradient); color: white; display: flex; align-items: center; gap: 15px; }
    .form-header i { font-size: 1.8rem; }
    .form-header h3 { font-size: 1.5rem; font-weight: 700; margin: 0; }
    
    .form-body { padding: 30px; }
    .form-section { margin-bottom: 30px; }
    .form-section-title { font-size: 1.1rem; font-weight: 700; color: var(--dark-color); margin-bottom: 20px; border-right: 4px solid var(--primary-color); padding-right: 12px; }
    
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; }
    .form-group { position: relative; }
    .form-group .form-icon { position: absolute; top: 50%; right: 15px; transform: translateY(-50%); color: #aaa; }
    .form-control { width: 100%; border: 1px solid #e5e7eb; border-radius: 12px; padding: 12px 45px 12px 20px; transition: all 0.3s ease; background-color: #fff; }
    .form-control:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(78, 107, 58, 0.1); }
    .form-control.select-control, .form-control.textarea-control { padding: 12px 20px; }
    .text-danger { color: #dc2626; font-size: 0.85rem; margin-top: 5px; display: block; }

    /* --- قسم الصورة --- */
    .image-upload-section { display: flex; align-items: center; gap: 20px; }
    .current-image-preview { width: 100px; height: 100px; border-radius: 12px; object-fit: cover; border: 3px solid var(--primary-color); }
    .file-input-wrapper { flex-grow: 1; }
    .form-control[type="file"] { padding: 8px; }

    .form-footer { padding: 20px 30px; background: rgba(249, 250, 251, 0.8); border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 12px; }
    .btn { padding: 12px 28px; border-radius: 12px; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.3s ease; }
    .btn-save { background: var(--gradient); color: white; box-shadow: 0 4px 15px rgba(78, 107, 58, 0.2); }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(78, 107, 58, 0.3); }
    .btn-cancel { background-color: #fff; color: #6c757d; border: 1px solid #e5e7eb; }
    .btn-cancel:hover { background-color: #f3f4f6; }
</style>

<div class="page-header">
    <h2 class="page-title"><i class="fas fa-edit"></i> تعديل المنتج</h2>
    <a href="{{ route('productad.index') }}" class="btn-back"><i class="fas fa-arrow-right"></i> العودة لقائمة المنتجات</a>
</div>

<div class="form-card">
    <form action="{{ route('productad.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-header">
            <i class="fas fa-cube"></i>
            <h3>تعديل بيانات: {{ $product->name }}</h3>
        </div>

        <div class="form-body">
            <!-- القسم الأول: المعلومات الأساسية -->
            <div class="form-section">
                <h4 class="form-section-title">المعلومات الأساسية</h4>
                <div class="form-group mb-3">
                    <label>اسم المنتج</label>
                    <input class="form-control" type="text" name="name" value="{{ old('name', $product->name) }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label>الوصف</label>
                    <textarea name="description" class="form-control textarea-control" rows="4">{{ old('description', $product->description) }}</textarea>
                    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- القسم الثاني: التفاصيل والمخزون -->
            <div class="form-section">
                <h4 class="form-section-title">التفاصيل والمخزون</h4>
                <div class="form-grid">
                    <div class="form-group"><label>السعر</label><input class="form-control" type="text" name="price" value="{{ old('price', $product->price) }}" required></div>
                    <div class="form-group"><label>الكمية</label><input class="form-control" type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}" required></div>
                    <div class="form-group"><label>الوحدة (مثال: كجم, قطعة)</label><input class="form-control" type="text" name="unit" value="{{ old('unit', $product->unit) }}"></div>
                    <div class="form-group"><label>الحجم (مثال: 1 لتر, 500 جرام)</label><input class="form-control" type="text" name="size" value="{{ old('size', $product->size) }}"></div>
                </div>
            </div>

            <!-- القسم الثالث: التصنيف -->
            <div class="form-section">
                <h4 class="form-section-title">التصنيف</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label>الفئة</label>
                        <select name="category_id" class="form-control select-control" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>الماركة</label>
                        <select name="brand_id" class="form-control select-control" required>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>المورد</label>
                        <select name="supplier_id" class="form-control select-control" required>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- القسم الرابع: صورة المنتج -->
            <div class="form-section">
                <h4 class="form-section-title">صورة المنتج</h4>
                <div class="image-upload-section">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/100x100.png/f8f9fa/6c757d?text=No+Image' }}" alt="الصورة الحالية" class="current-image-preview">
                    <div class="file-input-wrapper">
                        <label>تغيير الصورة (اختياري )</label>
                        <input type="file" name="image" class="form-control">
                        @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('productad.index') }}" class="btn btn-cancel">إلغاء</a>
            <button class="btn btn-save" type="submit"><i class="fas fa-save"></i> حفظ التعديلات</button>
        </div>
    </form>
</div>
@endsection
