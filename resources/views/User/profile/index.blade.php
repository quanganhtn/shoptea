@extends('User.layouts.home')

@section('title', 'Thông tin cá nhân')

@section('content')
    <div class="container py-3 page-offset">
        <h2 class="mb-3">👤 Thông tin cá nhân</h2>

        <div class="card-soft" style="padding:14px">
            <div style="display:grid;gap:10px;max-width:520px">
                <div>
                    <div style="opacity:.7;font-size:13px">Tên</div>
                    <div style="font-weight:700">{{ $user->name }}</div>
                </div>
                <div>
                    <div style="opacity:.7;font-size:13px">Email</div>
                    <div style="font-weight:700">{{ $user->email }}</div>
                </div>
                <div>
                    <div style="opacity:.7;font-size:13px">Số điện thoại</div>
                    <div style="font-weight:700">{{ $user->phone ?? '—' }}</div>
                </div>
                <div>
                    <div style="opacity:.7;font-size:13px">Vai trò</div>
                    <div style="font-weight:700">{{ $user->role ?? 'user' }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
