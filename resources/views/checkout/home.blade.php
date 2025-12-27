@extends('layouts.home')

@section('title', 'ShopTea - Hành trình của hương trà')

@section('content')

    {{-- HERO --}}
    <section id="home" class="hero"
             style="--hero-image: url('{{ asset($homepage['hero']['image'] ?? 'images/nentra.jpg') }}');">
        <div class="container hero__inner">
            <div class="hero__content">
                <h1 class="hero__title">{{ $homepage['hero']['title'] ?? 'Thưởng trọn vị trà' }}</h1>
                <p class="hero__subtitle">{{ $homepage['hero']['subtitle'] ?? 'Sống trọn khoảnh khắc' }}</p>

                <div class="hero__actions">
                    <a href="#products" class="btn btn-light">Khám phá sản phẩm</a>
                    <a href="#about" class="btn btn-outline-light">Giới thiệu</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ABOUT --}}
    <section id="about" class="section section--full">
        <div class="container">
            <div class="split">
                <div class="split__image">
                    <img src="{{ asset($homepage['about']['image'] ?? 'images/traxanh.jpg') }}" alt="Về ShopTea">
                </div>

                <div class="split__content">
                    <h2 class="section__title">{{ $homepage['about']['title'] ?? 'Về ShopTea' }}</h2>

                    <p class="section__desc">
                        {{ $homepage['about']['desc'] ?? 'Khám phá thế giới trà thơm ngon với giao diện hiện đại và trải nghiệm mua sắm dễ dàng.' }}
                    </p>

                    <div class="card-soft">
                        <p class="mb-0">
                            {{ $homepage['about']['text'] ?? 'ShopTea được xây dựng nhằm cung cấp một hệ thống bán trà trực tuyến...' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PRODUCTS --}}
    <section id="products" class="section section--muted section--full products-section">
        <div class="container">

            <div class="section__head products-head">
                <h2 class="section__title mb-1">Danh mục sản phẩm</h2>
                <p class="section__desc mb-0">Chọn loại trà để xem sản phẩm theo danh mục.</p>

                @php
                    $categories = ['Trà xanh','Trà đen','Trà ô long','Trà thảo mộc','Trà trái cây','Trà sữa','Trà đặc sản'];
                    $currentCategory = request('category');
                @endphp

                <div class="category-chips mt-3">
                    <a href="{{ route('home') }}#products" class="chip {{ empty($currentCategory) ? 'active' : '' }}">
                        Tất cả
                    </a>

                    @foreach ($categories as $cat)
                        <a href="{{ route('home', ['category' => $cat]) }}#products"
                           class="chip {{ $currentCategory == $cat ? 'active' : '' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="product-slider-wrapper">
                <button type="button" class="slider-btn slider-btn--left" onclick="scrollProducts(-1)">‹</button>

                <div class="product-slider" id="productSlider">
                    @forelse ($products as $product)
                        <a href="{{ route('products.show', $product->id) }}" class="product-slide-item">
                            <div class="product-card2">
                                <div class="product-card2__media">
                                    <img src="{{ asset('uploads/' . $product->image) }}" class="product-card2__img"
                                         alt="{{ $product->name }}" loading="lazy">
                                </div>

                                <div class="product-card2__body">
                                    <div class="product-card2__name">
                                        {{ strlen($product->name) > 45 ? substr($product->name, 0, 45) . '...' : $product->name }}
                                    </div>

                                    <div class="product-card2__bottom">
                                        <div class="product-card2__price">{{ number_format($product->price) }} đ</div>
                                        <div class="product-card2__badge">Xem</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="alert alert-warning w-100">Không có sản phẩm trong danh mục này.</div>
                    @endforelse
                </div>

                <button type="button" class="slider-btn slider-btn--right" onclick="scrollProducts(1)">›</button>
            </div>

        </div>
    </section>

    {{-- NEWS --}}
    <section id="news" class="section">
        <div class="container">
            <div class="section__head text-center">
                <h2 class="section__title">{{ $homepage['news']['title'] ?? 'Tin tức & Kiến thức' }}</h2>
                <p class="section__desc">{{ $homepage['news']['desc'] ?? 'Mẹo pha trà – lợi ích sức khỏe – văn hóa thưởng trà.' }}</p>
            </div>
        </div>
    </section>

    
    {{-- CONTACT --}}
    <section id="contact" class="section section--muted">
        <div class="container">
            <div class="section__head text-center">
                <h2 class="section__title">Liên hệ với chúng tôi</h2>
                <p class="section__desc">ShopTea luôn sẵn sàng hỗ trợ bạn.</p>
            </div>

            {{-- ✅ 3 cột ngang đúng --}}
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-card__icon">📞</div>
                    <div class="contact-card__title">Điện thoại</div>
                    <div class="contact-card__value">{{ $homepage['contact']['phone'] ?? '0399869844' }}</div>
                </div>

                <div class="contact-card">
                    <div class="contact-card__icon">✉️</div>
                    <div class="contact-card__title">Email</div>
                    <div class="contact-card__value">
                        {{ $homepage['contact']['email'] ?? 'phamquang4869@gmail.com' }}
                    </div>
                </div>

                <div class="contact-card">
                    <div class="contact-card__icon">📍</div>
                    <div class="contact-card__title">Địa chỉ</div>
                    <div class="contact-card__value">
                        {{ $homepage['contact']['address'] ?? 'số 1,tổ 1, phường Phan Đình Phùng,Tỉnh Thái Nguyên' }}
                    </div>
                </div>
            </div>
        </div>
    </section>


    @push('scripts')
        <script>
            function scrollProducts(direction) {
                const slider = document.getElementById('productSlider');
                if (!slider) return;
                const amount = slider.clientWidth * 0.9;
                slider.scrollBy({left: direction * amount, behavior: 'smooth'});
            }
        </script>
    @endpush

@endsection
