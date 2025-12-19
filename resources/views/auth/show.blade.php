@extends('layouts.home')

@section('content')
    <div class="container py-3 page-offset">
        <div class="row align-items-start">
            <!-- Ảnh sản phẩm -->
            <div class="col-md-3 text-center mb-3 mb-md-0 pt-4">
                <img src="{{ asset('uploads/' . $product->image) }}" class="img-fluid rounded shadow-sm">
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-md-9 d-flex flex-column">
                <h4 class="mb-2">{{ $product->name }}</h4>
                <p class="text-success fw-bold fs-4 mb-3">{{ number_format($product->price) }} đ</p>

                <!-- Số lượng -->
                <div class="d-flex align-items-center mb-3" style="gap:10px;">
                    <label for="quantity" class="fw-bold mb-0">Số lượng:</label>
                    <div class="input-group" style="width: 100px;">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="decrease">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1"
                            class="form-control text-center form-control-sm">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="increase">+</button>
                    </div>
                </div>

                <!-- Nút Thêm vào giỏ hàng & Mua ngay -->
                <div class="mb-3">
                    <form action="{{ route('cart.add') }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="quantityHidden" value="1">

                        <!-- Thêm vào giỏ -->
                        <button type="submit" class="btn px-3 py-2 rounded shadow-sm"
                            style="background: linear-gradient(45deg, #ff9800, #ffc107); color:white;">
                            🛒 Thêm vào giỏ
                        </button>

                        <!-- Mua ngay -->
                        <button type="submit" name="buy_now" value="1"
                            class="btn btn-success px-3 py-2 rounded shadow-sm">
                            Mua ngay
                        </button>
                    </form>
                </div>


                <!-- Nút Quay lại -->
                <div class="mt-auto">
                    <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">
                        ← Quay lại
                    </a>
                </div>
            </div>

            <!-- Mô tả sản phẩm kéo dài toàn màn hình -->
            <div class="row mt-3">
                <div class="col-12">
                    <h6>Mô tả sản phẩm:</h6>
                    <p class="product-description" style="white-space: pre-line; margin:0.2rem 0; line-height:1.4;">
                        {{ $product->description }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Script tăng giảm số lượng -->
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
        </script>

    </div>
@endsection
