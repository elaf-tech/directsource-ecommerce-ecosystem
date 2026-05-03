@extends('admin.layouts.app')

@section('title', 'إضافة مورد جديد')

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
    .form-control.select-control { padding-right: 20px; }
    .form-control[type="file"] { padding: 8px; padding-right: 15px; }
    .text-danger { color: #dc2626; font-size: 0.85rem; margin-top: 5px; display: block; }

    .form-footer { padding: 20px 30px; background: rgba(249, 250, 251, 0.8); border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 12px; }
    .btn { padding: 12px 28px; border-radius: 12px; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.3s ease; }
    .btn-save { background: var(--gradient); color: white; box-shadow: 0 4px 15px rgba(78, 107, 58, 0.2); }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(78, 107, 58, 0.3); }
    .btn-cancel { background-color: #fff; color: #6c757d; border: 1px solid #e5e7eb; }
    .btn-cancel:hover { background-color: #f3f4f6; }
</style>

<div class="page-header">
    <h2 class="page-title"><i class="fas fa-truck-loading"></i> إضافة مورد جديد</h2>
    <a href="{{ route('supplierad.index') }}" class="btn-back"><i class="fas fa-arrow-right"></i> العودة لقائمة الموردين</a>
</div>

<div class="form-card">
    <form action="{{ route('supplierad.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-header">
            <i class="fas fa-plus-circle"></i>
            <h3>نموذج إضافة مورد</h3>
        </div>

        <div class="form-body">
            <!-- القسم الأول: معلومات الشركة -->
            <div class="form-section">
                <h4 class="form-section-title">معلومات الشركة</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <i class="fas fa-building form-icon"></i>
                        <input class="form-control" type="text" name="company_name" value="{{ old('company_name') }}" required placeholder="اسم الشركة">
                        @error('company_name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <i class="fas fa-briefcase form-icon"></i>
                        <input class="form-control" type="text" name="business_type" value="{{ old('business_type') }}" required placeholder="نوع النشاط">
                        @error('business_type') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="form-group mt-3">
                    <i class="fas fa-map-marker-alt form-icon"></i>
                    <input class="form-control" type="text" name="address" value="{{ old('address') }}" required placeholder="العنوان التفصيلي">
                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- القسم الثاني: الهوية والشعار -->
            <div class="form-section">
                <h4 class="form-section-title">الهوية والشعار</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <i class="fas fa-id-card form-icon"></i>
                        <input class="form-control" type="text" name="identity_number" value="{{ old('identity_number') }}" required placeholder="رقم الهوية / الإقامة">
                        @error('identity_number') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <i class="fas fa-file-contract form-icon"></i>
                        <input class="form-control" type="text" name="commercial_registration_number" value="{{ old('commercial_registration_number') }}" required placeholder="رقم السجل التجاري">
                        @error('commercial_registration_number') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label>شعار الشركة (اختياري)</label>
                    <input type="file" name="logo" class="form-control">
                    @error('logo') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- القسم الثالث: المعلومات المالية وربط المستخدم -->
            <div class="form-section">
                <h4 class="form-section-title">المعلومات المالية وربط المستخدم</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <i class="fas fa-credit-card form-icon"></i>
                        <input class="form-control" type="text" name="bank_account" value="{{ old('bank_account') }}" required placeholder="رقم الحساب البنكي (IBAN)">
                        @error('bank_account') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <select name="user_id" class="form-control select-control" required>
                            <option value="" disabled selected>-- ربط المورد بمستخدم --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('supplierad.index') }}" class="btn btn-cancel">إلغاء</a>
            <button class="btn btn-save" type="submit"><i class="fas fa-plus-circle"></i> إضافة المورد</button>
        </div>
    </form>
</div>
@endsection
