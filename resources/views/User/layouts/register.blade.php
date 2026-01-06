<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'ShopTea')</title>

    <style>
        /* ===== Reset ===== */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* ===== Background full screen ===== */
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('{{ asset('images/nen.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* ===== Glass box ===== */
        .center-box {
            width: 100%;
            max-width: 450px;
            padding: 26px 22px;
            text-align: center;
            border-radius: 16px;

            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .28);
            box-shadow: 0 10px 35px rgba(0, 0, 0, .28);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);

            color: #fff;
        }

        /* hơi nhỏ hơn cho login (tuỳ chọn) */
        .center-box.box--login {
            max-width: 420px;
        }

        /* ===== Logo ===== */
        .logo-circle {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .28);
            margin-bottom: 18px;
        }

        /* ===== Title ===== */
        .title {
            margin: 0 0 14px;
            font-size: 26px;
            letter-spacing: .5px;
        }

        /* ===== Alerts ===== */
        .alert {
            margin: 0 0 12px;
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 14px;
            line-height: 1.4;
            text-align: left;
        }

        .alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .alert-success {
            background: rgba(46, 204, 113, .18);
            border: 1px solid rgba(46, 204, 113, .35);
        }

        .alert-danger {
            background: rgba(255, 99, 71, .18);
            border: 1px solid rgba(255, 99, 71, .35);
        }

        /* ===== Form ===== */
        .form-wrap {
            text-align: left;
        }

        label {
            display: block;
            margin: 10px 0 6px;
            font-size: 14px;
            opacity: .95;
        }

        .input {
            width: 100%;
            padding: 12px 12px;
            border-radius: 12px;
            outline: none;

            background: rgba(255, 255, 255, .16);
            border: 1px solid rgba(255, 255, 255, .28);
            color: #fff;

            transition: all .2s ease;
        }

        .input::placeholder {
            color: rgba(255, 255, 255, .75);
        }

        .input:focus {
            background: rgba(255, 255, 255, .95);
            color: #111;
            border-color: rgba(255, 255, 255, .95);
        }

        .input:focus::placeholder {
            color: rgba(0, 0, 0, .35);
        }

        /* ===== Checkbox row (remember) ===== */
        .row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
            user-select: none;
        }

        .checkbox {
            width: 16px;
            height: 16px;
            accent-color: #2f80ff; /* tiện, vẫn là CSS thuần */
        }

        /* ===== Button ===== */
        .btn-primary {
            width: 100%;
            margin-top: 14px;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, .22);
            cursor: pointer;

            font-weight: 700;
            font-size: 15px;
            color: #fff;
            background: linear-gradient(135deg, rgba(0, 89, 255, .78), rgba(93, 63, 255, .72));
            box-shadow: 0 10px 22px rgba(0, 0, 0, .22);

            transition: transform .12s ease, filter .15s ease;
        }

        .btn-primary:hover {
            filter: brightness(1.05);
        }

        .btn-primary:active {
            transform: scale(.985);
        }

        /* ===== Links ===== */
        .auth-links {
            margin-top: 14px;
            text-align: center;
            font-size: 14px;
        }

        .link {
            color: #9ec5ff;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .center-box {
                max-width: 90%;
                padding: 22px 18px;
            }

            .logo-circle {
                width: 105px;
                height: 105px;
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
<div class="center-box @yield('box_class')">
    <img src="{{ asset('images/logo.png') }}" class="logo-circle" alt="ShopTea Logo">
    @yield('content')
</div>

@yield('page_js')
</body>
</html>
