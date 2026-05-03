<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // البحث في كل الحقول المطلوبة: الاسم، الإيميل، الهاتف، والدور
        $users = User::query()
            ->when($request->input('q'), function ($query, $q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%")
                      ->orWhere('role', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate(10); // يمكنك تعديل عدد النتائج في كل صفحة

        // هذا الشرط مهم للبحث المباشر (AJAX)
        // إذا كان الطلب من نوع AJAX، سنقوم بإرجاع جزء HTML الخاص بالجدول فقط
        if ($request->ajax()) {
            return view('admin.users.users_table', compact('users'))->render();
        }

        // في حالة التحميل العادي للصفحة، نعرض الصفحة كاملة
        return view('admin.users.index', compact('users'));
    }


    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // حفظ التعديل
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'phone'   => 'nullable|string|max:50',
            'role'    => 'required|in:customer,admin,supplier', // عدّل الأدوار حسب نظامك
            'password'=> 'nullable|min:6',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'تم تحديث المستخدم بنجاح.');
    }

    // حذف المستخدم
    public function destroy(User $user)
    {
        // اختياري: منع حذف نفسك
        if (auth()->id() === $user->id) {
            return back()->with('error', 'لا يمكنك حذف حسابك.');
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'تم حذف المستخدم بنجاح.');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'الاسم مطلوب.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'صيغة البريد غير صحيحة.',
            'email.unique' => 'هذا البريد مستخدم مسبقاً.',
            'role.required' => 'الدور مطلوب.',
            'role.in' => 'القيمة المختارة للدور غير صالحة.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير مطابق.',
        ];

        $validated = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255'],
            'phone'    => ['nullable','string','max:20'],
            'address'  => ['nullable','string','max:255'],
            'role'     => ['required'],
            'password' => ['required','string','min:8'],
        ], $messages);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'address'  => $validated['address'] ?? null,
            'role'     => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('user.index')->with('success', 'تم إضافة المستخدم بنجاح.');
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
    

    /**
     * Update the specified resource in storage.
     */
    
}
