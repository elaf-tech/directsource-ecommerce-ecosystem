@extends('admin.layouts.app')

@section('title', 'لوحة التحكم الرئيسية')

@section('content')
<style>
    /* --- متغيرات الألوان الخاصة بك --- */
    :root {
        --primary-color: #4e6b3a;
        --secondary-color: #8aa57c;
        --accent-color: #f8a31b;
        --dark-color: #2c3e50;
        --light-color: #f9f9f9;
        --card-bg: #ffffff;
        --text-dark: #2c3e50;
        --text-light: #5a6878;
        --gradient: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        --shadow-soft: 0 4px 12px rgba(0,0,0,0.05);
        --shadow-medium: 0 8px 30px rgba(0,0,0,0.1);
    }

    /* --- تصميم الصفحة العام --- */
    .page-content {
        background: linear-gradient(120deg, #f7f9f6 0%, #f0f5eb 100%);
        border-radius: 20px;
        padding: 30px !important;
    }

    /* --- العناوين --- */
    .page-title { font-size: 2.2rem; font-weight: 800; color: var(--text-dark); margin-bottom: 5px; }
    .page-subtitle { font-size: 1.1rem; color: var(--text-light); margin-bottom: 35px; }
    .section-title { font-size: 1.6rem; font-weight: 700; color: var(--text-dark); margin-bottom: 25px; }

    /* --- بطاقات الإحصائيات --- */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 25px; margin-bottom: 50px; }
    .stat-card {
        background: var(--card-bg);
        padding: 25px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-soft);
        border: 1px solid #eef;
    }
    .stat-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-medium); }
    .stat-icon {
        width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center;
        justify-content: center; font-size: 1.8rem; color: #fff; flex-shrink: 0; background: var(--gradient);
    }
    .stat-info .stat-number { font-size: 2.3rem; font-weight: 800; color: var(--text-dark); }
    .stat-info .stat-label { font-size: 1rem; color: var(--text-light); font-weight: 600; }

    /* --- تصميم قسم التقارير الذكية الجديد --- */
    .reports-container {
        display: grid;
        grid-template-columns: 1.2fr 1fr; /* العمود الأيسر أعرض قليلاً */
        gap: 30px;
        margin-bottom: 50px;
    }
    .main-report-card {
        background: var(--card-bg);
        border-radius: 20px;
        box-shadow: var(--shadow-medium);
        padding: 35px;
        display: flex;
        flex-direction: column;
    }
    .main-report-card .report-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }
    .main-report-card .report-icon {
        font-size: 2.5rem;
        color: var(--accent-color);
    }
    .main-report-card .report-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--dark-color);
        margin: 0;
    }
    .main-report-card .report-content {
        font-size: 1.1rem;
        line-height: 1.9;
        color: var(--text-light);
        flex-grow: 1;
    }
    .side-reports-stack {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }
    .side-report-card {
        background: var(--card-bg);
        border-radius: 20px;
        box-shadow: var(--shadow-soft);
        padding: 25px;
        transition: all 0.3s ease;
    }
    .side-report-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
    .side-report-card h4 {
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .data-list { list-style: none; padding: 0; }
    .data-list li {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f0f2f5;
        font-weight: 600;
    }
    .data-list li:last-child { border-bottom: none; }
    .data-list .label { color: var(--text-dark); font-size: 0.95rem; }
    .data-list .value {
        background-color: #f0f5eb;
        color: var(--primary-color);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.9rem;
    }

    /* --- قسم الرسم البياني --- */
    .charts-section {
        background: var(--card-bg);
        padding: 30px;
        border-radius: 20px;
        height: 450px;
        box-shadow: var(--shadow-soft);
    }

    /* --- التجاوب مع الشاشات --- */
    @media (max-width: 992px) {
        .reports-container {
            grid-template-columns: 1fr; /* عمود واحد في الشاشات الصغيرة */
        }
    }
</style>

<h2 class="page-title">لوحة التحكم</h2>
<p class="page-subtitle">أهلاً بك مجدداً! إليك نظرة شاملة على أداء متجرك.</p>

<!-- قسم الإحصائيات العامة -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-info"><div class="stat-number">{{ $userCount }}</div><div class="stat-label">مستخدم</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-truck"></i></div>
        <div class="stat-info"><div class="stat-number">{{ $supplierCount }}</div><div class="stat-label">مورد</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-box-open"></i></div>
        <div class="stat-info"><div class="stat-number">{{ $productCount }}</div><div class="stat-label">منتج</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-globe-asia"></i></div>
        <div class="stat-info"><div class="stat-number">{{ $totalOrders }}</div><div class="stat-label">إجمالي الطلبات</div></div>
    </div>
</div>

<!-- قسم التقارير الذكية بالتصميم الجديد -->
<div class="reports-container">
    <!-- العمود الأيسر: التقرير الذكي الرئيسي -->
    <div class="main-report-card">
        <div class="report-header">
            <i class="fas fa-lightbulb report-icon"></i>
            <h3 class="report-title">تحليل الأداء الذكي</h3>
        </div>
        <div class="report-content">
            <p>{{ $intelligentReport ?? 'لا يتوفر تحليل حالياً. تأكد من وجود بيانات كافية.' }}</p>
        </div>
    </div>

    <!-- العمود الأيمن: بطاقات البيانات -->
    <div class="side-reports-stack">
        <div class="side-report-card">
            <h4><i class="fas fa-star"></i> الأكثر مبيعاً</h4>
            <ul class="data-list">
                @forelse($topProducts as $product)
                    <li>
                        <span class="label">{{ Str::limit($product->name, 20) }}</span>
                        <span class="value">{{ $product->total_sold }} قطعة</span>
                    </li>
                @empty
                    <li>لا توجد بيانات.</li>
                @endforelse
            </ul>
        </div>
        <div class="side-report-card">
            <h4><i class="fas fa-truck"></i> أفضل الموردين</h4>
            <ul class="data-list">
                @forelse($topSuppliers as $supplier)
                    <li>
                        <span class="label">{{ Str::limit($supplier->company_name, 20) }}</span>
                        <span class="value">{{ $supplier->total_sales }} مبيعات</span>
                    </li>
                @empty
                    <li>لا توجد بيانات.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>


<!-- قسم الرسم البياني للطلبات -->
<div class="charts-section">
    <h3 class="section-title">أداء الطلبات (آخر 8 أسابيع)</h3>
    <canvas id="ordersBarChart"></canvas>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const canvasElement = document.getElementById('ordersBarChart');
    if (canvasElement) {
        const ctx = canvasElement.getContext('2d');
        const labels = @json($chartLabels);
        const data = @json($chartData);

        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(78, 107, 58, 0.8)');
        gradient.addColorStop(1, 'rgba(138, 165, 124, 0.3)');

        const ordersBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'عدد الطلبات',
                    data: data,
                    backgroundColor: gradient,
                    borderColor: 'rgba(78, 107, 58, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(78, 107, 58, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, padding: 15, color: '#6c757d' },
                        grid: { drawBorder: false, color: '#e9ecef' }
                    },
                    x: {
                        ticks: { padding: 10, color: '#6c757d' },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#2c3e50',
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                }
            }
        });
    }
});
</script>
@endpush
