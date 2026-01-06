@extends('admin.admin')

@section('title','Dashboard')
@section('page_title','📊 Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@section('content')
    <div class="admin-container">

        {{-- Header --}}
        <div class="admin-card dash-head">
            <div class="dash-head__row">
                <div>
                    <h2 class="admin-h2 dash-head__title">Tổng quan hệ thống</h2>
                    <div class="admin-muted dash-head__sub">
                        Cập nhật nhanh số liệu: sản phẩm • đơn hàng • danh mục • người dùng • doanh thu
                    </div>
                </div>
                <span class="admin-pill">Admin Panel</span>
            </div>
        </div>

        {{-- Stats --}}
        <div class="dash-stats">
            <div class="admin-stat dash-stat">
                <div class="dash-stat__top">
                    <div class="admin-stat__label">🧋 Tổng sản phẩm</div>
                    <span class="dash-dot dash-dot--green"></span>
                </div>
                <div class="admin-stat__value">{{ $totalProducts ?? 0 }}</div>
                <div class="admin-muted dash-stat__sub">Sản phẩm đang hiển thị</div>
            </div>

            <div class="admin-stat dash-stat">
                <div class="dash-stat__top">
                    <div class="admin-stat__label">📦 Tổng đơn hàng</div>
                    <span class="dash-dot dash-dot--blue"></span>
                </div>
                <div class="admin-stat__value">{{ $totalOrders ?? 0 }}</div>
                <div class="admin-muted dash-stat__sub">Tất cả trạng thái</div>
            </div>

            <div class="admin-stat dash-stat">
                <div class="dash-stat__top">
                    <div class="admin-stat__label">📁 Tổng danh mục</div>
                    <span class="dash-dot dash-dot--purple"></span>
                </div>
                <div class="admin-stat__value">{{ $totalCategories ?? 0 }}</div>
                <div class="admin-muted dash-stat__sub">Phân loại sản phẩm</div>
            </div>

            <div class="admin-stat dash-stat dash-stat--revenue">
                <div class="dash-stat__top">
                    <div class="admin-stat__label">💰 Doanh thu (Completed)</div>
                    <span class="dash-dot dash-dot--gold"></span>
                </div>
                <div class="admin-stat__value">
                    {{ number_format($revenueCompleted ?? 0) }} đ
                </div>
                <div class="admin-muted dash-stat__sub">
                    Tháng này: {{ number_format($revenueThisMonth ?? 0) }} đ
                </div>
            </div>
        </div>

        {{-- Tables --}}
        <div class="dash-grid">

            {{-- USERS --}}
            <div class="admin-card dash-card">
                <div class="dash-card__head">
                    <h3 class="dash-card__title">👤 Người dùng</h3>
                    <span class="dash-chip">Tổng: <b>{{ $totalUsers ?? 0 }}</b></span>
                </div>

                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Loại</th>
                            <th style="width:160px" class="td-right">Số lượng</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="admin-td--left">
                                <span class="admin-badge">ADMIN</span>
                            </td>
                            <td class="td-right"><b>{{ $totalAdmins ?? 0 }}</b></td>
                        </tr>
                        <tr>
                            <td class="admin-td--left">
                                <span class="admin-pill">USER</span>
                            </td>
                            <td class="td-right"><b>{{ $totalNormalUsers ?? 0 }}</b></td>
                        </tr>
                        <tr>
                            <td class="admin-td--left"><b>Tổng</b></td>
                            <td class="td-right"><b>{{ $totalUsers ?? 0 }}</b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="dash-note">
                    * USER tính cả role = null hoặc role = user
                </div>
            </div>

            {{-- ORDERS STATUS --}}
            <div class="admin-card dash-card">
                <div class="dash-card__head">
                    <h3 class="dash-card__title">📦 Đơn hàng theo trạng thái</h3>
                    <span class="dash-chip">Completed: <b>{{ $ordersCompleted ?? 0 }}</b></span>
                </div>

                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Trạng thái</th>
                            <th style="width:160px" class="td-right">Số lượng</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="admin-td--left">
                                <span class="dash-status dash-status--pending">pending</span>
                            </td>
                            <td class="td-right"><b>{{ $ordersPending ?? 0 }}</b></td>
                        </tr>
                        <tr>
                            <td class="admin-td--left">
                                <span class="dash-status dash-status--confirmed">confirmed</span>
                            </td>
                            <td class="td-right"><b>{{ $ordersConfirmed ?? 0 }}</b></td>
                        </tr>
                        <tr>
                            <td class="admin-td--left">
                                <span class="dash-status dash-status--shipping">shipping</span>
                            </td>
                            <td class="td-right"><b>{{ $ordersShipping ?? 0 }}</b></td>
                        </tr>
                        <tr>
                            <td class="admin-td--left">
                                <span class="dash-status dash-status--completed">completed</span>
                            </td>
                            <td class="td-right"><b>{{ $ordersCompleted ?? 0 }}</b></td>
                        </tr>
                        <tr>
                            <td class="admin-td--left">
                                <span class="dash-status dash-status--cancelled">cancelled</span>
                            </td>
                            <td class="td-right"><b>{{ $ordersCancelled ?? 0 }}</b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="dash-note">
                    Doanh thu đang tính theo đơn <b>completed</b>.
                </div>
            </div>

        </div>
    </div>
@endsection
