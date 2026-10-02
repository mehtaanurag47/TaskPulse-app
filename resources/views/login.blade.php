<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TaskPulse - Account Sign In</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            min-height: 100vh;
            /* डैशबोर्ड के लेफ्ट साइडबार से मैच करता हुआ डार्क प्रीमियम बैकग्राउंड */
            background: linear-gradient(135deg, #111c3a 0%, #080d1a 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            width: auto;
            height: 52px;
            object-fit: contain;
        }

        .company-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .company-header h3 {
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.025em;
            margin-bottom: 4px;
        }

        .company-header p {
            color: #64748b;
            font-size: 14px;
            margin: 0;
            font-weight: 500;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-control {
            height: 48px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 10px 16px;
            font-size: 15px;
            color: #0f172a;
            transition: all 0.2s ease;
        }

        /* इनपुट फोकस होने पर स्टार सीमेंट रेड टोन हाइलाइट */
        .form-control:focus {
            border-color: #e62222;
            box-shadow: 0 0 0 4px rgba(230, 34, 34, 0.15);
            color: #0f172a;
        }

        .form-check-input:checked {
            background-color: #e62222;
            border-color: #e62222;
        }

        .form-check-label {
            font-size: 14px;
            color: #475569;
            user-select: none;
            font-weight: 500;
        }

        /* बटन को स्क्रीनशॉट के अनुसार सॉलिड रेड थीम दी गई है */
        .btn-login {
            height: 48px;
            border-radius: 8px;
            background: #e62222;
            border: none;
            color: #ffffff;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.2s ease;
        }

        .btn-login:hover, .btn-login:focus {
            background: #cc1f1f;
            color: #ffffff;
            transform: translateY(-1px);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }

        .action-links {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
        }

        .action-links a {
            color: #e62222;
            text-decoration: none;
            font-weight: 600;
        }

        .action-links a:hover {
            text-decoration: underline;
        }

        .footer-branding {
            text-align: center;
            color: #94a3b8;
            margin-top: 32px;
            font-size: 12px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .alert {
            border-radius: 8px;
            font-size: 14px;
            padding: 12px 16px;
            border: none;
            font-weight: 500;
        }
    </style>
</head>

<body>

<div class="login-card">

    <div class="logo-container">
        <img src="{{ asset('images/Star_Cement.logo.png') }}" alt="Star Cement Logo">
    </div>

    <div class="company-header">
        <h3>TaskPulse</h3>
        <p>Smart Task Management System</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>{{ $errors->first() }}</div>
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" 
                   name="email" 
                   class="form-control" 
                   placeholder="name@starcement.co.in"
                   value="{{ old('email') }}"
                   required 
                   autocomplete="email"
                   autofocus>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" 
                   name="password" 
                   class="form-control" 
                   placeholder="••••••••"
                   required 
                   autocomplete="current-password">
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check m-0">
                <input class="form-check-input" 
                       type="checkbox" 
                       name="remember" 
                       id="rememberMe">
                <label class="form-check-label" for="rememberMe">
                    Remember identification
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-login w-100 shadow-sm">
            Sign In to Dashboard
        </button>
    </form>

    <div class="action-links">
        <span class="text-muted">New to platform?</span>
        <a href="/register" class="ms-1">Create an account</a>
    </div>

    <div class="footer-branding">
        © 2026 TaskPulse • Star Cement Group
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
