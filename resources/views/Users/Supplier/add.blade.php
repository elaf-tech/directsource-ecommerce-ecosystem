@extends('Users.home')

@section('content')

{{-- تم دمج الـ CSS هنا لتسهيل التطبيق --}}
<style>
    /* === تصميم صفحة إضافة مورد الجديدة === */

    /* خلفية الصفحة مع تأثير الموجة */
    .page-wrapper-wave {
        background-color: #f4f7f6; /* لون الخلفية العام */
        position: relative;
        padding: 60px 0;
        overflow: hidden; /* لإخفاء أجزاء الموجة الزائدة */
    }

    .wave-background {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 250px; /* ارتفاع الموجة */
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3e%3cpath fill='%23ffffff' fill-opacity='1' d='M0,160L48,170.7C96,181,192,203,288,208C384,213,480,203,576,176C672,149,768,107,864,112C960,117,1056,171,1152,197.3C1248,224,1344,224,1392,224L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3e%3c/path%3e%3c/svg%3e" );
        background-repeat: no-repeat;
        background-size: cover;
        z-index: 0;
    }

    /* حاوية الفورم الرئيسية */
    .form-container-main {
        max-width: 900px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    /* بطاقة الفورم العائمة */
    .form-card-elegant {
        background-color: #ffffff;
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.09);
        overflow: hidden;
    }

    /* رأس البطاقة */
    .form-card-header-elegant {
        padding: 35px 40px;
        background: var(--gradient);
        color: white;
    }

    .form-card-header-elegant h2 {
        margin: 0;
        font-weight: 700;
        font-size: 2rem;
    }
    .form-card-header-elegant p {
        margin: 5px 0 0;
        opacity: 0.8;
        font-size: 1rem;
    }

    /* جسم البطاقة مع تقسيم الأعمدة */
    .form-card-body-elegant {
        padding: 40px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr; /* عمودان متساويان */
        gap: 30px; /* المسافة بين الحقول */
    }

    /* تصميم حقول الإدخال مع أيقونات */
    .input-group-elegant {
        position: relative;
        margin-bottom: 10px;
    }

    .input-group-elegant .form-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 8px;
        display: block;
    }

    .input-group-elegant .form-control-elegant {
        width: 100%;
        padding: 14px 45px 14px 20px; /* مساحة للأيقونة على اليمين */
        border: 1px solid #dde2e7;
        border-radius: 12px;
        font-size: 1rem;
        transition: var(--transition);
        background-color: #f9fafb;
    }
    
    .input-group-elegant .form-control-elegant:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(78, 107, 58, 0.1);
        background-color: #fff;
    }

    .input-group-elegant .input-icon {
        position: absolute;
        top: 45px;
        right: 15px;
        color: #909aa3;
        transition: var(--transition);
    }

    .input-group-elegant .form-control-elegant:focus + .input-icon {
        color: var(--primary-color);
    }

    /* تصميم حقل رفع الشعار */
    .logo-upload-area {
        grid-column: 1 / -1; /* اجعل هذا الحقل يمتد على عرض العمودين */
        border: 2px dashed #dde2e7;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
        background-color: #f9fafb;
    }
    .logo-upload-area:hover {
        border-color: var(--primary-color);
        background-color: #f5f8f6;
    }
    .logo-upload-area .upload-icon {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 10px;
    }
    .logo-upload-area .upload-text {
        font-weight: 500;
        color: #6c757d;
    }
    .logo-upload-area input[type="file"] {
        display: none;
    }

    /* تصميم الأزرار */
    .form-actions {
        grid-column: 1 / -1; /* اجعل الأزرار تمتد على عرض العمودين */
        display: flex;
        justify-content: flex-end; /* محاذاة لليسار (لأن الصفحة بالعربية) */
        gap: 15px;
        margin-top: 20px;
    }
    .btn-elegant {
        padding: 12px 30px;
        font-size: 1rem;
        font-weight: 700;
        border-radius: 50px;
        border: none;
        cursor: pointer;
        transition: var(--transition);
    }
    .btn-save {
        background-color: var(--primary-color);
        color: white;
    }
    .btn-save:hover {
        background-color: var(--accent-color);
        color: var(--dark-color);
        transform: translateY(-2px);
    }
    .btn-cancel {
        background-color: #e9ecef;
        color: #6c757d;
    }
    .btn-cancel:hover {
        background-color: #ced4da;
    }

    /* التجاوب مع الشاشات الصغيرة */
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr; /* عمود واحد فقط في الشاشات الصغيرة */
        }
        .logo-upload-area, .form-actions {
            grid-column: 1; /* إعادة الحقول الممتدة إلى عمود واحد */
        }
    }
