<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* في ملف resources/views/admin/layouts/app.blade.php داخل <style> */

.sidebar {
    /* ... (بقية الأنماط) ... */
    display: flex;
    flex-direction: column;
}

.sidebar-nav {
    flex-grow: 1; /* هذا يضمن أن القائمة تملأ المساحة المتاحة */
    /* ... (بقية الأنماط) ... */
}

.sidebar-footer {
    padding: 15px;
    margin-top: auto; /* هذا يدفع العنصر لأسفل الشريط الجانبي */
    border-top: 1px solid var(--border-color);
}

.btn-back-to-site {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px;
    border-radius: 8px;
    background: var(--gradient);
    color: white;
    text-decoration: none;
    font-weight: 700;
    transition: var(--transition);
}

.btn-back-to-site:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(78, 107, 58, 0.3);
}

/* في ملف app.blade.php داخل <style> */

/* --- الشريط الجانبي (Sidebar) - تصميم احترافي وحيوي --- */
.sidebar {
    width: 260px;
    background: linear-gradient(180deg, #ffffff 0%, #fcfdfc 100%);
    border-left: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    transition: width 0.3s ease;
    box-shadow: 0 0 30px rgba(0,0,0,0.05);
}

/* --- رأس الشريط الجانبي (الشعار) --- */
.sidebar-header {
    padding: 25px;
    margin-bottom: 15px;
    text-align: center;
}
.sidebar-header .logo {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--primary-color);
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}
.sidebar-header .logo i {
    transition: transform 0.3s ease;
}
.sidebar-header .logo:hover i {
    transform: rotate(360deg);
}

/* --- قائمة التنقل --- */
.sidebar-nav {
    flex-grow: 1;
    padding: 0 15px;
}
.nav-list { list-style: none; }
.nav-item { margin-bottom: 5px; }
.nav-link {
    display: flex;
    align-items: center;
    padding: 14px 20px;
    border-radius: 12px;
    text-decoration: none;
    color: var(--text-light);
    font-weight: 600;
    transition: all 0.25s ease-in-out;
    position: relative;
    overflow: hidden;
}
.nav-link i {
    margin-left: 15px;
    width: 20px;
    font-size: 1.1rem;
    color: var(--secondary-color);
    transition: all 0.25s ease-in-out;
}

/* --- تأثيرات الروابط --- */
.nav-link:hover {
    background-color: #f0f5eb;
    color: var(--primary-color);
}
.nav-link:hover i {
    color: var(--primary-color);
}

/* --- الرابط النشط (الصفحة الحالية) --- */
.nav-link.active {
    background: var(--gradient);
    color: white;
    box-shadow: 0 5px 15px rgba(78, 107, 58, 0.3);
}
.nav-link.active i {
    color: white;
}

/* --- قسم "العودة للموقع" في الأسفل --- */
.sidebar-footer {
    padding: 20px;
    margin-top: auto; /* يدفع العنصر لأسفل */
    border-top: 1px solid var(--border-color);
}
.btn-back-to-site {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px;
    border-radius: 12px;
    background-color: #f0f5eb;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 700;
    transition: var(--transition);
}
.btn-back-to-site:hover {
    background: var(--primary-color);
    color: white;
    transform: scale(1.03);
}

/* في ملف app.blade.php داخل <style> */

/* --- الهيدر العلوي (Main Header) - تصميم احترافي --- */
.main-header {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 15px 30px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky; /* يجعله ثابتاً في الأعلى عند التمرير */
    top: 0;
    z-index: 100;
}

/* --- تصميم حقل البحث --- */
.header-search {
    position: relative;
}
.header-search .search-icon {
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    color: var(--text-light);
}
.header-search input {
    border: 1px solid var(--border-color);
    border-radius: 25px;
    padding: 10px 40px 10px 20px;
    width: 350px;
    background-color: #fff;
    transition: all 0.3s ease;
}
.header-search input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(78, 107, 58, 0.15);
}

/* --- قسم الأزرار والملف الشخصي --- */
.header-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}
.action-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #f0f5eb;
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 1.1rem;
    transition: all 0.2s ease;
    position: relative;
}
.action-btn:hover {
    background-color: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}
.notification-badge {
    position: absolute;
    top: -2px;
    right: -4px;
    background-color: var(--accent-color);
    color: white;
    font-size: 0.7rem;
    font-weight: 700;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
}

