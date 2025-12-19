<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ShopTea')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body class="bg-light">

    {{-- HEADER STICKY (chỉ chứa Topbar + Navbar) --}}
    <header class="header-sticky">


        {{-- NAVBAR --}}
        <nav class="navbar navbar-expand-lg bg-white shadow-sm navbar--shoptea">
            <div class="container align-items-center">

                {{-- LOGO --}}
                <a class="navbar-brand p-0 me-3" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" class="logo-round" alt="ShopTea">
                </a>

                {{-- Toggle mobile --}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#shopteaNav"
                    aria-controls="shopteaNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="shopteaNav">

                    {{-- MENU GIỮA --}}
                    <ul class="navbar-nav mx-auto navbar-menu">
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="nav-item"><a class="nav-link" href="#products">Sản phẩm</a></li>
                        <li class="nav-item"><a class="nav-link" href="#news">Tin tức</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Liên hệ</a></li>
                    </ul>

                    {{-- SEARCH --}}
                    <form action="{{ route('home') }}" method="GET" class="search-box ms-auto me-3 my-2 my-lg-0">
                        <input type="text" name="keyword" class="form-control" placeholder="🔍 Tìm trà bạn thích..."
                            value="{{ request('keyword') }}">
                    </form>

                    {{-- CART + AUTH --}}
                    <div class="d-flex align-items-center gap-3">
                        <a class="nav-link position-relative fs-4" href="{{ route('cart.index') }}"
                            aria-label="Giỏ hàng">
                            🛒
                            @if (session('cart_count'))
                                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                    {{ session('cart_count') }}
                                </span>
                            @endif
                        </a>

                        @guest
                            <a class="btn btn-outline-success btn-sm" href="{{ route('login') }}">Đăng nhập</a>
                            <a class="btn btn-success btn-sm" href="{{ route('register') }}">Đăng ký</a>
                        @else
                            <span class="fw-semibold d-none d-md-inline">{{ auth()->user()->name }}</span>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button class="btn btn-outline-danger btn-sm">Đăng xuất</button>
                            </form>
                        @endguest
                    </div>

                </div>
            </div>
        </nav>
    </header>

    {{-- ADMIN BUTTONS (nằm ngoài header sticky) --}}
    @auth
        @if (auth()->user()->role === 'admin')
            <div class="admin-fab">
                <a href="{{ route('admin.products.create') }}" class="btn btn-success btn-sm">
                    ➕ Thêm SP
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-sm">
                    ✏️ Quản lý
                </a>
            </div>
        @endif
    @endauth


    {{-- PAGE CONTENT --}}
    <main class="page-with-header">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="footer">
        <div class="container text-center">
            <div class="footer__brand">🍃 ShopTea</div>
            <p class="mb-1">Hành trình của hương trà và sự tinh tế</p>
            <p class="mb-0 footer__copy">© 2024 ShopTea. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Smooth scroll --}}
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', (e) => {
                const id = a.getAttribute('href');
                const el = document.querySelector(id);
                if (!el) return;
                e.preventDefault();
                el.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
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
                if (el) el.scrollIntoView({
                    behavior: 'auto',
                    block: 'start'
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
