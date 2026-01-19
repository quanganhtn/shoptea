@extends('User.layouts.home')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/product-show.css') }}">
@endpush

@section('content')
    <div class="container py-3 page-offset">
        <div class="product-show">

            <div class="product-show__media">
                <img src="{{ asset('uploads/' . $product->image) }}" class="product-img" alt="{{ $product->name }}">
            </div>

            <div class="product-show__info">
                <h2 class="product-title">{{ $product->name }}</h2>
                <p class="product-price">{{ number_format($product->price) }} đ</p>

                {{-- ✅ Chỉ hiện đã bán --}}
                <div class="product-meta">
                    <span class="meta-item">Đã bán: <b>{{ number_format($soldCount ?? 0) }}</b></span>
                </div>

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

                        <button type="submit" class="btn btn-warm">🛒 Thêm vào giỏ</button>
                        <button type="submit" name="buy_now" value="1" class="btn btn-success">Mua ngay</button>
                    </form>
                </div>

                <div class="product-back">
                    <a href="{{ route('user') }}#products" class="btn btn-secondary btn-sm">← Quay lại</a>
                </div>
            </div>

            {{-- TAB BUTTONS --}}
            <div class="pd-tabs">
                <button type="button" class="pd-tab is-active" data-tab="detail">Chi tiết sản phẩm</button>
                <button type="button" class="pd-tab" data-tab="review">Đánh giá</button>
            </div>

            {{-- TAB CONTENT --}}
            <div class="pd-panels">

                {{-- CHI TIẾT --}}
                <div class="pd-panel is-active" id="panel-detail">
                    <h3 class="pd-title">Mô tả sản phẩm</h3>

                    <div class="pd-desc" id="descBox">
                        <p class="product-description is-clamp" id="descText">
                            {{ $product->description }}
                        </p>

                        <button type="button" class="btn-desc-more" id="descToggle" style="display:none;">
                            Xem thêm
                        </button>
                    </div>
                </div>

                {{-- ĐÁNH GIÁ --}}
                <div class="pd-panel" id="panel-review">
                    <div class="review-card">
                        <div class="review-head">
                            <div class="review-title">Đánh giá sản phẩm</div>
                            <div class="review-sub">Chạm vào sao để chọn mức đánh giá</div>
                        </div>

                        <form action="{{ route('review.store', $product) }}" method="POST" class="review-form"
                              id="reviewForm">
                            @csrf
                            <input type="hidden" name="rating" id="ratingValue" value="0">

                            <div class="tt-stars" aria-label="Chọn số sao">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" class="tt-star" data-value="{{ $i }}"
                                            aria-label="{{ $i }} sao">
                                        <svg viewBox="0 0 24 24" class="tt-star__icon" aria-hidden="true">
                                            <path
                                                d="M12 17.27l-5.18 3.04 1.39-5.97L3.5 9.9l6.06-.52L12 3.8l2.44 5.58 6.06.52-4.71 4.44 1.39 5.97z"/>
                                        </svg>
                                    </button>
                                @endfor

                                <span class="tt-stars__label" id="ratingLabel">Chưa chọn</span>
                            </div>

                            <textarea name="content" class="tt-input" rows="4"
                                      placeholder="Chia sẻ suy nghĩ của bạn ..."></textarea>

                            <div class="tt-actions">
                                <button type="submit" class="btn btn-dark tt-btn">Gửi đánh giá</button>
                            </div>
                        </form>

                        <hr class="rv-divider">

                        <div class="rv-head">
                            <div class="rv-title">Đánh giá gần đây</div>
                            <div class="rv-meta">
                                {{ $product->reviews->count() }} đánh giá •
                                {{ number_format($product->reviews->avg('rating') ?? 0, 1) }}/5
                            </div>
                        </div>

                        <div class="rv-list">
                            @forelse($product->reviews->sortByDesc('created_at') as $rv)
                                <div class="rv-item">
                                    <div class="rv-top">
                                        <div class="rv-anon">
                                            <span class="rv-anon-icon">👤</span>
                                            <span class="rv-anon-text">Người mua ẩn danh</span>
                                        </div>

                                        <div class="rv-stars">
                                            @for($i=1;$i<=5;$i++)
                                                <span class="rv-star {{ $i <= $rv->rating ? 'is-on' : '' }}">★</span>
                                            @endfor
                                        </div>
                                    </div>

                                    <div class="rv-content">
                                        {{ $rv->content ?? '—' }}
                                    </div>

                                    <div class="rv-time">
                                        {{ $rv->created_at?->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            @empty
                                <div class="rv-emptybox">Chưa có đánh giá nào.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div> {{-- end .pd-panels --}}
        </div>
    </div>

    {{-- ✅ GỢI Ý FULL WIDTH (ngoài container để trải màn hình) --}}
    @if(!empty($suggestProducts) && $suggestProducts->count())
        <div class="pd-suggest-full">
            <div class="container">
                <h3 class="pd-title">Gợi ý cho bạn</h3>

                <div class="suggest-grid-full">
                    @foreach($suggestProducts as $sp)
                        <a href="{{ route('products.show', $sp->id) }}" class="suggest-item">
                            <img src="{{ asset('uploads/' . $sp->image) }}" alt="{{ $sp->name }}">
                            <div class="suggest-name">{{ $sp->name }}</div>
                            @php
                                $price   = (int) $product->price;
                                $percent = (int) ($product->discount_percent ?? 0);
                                $final   = (int) ($product->final_price ?? $price);
                            @endphp

                            <div class="price-box">
                                @if($percent > 0 && $final < $price)
                                    <div class="price-box__row">
                                        <span class="price-box__sale">{{ number_format($final) }} đ</span>
                                        <span class="price-box__badge">-{{ $percent }}%</span>
                                    </div>
                                    <div class="price-box__old">{{ number_format($price) }} đ</div>
                                @else
                                    <div class="price-box__row">
                                        <span class="price-box__sale">{{ number_format($price) }} đ</span>
                                    </div>
                                @endif
                            </div>

                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif


    <script>
        // ===== QTY =====
        const qtyInput = document.getElementById('quantity');
        const qtyHidden = document.getElementById('quantityHidden');
        const btnDecrease = document.getElementById('decrease');
        const btnIncrease = document.getElementById('increase');

        if (qtyInput && qtyHidden && btnDecrease && btnIncrease) {
            btnDecrease.addEventListener('click', () => {
                const qty = Number(qtyInput.value) || 1;
                const next = Math.max(1, qty - 1);
                qtyInput.value = next;
                qtyHidden.value = next;
            });

            btnIncrease.addEventListener('click', () => {
                const qty = Number(qtyInput.value) || 1;
                const next = qty + 1;
                qtyInput.value = next;
                qtyHidden.value = next;
            });

            qtyInput.addEventListener('input', () => {
                const next = Math.max(1, Number(qtyInput.value) || 1);
                qtyInput.value = next;
                qtyHidden.value = next;
            });
        }

        // ===== TAB: detail / review =====
        const tabButtons = document.querySelectorAll('.pd-tab');
        const panelDetail = document.getElementById('panel-detail');
        const panelReview = document.getElementById('panel-review');

        if (tabButtons.length && panelDetail && panelReview) {
            tabButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    tabButtons.forEach(b => b.classList.remove('is-active'));
                    btn.classList.add('is-active');

                    const tab = btn.dataset.tab;
                    panelDetail.classList.toggle('is-active', tab === 'detail');
                    panelReview.classList.toggle('is-active', tab === 'review');
                });
            });
        }

        // ===== STAR RATING =====
        const starButtons = document.querySelectorAll('.tt-star');
        const ratingValue = document.getElementById('ratingValue');
        const ratingLabel = document.getElementById('ratingLabel');
        const reviewForm = document.getElementById('reviewForm');

        const ratingTextMap = {1: 'Rất tệ', 2: 'Tệ', 3: 'Ổn', 4: 'Tốt', 5: 'Xuất sắc'};

        function paintStars(val) {
            starButtons.forEach(btn => {
                const v = Number(btn.dataset.value);
                btn.classList.toggle('is-on', v <= val);
            });
        }

        function renderSelected(val) {
            paintStars(val);
            if (ratingLabel) ratingLabel.textContent = val > 0 ? ratingTextMap[val] : 'Chưa chọn';
        }

        function getSelected() {
            return ratingValue ? (Number(ratingValue.value) || 0) : 0;
        }

        if (starButtons.length && ratingValue) {
            starButtons.forEach(btn => {
                const v = Number(btn.dataset.value);

                btn.addEventListener('click', () => {
                    ratingValue.value = v;
                    renderSelected(v);
                });

                btn.addEventListener('mouseenter', () => paintStars(v));
                btn.addEventListener('mouseleave', () => paintStars(getSelected()));
            });

            renderSelected(getSelected());
        }

        if (reviewForm && ratingValue) {
            reviewForm.addEventListener('submit', (e) => {
                if ((Number(ratingValue.value) || 0) <= 0) {
                    e.preventDefault();
                    alert('Bạn chọn số sao trước nhé!');
                }
            });
        }

        // ===== DESC: CLAMP + TOGGLE =====
        const descText = document.getElementById('descText');
        const descToggle = document.getElementById('descToggle');

        function isClamped(el) {
            return el && (el.scrollHeight > el.clientHeight + 1);
        }

        function initDescToggle() {
            if (!descText || !descToggle) return;

            descText.classList.add('is-clamp');

            requestAnimationFrame(() => {
                descToggle.style.display = isClamped(descText) ? 'inline-block' : 'none';
            });

            descToggle.addEventListener('click', () => {
                const isClampNow = descText.classList.contains('is-clamp');
                descText.classList.toggle('is-clamp', !isClampNow);
                descToggle.textContent = isClampNow ? 'Thu gọn' : 'Xem thêm';
            });
        }

        initDescToggle();
    </script>
@endsection