</style>

<div class="page-wrapper-wave">
    <div class="form-container-main">
        <div class="form-card-elegant">
            <div class="form-card-header-elegant">
                <h2>انضم إلينا كمورد</h2>
                <p>املأ البيانات التالية لتصبح جزءًا من شبكة موردينا المعتمدين.</p>
            </div>
            <div class="form-card-body-elegant">
                <form action="{{route('suppliers.store')}}" method="POST" enctype="multipart/form-data" class="form-grid">
                    @csrf

                    <div class="input-group-elegant">
                        <label for="company_name" class="form-label">اسم الشركة</label>
                        <input type="text" name="company_name" id="company_name" class="form-control-elegant" required>
                        <i class="fas fa-building input-icon"></i>
                    </div>

                    <div class="input-group-elegant">
                        <label for="business_type" class="form-label">نوع النشاط</label>
                        <input type="text" name="business_type" id="business_type" class="form-control-elegant" required>
                        <i class="fas fa-briefcase input-icon"></i>
                    </div>

                    <div class="input-group-elegant" style="grid-column: 1 / -1;">
                        <label for="address" class="form-label">العنوان</label>
                        <textarea name="address" id="address" rows="3" class="form-control-elegant" required></textarea>
                    </div>

                    <label for="logo" class="logo-upload-area">
                        <div class="upload-icon"><i class="fas fa-image"></i></div>
                        <div class="upload-text" id="logo-name">انقر هنا لرفع شعار الشركة</div>
                        <input type="file" name="logo" id="logo" accept="image/*">
                    </label>

                    <div class="input-group-elegant">
                        <label for="commercial_registration_number" class="form-label">رقم السجل التجاري</label>
                        <input type="text" name="commercial_registration_number" id="commercial_registration_number" class="form-control-elegant" required>
                        <i class="fas fa-file-alt input-icon"></i>
                    </div>

                    <div class="input-group-elegant">
                        <label for="identity_number" class="form-label">رقم الهوية</label>
                        <input type="text" name="identity_number" id="identity_number" class="form-control-elegant" required>
                        <i class="fas fa-id-card input-icon"></i>
                    </div>

                    <div class="input-group-elegant" style="grid-column: 1 / -1;">
                        <label for="bank_account" class="form-label">الحساب البنكي (IBAN)</label>
                        <input type="text" name="bank_account" id="bank_account" class="form-control-elegant" required>
                        <i class="fas fa-university input-icon"></i>
                    </div>

                    <div class="form-actions">
                        <a href="/" class="btn-elegant btn-cancel">إلغاء</a>
                        <button type="submit" class="btn-elegant btn-save">حفظ وإرسال الطلب</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="wave-background"></div>
</div>

{{-- كود JavaScript لتحسين تجربة المستخدم --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const logoInput = document.getElementById('logo');
    const logoNameDisplay = document.getElementById('logo-name');

    if (logoInput && logoNameDisplay) {
        logoInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                logoNameDisplay.textContent = this.files[0].name;
            } else {
                logoNameDisplay.textContent = 'انقر هنا لرفع شعار الشركة';
            }
        });
    }
});
</script>

@endsection
