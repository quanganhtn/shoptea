<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopTea</title>

    <link rel="stylesheet" href="{{ asset('css/user/auth.css') }}">
    @stack('styles')
</head>
<body>
<div class="center-box">
    <!-- Logo -->
    <img src="{{ asset('images/logo.png') }}" class="logo-circle" alt="ShopTea Logo">

    <body style="background-image: url('{{ asset('images/nen.png') }}')">

    <!-- Form content -->
    @yield('content')
</div>
</body>

</html>
