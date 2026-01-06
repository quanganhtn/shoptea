@extends('User.layouts.home')

@section('title', 'Giỏ hàng - ShopTea')

@section('content')
    <div class="container cart-page">
        <h3 class="mb-3">Giỏ hàng của bạn</h3>

        @if (!empty($cart))
            <div class="table-wrap">
                <table class="table cart-table">
                    <thead>
                    <tr>
                        <th style="width:48px">
                            <input type="checkbox" id="checkAll">
                        </th>
                        <th style="width:90px">Hình ảnh</th>
                        <th>Sản phẩm</th>
                        <th style="width:120px">Giá</th>
                        <th style="width:150px">Số lượng</th>
                        <th style="width:140px">Tạm tính</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($cart as $id => $item)
                        <tr class="cart-row">
                            <td class="td-center">
                                <input type="checkbox"
                                       class="item-check"
                                       data-id="{{ $id }}"
                                       data-price="{{ $item['price'] }}"
                                       aria-label="Chọn sản phẩm">
                            </td>

                            <td class="td-center">
                                <img src="{{ asset('uploads/' . $item['image']) }}" class="cart-img"
                                     alt="{{ $item['name'] }}">
                            </td>

                            <td class="td-left">{{ $item['name'] }}</td>

                            <td class="td-center">
                                <span class="cart-price">{{ number_format($item['price']) }} đ</span>
                            </td>

                            <td class="td-center">
                                <div class="qty-inline">
                                    <form action="{{ route('cart.update') }}" method="POST" class="inline-form">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <button name="action" value="decrease" class="btn btn-outline-secondary btn-xs">
                                            −
                                        </button>
                                    </form>

                                    <span class="item-qty">{{ $item['quantity'] }}</span>

                                    <form action="{{ route('cart.update') }}" method="POST" class="inline-form">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <button name="action" value="increase" class="btn btn-outline-secondary btn-xs">
                                            +
                                        </button>
                                    </form>
                                </div>
                            </td>

                            <td class="td-center total-red">
                                {{ number_format($item['price'] * $item['quantity']) }} đ
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- FORM xóa nhiều --}}
            <form id="deleteSelectedForm" action="{{ route('cart.deleteSelected') }}" method="POST" class="d-none">
                @csrf
                <input type="hidden" name="ids" id="deleteIds">
            </form>

            <div class="cart-summary">

                <div class="cart-summary__actions">
                    <button type="button" class="btn btn-outline-primary" id="btnSelectAll">
                        Chọn tất cả
                    </button>

                    <button type="button" class="btn btn-outline-danger disabled" id="btnDeleteSelected"
                            aria-disabled="true">
                        Xóa đơn hàng
                    </button>
                </div>

                <div class="cart-summary__right">
                    <div class="cart-summary__text">
                        <span class="cart-summary__label">
                            Tổng cộng (<span id="totalQty">0</span> sản phẩm):
                        </span>
                        <span class="cart-summary__price" id="totalPrice">0 đ</span>
                    </div>

                    <form id="checkoutForm" action="{{ route('checkout') }}" method="GET" class="d-inline">
                        <input type="hidden" name="ids" id="checkoutIds">
                        <button type="submit" class="btn btn-success" id="buyBtn" disabled>
                            Thanh toán
                        </button>

                    </form>

                </div>

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

        const btnSelectAll = document.getElementById('btnSelectAll');
        const btnDeleteSelected = document.getElementById('btnDeleteSelected');
        const deleteSelectedForm = document.getElementById('deleteSelectedForm');
        const deleteIdsInput = document.getElementById('deleteIds');

        function formatVND(n) {
            return n.toLocaleString('vi-VN') + ' đ';
        }

        function getCheckedItems() {
            return [...items].filter(i => i.checked);
        }

        function setBtnState(btn, enabled) {
            btn.disabled = !enabled;
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

            setBtnState(buyBtn, qtySum > 0);
            setBtnState(btnDeleteSelected, getCheckedItems().length > 0);
        }

        items.forEach(cb => cb.addEventListener('change', () => {
            const checkedCount = getCheckedItems().length;
            checkAll.checked = checkedCount === items.length && items.length > 0;
            updateTotal();
        }));

        if (checkAll) {
            checkAll.addEventListener('change', function () {
                items.forEach(cb => cb.checked = this.checked);
                updateTotal();
            });
        }

        if (btnSelectAll) {
            btnSelectAll.addEventListener('click', () => {
                const allChecked = getCheckedItems().length === items.length && items.length > 0;
                const next = !allChecked;
                items.forEach(cb => cb.checked = next);
                checkAll.checked = next;
                updateTotal();
            });
        }

        if (btnDeleteSelected) {
            btnDeleteSelected.addEventListener('click', () => {
                const checked = getCheckedItems();
                if (checked.length === 0) return;

                const ids = checked.map(i => i.dataset.id);
                deleteIdsInput.value = ids.join(',');
                deleteSelectedForm.submit();
            });
        }

        updateTotal();
    </script>
    <script>
        const checkoutForm = document.getElementById('checkoutForm');
        const checkoutIdsInput = document.getElementById('checkoutIds');

        checkoutForm.addEventListener('submit', function (e) {
            const checked = getCheckedItems();
            if (checked.length === 0) {
                e.preventDefault();
                return;
            }
            const ids = checked.map(i => i.dataset.id);
            checkoutIdsInput.value = ids.join(',');
        });
    </script>

@endsection
