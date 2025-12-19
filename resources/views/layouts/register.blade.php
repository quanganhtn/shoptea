<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopTea</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        /* Body full màn hình, nền ảnh */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            /* căn giữa ngang */
            align-items: center;
            /* căn giữa dọc */
            font-family: Arial, sans-serif;

            background-image: url('{{ asset('images/nen.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Box chính (logo + form) */
        .center-box {
            width: 100%;
            max-width: 500px;
            padding: 25px 20px;
            text-align: center;
            border-radius: 15px;

            background: rgba(255, 255, 255, 0.1);
            /* nền trắng mờ */
            border: 1px solid rgba(255, 255, 255, 0.3);
            /* viền mờ */
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);

            color: white;
            /* chữ trắng */
        }

        /* Logo tròn */
        .logo-circle {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 20px;
        }

        /* Input form */
        .form-control {
            margin-bottom: 10px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        /* Button full width */
        .btn-full {
            width: 100%;
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-full:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        /* Dòng "Đã có tài khoản" căn giữa */
        .text-login-link {
            margin-top: 15px;
            text-align: center;
            color: white;
        }

        /* Responsive: giảm box & logo trên điện thoại */
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

        /* Ẩn navbar nếu có */
        nav {
            display: none;
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
