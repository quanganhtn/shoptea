@extends('admin.admin')

@section('title','Homepage - Contact')
@section('page_title','🏠 Trang chủ / Contact')

@section('content')
    <div class="admin-container">
        <div class="admin-card">
            @if (session('success'))
                <div class="admin-alert admin-alert--success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="admin-alert admin-alert--danger">
                    @foreach($errors->all() as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.homepage.contact.update') }}" method="POST" class="admin-form">
                @csrf

                <div class="admin-grid admin-grid--2">
                    <div class="admin-field">
                        <label class="admin-label">Số điện thoại</label>
                        <input type="text" name="phone" class="admin-input"
                               value="{{ old('phone', $data['phone'] ?? '') }}"
                               placeholder="VD: 0909xxxxxx">
                    </div>

                    <div class="admin-field">
                        <label class="admin-label">Email</label>
                        <input type="email" name="email" class="admin-input"
                               value="{{ old('email', $data['email'] ?? '') }}"
                               placeholder="VD: shoptea@gmail.com">
                    </div>
                </div>

                <div class="admin-field">
                    <label class="admin-label">Địa chỉ</label>
                    <input type="text" name="address" class="admin-input"
                           value="{{ old('address', $data['address'] ?? '') }}"
                           placeholder="VD: 123 Lê Lợi, Q1, TP.HCM">
                </div>

                <div class="admin-form__actions">
                    <button class="admin-btn admin-btn--primary">💾 Lưu Contact</button>
                </div>
            </form>
        </div>
    </div>
@endsection
