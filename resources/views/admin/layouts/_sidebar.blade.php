<aside class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.index') }}" class="logo">
            <i class="fas fa-leaf"></i>
            <span>لوحة التحكم</span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="{{ route('admin.index') }}" class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt fa-fw"></i>
                    <span>الرئيسية</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('user.index')}}" class="nav-link">
                    <i class="fas fa-users fa-fw"></i>
                    <span>المستخدمين</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('supplierad.index')}}" class="nav-link">
                    <i class="fas fa-truck fa-fw"></i>
                    <span>الموردين</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('productad.index')}}" class="nav-link">
                    <i class="fas fa-box-open fa-fw"></i>
                    <span>المنتجات</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('categoryad.index')}}" class="nav-link">
                    <i class="fas fa-sitemap fa-fw"></i>
                    <span>الفئات</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('brandad.index')}}" class="nav-link">
                    <i class="fas fa-tags fa-fw"></i>
                    <span>الماركات</span>
                </a>
            </li>
             <li class="nav-item">
                <a href="{{route('orderad.index')}}" class="nav-link">
                    <i class="fas fa-receipt fa-fw"></i>
                    <span>الطلبات</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- زر العودة للموقع في الأسفل -->
    <div class="sidebar-footer">
        <a href="{{ url('/') }}" class="btn-back-to-site">
            <i class="fas fa-external-link-alt"></i>
            <span>العودة إلى الموقع</span>
        </a>
    </div>
</aside>
