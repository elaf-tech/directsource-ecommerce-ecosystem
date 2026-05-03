@extends('admin.layouts.app')

@section('title', 'تعديل المستخدم')

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
    .form-header { padding: 25px; background: rgba(255, 255, 255, 0.5); border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 20px; }
    .user-avatar { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-color); }
    .form-header-title h3 { font-size: 1.5rem; font-weight: 700; color: var(--dark-color); margin: 0; }
    .form-header-title p { font-size: 1rem; color: #6c757d; margin: 0; }
    
    .form-body { padding: 30px; }
    .form-section { margin-bottom: 25px; }
    .form-section-title { font-size: 1.1rem; font-weight: 700; color: var(--dark-color); margin-bottom: 15px; border-right: 4px solid var(--primary-color); padding-right: 12px; }
    
    .form-group { position: relative; margin-bottom: 20px; }
    .form-group .form-icon { position: absolute; top: 50%; right: 15px; transform: translateY(-50%); color: #aaa; }
    .form-control { width: 100%; border: 1px solid #e5e7eb; border-radius: 12px; padding: 12px 45px 12px 20px; transition: all 0.3s ease; background-color: #fff; }
    .form-control:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(78, 107, 58, 0.1); }
    .form-control.select-control { padding-right: 20px; /* تعديل للـ select */ }
    
    .form-footer { padding: 20px 30px; background: rgba(249, 250, 251, 0.8); border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 12px; }
    .btn { padding: 12px 28px; border-radius: 12px; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.3s ease; }
    .btn-save { background: var(--gradient); color: white; box-shadow: 0 4px 15px rgba(78, 107, 58, 0.2); }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(78, 107, 58, 0.3); }
    .btn-cancel { background-color: #fff; color: #6c757d; border: 1px solid #e5e7eb; }
    .btn-cancel:hover { background-color: #f3f4f6; }
</style>

<div class="page-header">
    <h2 class="page-title"><i class="fas fa-user-edit"></i> تعديل بيانات المستخدم</h2>
    <a href="{{ route('user.index') }}" class="btn-back"><i class="fas fa-arrow-right"></i> العودة لقائمة المستخدمين</a>
</div>

<div class="form-card">
    <form action="{{ route('user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-header">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ) }}&background=random&color=fff&size=60" alt="{{ $user->name }}" class="user-avatar">
            <div class="form-header-title">
                <h3>{{ $user->name }}</h3>
                <p>تعديل بيانات المستخدم صاحب المعرف #{{ $user->id }}</p>
            </div>
        </div>

        <div class="form-body">
            <!-- القسم الأول: المعلومات الأساسية -->
            <div class="form-section">
                <h4 class="form-section-title">المعلومات الأساسية</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <i class="fas fa-user form-icon"></i>
                            <input class="form-control" type="text" name="name" value="{{ old('name', $user->name) }}" required placeholder="الاسم الكامل">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <i class="fas fa-envelope form-icon"></i>
                            <input class="form-control" type="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="البريد الإلكتروني">
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- القسم الثاني: معلومات الاتصال -->
            <div class="form-section">
                <h4 class="form-section-title">معلومات الاتصال</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <i class="fas fa-map-marker-alt form-icon"></i>
                            <input class="form-control" type="text" name="address" value="{{ old('address', $user->address) }}" placeholder="العنوان (اختياري)">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <i class="fas fa-phone form-icon"></i>
                            <input class="form-control" type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="رقم الهاتف (اختياري)">
                        </div>
                    </div>
                </div>
            </div>

            <!-- القسم الثالث: الصلاحيات والأمان -->
            <div class="form-section">
                <h4 class="form-section-title">الصلاحيات والأمان</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <select class="form-control select-control" name="role" required>
                                <option value="" disabled>-- اختر الدور --</option>
                                <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>مستخدم</option>
                                <option value="admin" {{ old('role', 'admin') == 'admin' ? 'selected' : '' }}>مدير</option>
                                <option value="supplier" {{ old('role', $user->role) == 'supplier' ? 'selected' : '' }}>مورد</option>
                            </select>
                            @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <i class="fas fa-lock form-icon"></i>
                            <input class="form-control" type="password" name="password" placeholder="كلمة المرور (اتركها فارغة لعدم التغيير)">
                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('user.index') }}" class="btn btn-cancel">إلغاء</a>
            <button class="btn btn-save" type="submit"><i class="fas fa-save"></i> حفظ التعديلات</button>
        </div>
    </form>
</div>
@endsection
