<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>المستخدم</th>
                <th>الدور</th>
                <th>تاريخ الانضمام</th>
                <th class="text-end">إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        <div class="user-info">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ) }}&background=random&color=fff&size=40" alt="{{ $user->name }}" class="user-avatar">
                            <div>
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-email">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-role role-{{ $user->role }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('user.edit', $user->id) }}" class="btn-action btn-edit" title="تعديل">
                                <i class="fa fa-pen"></i>
                            </a>
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="حذف">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted p-4">لا توجد نتائج مطابقة لبحثك.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-container mt-3">
    {{ $users->links() }}
</div>
