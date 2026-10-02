<!DOCTYPE html>
<html lang="en" data-theme="dark" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#b91c1c">
    <link rel="icon" href="/images/icon-192.png">
    <link rel="apple-touch-icon" href="/images/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="TaskPulse">

    <title>TaskPulse — Star Cement</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    

    <style>
        /* 🎨 THEME ROOT DEFINITIONS (DYNAMIC COLOR MATRIX) */
        :root[data-theme="light"] {
            --bg-body: #f4f6fa;
            --bg-sidebar: linear-gradient(180deg, #ffffff 0%, #f1f5f9 100%);
            --sidebar-border: #e2e8f0;
            --sidebar-text: #475569;
            --sidebar-text-hover: #0f172a;
            --sidebar-bg-hover: rgba(15, 23, 42, 0.05);
            --bg-topbar: #ffffff;
            --border-topbar: #e5e9f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --bg-card: #ffffff;
            --border-card: #e5e9f0;
            --bg-icon-btn: #f1f5f9;
            --border-icon-btn: #cbd5e1;
            --text-icon-btn: #475569;
            --signout-bg: #fff5f5;
            --signout-text: #e53e3e;
            --signout-border: #fed7d7;
            
            /* 🔴 BRAND TEXT FIX FOR LIGHT MODE */
            --text-brand-title: #0f172a;
        }

        :root[data-theme="dark"] {
            --bg-body: #050d1a;
            --bg-sidebar: linear-gradient(180deg, #091930 0%, #050d1a 100%);
            --sidebar-border: rgba(255, 255, 255, .05);
            --sidebar-text: #a3b8cc;
            --sidebar-text-hover: #ffffff;
            --sidebar-bg-hover: rgba(255, 255, 255, 0.04);
            --bg-topbar: #091930;
            --border-topbar: rgba(255, 255, 255, .06);
            --text-main: #ffffff;
            --text-muted: #94a3b8;
            --bg-card: #0b1e36;
            --border-card: rgba(255, 255, 255, .06);
            --bg-icon-btn: rgba(255, 255, 255, 0.05);
            --border-icon-btn: rgba(255, 255, 255, 0.1);
            --text-icon-btn: #cbd5e1;
            --signout-bg: rgba(239, 28, 37, 0.06);
            --signout-text: #ff4d4d;
            --signout-border: rgba(239, 28, 37, 0.15);
            
            /* 🔴 BRAND TEXT FIX FOR DARK MODE */
            --text-brand-title: #ffffff;
        }

        /* 🏎️ SYSTEM BASE & CORE RESETS */
        body {
            background: var(--bg-body);
            color: var(--text-main);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            overflow-x: hidden;
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* PREMIUM FIXED SIDEBAR SETUP */
        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--bg-sidebar);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 1px solid var(--sidebar-border);
            z-index: 1000;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .sidebar-scrollable {
            overflow-y: auto;
            flex-grow: 1;
            scroll-behavior: smooth;
        }

        .brand {
            padding: 28px 24px;
            border-bottom: 1px solid var(--border-topbar);
            background: rgba(255, 255, 255, 0.01);
        }

        .brand-logo-container {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #ef1c25 0%, #b81414 100%);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(239, 28, 37, 0.3);
            flex-shrink: 0;
        }

        .brand-title-text {
            color: var(--text-brand-title) !important;
            transition: color 0.3s ease;
        }

        .menu-title {
            color: #5c7294;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 28px 24px 12px;
        }

        /* NAVIGATION INTERNALS STYLE PACK */
        .sidebar a, .sidebar button.theme-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--sidebar-text);
            text-decoration: none;
            margin: 4px 16px;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            background: transparent;
            border: none;
            width: calc(100% - 32px);
            text-align: left;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar a i, .sidebar button.theme-link i {
            font-size: 18px;
            transition: transform 0.25s ease;
        }

        .sidebar a:hover, .sidebar button.theme-link:hover {
            color: var(--sidebar-text-hover);
            background: var(--sidebar-bg-hover);
            transform: translateX(4px);
        }

        .sidebar a.active {
            color: #ffffff !important;
            background: #ef1c25;
            font-weight: 600;
            box-shadow: 0 8px 20px rgba(239, 28, 37, 0.3);
        }
        
        .sidebar a.active i {
            transform: scale(1.1);
        }

        /* 🚪 SIGN OUT ACTION CONTAINER BLOCK */
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--sidebar-border);
        }

        .btn-signout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            background: var(--signout-bg);
            color: var(--signout-text);
            border: 1px solid var(--signout-border);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-signout:hover {
            background: #ef1c25;
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(239, 28, 37, 0.25);
            border-color: #ef1c25;
        }

        /* SITE BODY PANEL WRAPPERS */
        .content {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* TOP PLATFORM CONSOLE BAR */
        .topbar {
            background: var(--bg-topbar);
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 32px;
            border-bottom: 1px solid var(--border-topbar);
            box-shadow: 0 2px 4px rgba(0,0,0,0.01);
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .page-content {
            padding: 32px;
        }

        .live-badge {
            background: #e6f9f0;
            color: #0fa968;
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid rgba(15, 169, 104, 0.15);
        }

        .live-dot {
            width: 7px;
            height: 7px;
            background: #0fa968;
            display: inline-block;
            border-radius: 50%;
            margin-right: 6px;
        }

        .icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--bg-icon-btn);
            color: var(--text-icon-btn);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid var(--border-icon-btn);
        }

        .icon-btn:hover {
            background: var(--border-icon-btn);
            transform: scale(1.05);
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #ef1c25;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            box-shadow: 0 4px 10px rgba(239, 28, 37, 0.2);
        }

        /* 📦 ENHANCED ENGINE CARDS MAPPED WITH TEXT VARIABLES */
        .card {
            border: 1px solid var(--border-card) !important;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--bg-card) !important;
            color: var(--text-main);
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        /* RESPONSIVE SCALED ENGINES */
        @media(max-width: 991px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .content {
                margin-left: 0;
            }
            .topbar {
                flex-wrap: wrap;
                height: auto;
                padding: 16px;
                gap: 12px;
            }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-scrollable">
            <div class="brand d-flex align-items-center gap-3">
                <div class="brand-logo-container d-flex align-items-center justify-content-center animate-pulse">
                    <i class="bi bi-star-fill text-warning fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 brand-title-text" style="font-size: 15px; letter-spacing: 0.5px;">STAR CEMENT</h5>
                    <span class="text-danger fw-bold font-monospace" style="font-size: 10px; letter-spacing: 0.05em; opacity: 0.95;">TASKPULSE ENGINE</span>
                </div>
            </div>

            <div class="menu-title">Main Menu</div>

            <a href="/dashboard" class="{{ Request::is('dashboard*') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>

            <a href="/tasks" class="{{ Request::is('tasks') ? 'active' : '' }}">
                <i class="bi bi-list-task"></i>
                <span>Tasks Index</span>
            </a>

            <a href="/tasks/create" class="{{ Request::is('tasks/create*') ? 'active' : '' }}">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Create Task</span>
            </a>

            <div class="menu-title">Management Vector</div>

          <a href="{{ url('/notifications') }}" class="{{ Request::is('notifications*') ? 'active' : '' }}">
    <i class="bi bi-bell-fill"></i>
    <span>Notification Center</span>
</a>

            <a href="{{ url('/scheduler') }}" class="{{ Request::is('scheduler*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span>Scheduler Matrix</span>
            </a>

            <a href="{{ url('/reports') }}" class="{{ Request::is('reports*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line-fill"></i>
                <span>Reports</span>
            </a>

            <button class="theme-link" id="settingsThemeBtn" title="Change Layout Theme Base">
                <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                <span id="themeText">Switch to Light Mode</span>
            </button>
        </div>

        <div class="sidebar-footer">
            <form action="/logout" method="POST" id="logoutForm">
                @csrf
                <button type="submit" class="btn-signout" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                    <i class="bi bi-box-arrow-left fs-5"></i>
                    <span>Sign Out Engine</span>
                </button>
            </form>
        </div>
    </div>

    <div class="content">
        <div class="topbar">
            <div class="d-flex align-items-center gap-2" style="font-size: 14px;">
                <span class="text-secondary opacity-70">Star Cement Limited</span>
                <i class="bi bi-chevron-right text-muted" style="font-size: 10px; opacity: 0.5;"></i>
                <strong class="fw-semibold" style="color: var(--text-main);">TaskPulse Console</strong>
            </div>

            <div class="d-flex align-items-center gap-3">

    <div class="live-badge d-flex align-items-center">
        <span class="live-dot animate-pulse"></span>
        <span>System Connected</span>
    </div>

    <!-- Install App Button (hidden until browser allows install) -->
    <button
        type="button"
        id="installAppBtn"
        class="btn btn-sm btn-outline-danger d-none align-items-center gap-1">
        <i class="bi bi-download"></i>
        <span>Install App</span>
    </button>

    <!-- Notification Bell -->
    <a
        href="{{ url('/notifications') }}"
        class="btn icon-btn position-relative"
        title="Notification Center">

        <i class="bi bi-bell-fill"></i>

        @if($layoutNotificationCount > 0)
            <span
                id="notificationBadge"
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

                {{ $layoutNotificationCount }}

            </span>
        @endif

    </a>

    <!-- Avatar -->

    <div class="user-avatar">

        SC

    </div>

</div>
        </div>

        <div class="page-content">
            @yield('content')
        </div>
    </div>

    {{-- REMINDER MODAL (shared across every page, not just the dashboard) --}}
    <div class="modal fade" id="reminderModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 400px;">
            <div class="modal-content border shadow-lg" style="border-radius: 8px;">
                <div class="modal-header border-bottom-0 py-3 px-3">
                    <h6 class="modal-title fw-bold d-flex align-items-center gap-2 mb-0 fs-7">
                        <i class="bi bi-shield-fill-exclamation text-danger fs-6"></i> Task Reminder
                    </h6>
                    <button type="button" class="btn-close fs-8" onclick="dismissTaskReminder()" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <h5 id="reminderTaskName" class="fw-bold mb-2"></h5>
                    <p class="text-muted mb-0 fs-7">This task's reminder time has arrived.</p>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0 d-flex gap-2">
                    <button class="btn btn-success flex-grow-1 py-2 rounded-2 fw-semibold fs-7 shadow-sm" onclick="completeTask()">
                        <i class="bi bi-check-circle me-1"></i> Mark Completed
                    </button>
                    <button class="btn border flex-grow-1 py-2 rounded-2 fw-semibold fs-7" onclick="dismissTaskReminder()">
                        <i class="bi bi-slash-circle me-1"></i> Dismiss
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // 🔔 SITE-WIDE REMINDER POPUP POLLING
        // Polls every 15s on every authenticated page (not just /dashboard),
        // so a due reminder pops up no matter where the user is in the app.
        let currentTaskId = null;
        let reminderModalInstance = null;

        function checkReminder() {
            fetch('/pending-reminder')
                .then(r => r.json())
                .then(task => {
                    if (task && task.id) {
                        currentTaskId = task.id;
                        document.getElementById('reminderTaskName').innerText = task.task_name;
                        reminderModalInstance = new bootstrap.Modal(document.getElementById('reminderModal'));
                        reminderModalInstance.show();
                    }
                })
                .catch(e => console.error('Reminder check failed:', e));
        }

        function completeTask() {
            if (!currentTaskId) return;
            fetch('/reminder/complete/' + currentTaskId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }).then(r => r.json()).then(() => {
                if (reminderModalInstance) reminderModalInstance.hide();
                location.reload();
            }).catch(e => console.error('Complete error:', e));
        }

        function dismissTaskReminder() {
            if (!currentTaskId) return;
            fetch('/reminder/dismiss/' + currentTaskId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }).then(r => r.json()).then(() => {
                if (reminderModalInstance) reminderModalInstance.hide();
                currentTaskId = null;
            }).catch(e => console.error('Dismiss error:', e));
        }

        document.addEventListener('DOMContentLoaded', function () {
            checkReminder();
            setInterval(checkReminder, 15000);
        });

        // ---------------------------------------------------------------
        // PWA INSTALL PROMPT
        // ---------------------------------------------------------------
        let deferredInstallPrompt = null;
        const installBtn = document.getElementById('installAppBtn');

        window.addEventListener('beforeinstallprompt', function (e) {
            e.preventDefault();
            deferredInstallPrompt = e;
            if (installBtn) installBtn.classList.remove('d-none');
        });

        if (installBtn) {
            installBtn.addEventListener('click', async function () {
                if (!deferredInstallPrompt) return;
                deferredInstallPrompt.prompt();
                const choice = await deferredInstallPrompt.userChoice;
                console.log('Install prompt outcome:', choice.outcome);
                deferredInstallPrompt = null;
                installBtn.classList.add('d-none');
            });
        }

        window.addEventListener('appinstalled', function () {
            console.log('🎉 TaskPulse installed successfully');
            if (installBtn) installBtn.classList.add('d-none');
        });
    </script>
    
    <script>
        // 🌓 LIVE CONFIGURATION THEME TOGGLER INSIDE SETTINGS SYSTEM
        const settingsThemeBtn = document.getElementById('settingsThemeBtn');
        const themeIcon = document.getElementById('themeIcon');
        const themeText = document.getElementById('themeText');
        const rootElement = document.documentElement;

        // Load persisted selection config
        const savedTheme = localStorage.getItem('taskpulse-theme') || 'dark';
        rootElement.setAttribute('data-theme', savedTheme);
        rootElement.setAttribute('data-bs-theme', savedTheme);
        updateThemeUI(savedTheme);

        if (settingsThemeBtn) {
            settingsThemeBtn.addEventListener('click', () => {
                const currentTheme = rootElement.getAttribute('data-theme');
                const targetTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                rootElement.setAttribute('data-theme', targetTheme);
                rootElement.setAttribute('data-bs-theme', targetTheme);
                localStorage.setItem('taskpulse-theme', targetTheme);
                updateThemeUI(targetTheme);
            });
        }

        function updateThemeUI(theme) {
            if (!themeIcon || !themeText) return;

            if (theme === 'dark') {
                themeIcon.className = 'bi bi-sun-fill text-warning'; 
                themeText.innerText = 'Switch to Light Mode';
            } else {
                themeIcon.className = 'bi bi-moon-stars-fill text-secondary'; 
                themeText.innerText = 'Switch to Dark Mode';
            }
        }
    </script>

    <style>
        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        .animate-pulse {
            animation: pulseGlow 3s infinite ease-in-out;
        }
    </style>
    
   @vite(['resources/js/app.js'])

    @stack('scripts')
</body>
</html>