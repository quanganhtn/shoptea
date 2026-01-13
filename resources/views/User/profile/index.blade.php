@extends('User.layouts.home')
@section('title','Thông tin cá nhân')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/profile.css') }}">
@endpush

@php
    $g = $user->gender ?? '';
    $genderText = $g === 'male' ? 'Nam' : ($g === 'female' ? 'Nữ' : ($g === 'other' ? 'Khác' : '—'));
@endphp

@section('content')
    <div class="container py-3 page-offset">

        <div class="pro-head">
            <div>
                <h2 class="pro-title">👤 Thông tin cá nhân</h2>
                <div class="pro-sub">
                    Xin chào <b>{{ auth()->user()->name }}</b> • Cập nhật sẵn để khi mua hàng tự điền địa chỉ
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="pro-alert pro-alert--success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="pro-alert pro-alert--danger">
                <b>Vui lòng kiểm tra lại:</b>
                <ul style="margin:8px 0 0 18px">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="pro-grid">
            {{-- LEFT: summary --}}
            <div class="pro-card">
                <div class="pro-card__top">
                    <div class="pro-user">
                        <div class="pro-avatar">
                            {{ mb_strtoupper(mb_substr($user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="pro-name">{{ $user->name }}</div>
                            <div class="pro-email">{{ $user->email }}</div>
                        </div>
                    </div>

                    <span class="pro-badge">Hồ sơ</span>
                </div>

                <div class="pro-kv">
                    <div class="pro-kv__item">
                        <div class="pro-k">SĐT</div>
                        <div class="pro-v">{{ $user->phone ?? '—' }}</div>
                    </div>

                    <div class="pro-kv__item">
                        <div class="pro-k">Giới tính</div>
                        <div class="pro-v">{{ $genderText }}</div>
                    </div>

                    <div class="pro-kv__item">
                        <div class="pro-k">Ngày sinh</div>
                        <div
                            class="pro-v">{{ $user->dob ?Carbon::parse($user->dob)->format('d/m/Y') : '—' }}</div>
                    </div>

                    <div class="pro-kv__item pro-kv__item--full">
                        <div class="pro-k">Địa chỉ mặc định</div>
                        <div class="pro-v">{{ $user->address ?? 'Chưa có' }}</div>
                    </div>

                    <div class="pro-kv__item pro-kv__item--full">
                        <div class="pro-k">Tiểu sử</div>
                        <div class="pro-v" style="font-weight:700">
                            {{ $user->bio ?: 'Chưa có' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: form --}}
            <div class="pro-card">
                <div class="pro-card__head">
                    <div>
                        <div class="pro-card__title">Cập nhật thông tin</div>
                        <div class="pro-card__sub">Chỉnh sửa trực tiếp tại đây</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="pro-form">
                    @csrf

                    <div class="pro-row">
                        <div class="pro-field">
                            <label class="pro-label">Tên</label>
                            <input class="pro-input" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="pro-field">
                            <label class="pro-label">Số điện thoại</label>
                            <input class="pro-input" name="phone" value="{{ old('phone', $user->phone) }}"
                                   placeholder="VD: 0989xxxxxx">
                        </div>
                    </div>

                    <div class="pro-row">
                        <div class="pro-field">
                            <label class="pro-label">Giới tính</label>
                            <select class="pro-input" name="gender">
                                <option value="">— Chọn —</option>
                                <option value="male" @selected(old('gender', $user->gender) === 'male')>Nam</option>
                                <option value="female" @selected(old('gender', $user->gender) === 'female')>Nữ</option>
                                <option value="other" @selected(old('gender', $user->gender) === 'other')>Khác</option>
                            </select>
                        </div>

                        <div class="pro-field">
                            <label class="pro-label">Ngày sinh</label>
                            <input type="date" class="pro-input" name="dob" value="{{ old('dob', $user->dob) }}">
                        </div>
                    </div>

                    <div class="pro-field">
                        <label class="pro-label">Địa chỉ</label>
                        <input class="pro-input" name="address" value="{{ old('address', $user->address) }}"
                               placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành">
                        <div class="pro-hint">Địa chỉ này sẽ tự điền khi bạn mua hàng.</div>
                    </div>

                    <div class="pro-field">
                        <label class="pro-label">Tiểu sử</label>
                        <textarea class="pro-input pro-textarea" name="bio" rows="4"
                                  placeholder="Viết vài dòng về bạn...">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <div class="pro-actions">
                        <button class="pro-btn pro-btn--primary" type="submit">Lưu thay đổi</button>
                        <a class="pro-btn pro-btn--ghost" href="{{ route('user') }}">Về trang chủ</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
