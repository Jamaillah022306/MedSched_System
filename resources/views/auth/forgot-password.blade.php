<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedSched - Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .login-page {
            width: 100%; height: 100vh;
            background-image: url("{{ asset('image/doctor-bg.jpg') }}");
            background-size: cover; background-position: center;
            display: flex; align-items: center;
            justify-content: flex-start; padding-left: 7%;
        }
        .form-wrapper { width: 100%; max-width: 580px; }
        .logo-text { font-size: 48px; font-weight: 800; color: #4dc018; text-align: center; }
        .logo-text span { color: #2196F3; }
        .welcome-title { font-size: 32px; font-weight: 900; color: #000; margin-bottom: 6px; text-align: center; }
        .welcome-subtitle { font-size: 16px; color: #1a3a5c; margin-bottom: 24px; text-align: center; }
        .form-label-custom { font-weight: 700; font-size: 18px; color: #1a3a5c; margin-bottom: 8px; display: block; }
        .form-control-custom {
            width: 100%; padding: 16px 20px; border: none; outline: none;
            border-radius: 8px; font-size: 18px;
            background: rgba(255,255,255,0.70); color: #1a3a5c; margin-bottom: 20px;
        }
        .btn-submit {
            width: 100%; padding: 18px; background: #4CAF50; color: white;
            border: none; border-radius: 8px; font-size: 20px;
            font-weight: 700; cursor: pointer; transition: all 0.2s;
        }
        .btn-submit:hover { background: #388e3c; }
        .back-link { text-align: center; margin-top: 16px; font-size: 15px; }
        .back-link a { color: #070707; font-weight: 600; text-decoration: none; }
        .alert-success {
            background: rgba(76,175,80,0.15); border: 1px solid #4CAF50;
            border-radius: 6px; padding: 12px 16px; margin-bottom: 16px;
            font-size: 15px; color: #2e7d32;
        }
        .alert-error {
            background: rgba(244,67,54,0.12); border: 1px solid #f44336;
            border-radius: 6px; padding: 12px 16px; margin-bottom: 16px;
            font-size: 15px; color: #c62828;
        }
    </style>
</head>
<body>
<div class="login-page">
    <div class="form-wrapper">
        <div style="text-align:center; margin-bottom:10px;">
            <img src="{{ asset('image/image_2026-03-19_111753926-removebg-preview.png') }}"
                 style="width:120px; height:120px; object-fit:contain;">
            <div class="logo-text"><span>Med</span>Sched</div>
        </div>

        <div class="welcome-title">Forgot Password?</div>
        <div class="welcome-subtitle">Enter your email and we'll send you a reset link.</div>

        @if (session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <label class="form-label-custom">Email:</label>
            <input type="email" name="email" class="form-control-custom"
                   value="{{ old('email') }}" required autofocus>
            <button type="submit" class="btn-submit">Send Reset Link</button>
        </form>

        <div class="back-link">
            Remember your password? <a href="{{ route('login') }}">Back to login</a>
        </div>
    </div>
</div>
</body>
</html>