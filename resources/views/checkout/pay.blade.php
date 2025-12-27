@extends('layouts.home')

@section('title', 'Thanh toán - ShopTea')

@section('content')
    <div class="container py-4 page-offset checkout-onepage">

        @if (session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        <h3 class="mb-3">Thanh toán</h3>

        {{-- LỖI VALIDATE --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $e)
                    <div>{{ $e }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('checkout.placeOrder') }}" method="POST" class="pay-form">
            @csrf


            <details class="pay-card pay-collapse" open>
                <summary class="pay-collapse__summary">
                    <div class="summary-left">
                        <div class="summary-title">📦 Thông tin nhận hàng</div>
                        <div class="summary-sub">Nhấn để mở/ẩn thông tin</div>
                    </div>
                    <span class="chev" aria-hidden="true">▾</span>
                </summary>

                <div class="pay-card__body">
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="label">Họ và tên</label>
                            <input type="text" name="fullname" class="input input--big"
                                   value="{{ old('fullname', auth()->user()->name ?? '') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="label">Số điện thoại</label>
                            <input type="text" name="phone" class="input input--big"
                                   value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                   placeholder="0xxxxxxxxx" required>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label class="label">Địa chỉ nhận hàng</label>
                        <textarea name="address" class="textarea textarea--big" rows="3"
                                  required>{{ old('address') }}</textarea>
                    </div>

                    <div class="form-group mt-2">
                        <label class="label">Ghi chú (tuỳ chọn)</label>
                        <textarea name="note" class="textarea" rows="2">{{ old('note') }}</textarea>
                    </div>


                    <div class="pay-method mt-3">
                        <div class="pay-method__title">💰 Phương thức thanh toán</div>

                        <label class="pay-pill">
                            <input type="hidden" name="payment" value="cod">
                            <span class="pay-pill__badge">COD</span>
                            <span class="pay-pill__text">Thanh toán khi nhận hàng</span>
                        </label>
                    </div>
                </div>
            </details>


            <div class="pay-card mt-3">
                <div class="pay-card__header">
                    <div>🧾 Tóm tắt đơn hàng</div>

                    @php
                        $qtySum = 0;
                        foreach($cart as $id => $it) { $qtySum += (int)$it['quantity']; }
                    @endphp

                    <div class="qty-badge">
                        Tổng SL: <span class="qty-badge__num">{{ $qtySum }}</span>
                    </div>
                </div>

                <div class="pay-card__body">
                    <div class="order-mini">
                        @foreach ($cart as $id => $item)
                            <div class="order-mini__row">
                                <img src="{{ asset('uploads/' . $item['image']) }}" class="order-mini__img"
                                     alt="{{ $item['name'] }}">

                                <div class="order-mini__info">
                                    <div class="order-mini__name">{{ $item['name'] }}</div>
                                    <div class="order-mini__sub">
                                        SL: {{ $item['quantity'] }} × {{ number_format($item['price']) }}đ
                                    </div>
                                </div>

                                <div class="order-mini__price">
                                    {{ number_format($item['price'] * $item['quantity']) }}đ
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pay-total">
                        <div class="pay-total__label">Tổng tiền</div>
                        <div class="pay-total__value">{{ number_format($total) }}đ</div>
                    </div>
                </div>
            </div>


            <div class="pay-actions mt-4">
                <a href="{{ route('cart.index') }}" class="btn-back">
                    ← Quay lại giỏ hàng
                </a>

                <button type="submit" class="btn-order">
                    ✅ Đặt hàng
                </button>
            </div>

        </form>
    </div>
@endsection
