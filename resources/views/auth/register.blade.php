@extends('layouts.register')

@section('page_title', 'Đăng ký - ShopTea')

@section('content')

    {{-- Lỗi validate --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2 class="title">ĐĂNG KÝ</h2>

    <div class="form-wrap">
        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <label>Họ tên</label>
            <input type="text" name="name" class="input" required>

            <label>Email</label>
            <input type="email" name="email" class="input" required>

            <label>Điện thoại</label>
            <input type="tel" name="phone" class="input" required>

            <label>Mật khẩu</label>
            <input type="password" name="password" class="input" required>

            <button class="btn-primary" type="submit">Đăng ký</button>
        </form>

        <div class="auth-links auth-links--row">
            <span>Đã có tài khoản?</span>
            <a class="link" href="{{ route('login') }}">Đăng nhập</a>

            <a href="{{ route('auth.google.redirect') }}" class="google-pill">
                <span class="google-pill-icon">G</span>
                Google
            </a>
        </div>


    </div>
@endsection
