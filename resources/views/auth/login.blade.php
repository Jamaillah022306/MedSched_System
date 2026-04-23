<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedSched - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        .login-page {
            width: 100%;
            min-height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 7%;
        }

        .login-page::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url("{{ asset('image/doctor-bg.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.45;
            z-index: 0;
        }

        .form-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
        }

        .medsched-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 6px;
        }

        .logo-text { font-size: 38px; font-weight: 800; color: #2c750a; }
        .logo-text span { color: #0f5288; }

        .welcome-title {
            font-size: 34px;
            font-weight: 900;
            color: #000000;
            margin-bottom: 4px;
            text-align: center;
        }

        .welcome-subtitle {
            font-size: 16px;
            color: #1a3a5c;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-label-custom {
            font-weight: 700;
            font-size: 17px;
            color: #1a3a5c;
            margin-bottom: 6px;
            display: block;
        }

        .form-control-custom {
            width: 100%;
            padding: 13px 16px;
            border: none;
            outline: none;
            border-radius: 8px;
            font-size: 15px;
            background: rgba(255,255,255,0.80);
            color: #1a3a5c;
            margin-bottom: 16px;
        }

        .form-control-custom:focus {
            outline: none;
            box-shadow: none;
            background: rgba(255,255,255,0.95);
        }

        .forgot-link {
            display: block;
            text-align: right;
            font-size: 14px;
            color: #000000;
            text-decoration: none;
            margin-top: -10px;
            margin-bottom: 20px;
        }

        .forgot-link:hover { text-decoration: underline; }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 20px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-login:hover { background: #388e3c; transform: translateY(-1px); }

        .signup-link {
            text-align: center;
            margin-top: 16px;
            font-size: 15px;
            color: #1a3a5c;
        }

        .signup-link a { color: #070707; font-weight: 600; text-decoration: none; }
        .signup-link a:hover { text-decoration: underline; }

        .alert-error {
            background: rgba(244,67,54,0.12);
            border: 1px solid #f44336;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 16px;
            font-size: 14px;
            color: #c62828;
        }

        /* ===== TABLET (max 768px) ===== */
        @media (max-width: 768px) {
            .login-page {
                justify-content: center;
                padding: 40px 24px;
            }
            .form-wrapper { max-width: 100%; }
        }

        /* ===== MOBILE (max 480px) ===== */
        @media (max-width: 480px) {
            .login-page {
                padding: 30px 16px;
                align-items: flex-start;
            }
            .form-wrapper { padding: 24px 20px; }
            .medsched-logo img { width: 90px !important; height: 90px !important; }
            .logo-text { font-size: 30px; }
            .welcome-title { font-size: 24px; }
            .welcome-subtitle { font-size: 13px; }
            .form-label-custom { font-size: 15px; }
            .form-control-custom { font-size: 14px; padding: 11px 14px; }
            .btn-login { font-size: 17px; padding: 13px; }
            .forgot-link { font-size: 13px; }
            .signup-link { font-size: 13px; }
        }
    </style>
</head>
<body>
<div class="login-page">
    <div class="form-wrapper" style="background-color: #57a9ec; padding: 40px 32px; border-radius: 14px; box-shadow: 0 8px 32px rgba(0,0,0,0.18);">
        <div class="medsched-logo">
            <img src="{{ asset('image/image_2026-03-19_111753926-removebg-preview.png') }}"
                 alt="MedSched Logo" style="width:120px; height:120px; object-fit:contain; margin-bottom:4px;">
            <div class="logo-text"><span>Med</span>Sched</div>
        </div>
        <div class="welcome-title">WELCOME BACK!</div>
        <div class="welcome-subtitle">Sign in to your account</div>

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <label class="form-label-custom">Email:</label>
            <input type="email" name="email" class="form-control-custom" value="{{ old('email') }}" required autofocus>
            <label class="form-label-custom">Password:</label>
            <input type="password" name="password" class="form-control-custom" required>
            <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
            <button type="submit" class="btn-login">Log in</button>
        </form>

        <div class="signup-link">
            Don't have account? <a href="{{ route('register') }}">Click here</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>