<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopTea</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;

            background-image: url('{{ asset('images/nen.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .center-box {
            width: 100%;
            max-width: 400px;
            padding: 25px 20px;
            text-align: center;
            border-radius: 15px;

            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.30);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            color: #fff;
        }

        .logo-circle {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 20px;
        }

        /* ====== Fake bootstrap classes ====== */
        .text-center {
            text-align: center;
        }

        .text-start {
            text-align: left;
        }

        .w-100 {
            width: 100%;
        }

        .mb-2 {
            margin-bottom: 10px;
        }

        .mb-3 {
            margin-bottom: 14px;
        }

        .mt-2 {
            margin-top: 10px;
        }

        .mt-3 {
            margin-top: 14px;
        }

        label {
            display: block;
            margin: 8px 0 6px;
            font-size: 14px;
            opacity: .95;
        }

        .form-control {
            width: 100%;
            padding: 12px 12px;
            border-radius: 10px;
            outline: none;

            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.30);
            color: white;
            transition: all .2s ease;
        }

        .form-control:focus {
            background: #fff;
            color: #000;
            border-color: rgba(255, 255, 255, .9);
        }

        .btn {
            display: inline-block;
            padding: 12px 14px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            transition: background-color .2s ease, transform .15s ease;
        }

        .btn-primary {
            color: #fff;
            background-color: rgba(0, 89, 255, 0.55);
        }

        .btn-primary:hover {
            background-color: rgba(0, 89, 255, 0.75);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }

        .form-check-input {
            width: 16px;
            height: 16px;
            accent-color: rgba(0, 89, 255, 0.9);
            cursor: pointer;
        }

        .form-check-label {
            font-size: 14px;
            opacity: .95;
            cursor: pointer;
        }

        .alert {
            margin: 0 0 12px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 14px;
            line-height: 1.4;
        }

        .alert-success {
            background: rgba(52, 199, 89, 0.18);
            border: 1px solid rgba(52, 199, 89, 0.35);
        }

        .alert-danger {
            background: rgba(255, 59, 48, 0.18);
            border: 1px solid rgba(255, 59, 48, 0.35);
        }

        a {
            color: #9db5ff;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        p {
            margin: 0;
        }

        @media (max-width: 576px) {
            .center-box {
                max-width: 90%;
                padding: 20px;
            }

            .logo-circle {
                width: 100px;
                height: 100px;
            }
        }

        nav {
            display: none;
        }

        /* ===== Google Login Button ===== */
        .google-btn {
            width: 100%;
            margin-top: 12px;
            padding: 11px 14px;

            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;

            border-radius: 12px;
            cursor: pointer;

            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.7);

            color: #222;
            font-weight: 600;
            font-size: 14.5px;
            text-decoration: none;

            transition: transform .12s ease, box-shadow .15s ease;
        }

        .google-btn:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, .18);
            transform: translateY(-1px);
        }

        .google-btn:active {
            transform: scale(.985);
        }

        /* Icon Google */
        .google-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            font-weight: 700;
            font-size: 13px;

            color: #4285F4;
            background: rgba(66, 133, 244, .12);
        }

        /* ===== Auth links row ===== */
        .auth-links--row {
            margin-top: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 14px;
            flex-wrap: wrap;
        }

        /* ===== Google pill button (NỔI) ===== */
        .google-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            padding: 8px 14px;
            border-radius: 999px;

            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;

            color: #111;
            background: linear-gradient(
                135deg,
                rgba(255, 255, 255, .95),
                rgba(245, 247, 255, .92)
            );

            border: 1px solid rgba(255, 255, 255, .75);

            box-shadow: 0 6px 16px rgba(0, 0, 0, .22),
            inset 0 1px 0 rgba(255, 255, 255, .6);

            transition: transform .12s ease,
            box-shadow .15s ease,
            filter .15s ease;
        }

        /* Hover: nổi hơn */
        .google-pill:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 26px rgba(0, 0, 0, .28),
            inset 0 1px 0 rgba(255, 255, 255, .7);
        }

        /* Active: ấn xuống */
        .google-pill:active {
            transform: scale(.97);
            box-shadow: 0 4px 10px rgba(0, 0, 0, .25),
            inset 0 2px 6px rgba(0, 0, 0, .15);
        }

        /* Icon Google */
        .google-pill-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            font-size: 12px;
            font-weight: 700;

            color: #4285F4;
            background: rgba(66, 133, 244, .18);
        }

    </style>

</head>

<body>
<div class="center-box">
    <!-- Logo -->
    <img src="{{ asset('images/logo.png') }}" class="logo-circle" alt="ShopTea Logo">

    <!-- Form content -->
    @yield('content')
</div>
</body>

</html>
