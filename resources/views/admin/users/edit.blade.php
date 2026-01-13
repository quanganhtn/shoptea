@extends('admin.admin')

@section('title','Chi tiết người dùng')
@section('page_title','👤 Chi tiết người dùng')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/users.css') }}">
@endpush

@section('content')
    <div class="admin-container">

        @if(session('success'))
            <div class="admin-alert admin-alert--success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="admin-alert admin-alert--danger">{{ session('error') }}</div>
        @endif

        {{-- Header --}}
        <div class="admin-card" style="margin-bottom:16px">
            <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;align-items:center">
                <div>
                    <h2 class="admin-h2" style="margin:0">User {{ $user->id }}</h2>
                    <div class="admin-muted" style="margin-top:6px">
                        Tạo ngày: {{ $user->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
                    <span class="admin-badge">{{ $user->role ?? 'user' }}</span>

                    {{-- Nút xóa --}}
                    <form method="POST"
                          action="{{ route('admin.users.destroy', $user->id) }}"
                          onsubmit="return confirm('Xóa user này? Hành động không thể hoàn tác!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-btn admin-btn--danger admin-btn--sm">
                            Xóa
                        </button>
                    </form>

                    <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn--ghost admin-btn--sm">
                        Quay lại
                    </a>
                </div>
            </div>
        </div>

        {{-- Thông tin --}}
        <div class="admin-card">
            <h3 style="margin:0 0 12px 0">Thông tin người dùng</h3>

            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <tbody>
                        <tr>
                            <th style="width:180px">Tên</th>
                            <td>
                                <input class="admin-input" value="{{ $user->name }}" disabled>
                            </td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td>
                                <input class="admin-input" value="{{ $user->email }}" disabled>
                            </td>
                        </tr>

                        <tr>
                            <th>Vai trò</th>
                            <td>
                                <select class="admin-input" name="role">
                                    <option value="user" @selected(($user->role ?? 'user') === 'user')>User</option>
                                    <option value="admin" @selected(($user->role ?? 'user') === 'admin')>Admin</option>
                                </select>
                                @error('role')
                                <div class="admin-error">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <th>Ngày tạo</th>
                            <td>
                                <input class="admin-input" value="{{ $user->created_at->format('d/m/Y') }}" disabled>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="admin-actions" style="margin-top:12px">
                    <button class="admin-btn admin-btn--primary" type="submit">
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
