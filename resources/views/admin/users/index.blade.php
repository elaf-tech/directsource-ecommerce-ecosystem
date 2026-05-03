@extends('admin.layouts.app')

@section('title', 'إدارة المستخدمين')

@section('content')
<style>
    /* --- تصميم الصفحة العام --- */
    .page-content { background: linear-gradient(120deg, #f7f9f6 0%, #f0f5eb 100%); border-radius: 20px; padding: 30px !important; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .page-title { font-size: 2.2rem; font-weight: 800; color: var(--dark-color); display: flex; align-items: center; gap: 15px; }
    .page-title i { color: var(--primary-color); }
    .btn-add-user { background: var(--gradient); color: white; padding: 12px 25px; border-radius: 12px; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(78, 107, 58, 0.2); }
    .btn-add-user:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(78, 107, 58, 0.3); }

    /* --- بطاقة المحتوى الرئيسية --- */
    .content-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); border-radius: 20px; padding: 25px; box-shadow: 0 8px 32px 0 rgba(44, 62, 80, 0.1); }
    
    /* --- شريط الأدوات والبحث --- */
    .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .search-box { position: relative; }
    .search-box i { position: absolute; top: 50%; right: 15px; transform: translateY(-50%); color: #aaa; }
    .search-input { border: 1px solid #e5e7eb; border-radius: 12px; padding: 12px 40px 12px 20px; width: 320px; transition: all 0.3s ease; }
    .search-input:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(78, 107, 58, 0.1); }
    
    /* --- تصميم الجدول --- */
    .table { width: 100%; border-collapse: collapse; }
    .table thead th { padding: 12px 15px; text-align: right; color: #6b7280; font-weight: 700; border-bottom: 2px solid var(--border-color); }
    .table tbody tr { border-bottom: 1px solid var(--border-color); transition: background-color 0.2s ease; }
    .table tbody tr:last-child { border-bottom: none; }
    .table tbody tr:hover { background-color: #f7fafc; }
    .table tbody td { padding: 15px; vertical-align: middle; }
    
    .user-info { display: flex; align-items: center; gap: 12px; }
    .user-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
    .user-name { font-weight: 700; color: var(--dark-color); }
    .user-email { font-size: 0.9rem; color: #6c757d; }
    
    .badge-role { padding: 5px 12px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; text-transform: capitalize; }
    .role-user { background-color: #e0e7ff; color: #4338ca; }
    .role-admin { background-color: #fef3c7; color: #92400e; }
    .role-supplier { background-color: #d1fae5; color: #065f46; }
    
    .actions { display: flex; gap: 8px; justify-content: flex-end; }
    .btn-action { width: 36px; height: 36px; border-radius: 10px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; transition: all 0.2s ease; }
    .btn-edit { background-color: #e0e7ff; color: #4338ca; }
    .btn-edit:hover { background-color: #c7d2fe; }
    .btn-delete { background-color: #fee2e2; color: #b91c1c; }
    .btn-delete:hover { background-color: #fecaca; }
</style>

<div class="page-header">
    <h2 class="page-title"><i class="fas fa-users"></i> إدارة المستخدمين</h2>
    <a href="{{route('user.create')}}" class="btn-add-user"><i class="fas fa-plus"></i> إضافة مستخدم جديد</a>
</div>

<div class="content-card">
    <div class="toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input class="search-input" type="text" id="user-search-input" placeholder="ابحث بالاسم, الإيميل, الهاتف, الدور...">
        </div>
        <div class="count text-muted">إجمالي: <span id="users-total-count">{{ $users->total() }}</span> مستخدم</div>
    </div>

    <!-- حاوية الجدول التي سيتم تحديثها -->
    <div id="users-table-container">
        @include('admin.users.users_table', ['users' => $users])
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('user-search-input');
    const tableContainer = document.getElementById('users-table-container');
    const totalCountSpan = document.getElementById('users-total-count');
    let searchTimeout;

    // دالة لجلب البيانات وتحديث الجدول
    function fetchUsers(query = '') {
        const url = `{{ route('user.index') }}?q=${encodeURIComponent(query)}`;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // هذا الهيدر مهم ليعرف Laravel أنه طلب AJAX
            }
        })
        .then(response => response.text())
        .then(html => {
            tableContainer.innerHTML = html;
            // تحديث العدد الإجمالي (اختياري، يتطلب تعديل بسيط في الكنترولر إذا أردت العدد المفلتر)
        })
        .catch(error => console.error('Error fetching users:', error));
    }

    // الاستماع لحدث الكتابة في حقل البحث
    searchInput.addEventListener('keyup', function () {
        // إلغاء المؤقت السابق إذا كان موجوداً
        clearTimeout(searchTimeout);

        // تعيين مؤقت جديد لتجنب إرسال طلبات مع كل ضغطة مفتاح
        searchTimeout = setTimeout(() => {
            fetchUsers(this.value);
        }, 300); // الانتظار 300 ميلي ثانية بعد توقف المستخدم عن الكتابة
    });
});
</script>
@endpush
