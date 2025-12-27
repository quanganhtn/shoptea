<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Admin - ShopTea')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-body">

<div class="admin-layout">

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <img src="{{asset('images/logo.png')}}" alt="Shoptea Logo" class="admin-logo">
            <div>
                <div class="name">ShopTea</div>
                <div class="small">Admin Panel</div>
            </div>
        </div>

        <nav class="admin-nav">
            <a class="admin-link admin-link--dashboard {{request()->routeIs('admin.dashboard') ? 'active':''}}"
               href="{{route('admin.dashboard')}}">
                <span class="icon">📊</span>
                <span class="text">Dashboard</span>
            </a>
            {{-- Trang chủ --}}
            <div class="admin-menu-group">
                <button type="button"
                        class="admin-link admin-toggle {{ request()->routeIs('admin.homepage.*') ? 'active' : '' }}"
                        onclick="toggleMenu('homepage-menu')">
                    🏠 Trang chủ
                    <span class="arrow">▾</span>
                </button>

                <div id="homepage-menu"
                     class="admin-submenu {{ request()->routeIs('admin.homepage.*') ? 'show' : '' }}">

                    <a class="admin-sublink {{ request()->routeIs('admin.homepage.hero') ? 'active' : '' }}"
                       href="{{ route('admin.homepage.hero') }}">
                        🖼 Banner
                    </a>

                    <a class="admin-sublink {{ request()->routeIs('admin.homepage.about') ? 'active' : '' }}"
                       href="{{ route('admin.homepage.about') }}">
                        ℹ️ About
                    </a>

                    <a class="admin-sublink {{ request()->routeIs('admin.homepage.news') ? 'active' : '' }}"
                       href="{{ route('admin.homepage.news') }}">
                        📰 News
                    </a>

                    <a class="admin-sublink {{ request()->routeIs('admin.homepage.contact') ? 'active' : '' }}"
                       href="{{ route('admin.homepage.contact') }}">
                        📞 Contact
                    </a>
                </div>
            </div>


            <a class="admin-link {{ request()->routeIs('admin.products.*') ? 'active':'' }}"
               href="{{ route('admin.products.index') }}">🧋 Sản phẩm</a>

            {{--            <a class="admin-link {{ request()->routeIs('admin.categories.*') ? 'active':'' }}"--}}
            {{--               href="{{ route('admin.categories.index') }}">🏷️ Danh mục</a>--}}
            {{--             <a class="admin-link" href="{{ route('admin.orders.index') }}">📦 Đơn hàng</a>--}}
            {{--            <a class="admin-link {{ request()->routeIs('admin.users.*') ? 'active':'' }}"--}}
            {{--               href="{{ route('admin.users.index') }}">👤 Users</a>--}}
        </nav>

        <div class="admin-sidebar__bottom">
            <a class="btn btn-light w-100 mb-2" href="{{ route('home') }}">← Về trang user</a>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button class="btn btn-outline-danger w-100">Đăng xuất</button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="admin-main">

        {{-- TOPBAR --}}
        <header class="admin-topbar">
            <div class="admin-topbar__left">
                <div class="title">@yield('page_title','Dashboard')</div>
            </div>

            <div class="admin-topbar__right">
                <span class="me-2 fw-semibold">{{ auth()->user()->name }}</span>
                <span class="badge bg-success">ADMIN</span>
            </div>
        </header>

        {{-- CONTENT --}}
        <section class="admin-content">
            @yield('content')
        </section>

    </main>
</div>
<script>
    function toggleMenu(id) {
        document.getElementById(id)?.classList.toggle('show');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
