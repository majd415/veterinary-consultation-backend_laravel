<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fluffy Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8b5cf6; /* Neon Violet */
            --primary-glow: rgba(139, 92, 246, 0.5);
            --secondary: #ec4899; /* Neon Pink */
            --cyan: #06b6d4; /* Neon Cyan */
            --bg: #030712; /* Deep Black/Blue */
            --sidebar-bg: rgba(17, 24, 39, 0.8);
            --card-bg: rgba(31, 41, 55, 0.6);
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.08);
            --sidebar-width: 260px;
        }

        /* Custom Modern Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { 
            background: rgba(139, 92, 246, 0.3); 
            border-radius: 10px; 
            transition: background 0.3s;
        }
        ::-webkit-scrollbar-thumb:hover { 
            background: var(--primary); 
            box-shadow: 0 0 10px var(--primary-glow);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(6, 182, 212, 0.1) 0px, transparent 50%);
            color: var(--text);
            margin: 0;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            padding: 0.0rem;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 50;
            transition: transform 0.3s ease-in-out;
            left: 0;
            top: 0;
        }

        .brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-shadow: 0 0 15px var(--primary-glow);
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.85rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 0.75rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
        }

        .nav-item:hover {
            color: white;
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .nav-item.active {
            background: linear-gradient(90deg, var(--primary-glow), transparent);
            color: white;
            border-left: 3px solid var(--primary);
            box-shadow: -10px 0 20px -10px var(--primary-glow);
        }

        .nav-icon { margin-right: 0.75rem; font-size: 1.25rem; }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 2rem;
            width: calc(100% - var(--sidebar-width));
            box-sizing: border-box;
        }

        /* Cards */
        .card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border-radius: 1.25rem;
            padding: 1.75rem;
            border: 1px solid var(--border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            border-color: rgba(139, 92, 246, 0.3);
            box-shadow: 0 8px 32px 0 rgba(139, 92, 246, 0.15);
        }

        /* Tables */
        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        th { text-align: left; padding: 1.25rem 1rem; color: var(--text-muted); border-bottom: 2px solid var(--border); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; }
        td { padding: 1.25rem 1rem; border-bottom: 1px solid var(--border); vertical-align: middle; }

        /* Buttons Neon Style */
        .btn {
            border-radius: 0.75rem;
            padding: 0.65rem 1.25rem;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: translateY(0);
        }

        .btn:active { transform: translateY(1px); }

        .btn-primary { 
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            color: white;
            box-shadow: 0 4px 15px var(--primary-glow);
        }

        .btn-primary:hover {
            box-shadow: 0 6px 20px var(--primary-glow);
            filter: brightness(1.1);
        }

        /* Pagination Refined */
        nav[aria-label="Pagination Navigation"] svg { width: 18px; height: 18px; }
        nav[aria-label="Pagination Navigation"] .relative.inline-flex { gap: 0.4rem; }
        nav[aria-label="Pagination Navigation"] span[aria-current="page"] span {
            background: var(--primary) !important;
            box-shadow: 0 0 15px var(--primary-glow);
        }
        /* Header & User Menu */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .menu-toggle {
            display: none;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            cursor: pointer;
            margin-right: 1.25rem;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            background: rgba(255, 255, 255, 0.03);
            padding: 0.5rem 1.25rem;
            border-radius: 99px;
            border: 1px solid var(--border);
        }

        .user-name {
            font-weight: 600;
            color: var(--text);
            text-shadow: 0 0 10px rgba(255,255,255,0.2);
            font-size: 0.95rem;
        }

        .logout-btn {
            background: transparent;
            border: 1px solid var(--secondary);
            color: var(--secondary);
            padding: 0.4rem 1rem;
            border-radius: 99px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s;
            text-shadow: 0 0 5px var(--secondary);
            box-shadow: 0 0 10px rgba(236, 72, 153, 0.1);
        }

        .logout-btn:hover {
            background: var(--secondary);
            color: white;
            box-shadow: 0 0 20px rgba(236, 72, 153, 0.4);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; padding: 1.5rem; }
            .menu-toggle { display: block; }
            .hidden-mobile { display: none; }

            [dir="rtl"] .sidebar { transform: translateX(100%); }
            [dir="rtl"] .sidebar.open { transform: translateX(0); }
        }
        /* Overlay & Modals */
        .overlay { 
            display: none; 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(0,0,0,0.6); 
            backdrop-filter: blur(8px); 
            z-index: 1000; 
            overflow-y: auto;
            padding: 2rem 1rem;
        }
        .overlay.show { display: block; }

        .modal-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-radius: 1.5rem;
            padding: 2rem;
            border: 1px solid var(--border);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            max-width: 550px;
            margin: 0 auto;
            position: relative;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 0 10px var(--primary-glow);
        }

        .close-modal {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close-modal:hover { color: white; }

        /* Form Controls */
        label { display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.85rem; font-weight: 500; }
        input, select, textarea {
            width: 100%;
            padding: 0.85rem 1rem;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            color: white;
            font-family: inherit;
            transition: all 0.3s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px var(--primary-glow);
            background: rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <nav class="sidebar" id="sidebar">
        <div class="brand">
            <span>🐾</span> {{ __('admin.admin_panel') }}
        </div>
        
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> {{ __('admin.dashboard') }}
        </a>

        @if(Auth::user()->isSuperAdmin())
            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span> {{ __('admin.users') }}
            </a>
        @endif

        @if(Auth::user()->isSuperAdmin() || Auth::user()->isDataEntry())
            <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <span class="nav-icon">🛍️</span> {{ __('admin.products') }}
            </a>
        @endif

        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAccountant() || Auth::user()->isDataEntry())
            <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <span class="nav-icon">📦</span> {{ __('admin.orders') }}
            </a>
        @endif

        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAccountant())
            <a href="{{ route('admin.payments.index') }}" class="nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <span class="nav-icon">💰</span> {{ __('admin.payments') }}
            </a>
            <a href="{{ route('admin.service_prices.index') }}" class="nav-item {{ request()->routeIs('admin.service_prices.*') ? 'active' : '' }}">
                <span class="nav-icon">💲</span> {{ __('admin.service_prices') }}
            </a>
        @endif

        @if(Auth::user()->isSuperAdmin() || Auth::user()->isDataEntry())
            <a href="{{ route('admin.hotel_bookings.index') }}" class="nav-item {{ request()->routeIs('admin.hotel_bookings.*') ? 'active' : '' }}">
                <span class="nav-icon">🏨</span> {{ __('admin.hotel_booking') }}
            </a>
            <a href="{{ route('admin.shave_bath_bookings.index') }}" class="nav-item {{ request()->routeIs('admin.shave_bath_bookings.*') ? 'active' : '' }}">
                <span class="nav-icon">🛁</span> {{ __('admin.shave_bath') }}
            </a>
            <a href="{{ route('admin.top_rated.index') }}" class="nav-item {{ request()->routeIs('admin.top_rated.*') ? 'active' : '' }}">
                <span class="nav-icon">⭐</span> {{ __('admin.top_rated') }}
            </a>
            <a href="{{ route('admin.sliders.index') }}" class="nav-item {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                <span class="nav-icon">🎠</span> {{ __('admin.slider_offers') }}
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <span class="nav-icon">🔔</span> {{ __('admin.notifications') }}
            </a>
        @endif

        @if(Auth::user()->isSuperAdmin())
            <a href="{{ route('admin.chats.index') }}" class="nav-item {{ request()->routeIs('admin.chats.*') ? 'active' : '' }}">
                <span class="nav-icon">💬</span> {{ __('admin.chat_requests') }}
            </a>
            <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span> {{ __('admin.settings') }}
            </a>
        @endif
    </nav>

    <main class="main-content">
        <header class="header">
            <div style="display: flex; align-items: center;">
                <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
                <h2 style="margin: 0;">@yield('title', __('admin.dashboard'))</h2>
            </div>
            
            <div style="display: flex; align-items: center; gap: 1rem;">
                <!-- Language Switcher -->
                <div class="user-menu" style="padding: 0.25rem 0.75rem;">
                    <a href="{{ route('admin.set_language', 'en') }}" style="text-decoration: none; color: {{ app()->getLocale() == 'en' ? 'var(--primary)' : 'var(--text-muted)' }}; font-weight: 600; font-size: 0.8rem;">EN</a>
                    <span style="color: var(--border);">|</span>
                    <a href="{{ route('admin.set_language', 'ar') }}" style="text-decoration: none; color: {{ app()->getLocale() == 'ar' ? 'var(--primary)' : 'var(--text-muted)' }}; font-weight: 600; font-size: 0.8rem;">AR</a>
                </div>

                <div class="user-menu">
                    <span class="hidden-mobile">{{ Auth::user()->name }}</span>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">{{ __('admin.logout') }}</button>
                    </form>
                </div>
            </div>
        </header>

        @yield('content')
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('show');
        }
    </script>
</body>
</html>
