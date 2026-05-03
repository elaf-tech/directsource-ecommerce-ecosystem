@extends('Users.home')

@section('content')

{{-- تم دمج الـ CSS هنا لتسهيل التطبيق --}}
<style>
    /* === تصميم صفحة الملف الشخصي الجديدة === */

    /* خلفية الصفحة وتنسيقها العام */
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

    .container {
        position: relative;
        z-index: 1;
    }

    /* بطاقة الملف الشخصي الرئيسية */
    .profile-card-main {
        background-color: #ffffff;
        border-radius: 24px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        max-width: 900px;
        margin: 0 auto;
        overflow: hidden;
        display: grid;
        grid-template-columns: 300px 1fr; /* عمود للصورة وعمود للتفاصيل */
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
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(255,255,255,0.2);
    }
    .profile-avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }
    .profile-avatar-initials {
        font-size: 4rem;
        font-weight: 700;
    }
    .profile-name {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .profile-role {
        font-size: 1rem;
        opacity: 0.9;
    }

    /* عمود التفاصيل */
    .profile-details-column {
        padding: 40px 50px;
    }
    .profile-details-column h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e9ecef;
    }
    .profile-info-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 25px;
    }
    .profile-info-item {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .profile-info-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background-color: #f8f9fa;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .profile-info-content .label {
        font-size: 0.9rem;
        color: #6c757d;
        display: block;
        margin-bottom: 2px;
    }
    .profile-info-content .value {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-color);
    }
    .profile-info-content .badge {
        font-size: 1rem;
        padding: 8px 15px;
    }

    /* أزرار الإجراءات */
    .profile-actions-footer {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }
    .btn-profile-action {
        padding: 10px 25px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-profile-action.edit {
        background: var(--gradient);
        color: white;
    }
    .btn-profile-action.edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .btn-profile-action.back {
        background-color: #e9ecef;
        color: #6c757d;
    }
    .btn-profile-action.back:hover {
        background-color: #ced4da;
    }

    /* التجاوب مع الشاشات الصغيرة */
    @media (max-width: 768px) {
        .profile-card-main {
            grid-template-columns: 1fr; /* عمود واحد فقط */
        }
        .profile-details-column {
            padding: 30px;
        }
    }
</style>

<div class="profile-page-wrapper">
    <div class="container">
        <div class="profile-card-main">
            <!-- عمود الصورة الرمزية -->
            <div class="profile-avatar-column">
                <div class="profile-avatar-wrapper">
                    @if($user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}">
                    @else
                        {{-- عرض أول حرف من الاسم كصورة رمزية افتراضية --}}
                        <span class="profile-avatar-initials">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    @endif
                </div>
                <h2 class="profile-name">{{ $user->name }}</h2>
                <p class="profile-role">
                    @if($user->role == 'admin')
                        مدير النظام
                    @else
                        مستخدم
                    @endif
                </p>
            </div>

            <!-- عمود التفاصيل -->
            <div class="profile-details-column">
                <h3>تفاصيل الحساب</h3>
                <ul class="profile-info-list">
                    <li class="profile-info-item">
                        <div class="profile-info-icon"><i class="fas fa-envelope"></i></div>
                        <div class="profile-info-content">
                            <span class="label">البريد الإلكتروني</span>
                            <span class="value">{{ $user->email }}</span>
                        </div>
                    </li>
                    <li class="profile-info-item">
                        <div class="profile-info-icon"><i class="fas fa-phone-alt"></i></div>
                        <div class="profile-info-content">
                            <span class="label">رقم الهاتف</span>
                            <span class="value">{{ $user->phone ?? 'لم يتم إضافته' }}</span>
                        </div>
                    </li>
                    <li class="profile-info-item">
                        <div class="profile-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="profile-info-content">
                            <span class="label">العنوان</span>
                            <span class="value">{{ $user->address ?? 'لم يتم إضافته' }}</span>
                        </div>
                    </li>
                    <li class="profile-info-item">
                        <div class="profile-info-icon"><i class="fas fa-user-shield"></i></div>
                        <div class="profile-info-content">
                            <span class="label">الدور</span>
                            <span class="value">
                                @if($user->role == 0)
                                    <span class="badge bg-danger">مدير</span>
                                @else
                                    <span class="badge bg-success">مستخدم</span>
                                @endif
                            </span>
                        </div>
                    </li>
                </ul>

                <div class="profile-actions-footer">
                    <a href="/" class="btn-profile-action back">
                        <i class="fas fa-arrow-right"></i> رجوع
                    </a>
                    <a href="/editprofile" class="btn-profile-action edit">
                        <i class="fas fa-edit"></i> تعديل الملف الشخصي
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
