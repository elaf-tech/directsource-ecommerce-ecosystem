@extends('Users.home')

@section('content')

{{-- تم دمج الـ CSS هنا لتسهيل التطبيق --}}
<style>
    /* === تصميم صفحة تعديل الملف الشخصي الجديدة === */

    /* نفس خلفية وتنسيق صفحة عرض الملف الشخصي */
    .profile-page-wrapper {
        background: linear-gradient(to bottom, #ffffff, #f8f9fa);
        padding: 60px 0;
        min-height: 80vh;
        position: relative;
        overflow: hidden;
    }
    .profile-page-wrapper::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 150px;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3e%3cpath fill='%23ffffff' fill-opacity='1' d='M0,128L80,138.7C160,149,320,171,480,170.7C640,171,800,149,960,133.3C1120,117,1280,107,1360,101.3L1440,96L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z'%3e%3c/path%3e%3c/svg%3e"  );
        background-repeat: no-repeat;
        background-size: cover;
        z-index: 0;
    }
    .container { position: relative; z-index: 1; }

    /* بطاقة التعديل الرئيسية */
    .profile-card-main {
        background-color: #ffffff;
        border-radius: 24px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        max-width: 900px;
        margin: 0 auto;
        overflow: hidden;
        display: grid;
        grid-template-columns: 300px 1fr;
    }

    /* عمود الصورة الرمزية */
    .profile-avatar-column {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        padding: 40px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
    }
    .profile-avatar-wrapper {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        margin-bottom: 20px;
        position: relative;
        background-color: rgba(255,255,255,0.2);
    }
    .profile-avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }
    .profile-avatar-initials { font-size: 4rem; font-weight: 700; }
    .profile-avatar-edit-overlay {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 40px;
        height: 40px;
        background-color: white;
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: var(--transition);
    }
    .profile-avatar-edit-overlay:hover { transform: scale(1.1); }
    #profile_image_input { display: none; }

    /* عمود الفورم */
    .profile-details-column { padding: 40px 50px; }
    .profile-details-column h3 { font-size: 1.8rem; font-weight: 700; color: var(--dark-color); margin-bottom: 30px; }
    .form-group-profile { margin-bottom: 25px; }
    .form-group-profile .form-label { font-weight: 600; color: #6c757d; margin-bottom: 8px; font-size: 0.9rem; }
    .input-wrapper { position: relative; }
    .input-wrapper .form-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
    }
    .form-control-profile, .form-select-profile {
        width: 100%;
        padding: 12px 15px 12px 45px; /* مساحة للأيقونة */
        border: 1px solid #dde2e7;
        border-radius: 10px;
        font-size: 1rem;
        transition: var(--transition);
    }
    .form-control-profile:focus, .form-select-profile:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(78, 107, 58, 0.1);
    }
    .text-danger { font-size: 0.85rem; margin-top: 5px; }

    /* أزرار الإجراءات */
    .profile-actions-footer { margin-top: 30px; display: flex; justify-content: flex-end; gap: 15px; }
    .btn-profile-action { padding: 12px 30px; border-radius: 50px; text-decoration: none; font-weight: 700; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; border: none; cursor: pointer; }
    .btn-profile-action.save { background: var(--gradient); color: white; }
    .btn-profile-action.save:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .btn-profile-action.cancel { background-color: #e9ecef; color: #6c757d; }
    .btn-profile-action.cancel:hover { background-color: #ced4da; }

    @media (max-width: 768px) { .profile-card-main { grid-template-columns: 1fr; } .profile-details-column { padding: 30px; } }
</style>

<div class="profile-page-wrapper">
    <div class="container">
        <form action="/profileupdate" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="profile-card-main">
                <!-- عمود الصورة الرمزية -->
                <div class="profile-avatar-column">
                    <div class="profile-avatar-wrapper">
                        <img id="avatar-preview" src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ) . '&background=random&size=150' }}" alt="{{ $user->name }}">
                        <label for="profile_image_input" class="profile-avatar-edit-overlay" title="تغيير الصورة">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" name="profile_image" id="profile_image_input" accept="image/*">
                    </div>
                    <h2 class="profile-name">{{ $user->name }}</h2>
                    <p class="profile-role">
                        @if($user->role == 'admin') مدير النظام @else مستخدم @endif
                    </p>
                </div>

                <!-- عمود الفورم -->
                <div class="profile-details-column">
                    <h3>تعديل الملف الشخصي</h3>

                    <div class="form-group-profile">
                        <label for="name" class="form-label">الاسم</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user form-icon"></i>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control-profile" required>
                        </div>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group-profile">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope form-icon"></i>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control-profile" required>
                        </div>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group-profile">
                        <label for="phone" class="form-label">رقم الهاتف</label>
                        <div class="input-wrapper">
                            <i class="fas fa-phone-alt form-icon"></i>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="form-control-profile">
                        </div>
                        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group-profile">
                        <label for="address" class="form-label">العنوان</label>
                        <div class="input-wrapper">
                            <i class="fas fa-map-marker-alt form-icon"></i>
                            <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}" class="form-control-profile">
                        </div>
                        @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- يمكنك إضافة حقل كلمة المرور هنا إذا أردت السماح بتغييرها --}}

                    <div class="profile-actions-footer">
                        <a href="/profile" class="btn-profile-action cancel">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                        <button type="submit" class="btn-profile-action save">
                            <i class="fas fa-save"></i> حفظ التغييرات
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- كود JavaScript لمعاينة الصورة قبل الرفع --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('profile_image_input');
    const avatarPreview = document.getElementById('avatar-preview');

    if (imageInput && avatarPreview) {
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>

@endsection
