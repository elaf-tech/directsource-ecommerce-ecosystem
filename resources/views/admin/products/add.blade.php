@extends('admin.layouts.app')

@section('title', 'المنتجات')

@section('content')

{{-- تم دمج الـ CSS هنا لتسهيل التطبيق --}}
<style>
    /* === تصميم صفحة إضافة منتج الجديدة === */

    /* خلفية الصفحة مع تأثير الموجة */
    .page-wrapper-product {
        background-color: #f8f9fa; /* لون الخلفية العام */
        position: relative;
        padding: 50px 0;
        overflow: hidden; /* لإخفاء أجزاء الموجة الزائدة */
    }

    .wave-background-product {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 200px; /* ارتفاع الموجة */
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3e%3cpath fill='%23ffffff' fill-opacity='1' d='M0,160L48,170.7C96,181,192,203,288,208C384,213,480,203,576,176C672,149,768,107,864,112C960,117,1056,171,1152,197.3C1248,224,1344,224,1392,224L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3e%3c/path%3e%3c/svg%3e" );
        background-repeat: no-repeat;
        background-size: cover;
        z-index: 0;
    }

    /* حاوية الفورم الرئيسية */
    .form-container-product {
        max-width: 1100px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    /* بطاقة الفورم العائمة */
    .form-card-product {
        background-color: #ffffff;
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    /* رأس البطاقة */
    .form-card-header-product {
        padding: 30px 40px;
        background: var(--gradient);
        color: white;
    }

    .form-card-header-product h2 {
        margin: 0;
        font-weight: 700;
        font-size: 1.8rem;
    }

    /* جسم البطاقة مع تقسيم الأعمدة */
    .form-card-body-product {
        padding: 40px;
        display: grid;
        grid-template-columns: 1fr 320px; /* عمود للحقول وعمود للصورة */
        gap: 40px;
    }

    /* تصميم حقول الإدخال */
    .input-group-product {
        margin-bottom: 20px;
    }
    .input-group-product .form-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 8px;
        display: block;
    }
    .input-group-product .form-control,
    .input-group-product .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #dde2e7;
        border-radius: 10px;
        font-size: 1rem;
        transition: var(--transition);
        background-color: #fdfdff;
    }
    .input-group-product .form-control:focus,
    .input-group-product .form-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(78, 107, 58, 0.1);
    }

    /* تقسيم الحقول في أعمدة فرعية */
    .fields-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* تصميم منطقة رفع الصورة */
    .image-upload-col {
        text-align: center;
    }
    .image-upload-area {
        border: 2px dashed #dde2e7;
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: var(--transition);
        background-color: #f8f9fa;
        height: 250px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    .image-upload-area:hover {
        border-color: var(--primary-color);
        background-color: #f5f8f6;
    }
    .image-upload-area .upload-icon {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 15px;
    }
    .image-upload-area .upload-text {
        font-weight: 500;
        color: #6c757d;
    }
    .image-upload-area input[type="file"] {
        display: none;
    }
    #image-preview {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none; /* مخفي بشكل افتراضي */
    }

    /* تصميم الأزرار */
    .form-actions-product {
        margin-top: 30px;
        display: flex;
        justify-content: flex-end;
    }
    .btn-add-product {
        padding: 12px 40px;
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 50px;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        background: var(--gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .btn-add-product:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    /* التجاوب مع الشاشات الصغيرة */
    @media (max-width: 992px) {
        .form-card-body-product {
            grid-template-columns: 1fr; /* عمود واحد فقط */
        }
        .image-upload-col {
            order: -1; /* اجعل الصورة في الأعلى */
            margin-bottom: 30px;
        }
    }
    @media (max-width: 768px) {
        .fields-grid {
            grid-template-columns: 1fr; /* عمود واحد فقط للحقول الفرعية */
        }
    }
</style>

<div class="page-wrapper-product">
    <div class="form-container-product">
        <div class="form-card-product">
            <div class="form-card-header-product">
                <h2>إضافة منتج جديد إلى متجرك</h2>
            </div>
            <form action="{{ route('productad.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-card-body-product">
                    
                    <!-- عمود حقول البيانات -->
                    <div class="form-fields-col">
                        <div class="input-group-product">
                            <label for="name" class="form-label">اسم المنتج</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="input-group-product">
                            <label for="description" class="form-label">وصف المنتج</label>
                            <textarea name="description" class="form-control" id="description" rows="4">{{ old('description') }}</textarea>
                        </div>

                        <div class="fields-grid">
                            <div class="input-group-product">
                                <label for="category_id" class="form-label">الفئة</label>
                                <select name="category_id" id="category_id" class="form-select" required>
                                    <option value="">-- اختر --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group-product">
                                <label for="brand_id" class="form-label">الماركة</label>
                                <select name="brand_id" id="brand_id" class="form-select" required>
                                    <option value="">-- اختر --</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="input-group-product">
                            <label for="supplier_id" class="form-label">المورد</label>
                            <select name="supplier_id" id="supplier_id" class="form-select" required>
                                <option value="">-- اختر المورد المسؤول عن المنتج --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="fields-grid">
                            <div class="input-group-product">
                                <label for="price" class="form-label">السعر (ر.س)</label>
                                <input type="number" name="price" class="form-control" id="price" value="{{ old('price') }}" step="0.01" required>
                            </div>
                            <div class="input-group-product">
                                <label for="quantity" class="form-label">الكمية المتاحة</label>
                                <input type="number" name="quantity" class="form-control" id="quantity" value="{{ old('quantity') }}" required>
                            </div>
                            <div class="input-group-product">
                                <label for="size" class="form-label">الحجم (اختياري)</label>
                                <input type="text" name="size" class="form-control" id="size" value="{{ old('size') }}">
                            </div>
                            <div class="input-group-product">
                                <label for="unit" class="form-label">وحدة القياس (اختياري)</label>
                                <input type="text" name="unit" class="form-control" id="unit" value="{{ old('unit') }}" placeholder="مثال: كجم، قطعة، عبوة">
                            </div>
                        </div>
                    </div>

                    <!-- عمود رفع الصورة -->
                    <div class="image-upload-col">
                        <label for="image" class="form-label">صورة المنتج الرئيسية</label>
                        <label for="image" class="image-upload-area">
                            <img id="image-preview" src="#" alt="معاينة الصورة" />
                            <div id="upload-placeholder">
                                <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                <div class="upload-text">انقر هنا لاختيار صورة</div>
                            </div>
                        </label>
                        <input type="file" name="image" id="image" accept="image/*" required>
                    </div>
                </div>
                <div class="form-actions-product">
                    <button type="submit" class="btn-add-product">
                        <i class="fas fa-plus-circle"></i> إضافة المنتج
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="wave-background-product"></div>
</div>

{{-- كود JavaScript لمعاينة الصورة --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const uploadPlaceholder = document.getElementById('upload-placeholder');

    if (imageInput && imagePreview && uploadPlaceholder) {
        imageInput.addEventListener('change', function(event) {
            if (event.target.files && event.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    uploadPlaceholder.style.display = 'none';
                };
                reader.readAsDataURL(event.target.files[0]);
            }
        });
    }
});
</script>

@endsection
