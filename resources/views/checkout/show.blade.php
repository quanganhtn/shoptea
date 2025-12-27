@extends('layouts.home')

@section('content')
    <div class="container py-3 page-offset">
        <div class="product-show">

            <div class="product-show__media">
                <img src="{{ asset('uploads/' . $product->image) }}" class="product-img" alt="{{ $product->name }}">
            </div>

            <div class="product-show__info">
                <h4 class="product-title">{{ $product->name }}</h4>
                <p class="product-price">{{ number_format($product->price) }} đ</p>

                <div class="qty">
                    <label for="quantity" class="qty__label">Số lượng:</label>
                    <div class="qty__box">
                        <button type="button" class="btn btn-outline-secondary btn-xs" id="decrease">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" class="input input--qty">
                        <button type="button" class="btn btn-outline-secondary btn-xs" id="increase">+</button>
                    </div>
                </div>

                <div class="product-actions">
                    <form action="{{ route('cart.add') }}" method="POST" class="actions-row">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="quantityHidden" value="1">

                        <button type="submit" class="btn btn-warm">
                            🛒 Thêm vào giỏ
                        </button>

                        <button type="submit" name="buy_now" value="1" class="btn btn-success">
                            Mua ngay
                        </button>
                    </form>
                </div>

                <div class="product-back">
                    <a href="{{ route('home') }}#products" class="btn btn-secondary btn-sm">← Quay lại</a>
                </div>
            </div>

            <div class="product-desc">
                <h1>Mô tả sản phẩm:</h1>
                <p class="product-description">{{ $product->description }}</p>
            </div>

        </div>

        <script>
            const qtyInput = document.getElementById('quantity');
            const qtyHidden = document.getElementById('quantityHidden');

            document.getElementById('decrease').addEventListener('click', () => {
                let qty = parseInt(qtyInput.value);
                if (qty > 1) {
                    qtyInput.value = qty - 1;
                    qtyHidden.value = qtyInput.value;
                }
            });

            document.getElementById('increase').addEventListener('click', () => {
                qtyInput.value = parseInt(qtyInput.value) + 1;
                qtyHidden.value = qtyInput.value;
            });

            qtyInput.addEventListener('input', () => {
                const v = Math.max(1, parseInt(qtyInput.value || '1', 10));
                qtyInput.value = v;
                qtyHidden.value = v;
            });
        </script>
    </div>
@endsection
