@extends('admin.admin')

@section('title','Dashboard')
@section('page_title','📊 Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endpush

@section('content')
    <div class="admin-container">

        {{-- HEADER (gọn, không có nút) --}}
        <div class="admin-card dash-head">
            <div class="dash-head__row">
                <div>
                    <h2 class="admin-h2 dash-head__title">Tổng quan hệ thống</h2>

                </div>
                <span class="admin-pill">Admin Panel</span>
            </div>
        </div>

        {{-- KPI (clickable) --}}
        <div class="dash-stats">

            <a class="dash-kpi admin-card dash-link" href="{{ route('admin.products.index') }}">
                <div class="dash-kpi__top">
                    <div class="dash-kpi__label">🧋 Sản phẩm</div>
                </div>
                <div class="dash-kpi__value">{{ $totalProducts ?? 0 }}</div>
            </a>

            <a class="dash-kpi admin-card dash-link" href="{{ route('admin.orders.index') }}">
                <div class="dash-kpi__top">
                    <div class="dash-kpi__label">📦 Đơn hàng</div>
                </div>
                <div class="dash-kpi__value">{{ $totalOrders ?? 0 }}</div>
            </a>

            <a class="dash-kpi admin-card dash-link" href="{{ route('admin.categories.index') }}">
                <div class="dash-kpi__top">
                    <div class="dash-kpi__label">📁 Danh mục</div>
                </div>
                <div class="dash-kpi__value">{{ $totalCategories ?? 0 }}</div>
            </a>

            <a class="dash-kpi admin-card dash-link dash-kpi--revenue"
               href="{{ route('admin.orders.index', ['status' => 'completed']) }}">
                <div class="dash-kpi__top">
                    <div class="dash-kpi__label">💰 Doanh thu</div>
                </div>
                <div class="dash-kpi__value">{{ number_format($revenueCompleted ?? 0) }} đ</div>
            </a>

        </div>

        {{-- MINI INSIGHT (clickable) --}}
        <div class="dash-insights">

            <a class="admin-card dash-mini dash-link" href="{{ route('admin.orders.index', ['status' => 'pending']) }}">
                <div class="dash-mini__title">⏳ Pending</div>
                <div class="dash-mini__value">{{ $ordersPending ?? 0 }}</div>
            </a>

            <a class="admin-card dash-mini dash-link"
               href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}">
                <div class="dash-mini__title">✅ Confirmed</div>
                <div class="dash-mini__value">{{ $ordersConfirmed ?? 0 }}</div>
            </a>

            <a class="admin-card dash-mini dash-link"
               href="{{ route('admin.orders.index', ['status' => 'shipping']) }}">
                <div class="dash-mini__title">🚚 Shipping</div>
                <div class="dash-mini__value">{{ $ordersShipping ?? 0 }}</div>
            </a>

            <a class="admin-card dash-mini dash-link" href="{{ route('admin.users.index') }}">
                <div class="dash-mini__title">👤 Users</div>
                <div class="dash-mini__value">{{ $totalUsers ?? 0 }}</div>
            </a>

        </div>

        {{-- ĐƠN HÀNG GẦN ĐÂY (chỉ 1 card, gọn) --}}
        <div class="admin-card dash-card" style="margin-top:12px">
            <div class="dash-card__head">
                <h3 class="dash-card__title">🧾 5 đơn hàng gần nhất</h3>
                <span class="dash-chip">
                    Hôm nay: <b>{{ $ordersToday ?? 0 }}</b>
                </span>
            </div>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th style="width:90px">#</th>
                        <th>Khách</th>
                        <th style="width:140px">Trạng thái</th>
                        <th style="width:160px" class="td-right">Tổng</th>
                        <th style="width:140px">Ngày</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse(($recentOrders ?? []) as $o)
                        <tr>
                            <td><b>#{{ $o->id }}</b></td>

                            <td class="admin-td--left">
                                {{ $o->name ?? ($o->customer_name ?? ($o->user->name ?? '—')) }}
                                <div class="admin-muted" style="font-size:12px">
                                    {{ $o->phone ?? ($o->customer_phone ?? '') }}
                                </div>
                            </td>

                            <td>
                                <span class="dash-status dash-status--{{ $o->status }}">
                                    {{ $o->status }}
                                </span>
                            </td>

                            <td class="td-right">
                                <b>{{ number_format($o->total ?? ($o->total_price ?? 0)) }} đ</b>
                            </td>

                            <td>{{ optional($o->created_at)->format('d/m H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="admin-muted" style="padding:14px">
                                Chưa có đơn hàng nào.
                                <div style="margin-top:6px">

                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="dash-note">

            </div>
        </div>

    </div>
@endsection
