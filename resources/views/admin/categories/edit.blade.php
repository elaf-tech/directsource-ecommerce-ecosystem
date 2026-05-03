@extends('admin.layouts.app')

@section('title', 'تعديل الفئة')

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
    
    .form-group { position: relative; }
    .form-control { width: 100%; border: 1px solid #e5e7eb; border-radius: 12px; padding: 12px 20px; transition: all 0.3s ease; background-color: #fff; }
    .form-control:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(78, 107, 58, 0.1); }
    .text-danger { color: #dc2626; font-size: 0.85rem; margin-top: 5px; display: block; }

    /* --- قسم الصورة --- */
    .image-upload-section { display: flex; align-items: center; gap: 20px; }
    .current-image-preview { width: 120px; height: 120px; border-radius: 12px; object-fit: cover; border: 3px solid var(--primary-color); }
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
    <h2 class="page-title"><i class="fas fa-edit"></i> تعديل الفئة</h2>
    <a href="{{ route('categoryad.index') }}" class="btn-back"><i class="fas fa-arrow-right"></i> العودة لقائمة الفئات</a>
</div>

<div class="form-card">
    <form action="{{route('categoryad.update',$category->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-header">
            <i class="fas fa-sitemap"></i>
            <h3>تعديل بيانات: {{ $category->name }}</h3>
        </div>

        <div class="form-body">
            <!-- القسم الأول: بيانات الفئة -->
            <div class="form-section">
                <h4 class="form-section-title">بيانات الفئة</h4>
                <div class="form-group mb-3">
                    <label for="name">اسم الفئة</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label for="description">الوصف (اختياري)</label>
                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea>
                    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- القسم الثاني: صورة الفئة -->
            <div class="form-section">
                <h4 class="form-section-title">صورة الفئة</h4>
                <div class="image-upload-section">
                    <img src="{{ $category->image ? asset('storage/' . $category->image) : 'https://via.placeholder.com/150x150.png/f0f5eb/4e6b3a?text=No+Image' }}" alt="الصورة الحالية" class="current-image-preview">
                    <div class="file-input-wrapper">
                        <label for="image">تغيير الصورة (اختياري )</label>
                        <input type="file" name="image" id="image" class="form-control">
                        @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('categoryad.index') }}" class="btn btn-cancel">إلغاء</a>
            <button class="btn btn-save" type="submit"><i class="fas fa-save"></i> حفظ التعديلات</button>
        </div>
    </form>
</div>
@endsection
