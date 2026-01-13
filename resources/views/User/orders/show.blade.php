@extends('User.layouts.home')
@section('title','Chi tiết đơn hàng')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/my-orders.css') }}">
@endpush

@php
    $statusLabel = [
        'pending' => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'shipping' => 'Đang giao',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Đã hủy',
    ];
    $st = $order->status;
    $stText = $statusLabel[$st] ?? $st;
@endphp

@section('content')
    <div class="container py-3 page-offset">

        <div class="myo-head">
            <div>
                <h2 class="myo-title">🧾 Chi tiết đơn</h2>
                <div class="myo-sub">{{ $order->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <a href="{{ route('orders.my') }}" class="myo-btn myo-btn--ghost">← Quay lại</a>
        </div>

        <div class="myd-grid">
            {{-- LEFT: ORDER INFO --}}
            <div class="myd-card">
                <div class="myd-card__head">
                    <div class="myd-card__title">Trạng thái</div>
                    <span class="myo-badge myo-badge--{{ $st }}">{{ $stText }}</span>
                </div>

                <div class="myd-info">
                    <div class="myd-row">
                        <span class="myd-k">Tổng tiền</span>
                        <span class="myd-v myd-v--strong">{{ number_format($order->total_price) }} đ</span>
                    </div>
                    <div class="myd-row">
                        <span class="myd-k">Thanh toán</span>
                        <span class="myd-v">{{ strtoupper($order->payment ?? 'cod') }}</span>
                    </div>
                    @if($order->note)
                        <div class="myd-row">
                            <span class="myd-k">Ghi chú</span>
                            <span class="myd-v">{{ $order->note }}</span>
                        </div>
                    @endif
                    {{-- ACTIONS --}}
                    <div class="myd-actions">
                        @if($order->status === 'pending')
                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST"
                                  onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn này?');">
                                @csrf
                                <button type="submit" class="myd-btn myd-btn--danger">
                                    ❌ Hủy đơn hàng
                                </button>
                            </form>
                        @else
                            <button type="button" class="myd-btn myd-btn--danger is-disabled" disabled>
                                ❌ Hủy đơn hàng
                            </button>
                            <div class="myd-help">
                                Đơn đã <b>{{ $stText }}</b>.
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- RIGHT: USER/SHIPPING INFO --}}
            <div class="myd-card">
                <div class="myd-card__head">
                    <div class="myd-card__title">Thông tin người nhận</div>
                </div>

                <div class="myd-info">
                    <div class="myd-row">
                        <span class="myd-k">Họ tên</span>
                        <span class="myd-v">{{ $order->fullname }}</span>
                    </div>
                    <div class="myd-row">
                        <span class="myd-k">SĐT</span>
                        <span class="myd-v">{{ $order->phone }}</span>
                    </div>
                    <div class="myd-row">
                        <span class="myd-k">Địa chỉ</span>
                        <span class="myd-v">{{ $order->address }}</span>
                    </div>

                    <div class="myd-row">
                        <span class="myd-k">Tài khoản</span>
                        <span class="myd-v">
                        {{ $order->user?->name ?? auth()->user()->name }}
                        ({{ $order->user?->email ?? auth()->user()->email }})
                    </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ITEMS --}}
        <div class="myd-card" style="margin-top:12px">
            <div class="myd-card__head">
                <div class="myd-card__title">Sản phẩm trong đơn</div>
            </div>

            <div class="myd-items">
                @foreach($order->items as $it)
                    @php
                        $p = $it->product; // có thể null nếu sản phẩm bị xóa
                        $img = $p?->image ? asset('uploads/'.$p->image) : null;
                        $productUrl = $p ? route('products.show', $p->id) : null;
                    @endphp

                    <div class="myd-item">
                        {{-- ẢNH (CLICK) --}}
                        @if($productUrl)
                            <a href="{{ $productUrl }}" class="myd-item__img myd-item__link">
                                @if($img)
                                    <img src="{{ $img }}" alt="{{ $p->name }}">
                                @else
                                    <span>🧋</span>
                                @endif
                            </a>
                        @else
                            <div class="myd-item__img">
                                <span>🧋</span>
                            </div>
                        @endif

                        {{-- INFO --}}
                        <div class="myd-item__info">
                            {{-- TÊN (CLICK) --}}
                            @if($productUrl)
                                <a href="{{ $productUrl }}" class="myd-item__name myd-item__link">
                                    {{ $p->name }}
                                </a>
                            @else
                                <div class="myd-item__name">
                                    {{ '#'.$it->product_id }} (đã bị xóa)
                                </div>
                            @endif

                            <div class="myd-item__sub">
                                SL: {{ $it->quantity }} • Giá: {{ number_format($it->price) }} đ
                            </div>
                        </div>

                        {{-- TOTAL (KHÔNG CLICK) --}}
                        <div class="myd-item__total">
                            {{ number_format($it->price * $it->quantity) }} đ
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
