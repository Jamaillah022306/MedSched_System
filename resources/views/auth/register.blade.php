<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedSched - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .register-page {
            width: 100%;
            min-height: 100vh;
            background-image: url("{{ asset('image/doctor-bg.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 40px 7%;
        }

        .form-wrapper {
            width: 100%;
            max-width: 580px;
        }

        .register-title {
            font-size: 36px;
            font-weight: 900;
            color: #1a3a5c;
            margin-bottom: 4px;
            text-align: center;
        }

        .register-subtitle {
            font-size: 16px;
            color: #1a3a5c;
            margin-bottom: 22px;
            text-align: center;
        }

        .form-row {
            display: flex;
            gap: 14px;
        }

        .form-group-custom {
            margin-bottom: 14px;
            flex: 1;
        }

        .form-label-custom {
            font-weight: 700;
            font-size: 16px;
            color: #1a3a5c;
            margin-bottom: 5px;
            display: block;
        }

        .form-control-custom {
            width: 100%;
            padding: 12px 16px;
            border: none;
            outline: none;
            border-radius: 6px;
            font-size: 15px;
            background: rgba(255,255,255,0.70);
            color: #1a3a5c;
            transition: background 0.2s;
        }

        .form-control-custom:focus {
            outline: none;
            box-shadow: none;
            background: rgba(255,255,255,0.90);
        }

        .btn-register {
            width: 100%;
            padding: 16px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 20px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 6px;
            transition: all 0.2s;
        }

        .btn-register:hover {
            background: #388e3c;
            transform: translateY(-1px);
        }

        .login-link {
            text-align: center;
            margin-top: 16px;
            font-size: 15px;
            color: #1a3a5c;
        }

        .login-link a {
            color: #000000;
            font-weight: 700;
            text-decoration: none;
        }

        .login-link a:hover { text-decoration: underline; }

        .alert-error {
            background: rgba(244,67,54,0.12);
            border: 1px solid #f44336;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 14px;
            font-size: 13px;
            color: #c62828;
        }
    </style>
</head>
<body>

<div class="register-page">
    <div class="form-wrapper">

        <div class="register-title">Create your account</div>
        <div class="register-subtitle">Register as a patient to book appointments</div>

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf

            {{-- Name --}}
            <div class="form-row">
                <div class="form-group-custom">
                    <label class="form-label-custom">First Name:</label>
                    <input type="text" name="first_name" class="form-control-custom"
                           value="{{ old('first_name') }}" required>
                </div>
                <div class="form-group-custom">
                    <label class="form-label-custom">Last Name:</label>
                    <input type="text" name="last_name" class="form-control-custom"
                           value="{{ old('last_name') }}" required>
                </div>
            </div>

            {{-- Contact + Date of Birth --}}
            <div class="form-row">
                <div class="form-group-custom">
                    <label class="form-label-custom">Contact Number:</label>
                    <input type="text" name="phone" class="form-control-custom"
                           value="{{ old('phone') }}">
                </div>
                <div class="form-group-custom">
                    <label class="form-label-custom">Date of Birth:</label>
                    <input type="date" name="date_of_birth" class="form-control-custom"
                           value="{{ old('date_of_birth') }}">
                </div>
            </div>

            {{-- Gender --}}
            <div class="form-group-custom">
                <label class="form-label-custom">Gender:</label>
                <select name="gender" class="form-control-custom">
                    <option value="">Select gender...</option>
                    <option value="male"   {{ old('gender') == 'male'   ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other"  {{ old('gender') == 'other'  ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            {{-- Address --}}
            <div class="form-group-custom">
                <label class="form-label-custom">Address:</label>
                <input type="text" name="address" class="form-control-custom"
                       value="{{ old('address') }}">
            </div>

            {{-- Email --}}
            <div class="form-group-custom">
                <label class="form-label-custom">Email:</label>
                <input type="email" name="email" class="form-control-custom"
                       value="{{ old('email') }}" required>
            </div>

            {{-- Password --}}
            <div class="form-row">
                <div class="form-group-custom">
                    <label class="form-label-custom">Password:</label>
                    <input type="password" name="password" class="form-control-custom" required>
                </div>
                <div class="form-group-custom">
                    <label class="form-label-custom">Confirm Password:</label>
                    <input type="password" name="password_confirmation" class="form-control-custom" required>
                </div>
            </div>

            <button type="submit" class="btn-register">Register</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="{{ route('login') }}">Log in</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>