@extends('layouts.register')
{{-- Hiển thị lỗi --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Hiển thị thông báo thành công từ session --}}
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@section('content')
    <h2 class="text-center">ĐĂNG KÝ</h2>
    <div class="container mt-4 text-start"> <!-- text-start để label và input căn trái -->
        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <label>Họ tên</label>
            <input type="text" name="name" class="form-control mb-2" required>

            <label>Email</label>
            <input type="email" name="email" class="form-control mb-2" required>

            <label>Điện thoại</label>
            <input type="tel" name="phone" class="form-control mb-2" required>

            <label>Mật khẩu</label>
            <input type="password" name="password" class="form-control mb-2" required>

            <label>Nhập lại mật khẩu</label>
            <input type="password" name="password_confirmation" class="form-control mb-3" required>

            <!-- Nút đăng ký dài bằng width form -->
            <button class="btn btn-primary w-100">Đăng ký</button>
        </form>

        <!-- Dòng "Đã có tài khoản? Đăng nhập" xuống dòng mới, căn giữa -->
        <p class="mt-3 text-center">Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p>
    </div>
@endsection
