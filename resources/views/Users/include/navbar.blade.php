<div class="top-bar">
    <div class="container top-bar-content">
        <div class="top-bar-contact">
            <a href="tel:+966123456789"><i class="fas fa-phone"></i> 966123456789+</a>
            <a href="mailto:info@mystore.com"><i class="fas fa-envelope"></i> info@mystore.com</a>
        </div>
        <div class="top-bar-offers">
            <a href="#offers"><i class="fas fa-gift"></i> عروض خاصة لمدة محدودة</a>
        </div>
    </div>
</div>

<!-- شريط التنقل الرئيسي -->
<nav class="navbar">
    <div class="container navbar-content">
        <a href="#" class="logo"><i class="fas fa-shopping-bag"></i> متجرك</a>
        
        <div class="nav-links">
            <a href="/">الرئيسية</a>
            <a href="{{route('products.index')}}">المنتجات</a>
            
           
            <a href="{{route('suppliers.create')}}">هل أنت مورد؟</a>
            <a href="{{route('suppliers.me')}}">  بياناتك كمورد</a>
            <a href="{{route('orders.index')}}">    طلباتي</a>
            <a href="{{route('admin.index')}}">   لوحة التحكم</a>
            
        </div>
        
        <div class="nav-actions">
            
            
            <!-- === قائمة المستخدم بتصميم جديد واحترافي === -->
            <div class="user-profile-menu">
                <button class="user-profile-trigger" id="user-menu-trigger">
                    <span class="user-avatar"><i class="fas fa-user"></i></span>
                    <span class="welcome-text">أهلاً، {{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down arrow-icon"></i>
                </button>
        
                <div class="dropdown-menu" id="user-dropdown">
                    <div class="dropdown-header">
                        <span class="user-name-large">{{ Auth::user()->name }}</span>
                        <span class="user-email-small">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="/profile" class="dropdown-item">
                        <i class="fas fa-user-circle"></i>
                        <span>ملفي الشخصي</span>
                    </a>
                    <a href="/orders" class="dropdown-item">
                        <i class="fas fa-box-open"></i>
                        <span>طلباتي</span>
                    </a>
                    <a href="/wishlist" class="dropdown-item">
                        <i class="fas fa-heart"></i>
                        <span>قائمة الأمنيات</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                       class="dropdown-item logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>تسجيل الخروج</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
            <!-- === نهاية قائمة المستخدم === -->
            @php
    use App\Models\Cart;

    $cartCount = auth()->check()
        ? Cart::where('user_id', auth()->id())->count()
        : 0;
@endphp
@if ( $cartCount >0)
    

<a href="{{route('carts.index')}}" class="icon-button">
    <i class="fas fa-shopping-cart"></i>
    <span class="badge">{{ $cartCount }}</span>
</a>
@else
<div class="icon-button">
    <i class="fas fa-shopping-cart"></i>
   
</div>
@endif

@php
use App\Models\Order;

// تحقق من تسجيل الدخول
$orderCount = auth()->check()
    ? Order::whereHas('orderItems.product.supplier', function($q) {
        $q->where('user_id', auth()->user()->id); // تحقق من أن المورد هو المستخدم الحالي
      })->count() // لا تحتاج إلى تصفية user_id في الطلبات
    : 0;
@endphp

@if ($orderCount > 0)
    <a href="{{ route('suppliers.sup_orders') }}" class="icon-button">
        <i class="fas fa-bell"></i>
        <span class="badge">{{ $orderCount }}</span>
    </a>
@else
    <div class="icon-button">
        <i class="fas fa-bell"></i>
    </div>
@endif

   
        </div>
        
        
    </div>
</nav>
<style>
    /* === تنسيقات قائمة المستخدم المنسدلة === */
/* === تنسيقات قائمة المستخدم الجديدة والاحترافية === */

/* حاوية القائمة الرئيسية */
.user-profile-menu {
    position: relative;
}

/* الزر الذي يظهر اسم المستخدم ويفتح القائمة */
.user-profile-trigger {
    display: flex;
    align-items: center;
    gap: 12px;
    background-color: #f5f7fa;
    border: 1px solid #e1e4e8;
    border-radius: 50px; /* حواف دائرية بالكامل */
    padding: 6px 15px 6px 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.user-profile-trigger:hover {
    background-color: #eef1f5;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
}

/* الأفتار الدائري للأيقونة */
.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--gradient);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

/* نص الترحيب */
.welcome-text {
    font-size: 1rem; /* حجم خط أكبر وواضح */
    font-weight: 600;
    color: var(--dark-color);
}

/* أيقونة السهم */
.arrow-icon {
    font-size: 0.8rem;
    color: #6a737d;
    transition: transform 0.3s ease;
}

/* تدوير السهم عند فتح القائمة */
.user-profile-trigger.open .arrow-icon {
    transform: rotate(180deg);
}

/* القائمة المنسدلة */
.dropdown-menu {
    position: absolute;
    top: calc(100% + 10px); /* مسافة 10px أسفل الزر */
    left: 0;
    width: 240px; /* عرض أكبر للقائمة */
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid #e1e4e8;
    z-index: 1100;
    padding: 8px 0;
    
    /* إخفاء القائمة بتأثير ناعم */
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s;
}

.dropdown-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

/* رأس القائمة الذي يحتوي على الاسم والإيميل */
.dropdown-header {
    padding: 15px 20px;
    border-bottom: 1px solid #e1e4e8;
}

.user-name-large {
    display: block;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--dark-color);
}

.user-email-small {
    display: block;
    font-size: 0.85rem;
    color: #6a737d;
}

/* عناصر القائمة */
.dropdown-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px 20px;
    font-size: 1rem; /* حجم خط أكبر */
    color: var(--dark-color);
    text-decoration: none;
    transition: background-color 0.2s ease;
}

.dropdown-item i {
    font-size: 1rem;
    width: 20px;
    text-align: center;
    color: #888;
}

.dropdown-item:hover {
    background-color: #f5f7fa;
}

.dropdown-divider {
    height: 1px;
    background-color: #e1e4e8;
    margin: 8px 0;
}

/* تخصيص لون أيقونة تسجيل الخروج */
.dropdown-item.logout i {
    color: #d73a49;
}

</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // ... أي أكواد سابقة ...

    // === كود القائمة المنسدلة للمستخدم (نسخة محسّنة) ===
    const userMenuTrigger = document.getElementById('user-menu-trigger');
    const userDropdown = document.getElementById('user-dropdown');

    if (userMenuTrigger && userDropdown) {
        userMenuTrigger.addEventListener('click', function (event) {
            event.stopPropagation(); 
            // إضافة كلاس للتحكم في السهم
            this.classList.toggle('open'); 
            userDropdown.classList.toggle('show');
        });

        window.addEventListener('click', function (event) {
            // التأكد من أن النقر ليس على القائمة أو الزر
            if (!userMenuTrigger.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.remove('show');
                userMenuTrigger.classList.remove('open'); // إزالة كلاس السهم
            }
        });
    }
});


</script>