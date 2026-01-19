@extends('User.layouts.home')
@section('title','Đơn hàng của tôi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/my-orders.css') }}">
@endpush


@php
    $status = request('status');
    $statusLabel = [
        'pending' => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'shipping' => 'Đang giao',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Đã hủy',
    ];
@endphp

@section('content')
    <div class="container py-3 page-offset">

        <div class="myo-head">
            <div>
                <h2 class="myo-title">📦 Đơn hàng của tôi</h2>
                <div class="myo-sub">
                    Xin chào <b>{{ auth()->user()->name }}</b> • Theo dõi đơn hàng của bạn
                </div>
            </div>
        </div>

        {{-- FILTER --}}
        <div class="myo-filters">
            <a class="myo-chip {{ empty($status) ? 'is-active' : '' }}" href="{{ route('orders.my') }}">Tất cả</a>
            <a class="myo-chip {{ $status==='pending' ? 'is-active' : '' }}"
               href="{{ route('orders.my', ['status'=>'pending']) }}">Chờ xác nhận</a>
            <a class="myo-chip {{ $status==='confirmed' ? 'is-active' : '' }}"
               href="{{ route('orders.my', ['status'=>'confirmed']) }}">Đã xác nhận</a>
            <a class="myo-chip {{ $status==='shipping' ? 'is-active' : '' }}"
               href="{{ route('orders.my', ['status'=>'shipping']) }}">Đang giao</a>
            <a class="myo-chip {{ $status==='completed' ? 'is-active' : '' }}"
               href="{{ route('orders.my', ['status'=>'completed']) }}">Hoàn thành</a>
            <a class="myo-chip {{ $status==='cancelled' ? 'is-active' : '' }}"
               href="{{ route('orders.my', ['status'=>'cancelled']) }}">Đã hủy</a>
        </div>

        @if($orders->count())
            <div class="myo-list">
                @foreach($orders as $o)
                    @php
                        $st = $o->status;
                        $stText = $statusLabel[$st] ?? $st;
                        $firstItem = $o->items->first();
                        $firstProduct = $firstItem?->product;
                        $img = $firstProduct?->image ? asset('uploads/'.$firstProduct->image) : null;
                        $itemsCount = $o->items->count();
                    @endphp

                    <a class="myo-card" href="{{ route('orders.show', $o->id) }}">
                        <div class="myo-card__top">
                            <div class="myo-order">

                                <div class="myo-order__time">{{ $o->created_at->format('d/m/Y H:i') }}</div>
                            </div>

                            <span class="myo-badge myo-badge--{{ $st }}">{{ $stText }}</span>
                        </div>

                        <div class="myo-card__mid">
                            <div class="myo-prod">
                                <div class="myo-prod__img">
                                    @if($img)
                                        <img src="{{ $img }}" alt="{{ $firstProduct->name ?? 'Sản phẩm' }}">
                                    @else
                                        <span>🧋</span>
                                    @endif
                                </div>

                                <div class="myo-prod__info">
                                    <div class="myo-prod__name">
                                        {{ $firstProduct->name ?? 'Sản phẩm không tồn tại' }}
                                    </div>
                                    <div class="myo-prod__sub">
                                        {{ $itemsCount }} sản phẩm • Thanh toán: {{ strtoupper($o->payment ?? 'cod') }}
                                    </div>
                                </div>

                                <div class="myo-prod__price">
                                    {{ number_format($o->total_price) }} đ
                                </div>
                            </div>
                        </div>

                        <div class="myo-card__bot">
                            <div class="myo-mini">
                                <div class="myo-mini__k">Người nhận</div>
                                <div class="myo-mini__v">{{ $o->fullname }}</div>
                            </div>
                            <div class="myo-mini">
                                <div class="myo-mini__k">SĐT</div>
                                <div class="myo-mini__v">{{ $o->phone }}</div>
                            </div>
                            <div class="myo-go">Xem chi tiết →</div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="myo-pagi myo-pagi--beauty">
                {{ $orders->withQueryString()->links() }}
            </div>

        @else
            <div class="myo-empty">
                <div class="myo-empty__icon">🧾</div>
                <div class="myo-empty__title">Chưa có đơn hàng</div>
                <div class="myo-empty__sub">Hãy chọn một loại trà bạn thích và đặt ngay nhé.</div>
                <a href="{{ route('user') }}#products" class="myo-btn myo-btn--primary">Khám phá sản phẩm</a>
            </div>
        @endif

    </div>
@endsection
