<header class="main-header">
    <!-- قسم البحث -->
    <div class="header-search">
      
    </div>

    <!-- قسم الإجراءات والملف الشخصي -->
    <div class="header-actions">
        <!-- زر العودة للموقع -->
        <a href="{{ url('/') }}" class="action-btn" title="العودة إلى الموقع">
            <i class="fas fa-external-link-alt"></i>
        </a>

        <!-- زر الإشعارات (مثال) -->
      

        @php
        use App\Models\Supplier;
    
        $supCount = Supplier::where('status', 'pending')->count();
    @endphp
    
    @if ($supCount > 0)
        <a href="{{ route('supplierad.index') }}" class="action-btn"  title="عدد الموردين الغير معتمدين: {{ $supCount }}">
            <i class="fas fa-bell"></i>
            <span class="notification-badge">{{ $supCount }}</span>
        </a>
    @else
        <div class="icon-button" title="لا يوجد موردين غير معتمدين">
            <i class="fas fa-shopping-cart"></i>
        </div>
    @endif
    
        <!-- القائمة المنسدلة للمستخدم -->
        <div class="user-profile-dropdown">
            <button class="user-profile-btn">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user( )->name ?? 'A') }}&background=4e6b3a&color=fff&font-size=0.5" alt="صورة المستخدم">
                <span>{{ Auth::user()->name ?? 'اسم المدير' }}</span>
                <i class="fas fa-chevron-down dropdown-arrow"></i>
            </button>
            <div class="dropdown-content">
              
                <div class="dropdown-divider"></div>
                <!-- نموذج تسجيل الخروج -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt fa-fw"></i> تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
