<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'ShopTea')</title>

    <link rel="stylesheet" href="{{ asset('css/user/auth.css') }}">
    @stack('styles')
</head>

<body>
<div class="center-box @yield('box_class')">
    <img src="{{ asset('images/logo.png') }}" class="logo-circle" alt="ShopTea Logo">
    <body style="background-image: url('{{ asset('images/nen.png') }}')">
    @yield('content')
</div>

@yield('page_js')
</body>
</html>
