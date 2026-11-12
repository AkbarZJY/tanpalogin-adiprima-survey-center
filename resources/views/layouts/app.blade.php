<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title', 'Adiprima Survey Center - PT Adiprima Suraprinta')</title>
    <!-- Favicon (Icon Only without text) -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/svg+xml" href="{{ asset('images/adiprima-icon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/adiprima-icon.svg') }}">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --color-navy-primary: #0C2B64;
            --color-navy-dark: #071C43;
            --color-navy-light: #16469D;
            --color-blue-accent: #1EA1E5;
            --color-blue-light: #EFF6FF;
            --color-coral-primary: #FF4757;
            --color-coral-light: #FFEAEB;
            --color-green-accent: #0D9444;
            --color-bg-light: #F8FAFC;
            --color-border: #E2E8F0;
            --font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        html {
            scroll-behavior: auto !important;
            overflow-anchor: none;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--color-bg-light);
            color: #1E293B;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            overflow-anchor: none;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
        }

        /* Top Header Navigation */
        .top-navbar {
            background-color: #FFFFFF;
            border-bottom: 1px solid var(--color-border);
            height: 66px;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            backdrop-filter: blur(8px);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .btn-hamburger {
            display: none;
            background: #F8FAFC;
            border: 1px solid var(--color-border);
            color: var(--color-navy-primary);
            font-size: 1.35rem;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .btn-hamburger:active {
            transform: scale(0.92);
            background: #E2E8F0;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            text-decoration: none;
            color: var(--color-navy-primary);
        }

        .brand-logo-img {
            height: 40px;
            width: auto;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            flex-shrink: 0;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            font-weight: 900;
            font-size: 1.05rem;
            letter-spacing: -0.02em;
            color: #1EA1E5;
            line-height: 1.15;
        }

        .brand-subtitle {
            font-size: 0.675rem;
            color: #64748B;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background-color: #F1F5F9;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.825rem;
            font-weight: 600;
            color: var(--color-navy-primary);
            border: 1px solid #E2E8F0;
        }

        .user-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-navy-primary), var(--color-blue-accent));
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        .user-name-text {
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .btn-logout {
            background: #FFFFFF;
            border: 1px solid var(--color-border);
            color: #64748B;
            padding: 0.45rem 0.75rem;
            border-radius: 8px;
            font-size: 0.825rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            transition: all 0.2s ease;
            min-height: 38px;
        }

        .btn-logout:hover, .btn-logout:active {
            background-color: #FEE2E2;
            color: #DC2626;
            border-color: #FCA5A5;
        }

        .main-wrapper {
            display: flex;
            flex: 1;
            position: relative;
            min-height: calc(100vh - 64px);
        }

        /* Sidebar for Admin */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, var(--color-navy-primary) 0%, var(--color-navy-dark) 100%);
            color: #FFFFFF;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            flex-shrink: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }

        .sidebar-menu-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            color: #CBD5E1;
            text-decoration: none;
            font-size: 0.885rem;
            font-weight: 500;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 42px;
        }

        .sidebar-menu-item:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #FFFFFF;
            transform: translateX(3px);
        }

        .sidebar-menu-item.active {
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.4) 0%, rgba(37, 99, 235, 0.15) 100%);
            border-left: 3px solid #60A5FA;
            color: #FFFFFF;
            font-weight: 700;
        }

        .sidebar-menu-item i {
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .sidebar-section-title {
            font-size: 0.675rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94A3B8;
            margin: 1.15rem 0 0.35rem 0.85rem;
            font-weight: 800;
        }

        .content-area {
            flex: 1;
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            min-width: 0;
        }

        /* Universal Clean Pagination & Navigation */
        .custom-pagination-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.85rem;
            width: 100%;
        }

        .custom-pagination-info {
            font-size: 0.825rem;
            color: #64748B;
            font-weight: 500;
        }

        .custom-pagination-info .pagination-highlight {
            font-weight: 700;
            color: var(--color-navy-primary);
        }

        .custom-pagination-list,
        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: wrap;
        }

        .custom-page-item,
        .page-item {
            display: inline-block;
        }

        .custom-page-link,
        .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0.25rem 0.6rem;
            border-radius: 8px;
            border: 1px solid var(--color-border);
            background-color: #FFFFFF;
            color: #334155;
            font-size: 0.825rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.15s ease;
            box-sizing: border-box;
        }

        .custom-page-link i,
        .page-link i {
            font-size: 0.75rem;
            stroke-width: 1px;
        }

        .custom-page-link:hover,
        .page-link:hover {
            background-color: #F1F5F9;
            border-color: #CBD5E1;
            color: var(--color-navy-primary);
        }

        .custom-page-item.active .custom-page-link,
        .page-item.active .page-link {
            background-color: var(--color-navy-primary);
            border-color: var(--color-navy-primary);
            color: #FFFFFF;
            box-shadow: 0 2px 6px rgba(12, 43, 100, 0.2);
        }

        .custom-page-item.disabled .custom-page-link,
        .page-item.disabled .page-link {
            background-color: #F8FAFC;
            border-color: #E2E8F0;
            color: #94A3B8;
            cursor: not-allowed;
            opacity: 0.65;
        }

        .custom-page-link.dots {
            border: none;
            background: transparent;
            min-width: 24px;
            padding: 0;
        }

        /* SVG Sizing Safety for any external paginators */
        nav[role="navigation"] svg,
        .pagination svg {
            width: 14px !important;
            height: 14px !important;
            max-width: 14px !important;
            max-height: 14px !important;
            display: inline-block !important;
            vertical-align: middle;
        }

        @media (max-width: 640px) {
            .custom-pagination-nav {
                flex-direction: column;
                justify-content: center;
                text-align: center;
                gap: 0.65rem;
            }
            .custom-pagination-list,
            .pagination {
                justify-content: center;
                width: 100%;
            }
            .custom-page-link,
            .page-link {
                min-width: 32px;
                height: 32px;
                padding: 0.2rem 0.5rem;
                font-size: 0.8rem;
            }
        }

        /* Mobile Drawer Sidebar Overlay */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
            z-index: 1100;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-backdrop.active {
            display: block;
            opacity: 1;
        }

        /* Mobile Scroll Helper for Tables */
        .table-responsive-wrapper {
            position: relative;
            width: 100%;
            border-radius: 12px;
            border: 1px solid var(--color-border);
            background: #FFFFFF;
            overflow: hidden;
        }

        .table-touch-hint {
            display: none;
            padding: 0.4rem 0.75rem;
            background: #EFF6FF;
            color: #1E40AF;
            font-size: 0.75rem;
            font-weight: 600;
            border-bottom: 1px solid #DBEAFE;
            align-items: center;
            gap: 0.35rem;
        }

        @media (max-width: 900px) {
            .btn-hamburger {
                display: inline-flex;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: -290px;
                bottom: 0;
                width: 280px;
                z-index: 1200;
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 15px 0 35px rgba(0, 0, 0, 0.3);
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                padding: 1.25rem 1rem;
            }

            .sidebar.mobile-open {
                transform: translateX(290px);
            }

            .sidebar-header-mobile {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-bottom: 1rem;
                margin-bottom: 0.75rem;
                border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            }

            .sidebar-close-btn {
                background: rgba(255, 255, 255, 0.12);
                border: none;
                color: #FFFFFF;
                width: 36px;
                height: 36px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.15rem;
                cursor: pointer;
            }

            .sidebar-close-btn:active {
                background: rgba(255, 255, 255, 0.25);
            }

            .top-navbar {
                padding: 0 1rem;
                height: 58px;
            }

            .content-area {
                padding: 1.25rem 0.85rem;
            }

            .brand-subtitle {
                display: none;
            }

            .table-touch-hint {
                display: flex;
            }
        }

        @media (max-width: 480px) {
            .user-name-text {
                display: none;
            }
            .user-pill {
                padding: 0.25rem;
                background: transparent;
                border: none;
            }
            .btn-logout span {
                display: none;
            }
            .btn-logout {
                padding: 0.45rem 0.65rem;
                min-height: 36px;
            }
            .top-navbar {
                padding: 0 0.65rem;
            }
            .brand-title {
                font-size: 0.95rem;
            }
            .brand-icon {
                width: 34px;
                height: 34px;
                font-size: 1.05rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Top Navigation Bar -->
    <header class="top-navbar">
        <div class="navbar-left">
            @auth
                @if(Auth::user()->isAdmin())
                <button type="button" class="btn-hamburger" onclick="toggleMobileSidebar()" aria-label="Buka Menu Navigasi">
                    <i class="bi bi-list"></i>
                </button>
                @endif
            @endauth

            <a href="{{ route('home') }}" class="brand-logo" title="PT Adiprima Suraprinta - Survey Center">
                <img src="{{ asset('images/adiprima-icon.svg') }}" alt="Logo PT Adiprima Suraprinta" class="brand-logo-img">
                <div class="brand-text">
                    <span class="brand-title">PT ADIPRIMA SURAPRINTA</span>
                    <span class="brand-subtitle">SURVEY CENTER &bull; JAWA POS GROUP</span>
                </div>
            </a>
        </div>

        @auth
        <div class="nav-actions">
            <div class="user-pill">
                <div class="user-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>
                <span class="user-name-text">{{ Auth::user()->name }} @if(Auth::user()->isAdmin()) <small style="color:var(--color-navy-primary); font-weight:800;">(Admin)</small> @endif</span>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout" title="Keluar dari akun">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
        @else
        <div class="nav-actions">
            <a href="{{ route('login') }}" style="background: var(--color-navy-primary); color: #FFFFFF; text-decoration: none; padding: 0.45rem 1rem; border-radius: 8px; font-size: 0.825rem; font-weight: 700; display: flex; align-items: center; gap: 0.35rem; min-height: 38px; transition: all 0.2s ease;" title="Login Admin">
                <i class="bi bi-shield-lock-fill"></i>
                <span>Admin Login</span>
            </a>
        </div>
        @endauth
    </header>

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeMobileSidebar()"></div>

    <div class="main-wrapper">
        @auth
            @if(Auth::user()->isAdmin())
            <aside class="sidebar" id="appSidebar">
                <div class="sidebar-header-mobile" style="display: none;" id="sidebarMobileHeader">
                    <div style="display: flex; align-items: center; gap: 0.65rem; color: #FFFFFF;">
                        <img src="{{ asset('images/adiprima-icon.svg') }}" alt="Logo" style="width: 30px; height: 30px; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                        <span style="font-weight: 800; font-size: 0.95rem;">Navigasi Admin</span>
                    </div>
                    <button type="button" class="sidebar-close-btn" onclick="closeMobileSidebar()">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="sidebar-section-title">Menu Utama</div>
                <a href="{{ route('home') }}" class="sidebar-menu-item {{ request()->routeIs('home') ? 'active' : '' }}" onclick="closeMobileSidebar()">
                    <i class="bi bi-house-door-fill"></i> Beranda
                </a>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}" onclick="closeMobileSidebar()">
                    <i class="bi bi-bar-chart-line-fill"></i> Dashboard Analytics
                </a>

                <div class="sidebar-section-title">Manajemen Kuesioner</div>
                <a href="{{ route('admin.survey-categories.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.survey-categories*') ? 'active' : '' }}" onclick="closeMobileSidebar()">
                    <i class="bi bi-tags-fill"></i> Kategori Survei
                </a>
                <a href="{{ route('admin.surveys.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.surveys*') ? 'active' : '' }}" onclick="closeMobileSidebar()">
                    <i class="bi bi-kanban-fill"></i> Kelola Survei
                </a>
                <a href="{{ route('admin.question-bank.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.question-bank*') ? 'active' : '' }}" onclick="closeMobileSidebar()">
                    <i class="bi bi-collection-fill"></i> Bank Template Soal
                </a>
                <a href="{{ route('admin.dimensions.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.dimensions*') ? 'active' : '' }}" onclick="closeMobileSidebar()">
                    <i class="bi bi-diagram-3-fill"></i> Dimensi Indikator
                </a>
                <a href="{{ route('admin.rating-scales.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.rating-scales*') ? 'active' : '' }}" onclick="closeMobileSidebar()">
                    <i class="bi bi-star-half"></i> Master Skala
                </a>

                <div class="sidebar-section-title">Akses Kuesioner</div>
                @if(isset($sidebarActiveSurveys) && $sidebarActiveSurveys->count() > 0)
                    @foreach($sidebarActiveSurveys as $activeSurvey)
                        <a href="{{ route('survey.form', $activeSurvey->slug) }}" 
                           class="sidebar-menu-item {{ request()->is('survey/' . $activeSurvey->slug . '*') ? 'active' : '' }}" 
                           onclick="closeMobileSidebar()"
                           title="{{ $activeSurvey->title }}">
                            <i class="bi {{ $activeSurvey->icon ?: 'bi-file-earmark-text-fill' }}"></i>
                            <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block; max-width: 175px;">
                                {{ $activeSurvey->title }}
                            </span>
                        </a>
                    @endforeach
                @else
                    <div style="padding: 0.5rem 1rem; color: #94A3B8; font-size: 0.775rem; display: flex; align-items: center; gap: 0.4rem;">
                        <i class="bi bi-info-circle"></i> <span>Tidak ada survei aktif</span>
                    </div>
                @endif
            </aside>
            @endif
        @endauth

        <main class="content-area" id="mainContentArea">
            @if(session('success'))
                <div style="background-color: #DEF7EC; border: 1px solid #84E1BC; color: #03543F; padding: 0.85rem 1rem; border-radius: 10px; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.6rem; font-size: 0.9rem; font-weight: 500;">
                    <i class="bi bi-check-circle-fill" style="color: #10B981; font-size: 1.1rem; flex-shrink: 0;"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div style="background-color: #FDE8E8; border: 1px solid #F8B4B4; color: #9B1C1C; padding: 0.85rem 1rem; border-radius: 10px; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.6rem; font-size: 0.9rem; font-weight: 500;">
                    <i class="bi bi-exclamation-triangle-fill" style="color: #EF4444; font-size: 1.1rem; flex-shrink: 0;"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div style="background-color: #FDE8E8; border: 1px solid #F8B4B4; color: #9B1C1C; padding: 0.85rem 1rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: 0.875rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; margin-bottom: 0.35rem;">
                        <i class="bi bi-exclamation-octagon-fill" style="color: #EF4444; font-size: 1.1rem;"></i>
                        <span>Terjadi Kesalahan Validasi:</span>
                    </div>
                    <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.85rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('appSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const headerMobile = document.getElementById('sidebarMobileHeader');
            if (sidebar && backdrop) {
                const isOpen = sidebar.classList.contains('mobile-open');
                if (isOpen) {
                    closeMobileSidebar();
                } else {
                    sidebar.classList.add('mobile-open');
                    backdrop.classList.add('active');
                    if (headerMobile) headerMobile.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('appSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar && backdrop) {
                sidebar.classList.remove('mobile-open');
                backdrop.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    </script>
    @yield('scripts')
</body>
</html>