/* --- القائمة المنسدلة للمستخدم --- */
.user-profile-dropdown {
    position: relative;
}
.user-profile-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 25px;
    transition: background-color 0.2s ease;
}
.user-profile-btn:hover {
    background-color: #f0f0f0;
}
.user-profile-btn img {
    width: 38px;
    height: 38px;
    border-radius: 50%;
}
.user-profile-btn span {
    font-weight: 600;
    color: var(--dark-color);
}
.user-profile-btn .dropdown-arrow {
    font-size: 0.8rem;
    color: var(--text-light);
    transition: transform 0.3s ease;
}
.user-profile-dropdown.open .dropdown-arrow {
    transform: rotate(180deg);
}

/* --- محتوى القائمة المنسدلة --- */
.dropdown-content {
    display: none;
    position: absolute;
    left: 0;
    top: calc(100% + 10px);
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    width: 220px;
    overflow: hidden;
    z-index: 101;
}
.user-profile-dropdown.open .dropdown-content {
    display: block;
}
.dropdown-content a, .dropdown-content .logout-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    text-decoration: none;
    color: var(--text-dark);
    font-weight: 500;
    transition: background-color 0.2s ease;
    width: 100%;
    text-align: right;
    background: none;
    border: none;
    cursor: pointer;
}
.dropdown-content a:hover, .dropdown-content .logout-btn:hover {
    background-color: #f0f5eb;
    color: var(--primary-color);
}
.dropdown-content i {
    width: 16px;
    color: var(--secondary-color);
}
.dropdown-divider {
    height: 1px;
    background-color: var(--border-color);
    margin: 5px 0;
}


        /* --- متغيرات الألوان والأنماط --- */
        :root {
            --primary-color: #4e6b3a;
            --secondary-color: #8aa57c;
            --accent-color: #f8a31b;
            --dark-color: #2c3e50;
            --light-color: #f9f9f9;
            --text-color: #333;
            --light-text: #fff;
            --border-color: #e9ecef;
            --shadow: 0 8px 25px rgba(0, 0, 0, 0.07 );
            --transition: all 0.3s ease-in-out;
            --gradient: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        /* --- التنسيق العام --- */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--light-color);
            color: var(--text-color);
            direction: rtl;
        }
        .admin-panel-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* --- الشريط الجانبي (Sidebar) --- */
        .sidebar {
            width: 260px;
            background: #ffffff;
            border-left: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            padding: 20px 0;
            transition: width 0.3s ease;
        }
        .sidebar-header {
            padding: 0 25px 20px 25px;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            text-align: center;
        }
        .sidebar-header .logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary-color);
            text-decoration: none;
        }
        .sidebar-nav {
            flex-grow: 1;
            padding: 0 15px;
        }
        .nav-list { list-style: none; }
        .nav-item { margin-bottom: 8px; }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 8px;
            text-decoration: none;
            color: #555;
            font-weight: 600;
            transition: var(--transition);
        }
        .nav-link i {
            margin-left: 15px;
            width: 20px;
            font-size: 1.1rem;
            color: var(--secondary-color);
        }
        .nav-link:hover, .nav-link.active {
            background-color: #f0f5eb;
            color: var(--primary-color);
        }
        .nav-link.active i { color: var(--primary-color); }

        /* --- المحتوى الرئيسي --- */
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .main-header {
            background: #fff;
            padding: 15px 30px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-search input {
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 8px 15px;
            width: 300px;
        }
        .user-profile a {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--dark-color);
            font-weight: 600;
        }
        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        .page-content {
            padding: 40px;
            flex-grow: 1;
        }
    </style>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <div class="admin-panel-wrapper">
        {{-- تضمين الشريط الجانبي --}}
        @include('admin.layouts._sidebar')

        <main class="main-content">
            {{-- تضمين الهيدر العلوي --}}
            @include('admin.layouts._header')

            {{-- المحتوى المتغير للصفحات --}}
            <div class="page-content">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- يمكنك إضافة ملفات الجافاسكريبت هنا --}}
    <script>
        // كود الجافاسكريبت العام الذي يعمل على كل الصفحات
        document.addEventListener('DOMContentLoaded', function() {
            const userProfileDropdown = document.querySelector('.user-profile-dropdown');
            
            if (userProfileDropdown) {
                const userProfileBtn = userProfileDropdown.querySelector('.user-profile-btn');

                userProfileBtn.addEventListener('click', function(event) {
                    event.stopPropagation(); // منع انتشار النقر للـ document
                    userProfileDropdown.classList.toggle('open');
                });

                // إغلاق القائمة عند النقر خارجها
                document.addEventListener('click', function(event) {
                    if (userProfileDropdown.classList.contains('open') && !userProfileDropdown.contains(event.target)) {
                        userProfileDropdown.classList.remove('open');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
