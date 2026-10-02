<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TaskPulse - Enterprise Task & Management Platform</title>
    
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #f8fafc;
            color: #0f172a;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 0;
        }

        .navbar-brand-custom {
            font-weight: 800;
            color: #0f172a !important;
            letter-spacing: -0.025em;
            font-size: 22px;
        }

        .hero-section {
            /* Matched to the deep slate industrial background on your system screens */
            background: linear-gradient(135deg, #111c3a 0%, #080d1a 100%);
            border-radius: 24px;
            padding: 90px 40px;
            color: #ffffff;
            margin-top: 48px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }

        .hero-title {
            font-size: 52px;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 16px;
        }

        .hero-subtitle {
            color: #94a3b8;
            font-size: 18px;
            max-width: 640px;
            margin: 0 auto 36px auto;
            font-weight: 400;
            line-height: 1.6;
        }

        /* Star Cement Brand Core CTA Conversion */
        .btn-cta-primary {
            background-color: #e62222;
            color: #ffffff;
            font-weight: 600;
            padding: 14px 36px;
            border-radius: 8px;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-cta-primary:hover {
            background-color: #cc1f1f;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-cta-secondary {
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            font-weight: 500;
            padding: 14px 28px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.2s ease;
        }

        .btn-cta-secondary:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }

        .footer-branding {
            margin-top: auto;
            text-align: center;
            color: #64748b;
            font-size: 13px;
            padding: 40px 0;
            font-weight: 500;
            letter-spacing: 0.025em;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
    <div class="container">
        <a class="navbar-brand navbar-brand-custom d-flex align-items-center gap-2" href="#">
            <i class="bi bi-pulse style-brand-color" style="color: #e62222;"></i> TaskPulse
        </a>
        <div class="ms-auto d-flex align-items-center gap-3">
            <a href="/login" class="text-secondary text-decoration-none fw-semibold fs-6 px-2">Sign In</a>
            <a href="/register" class="btn btn-dark btn-sm rounded-2 px-3 py-2 fw-medium">Create Account</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="hero-section text-center position-relative overflow-hidden">
        <div class="position-absolute top-0 start-50 translate-middle" style="width: 600px; height: 600px; background: radial-gradient(circle, rgba(230,34,34,0.12) 0%, rgba(0,0,0,0) 70%); pointer-events: none;"></div>
        
        <div class="position-relative z-1">
            <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-10 px-3 py-2 rounded-pill fw-semibold mb-4 text-uppercase tracking-wider" style="font-size: 11px;">
                v2.4 Live Platform Engine
            </span>
            <h1 class="hero-title">TaskPulse Management</h1>
            <p class="hero-subtitle">
                Optimize your plant workflow cadence with our professional-grade smart execution engine, real-time tracking logs, and direct notification sync.
            </p>

            <div class="d-flex flex-sm-row flex-column justify-content-center align-items-center gap-3">
                <a href="/register" class="btn btn-cta-primary shadow d-flex align-items-center gap-2 w-100 w-sm-auto justify-content-center">
                    Get Started Free <i class="bi bi-arrow-right"></i>
                </a>
                <a href="/login" class="btn btn-cta-secondary w-100 w-sm-auto justify-content-center">
                    Access Console
                </a>
            </div>
        </div>
    </div>
</div>

<footer class="footer-branding">
    <div class="container">
        © 2026 TaskPulse Hub • Powered by Star Cement Group Analytics Solutions
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>