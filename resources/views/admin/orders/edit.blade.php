@extends('admin.admin')

@section('title','Cập nhật đơn hàng')
@section('page_title','✏️ Cập nhật đơn hàng')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/orders.css') }}">
@endpush

@section('content')
    <div class="admin-container">

        @if(session('success'))
            <div class="admin-alert admin-alert--success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="admin-alert admin-alert--danger">{{ session('error') }}</div>
        @endif

        {{-- Header --}}
        <div class="admin-card" style="margin-bottom:16px">
            <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;align-items:center">
                <div>
                    <h2 class="admin-h2" style="margin:0">Đơn {{ $order->id }}</h2>
                    <div style="color:var(--muted);margin-top:6px">
                        Ngày tạo: {{ $order->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
                    <span class="admin-badge">{{ $order->status }}</span>

                    {{-- đổi trạng thái --}}
                    @if($order->status === 'pending')
                        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button class="admin-btn admin-btn--primary admin-btn--sm" type="submit">Xác nhận</button>
                        </form>

                        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST"
                              onsubmit="return confirm('Huỷ đơn này?')">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button class="admin-btn admin-btn--danger admin-btn--sm" type="submit">Huỷ</button>
                        </form>
                    @endif

                    @if($order->status === 'confirmed')
                        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="shipping">
                            <button class="admin-btn admin-btn--primary admin-btn--sm" type="submit">Giao hàng</button>
                        </form>
                    @endif

                    @if($order->status === 'shipping')
                        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button class="admin-btn admin-btn--primary admin-btn--sm" type="submit">Hoàn thành</button>
                        </form>
                    @endif

                    <a href="{{ route('admin.orders.index') }}" class="admin-btn admin-btn--ghost admin-btn--sm">
                        Quay lại
                    </a>
                </div>
            </div>
        </div>

        {{-- Form cập nhật thông tin khách --}}

        <details class="admin-accordion">
            <summary class="admin-accordion__sum">
                <div class="admin-accordion__left">
                    <span class="admin-accordion__icon">👤</span>
                    <div>
                        <div class="admin-accordion__title">Thông tin khách hàng</div>
                        <div class="admin-accordion__sub">
                            {{ $order->fullname }} • {{ $order->phone }}
                        </div>
                    </div>
                </div>

                <div class="admin-accordion__right">
            <span class="admin-pill">
                {{ strtoupper($order->payment) }}
            </span>
                    <span class="admin-accordion__chev">▾</span>
                </div>
            </summary>

            <div class="admin-card admin-accordion__body">
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="admin-form">
                    @csrf
                    @method('PUT')

                    <div class="admin-grid-2">
                        <div class="admin-field">
                            <label class="admin-label">Họ và tên</label>
                            <div class="admin-inputwrap">
                                <span class="admin-prefix">👤</span>
                                <input class="admin-input" name="fullname"
                                       value="{{ old('fullname', $order->fullname) }}"
                                       placeholder="Nhập họ và tên">
                            </div>
                            @error('fullname')
                            <div class="admin-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="admin-field">
                            <label class="admin-label">Số điện thoại</label>
                            <div class="admin-inputwrap">
                                <span class="admin-prefix">📞</span>
                                <input class="admin-input" name="phone"
                                       value="{{ old('phone', $order->phone) }}"
                                       placeholder="Nhập số điện thoại">
                            </div>
                            @error('phone')
                            <div class="admin-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="admin-field admin-col-span">
                            <label class="admin-label">Địa chỉ nhận hàng</label>
                            <div class="admin-inputwrap">
                                <span class="admin-prefix">📍</span>
                                <input class="admin-input" name="address"
                                       value="{{ old('address', $order->address) }}"
                                       placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành">
                            </div>
                            @error('address')
                            <div class="admin-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="admin-field">
                            <label class="admin-label">Phương thức thanh toán</label>
                            <div class="admin-inputwrap">
                                <span class="admin-prefix">💳</span>
                                <select class="admin-input" name="payment">
                                    @php $pay = old('payment', $order->payment); @endphp
                                    <option value="cod" @selected($pay==='cod')>COD (Thanh toán khi nhận)</option>
                                    {{--                                    <option value="bank" @selected($pay==='bank')>Chuyển khoản</option>--}}
                                    {{--                                    <option value="momo" @selected($pay==='momo')>Momo</option>--}}
                                </select>
                            </div>
                            @error('payment')
                            <div class="admin-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="admin-field">
                            <label class="admin-label">Ghi chú</label>
                            <div class="admin-inputwrap">
                                <span class="admin-prefix">📝</span>
                                <input class="admin-input" name="note"
                                       value="{{ old('note', $order->note) }}"
                                       placeholder="Ví dụ: gọi trước khi giao, giao giờ hành chính...">
                            </div>
                            @error('note')
                            <div class="admin-error">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="admin-actions">
                        <button class="admin-btn admin-btn--primary" type="submit">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </details>


        {{-- Danh sách items --}}
        <div class="admin-card">
            <h3 style="margin:0 0 12px 0">Sản phẩm</h3>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                    <tr>

                        <th style="width:90px">ID</th>
                        <th style="width:90px">Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th style="width:90px">SL</th>
                        <th style="width:140px">Giá</th>
                        <th style="width:160px">Thành tiền</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($order->items as $item)
                        @php
                            $p = $item->product;
                            $img = $p?->image ? asset('uploads/'.$p->image) : null;
                        @endphp

                        <tr>


                            {{-- ID --}}
                            <td class="td-center">
                                {{ $item->product_id }}
                            </td>
                            {{-- Ảnh --}}
                            <td class="td-center">
                                <div class="admin-prod-img">
                                    @if($img)
                                        <img src="{{ $img }}" alt="{{ $p->name }}">
                                    @else
                                        <span class="admin-prod-img--empty">🧋</span>
                                    @endif
                                </div>
                            </td>
                            {{-- Tên --}}
                            <td>
                                <div class="admin-prod-name">
                                    {{ $p->name ?? 'Sản phẩm đã xoá' }}
                                </div>
                            </td>

                            {{-- Số lượng --}}
                            <td class="td-center">
                                {{ $item->quantity }}
                            </td>

                            {{-- Giá --}}
                            <td class="td-right">
                                {{ number_format($item->price) }} đ
                            </td>

                            {{-- Thành tiền --}}
                            <td class="td-right">
                                {{ number_format($item->price * $item->quantity) }} đ
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-total">
                <b>Tổng: {{ number_format($order->total_price) }} đ</b>
            </div>
        </div>


    </div>
@endsection
