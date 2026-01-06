<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Admin - ShopTea')</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body class="admin-body">
<div class="admin-layout">

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <img src="{{ asset('images/logo.png') }}" alt="ShopTea Logo" class="admin-logo">
            <div>
                <div class="admin-brand__name">ShopTea</div>
                <div class="admin-brand__small">Admin Panel</div>
            </div>
        </div>

        <nav class="admin-nav">
            <a class="admin-link admin-link--dashboard {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}">
                <span class="admin-link__left">
                    <span class="admin-icon">📊</span>
                    <span class="admin-text">Dashboard</span>
                </span>
            </a>

            {{-- Trang chủ (submenu) --}}
            @php $homeActive = request()->routeIs('admin.homepage.*'); @endphp
            <div class="admin-menu">
                <button type="button"
                        class="admin-link admin-toggle {{ $homeActive ? 'active' : '' }}"
                        aria-expanded="{{ $homeActive ? 'true' : 'false' }}"
                        onclick="toggleMenu(this, 'homepage-menu')">
                    <span class="admin-link__left">
                        <span class="admin-icon">🏠</span>
                        <span class="admin-text">Trang chủ</span>
                    </span>
                    <span class="admin-arrow">▾</span>
                </button>

                <div id="homepage-menu" class="admin-submenu {{ $homeActive ? 'show' : '' }}">
                    <a class="admin-sublink {{ request()->routeIs('admin.homepage.hero') ? 'active' : '' }}"
                       href="{{ route('admin.homepage.hero') }}">🖼 Banner</a>

                    <a class="admin-sublink {{ request()->routeIs('admin.homepage.about') ? 'active' : '' }}"
                       href="{{ route('admin.homepage.about') }}">ℹ️ About</a>

                    <a class="admin-sublink {{ request()->routeIs('admin.homepage.news') ? 'active' : '' }}"
                       href="{{ route('admin.homepage.news') }}">📰 News</a>

                    <a class="admin-sublink {{ request()->routeIs('admin.homepage.contact') ? 'active' : '' }}"
                       href="{{ route('admin.homepage.contact') }}">📞 Contact</a>
                </div>
            </div>

            <a class="admin-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
               href="{{ route('admin.products.index') }}">
                <span class="admin-link__left">
                    <span class="admin-icon">🧋</span>
                    <span class="admin-text">Sản phẩm</span>
                </span>
            </a>

            <a class="admin-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
               href="{{ route('admin.categories.index') }}">
                <span class="admin-link__left">
                    <span class="admin-icon">🏷️</span>
                    <span class="admin-text">Danh mục</span>
                </span>
            </a>

            <a class="admin-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
               href="{{ route('admin.orders.index') }}">
                <span class="admin-link__left">
                    <span class="admin-icon">📦</span>
                    <span class="admin-text">Đơn hàng</span>
                </span>
            </a>

            <a class="admin-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
               href="{{ route('admin.users.index') }}">
                <span class="admin-link__left">
                    <span class="admin-icon">👤</span>
                    <span class="admin-text">Users</span>
                </span>
            </a>
        </nav>

        <div class="admin-sidebar__bottom">
            <a class="admin-btn admin-btn--light admin-btn--block" href="{{ route('home') }}">← Về trang user</a>

            <form action="{{ route('logout') }}" method="POST" class="admin-form--no-gap">
                @csrf
                <button class="admin-btn admin-btn--outline-danger admin-btn--block" type="submit">Đăng xuất</button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="admin-main">
        <header class="admin-topbar">
            <div class="admin-topbar__title">@yield('page_title','Dashboard')</div>

            <div class="admin-topbar__right">
                <span class="admin-user">{{ auth()->user()->name }}</span>
                <span class="admin-badge">ADMIN</span>
            </div>
        </header>

        <section class="admin-content">
            @yield('content')
        </section>
    </main>
</div>

<script>
    function toggleMenu(btn, id) {
        const el = document.getElementById(id);
        if (!el) return;

        el.classList.toggle('show');
        const isOpen = el.classList.contains('show');
        btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        btn.classList.toggle('is-open', isOpen);
    }
</script>

@stack('scripts')
</body>
</html>
