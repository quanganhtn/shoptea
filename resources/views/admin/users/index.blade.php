@extends('admin.admin')

@section('title','Users')
@section('page_title','👤 Người dùng')

@section('content')
    <div class="admin-container">

        <h2 class="admin-h2">Danh sách người dùng</h2>

        <div class="admin-card">
            <table class="admin-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Ngày tạo</th>
                    <th>chi tiết</th>
                </tr>
                </thead>

                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td class="admin-td--left">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                        <span class="admin-badge">
                            {{ $user->role ?? 'user' }}
                        </span>
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>

                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}"
                               class="admin-btn admin-btn--primary admin-btn--sm">
                                Xem
                            </a>
                        </td>


                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="admin-empty">Chưa có người dùng</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
