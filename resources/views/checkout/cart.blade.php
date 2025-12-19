@extends('layouts.home')

@section('title', 'Giỏ hàng - ShopTea')

@section('content')
    <div class="container cart-page">
        <h3 class="mb-3">Giỏ hàng của bạn</h3>

        @if (!empty($cart))
            <div class="table-responsive">
                <table class="table table-bordered align-middle mt-2 cart-table">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th style="width:48px">
                                <input type="checkbox" id="checkAll">
                            </th>
                            <th style="width:90px">Hình ảnh</th>
                            <th>Sản phẩm</th>
                            <th style="width:150px">Số lượng</th>
                            <th style="width:120px">Giá</th>
                            <th style="width:140px">Thành tiền</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($cart as $id => $item)
                            <tr class="text-center cart-row">
                                {{-- Checkbox --}}
                                <td>
                                    <input type="checkbox" class="item-check" data-price="{{ $item['price'] }}"
                                        aria-label="Chọn sản phẩm">
                                </td>

                                {{-- Image --}}
                                <td>
                                    <img src="{{ asset('uploads/' . $item['image']) }}" class="cart-img"
                                        alt="{{ $item['name'] }}">
                                </td>

                                {{-- Name --}}
                                <td class="text-start">{{ $item['name'] }}</td>

                                {{-- Quantity --}}
                                <td>
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <form action="{{ route('cart.update') }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $id }}">
                                            <button name="action" value="decrease"
                                                class="btn btn-sm btn-outline-secondary">
                                                −
                                            </button>
                                        </form>

                                        <span class="px-2 fw-bold item-qty">{{ $item['quantity'] }}</span>

                                        <form action="{{ route('cart.update') }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $id }}">
                                            <button name="action" value="increase"
                                                class="btn btn-sm btn-outline-secondary">
                                                +
                                            </button>
                                        </form>
                                    </div>
                                </td>

                                {{-- Price --}}
                                <td class="text-center">
                                    <span class="cart-price">{{ number_format($item['price']) }} đ</span>
                                </td>

                                {{-- Total --}}
                                <td class="text-center fw-bold text-danger">
                                    {{ number_format($item['price'] * $item['quantity']) }} đ
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Thanh tổng + mua hàng (dính dưới) --}}
            <div class="cart-summary">
                <div class="cart-summary__left">
                    <span class="cart-summary__label">
                        Tổng cộng (<span id="totalQty">0</span> sản phẩm):
                    </span>
                    <span class="cart-summary__price" id="totalPrice">0 đ</span>
                </div>

                <a href="{{ route('checkout') }}" class="btn btn-success cart-summary__btn" id="buyBtn"
                    aria-disabled="true">
                    Mua hàng
                </a>
            </div>
        @else
            <p>Giỏ hàng trống!</p>
            <a href="{{ route('home') }}" class="btn btn-secondary">Quay lại mua hàng</a>
        @endif
    </div>

    <script>
        const totalPriceEl = document.getElementById('totalPrice');
        const totalQtyEl = document.getElementById('totalQty');
        const checkAll = document.getElementById('checkAll');
        const items = document.querySelectorAll('.item-check');
        const buyBtn = document.getElementById('buyBtn');

        function formatVND(n) {
            return n.toLocaleString('vi-VN') + ' đ';
        }

        function updateTotal() {
            let total = 0;
            let qtySum = 0;

            items.forEach(cb => {
                if (cb.checked) {
                    const row = cb.closest('tr');
                    const price = parseInt(cb.dataset.price || '0', 10);
                    const qty = parseInt(row.querySelector('.item-qty')?.textContent || '0', 10);

                    qtySum += qty;
                    total += price * qty;
                }
            });

            totalQtyEl.textContent = qtySum;
            totalPriceEl.textContent = formatVND(total);

            // bật/tắt nút mua hàng
            if (qtySum > 0) {
                buyBtn.classList.remove('disabled');
                buyBtn.setAttribute('aria-disabled', 'false');
            } else {
                buyBtn.classList.add('disabled');
                buyBtn.setAttribute('aria-disabled', 'true');
            }
        }

        // Tick từng item
        items.forEach(cb => cb.addEventListener('change', () => {
            // cập nhật trạng thái checkAll
            const checkedCount = [...items].filter(i => i.checked).length;
            checkAll.checked = checkedCount === items.length;
            updateTotal();
        }));

        // Check all
        if (checkAll) {
            checkAll.addEventListener('change', function() {
                items.forEach(cb => cb.checked = this.checked);
                updateTotal();
            });
        }

        // Init
        updateTotal();
    </script>
@endsection
