@extends('User.layouts.login')

@section('box-type', 'login')

@section('content')
    {{-- ✅ HIỂN THỊ THÔNG BÁO THÀNH CÔNG --}}
    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    {{-- ✅ HIỂN THỊ LỖI --}}
    @if ($errors->any())
        <div class="alert alert-danger text-center">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif
    <h2 class="mb-3 text-center">ĐĂNG NHẬP</h2>

    <form action="{{ route('login.post') }}" method="POST" class="text-start"> <!-- căn trái -->
        @csrf

        <label>Tên Đăng Nhập</label>
        <input type="text" name="login" class="form-control mb-2" required autocomplete="username">

        <label>Mật khẩu</label>
        <input type="password" name="password" class="form-control mb-2" required autocomplete="current-password">

        <div class="form-check mb-2">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
        </div>

        <button class="btn btn-primary w-100 mt-2">Đăng nhập</button>
    </form>

    <div class="auth-links auth-links--row">
        <span>Chưa có tài khoản?</span>
        <a class="link" href="{{ route('register') }}"> Đăng ký ngay</a>

        <a href="{{ route('auth.google.redirect') }}" class="google-pill">
            <span class="google-pill-icon">G</span>
            Google
        </a>
    </div>
@endsection
