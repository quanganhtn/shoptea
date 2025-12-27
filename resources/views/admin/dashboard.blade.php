@extends('admin.admin')
@section('title','Dashboard')

@section('page_title','Dashboard')

@section('content')
    <div class="row g-3">
        <div class="col-12 col-md-4">
            <div class="admin-card">
                <div class="text-secondary">Tổng sản phẩm</div>
                <div class="fs-2 fw-bold">{{ $totalProducts ?? 0 }}</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="admin-card">
                <div class="text-secondary">Tổng đơn hàng</div>
                <div class="fs-2 fw-bold">{{ $totalOrders ?? 0 }}</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="admin-card">
                <div class="text-secondary">Tổng user</div>
                <div class="fs-2 fw-bold">{{ $totalUsers ?? 0 }}</div>
            </div>
        </div>
    </div>
@endsection
