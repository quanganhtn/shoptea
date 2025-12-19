@extends('layouts.home')

@section('title', 'ShopTea - Hành trình của hương trà')

@section('content')

    {{-- HERO --}}
    <section id="home" class="hero" style="--hero-image: url('{{ asset('images/nentra.jpg') }}');">
        <div class="container hero__inner">
            <div class="hero__content">
                <h1 class="hero__title" id="banner-title">Thưởng trọn vị trà</h1>
                <p class="hero__subtitle" id="banner-subtitle">Sống trọn khoảnh khắc</p>

                <div class="hero__actions">
                    <a href="#products" class="btn btn-light px-4">Khám phá sản phẩm</a>
                    <a href="#about" class="btn btn-outline-light px-4">Giới thiệu</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ABOUT --}}
    <section id="about" class="section section--full">
        <div class="container">
            <div class="split">
                <div class="split__image">
                    <img src="{{ asset('images/traxanh.jpg') }}" alt="Về ShopTea">
                </div>

                <div class="split__content">
                    <h2 class="section__title" id="about-title">Về ShopTea</h2>
                    <p class="section__desc">Từ vùng trà nổi tiếng đến tách trà tinh tế mỗi ngày.</p>

                    <div class="card-soft">
                        <p class="mb-0" id="about-text">
                            ShopTea được xây dựng từ niềm đam mê với văn hóa trà Việt, mong muốn đưa những lá trà thuần
                            khiết
                            từ các vùng trồng nổi tiếng đến gần hơn với người tiêu dùng. Chúng tôi cam kết chọn lọc kỹ lưỡng
                            nguyên liệu, quy trình chế biến an toàn và mang lại sản phẩm chất lượng cao, không pha tạp,
                            đặt giá trị trải nghiệm lên hàng đầu.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- DANH MỤC SẢN PHẨM (2 HÀNG + CUỘN NGANG + MŨI TÊN) --}}
    <section id="products" class="section section--muted section--full products-section">
        <div class="container">

            <div class="section__head products-head">
                <h2 class="section__title mb-1">Danh mục sản phẩm</h2>
                <p class="section__desc mb-0">Chọn loại trà để xem sản phẩm theo danh mục.</p>

                @php
                    $categories = [
                        'Trà xanh',
                        'Trà đen',
                        'Trà ô long',
                        'Trà thảo mộc',
                        'Trà trái cây',
                        'Trà sữa',
                        'Trà đặc sản',
                    ];
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

            {{-- SLIDER NGANG --}}
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
                        <div class="alert alert-warning mb-0 w-100">Không có sản phẩm trong danh mục này.</div>
                    @endforelse
                </div>

                <button type="button" class="slider-btn slider-btn--right" onclick="scrollProducts(1)">›</button>
            </div>

        </div>
    </section>

    {{-- NEWS (GIỮ NGUYÊN) --}}
    <section id="news" class="section">
        <div class="container">
            <div class="section__head text-center">
                <h2 class="section__title">Tin tức &amp; Kiến thức</h2>
                <p class="section__desc">Mẹo pha trà – lợi ích sức khỏe – văn hóa thưởng trà.</p>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="news-card2">
                        {{-- <div class="news-card2__media">📰</div> --}}

                        <img src="{{ asset('images/Cach-pha-tra-xanh-dung-dieu-1.jpg') }}"
                            alt="Cach pha tra xanh dung dieu" class="news-card2__img">
                        <div class="news-card2__body">
                            <h5 class="news-card2__title">Cách pha trà xanh đúng chuẩn</h5>
                            <p class="news-card2__text">Khám phá bí quyết pha một ấm trà xanh thơm ngon, giữ trọn hương vị
                                và dưỡng chất...</p>
                            <a href="https://www.foodmap.asia/tin-tuc/cach-pha-tra-xanh-dung-dieu"
                                class="btn btn-outline-success btn-sm">Đọc thêm</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="news-card2">
                        <img src="{{ asset('images/nhung-loi-ich-tuyet-voi-cua-tra-doi-voi-co-the-2.jpg') }}"
                            alt="Những lợi ích của trà" class="news-card2__img">
                        <div class="news-card2__body">
                            <h5 class="news-card2__title">Lợi ích của việc uống trà mỗi ngày</h5>
                            <p class="news-card2__text">Trà không chỉ là thức uống giải khát mà còn mang lại nhiều lợi ích
                                cho sức khỏe...</p>
                            <a href="https://hellobacsi.com/an-uong-lanh-manh/thong-tin-dinh-duong/loi-ich-cua-tra/"
                                class="btn btn-outline-success btn-sm">Đọc thêm</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="news-card2">
                        <img src="{{ asset('images/pha-tra-min.jpg') }}" alt="Nghệ thuật thưởng trà"
                            class="news-card2__img">
                        <div class="news-card2__body">
                            <h5 class="news-card2__title">Nghệ thuật thưởng trà của người Việt</h5>
                            <p class="news-card2__text">Tìm hiểu về văn hóa trà đạo và cách thưởng thức trà theo phong cách
                                truyền thống...</p>
                            <a href="https://bantradienthongminh.vn/nghe-thuat-thuong-tra-dung/"
                                class="btn btn-outline-success btn-sm">Đọc thêm</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTACT (GIỮ NGUYÊN) --}}
    <section id="contact" class="section section--muted">
        <div class="container">
            <div class="section__head text-center">
                <h2 class="section__title">Liên hệ với chúng tôi</h2>
                <p class="section__desc">ShopTea luôn sẵn sàng hỗ trợ bạn.</p>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-12 col-md-4">
                    <div class="contact-card">
                        <div class="contact-card__icon">📞</div>
                        <div class="contact-card__title">Điện thoại</div>
                        <div class="contact-card__value" id="contact-phone">0399869844</div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="contact-card">
                        <div class="contact-card__icon">✉️</div>
                        <div class="contact-card__title">Email</div>
                        <div class="contact-card__value" id="contact-email">phamquang4869@gmail.com</div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="contact-card">
                        <div class="contact-card__icon">📍</div>
                        <div class="contact-card__title">Địa chỉ</div>
                        <div class="contact-card__value" id="contact-address">Số 1,Phường Phan Đình Phùng, Tỉnh Thái
                            Nguyên</div>
                    </div>
                </div>
            </div>

            <div class="contact-form2 mt-4">
                <h4 class="text-center fw-bold mb-3">Gửi tin nhắn</h4>

                <form id="contactForm">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Họ và tên *</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Email *</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Số điện thoại</label>
                            <input type="tel" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Nội dung *</label>
                            <textarea class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-success w-100">Gửi tin nhắn</button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="contactToast" class="toast-mini">✓ Tin nhắn đã được gửi thành công!</div>
        </div>
    </section>

    @push('scripts')
        <script>
            // Slider scroll theo nút trái/phải
            function scrollProducts(direction) {
                const slider = document.getElementById('productSlider');
                if (!slider) return;
                const amount = slider.clientWidth * 0.9;
                slider.scrollBy({
                    left: direction * amount,
                    behavior: 'smooth'
                });
            }

            // Toast form liên hệ
            const form = document.getElementById('contactForm');
            const toast = document.getElementById('contactToast');

            if (form && toast) {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    form.reset();
                    toast.classList.add('show');
                    setTimeout(() => toast.classList.remove('show'), 2500);
                });
            }
        </script>
    @endpush

@endsection
