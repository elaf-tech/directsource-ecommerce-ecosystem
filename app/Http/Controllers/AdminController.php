<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Order;
use Carbon\Carbon; // <-
use App\Services\GroqService; // <-- تأكدي من استدعاء الخدمة

use Illuminate\Support\Facades\DB; // <-- **مهم: استيراد DB Facade**

    class AdminController extends Controller // قد يختلف اسم الكنترولر
    {

          private function getWeekExpression()
    {
        $driver = config('database.default');
        switch ($driver) {
            case 'mysql':
                return "DATE_FORMAT(created_at, '%Y-%m-%d') - INTERVAL (DAYOFWEEK(created_at) - 1) DAY";
            case 'sqlite':
                return "date(created_at, 'weekday 0', '-6 days')";
            case 'pgsql':
                return "date_trunc('week', created_at)";
            default:
                return "created_at"; // Fallback
        }
    }

    /**
     * عرض لوحة التحكم الرئيسية مع كل الإحصائيات والتقارير.
     */
    public function index(GroqService $groq) // <-- حقن الخدمة هنا
    {
        // --- القسم الأول: الإحصائيات العامة ---
        $userCount = User::count();
        $categoryCount = Category::count();
        $brandCount = Brand::count();
        $supplierCount = Supplier::count();
        $productCount = Product::count();
        $totalOrders = Order::count();

        // --- القسم الثاني: بيانات الرسم البياني (تجميع أسبوعي) ---
        $weekExpression = $this->getWeekExpression();
        $ordersData = Order::select(
                DB::raw("$weekExpression as week_start_date"),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subWeeks(8))
            ->groupBy('week_start_date')
            ->orderBy('week_start_date', 'asc')
            ->get();

        $chartLabels = $ordersData->pluck('week_start_date')->map(fn($date) => Carbon::parse($date)->format('d M'));
        $chartData = $ordersData->pluck('count');

        // --- القسم الثالث: بيانات التقارير الذكية ---
        
        // 1. تقرير أكثر 5 منتجات مبيعاً
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.name')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        // 2. تقرير أكثر 5 موردين تحقيقاً للمبيعات
        $topSuppliers = Supplier::withCount(['products as total_sales' => function ($query) {
            $query->select(DB::raw('sum(order_items.quantity)'))
                  ->join('order_items', 'products.id', '=', 'order_items.product_id');
        }])
        ->orderBy('total_sales', 'desc')
        ->take(5)
        ->get();

        // --- القسم الرابع: إنشاء التقرير الذكي باستخدام Groq ---
        $reportPrompt = "أنت محلل بيانات خبير في متجر إلكتروني. سأعطيك بعض الأرقام، ومهمتك هي كتابة تقرير موجز ومفيد لمدير النظام باللغة العربية. استخدم لغة بسيطة ومباشرة.

        البيانات:
        - عدد الطلبات في آخر 8 أسابيع (البيانات مرتبة من الأقدم للأحدث): " . json_encode($chartData->toArray()) . "
        - أكثر المنتجات مبيعاً: " . json_encode($topProducts->pluck('name')->toArray()) . "
        - أكثر الموردين تحقيقاً للمبيعات: " . json_encode($topSuppliers->pluck('company_name')->toArray()) . "

        اكتب تقريراً من 3 فقرات:
        1. فقرة عن أداء الطلبات خلال الفترة الماضية، مع الإشارة إلى أي نمو أو انخفاض ملحوظ.
        2. فقرة عن المنتجات الأكثر طلباً مع توصية بسيطة (مثلاً: التركيز على تسويقها أو زيادة مخزونها).
        3. فقرة عن الموردين الأفضل أداءً مع توصية (مثلاً: تعزيز الشراكة معهم).";

        // استدعاء Groq للحصول على التحليل الذكي
        $intelligentReport = $groq->ask($reportPrompt);

        // --- تمرير كل البيانات إلى الواجهة ---
        return view('admin.dashboard', [
            // بيانات الإحصائيات العامة
            'userCount' => $userCount,
            'productCount' => $productCount,
            'supplierCount' => $supplierCount,
            'brandCount' => $brandCount,
            'categoryCount' => $categoryCount,
            'totalOrders' => $totalOrders,
            
            // بيانات الرسم البياني
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,

            // بيانات التقارير الذكية
            'topProducts' => $topProducts,
            'topSuppliers' => $topSuppliers,
            'intelligentReport' => $intelligentReport,
        ]);
    }
    //     public function index()
    // {
    //     // --- الإحصائيات العامة (تبقى كما هي) ---
    //     $userCount = User::count();
    //     $categoryCount = Category::count();
    //     $brandCount = Brand::count();
    //     $supplierCount = Supplier::count();
    //     $productCount = Product::count();
    //     $totalOrders = Order::count();

    //     // --- **الجديد: تجهيز بيانات الرسم البياني (تجميع أسبوعي)** ---
        
    //     // تحديد دالة تجميع الأسبوع بناءً على نوع قاعدة البيانات
    //     $weekExpression = $this->getWeekExpression();

    //     $ordersData = Order::select(
    //             DB::raw("$weekExpression as week_start_date"), // تجميع حسب بداية الأسبوع
    //             DB::raw('count(*) as count')
    //         )
    //         ->where('created_at', '>=', Carbon::now()->subWeeks(8)) // جلب بيانات آخر 8 أسابيع
    //         ->groupBy('week_start_date')
    //         ->orderBy('week_start_date', 'asc')
    //         ->get();

    //     // تحضير المصفوفات للرسم البياني
    //     $chartLabels = $ordersData->pluck('week_start_date')->map(function ($date) {
    //         // عرض التاريخ بصيغة "يوم شهر" (e.g., 21 Oct)
    //         return Carbon::parse($date)->format('d M'); 
    //     });
    //     $chartData = $ordersData->pluck('count');


    //     // --- تمرير كل البيانات إلى الواجهة ---
    //     return view('admin.dashboard', [
    //         'userCount' => $userCount,
    //         'productCount' => $productCount,
    //         'supplierCount' => $supplierCount,
    //         'brandCount' => $brandCount,
    //         'categoryCount' => $categoryCount,
    //         'totalOrders' => $totalOrders,
            
    //         // تمرير بيانات الرسم البياني الأسبوعية
    //         'chartLabels' => $chartLabels,
    //         'chartData' => $chartData,
    //     ]);
    // }

    // /**
    //  * دالة مساعدة لتحديد صيغة تجميع الأسبوع بناءً على نوع قاعدة البيانات
    //  * هذا يجعل الكود يعمل على MySQL, PostgreSQL, و SQLite
    //  * @return string
    //  */
    // private function getWeekExpression()
    // {
    //     $driver = config('database.default');
    //     $connection = config("database.connections.$driver.driver");

    //     switch ($connection) {
    //         case 'mysql':
    //             // `created_at` - INTERVAL(DAYOFWEEK(`created_at`) - 1) DAY
    //             // هذه الصيغة ترجع تاريخ بداية الأسبوع (الأحد أو الاثنين حسب إعدادات السيرفر)
    //             return 'DATE(created_at - INTERVAL(DAYOFWEEK(created_at) - 1) DAY)';
    //         case 'sqlite':
    //             return "strftime('%Y-%W', created_at)";
    //         case 'pgsql':
    //             return "date_trunc('week', created_at)";
    //         default:
    //             // صيغة افتراضية لـ MySQL
    //             return 'DATE(created_at - INTERVAL(DAYOFWEEK(created_at) - 1) DAY)';
    //     }
    // }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
