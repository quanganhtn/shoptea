@extends('admin.admin')

@section('title','Đơn hàng')
@section('page_title','📦 Đơn hàng')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/orders.css') }}">
@endpush

@section('content')
    <div class="admin-container">

        <h2 class="admin-h2">Danh sách đơn hàng</h2>

        <div class="admin-card">
            <table class="admin-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Chi tiết</th>
                </tr>
                </thead>

                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td class="admin-td--left">{{ $order->fullname }}</td>
                        <td>{{ number_format($order->total_price) }} đ</td>

                        <td>
                        <span class="admin-badge">
                            {{ $order->status }}
                        </span>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.edit',$order->id) }}"
                               class="admin-btn admin-btn--primary admin-btn--sm">
                                Xem
                            </a>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="admin-empty">Chưa có đơn hàng</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
