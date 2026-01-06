<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ShopTea')</title>


    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body>

<header class="header-sticky">

    <nav class="navbar navbar--shoptea">
        <div class="container navbar__inner">

            {{-- LEFT: LOGO --}}
            <div class="navbar-left">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" class="logo-round" alt="ShopTea">
                </a>
            </div>

            {{-- Toggle mobile --}}
            <button class="nav-toggle" type="button" aria-controls="shopteaNav" aria-expanded="false">
                <span class="nav-toggle__bar"></span>
                <span class="nav-toggle__bar"></span>
                <span class="nav-toggle__bar"></span>
            </button>

            {{-- RIGHT: MENU + SEARCH + ACTIONS --}}
            <div class="navbar-collapse" id="shopteaNav">

                {{-- MENU --}}
                <ul class="navbar-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}#home">
                            Trang chủ
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#about">Giới thiệu</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#products">Sản phẩm</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#news">Tin tức</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#contact">Liên hệ</a></li>
                </ul>

                {{-- SEARCH --}}
                <div class="search-box">
                    <input type="text"
                           id="search-input"
                           class="input"
                           placeholder="🔍 Tìm trà bạn thích..."
                           autocomplete="off">
                    <ul id="search-result" class="suggest d-none"></ul>
                </div>

                {{-- ACTIONS: CART + USERNAME + AUTH BUTTONS --}}
                <div class="nav-actions">
                    <a class="cart-link" href="{{ route('cart.index') }}" aria-label="Giỏ hàng">
                        🛒
                        @if (session('cart_count'))
                            <span class="badge">{{ session('cart_count') }}</span>
                        @endif
                    </a>

                    @guest
                        <a class="btn btn-outline-success btn-sm" href="{{ route('login') }}">Đăng nhập</a>
                        <a class="btn btn-success btn-sm" href="{{ route('register') }}">Đăng ký</a>
                    @else
                        <div class="user-menu" id="userMenu">
                            <button type="button" class="user-menu__btn" id="userMenuBtn" aria-expanded="false">
                                <span class="user-menu__name">{{ auth()->user()->name }}</span>
                                <span class="user-menu__chev">▾</span>
                            </button>

                            <div class="user-menu__drop" id="userMenuDrop">
                                <a class="user-menu__item" href="{{ route('orders.my') }}">
                                    📦 Đơn hàng của tôi
                                </a>

                                <a class="user-menu__item" href="{{ route('profile') }}">
                                    👤 Thông tin cá nhân
                                </a>

                                <div class="user-menu__sep"></div>

                                <form action="{{ route('logout') }}" method="POST" class="user-menu__form">
                                    @csrf
                                    <button type="submit" class="user-menu__item user-menu__item--danger">
                                        🚪 Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest

                </div>

            </div>
        </div>
    </nav>


    {{-- admin --}}
    @auth
        @if(auth()->user()->role === 'admin')
            <div class="admin-quick-link">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-sm">
                    ⚙️ Quản trị Admin
                </a>
            </div>
        @endif
    @endauth
</header>

<main class="page-with-header">
    @yield('content')
</main>

<footer class="footer">
    <div class="container text-center">
        <div class="footer__brand">🍃 ShopTea</div>
        <p class="mb-1">Hành trình của hương trà và sự tinh tế</p>
        <p class="mb-0 footer__copy">© 2024 ShopTea. All rights reserved.</p>
    </div>
</footer>

{{-- ✅ Navbar collapse thuần (thay bootstrap bundle) --}}
<script>
    const toggleBtn = document.querySelector('.nav-toggle');
    const nav = document.getElementById('shopteaNav');

    if (toggleBtn && nav) {
        toggleBtn.addEventListener('click', () => {
            const isOpen = nav.classList.toggle('is-open');
            toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }
</script>

{{-- Smooth scroll --}}
<script>
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', (e) => {
            const id = a.getAttribute('href');
            const el = document.querySelector(id);
            if (!el) return;
            e.preventDefault();
            el.scrollIntoView({behavior: 'smooth', block: 'start'});
        });
    });
</script>

{{-- Khi bấm filter loại trà -> giữ nguyên ở #products --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(window.location.search);
        const hasCategory = params.has('category');
        if (window.location.hash === '#products' || hasCategory) {
            const el = document.querySelector('#products');
            if (el) el.scrollIntoView({behavior: 'auto', block: 'start'});
        }
    });
</script>

{{-- Search suggest --}}
<script>
    const input = document.getElementById('search-input');
    const resultBox = document.getElementById('search-result');

    input.addEventListener('input', async function () {
        const keyword = this.value.trim();

        if (keyword.length < 1) {
            resultBox.classList.add('d-none');
            resultBox.innerHTML = '';
            return;
        }

        const url = `{{ route('search.suggest') }}?keyword=${encodeURIComponent(keyword)}`;
        const res = await fetch(url);
        const data = await res.json();

        resultBox.innerHTML = '';

        if (!data || data.length === 0) {
            resultBox.classList.add('d-none');
            return;
        }

        data.forEach(item => {
            resultBox.innerHTML += `
              <li class="suggest__item">
                <a href="/products/${item.id}" class="suggest__link">
                  ${item.name}
                </a>
              </li>
            `;
        });

        resultBox.classList.remove('d-none');
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.search-box')) resultBox.classList.add('d-none');
    });
</script>
<script>
    (function () {
        const menu = document.getElementById('userMenu');
        const btn = document.getElementById('userMenuBtn');
        const drop = document.getElementById('userMenuDrop');

        if (!menu || !btn || !drop) return;

        function closeMenu() {
            menu.classList.remove('is-open');
            btn.setAttribute('aria-expanded', 'false');
        }

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = menu.classList.toggle('is-open');
            btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('#userMenu')) closeMenu();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeMenu();
        });
    })();
</script>

@stack('scripts')
</body>
</html>
