
@extends('admin.layouts.app')

@section('title', 'إدارة الفئات')

@section('content')

{{-- تم إضافة هذا الـ Style مباشرة هنا لتسهيل النسخ واللصق --}}
{{-- يمكنك نقله إلى ملف CSS خارجي إذا أردت --}}
<style>
    /* === تصميم صفحة إضافة فئة الجديدة === */

    /* خلفية الصفحة الرئيسية */
    .page-background {
        background-color: #f4f7f6; /* درجة رمادي فاتحة جدًا */
        min-height: 100vh;
        position: relative;
        padding: 40px 0;
    }

    /* حاوية الفورم الرئيسية */
    .form-container-wrapper {
        max-width: 800px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* بطاقة الفورم العائمة */
    .form-card {
        background-color: #ffffff;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e9ecef;
    }

    /* رأس البطاقة */
    .form-card-header {
        padding: 30px;
        background: linear-gradient(to right, var(--dark-color), var(--primary-color));
        color: white;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .form-card-header i {
        font-size: 2rem;
    }

    .form-card-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.8rem;
    }

    /* جسم البطاقة */
    .form-card-body {
        padding: 40px;
    }

    /* تصميم حقول الإدخال */
    .form-group {
        margin-bottom: 25px;
        position: relative;
    }

    .form-label {
        font-weight: 600;
        font-size: 1rem;
        color: var(--dark-color);
        margin-bottom: 8px;
        display: block;
    }

    .form-control-custom {
        width: 100%;
        padding: 15px 20px;
        font-size: 1rem;
        border: 1px solid #ced4da;
        border-radius: 12px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        background-color: #f8f9fa;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(78, 107, 58, 0.15);
        background-color: #fff;
    }

    /* تصميم حقل رفع الملف */
    .file-upload-wrapper {
        border: 2px dashed #ced4da;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.3s ease, background-color 0.3s ease;
    }

    .file-upload-wrapper:hover {
        border-color: var(--primary-color);
        background-color: #fdfdfd;
    }

    .file-upload-wrapper .file-upload-icon {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .file-upload-wrapper .file-upload-text {
        font-weight: 500;
        color: #6c757d;
    }

    .file-upload-wrapper input[type="file"] {
        display: none; /* إخفاء حقل الإدخال الأصلي */
    }

    /* تصميم زر الإرسال */
    .btn-submit-custom {
        width: 100%;
        padding: 16px;
        font-size: 1.2rem;
        font-weight: 700;
        color: white;
        background: linear-gradient(to right, var(--accent-color), #ffb84d);
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 15px rgba(248, 163, 27, 0.3);
    }

    .btn-submit-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 20px rgba(248, 163, 27, 0.4);
    }

    /* رسالة النجاح */
    .alert-success-custom {
        background-color: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #a5d6a7;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 20px;
        font-weight: 500;
    }
</style>

<div class="page-background">
    <div class="form-container-wrapper">
        <div class="form-card">
            <div class="form-card-header">
                <i class="fas fa-plus-circle"></i>
                <h4>إضافة فئة جديدة</h4>
            </div>
            <div class="form-card-body">
                @if(session('success'))
                    <div class="alert-success-custom">{{ session('success') }}</div>
                @endif

                <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- اسم الفئة -->
                    <div class="form-group">
                        <label for="name" class="form-label">اسم الفئة</label>
                        <input type="text" name="name" class="form-control-custom" id="name"  placeholder="مثال: إلكترونيات، ملابس رجالية..." required>
                        @error('name')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- وصف الفئة -->
                    <div class="form-group">
                        <label for="description" class="form-label">الوصف (اختياري)</label>
                        <textarea name="description" class="form-control-custom" id="description" rows="4" placeholder="اكتب وصفًا موجزًا وجذابًا عن هذه الفئة..."></textarea>
                        @error('description')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- صورة الفئة -->
                    <div class="form-group">
                        <label class="form-label">صورة الفئة</label>
                        <label for="image" class="file-upload-wrapper">
                            <div class="file-upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                            <div class="file-upload-text" id="file-name">انقر هنا لاختيار صورة</div>
                            <input type="file" name="image" class="form-control-custom" id="image" accept="image/*" required>
                        </label>
                        @error('image')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit-custom">
                        <i class="fas fa-check"></i> إضافة الفئة
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- كود JavaScript لتحسين تجربة المستخدم --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('image');
    const fileNameDisplay = document.getElementById('file-name');

    if (fileInput && fileNameDisplay) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                // عرض اسم الملف المختار بدلًا من النص الافتراضي
                fileNameDisplay.textContent = this.files[0].name;
            } else {
                fileNameDisplay.textContent = 'انقر هنا لاختيار صورة';
            }
        });
    }
});
</script>

@stop
```

