@props([
    'title' => 'Owner Portal',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Luwungragi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/luwungragi.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <style>
        /* ─── BRAND VARIABLES (same as admin) ─── */
        :root {
            --brand-maroon: #580d21;
            --brand-maroon-hover: rgba(88, 13, 33, 0.05);
            --brand-maroon-active: #580d21;
            --bg-cream: #FDFBF7;
            --text-warm: #79665e;
            --text-section: #a49791;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
        }

        /* ─── SIDEBAR (mirrors admin-sidebar exactly) ─── */
        @media (min-width: 992px) {
            .owner-sidebar {
                width: var(--sidebar-width) !important;
                position: fixed !important;
                top: 0; left: 0;
                height: 100vh;
                background-color: var(--bg-cream) !important;
                display: flex;
                flex-direction: column;
                padding: 2.5rem 1.5rem;
                z-index: 1000;
                overflow-y: auto;
                border-right: none !important;
            }
        }

        @media (max-width: 991.98px) {
            .owner-sidebar {
                width: var(--sidebar-width) !important;
                background-color: var(--bg-cream) !important;
                padding: 1.5rem;
                display: flex;
                flex-direction: column;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                position: fixed;
                top: 0; left: 0;
                height: 100vh;
                z-index: 1041;
                overflow-y: auto;
            }

            .owner-sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.35);
                z-index: 1040;
            }

            .sidebar-overlay.show {
                display: block;
            }

            .owner-mobile-header {
                display: flex !important;
            }
        }

        .owner-sidebar::-webkit-scrollbar { width: 6px; }
        .owner-sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(88, 13, 33, 0.2);
            border-radius: 4px;
        }

        /* ─── BRAND (same as .admin-brand) ─── */
        .owner-brand-logo {
            font-family: 'Playfair Display', serif;
            color: var(--brand-maroon);
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            margin-bottom: 0.25rem;
            display: block;
        }

        .owner-brand-subtitle {
            font-size: 0.75rem;
            color: var(--text-warm);
            margin-bottom: 2.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* ─── NAV LINKS (same as .admin-nav-link) ─── */
        .owner-nav-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 1rem;
            color: var(--text-warm);
            text-decoration: none;
            font-weight: 500;
            border-radius: 8px;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .owner-nav-link i {
            font-size: 1.1rem;
            color: var(--text-warm);
            transition: color 0.2s ease;
            width: 20px;
            text-align: center;
        }

        .owner-nav-link:hover {
            background-color: var(--brand-maroon-hover);
            color: var(--brand-maroon);
        }

        .owner-nav-link:hover i {
            color: var(--brand-maroon);
        }

        .owner-nav-link.active {
            background-color: var(--brand-maroon-active);
            color: #fff !important;
        }

        .owner-nav-link.active i {
            color: #fff;
        }

        /* Collapse arrow (for sub-menu toggle) */
        .owner-nav-link .nav-arrow {
            margin-left: auto;
            font-size: 0.7rem;
            transition: transform 0.2s ease;
            color: var(--text-section);
        }

        .owner-nav-link[aria-expanded="true"] .nav-arrow {
            transform: rotate(90deg);
        }

        /* Sub-nav indent */
        .owner-sub-nav {
            padding-left: 2.3rem;
        }

        .owner-sub-nav .owner-nav-link {
            font-size: 0.85rem;
            padding: 0.6rem 1rem;
        }

        /* ─── SECTION LABELS (same as .nav-section-title) ─── */
        .owner-nav-section {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-section);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 1.5rem 0 0.5rem 0.5rem;
        }

        /* ─── SIDEBAR FOOTER ─── */
        .owner-sidebar-footer {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1.5px solid rgba(0,0,0,0.03);
        }

        /* ─── MAIN CONTENT (same as .admin-main-content) ─── */
        .owner-main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding: 1.5rem;
            width: 100%;
        }

        @media (min-width: 992px) {
            .owner-main-content {
                box-shadow: -5px 0 25px rgba(0,0,0,0.03);
                margin-left: var(--sidebar-width);
                padding: 2.5rem 3rem;
                width: calc(100% - var(--sidebar-width));
            }
        }

        /* ─── MOBILE HEADER (same as .mobile-header) ─── */
        .owner-mobile-header {
            display: none;
            align-items: center;
            justify-content: space-between;
            background-color: #ffffff;
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* ─── OWNER-SPECIFIC UI COMPONENTS ─── */

        /* Stat cards — warm cream palette matching sidebar */
        .owner-stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.4rem 1.5rem;
            border: 1px solid rgba(88,13,33,0.08);
            box-shadow: 0 1px 4px rgba(88,13,33,0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .owner-stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--card-accent, var(--brand-maroon));
        }

        .owner-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(88,13,33,0.08);
        }

        .stat-icon-wrap {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            margin-bottom: 0.9rem;
            background: var(--icon-bg, rgba(88,13,33,0.08));
            color: var(--icon-color, var(--brand-maroon));
        }

        .stat-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: var(--text-section);
            margin-bottom: 0.3rem;
        }

        .stat-value {
            font-size: 1.55rem;
            font-weight: 800;
            color: #1a0a0e;
            line-height: 1.1;
        }

        .stat-helper {
            font-size: 0.75rem;
            color: var(--text-section);
            margin-top: 0.25rem;
        }

        .stat-trend {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.2rem 0.55rem;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            margin-top: 0.5rem;
        }

        .stat-trend.up   { background: #dcfce7; color: #16a34a; }
        .stat-trend.neutral { background: rgba(88,13,33,0.06); color: var(--brand-maroon); }

        /* Panels */
        .owner-panel {
            background: #fff;
            border-radius: 12px;
            border: 1px solid rgba(88,13,33,0.07);
            box-shadow: 0 1px 4px rgba(88,13,33,0.04);
            overflow: hidden;
        }

        .owner-panel-header {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid rgba(88,13,33,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .owner-panel-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a0a0e;
            margin: 0;
        }

        .owner-panel-body {
            padding: 1.5rem;
        }

        /* Table */
        .owner-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .owner-table th {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: var(--text-section);
            padding: 0 1rem 0.75rem;
            text-align: left;
            border-bottom: 1px solid rgba(88,13,33,0.07);
        }

        .owner-table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f8f4f0;
            color: #4a3540;
            vertical-align: middle;
        }

        .owner-table tr:last-child td { border-bottom: none; }
        .owner-table tr:hover td { background: #fdfbf7; }

        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.28rem 0.75rem;
            border-radius: 50px;
            font-size: 0.71rem;
            font-weight: 600;
        }

        /* Chart wrap */
        .chart-wrap { position: relative; width: 100%; }

        /* Page header */
        .owner-page-header { margin-bottom: 1.75rem; }
        .owner-page-header h1 {
            font-size: 1.45rem;
            font-weight: 700;
            color: #1a0a0e;
            margin: 0 0 0.2rem;
        }
        .owner-page-header p {
            color: var(--text-warm);
            font-size: 0.875rem;
            margin: 0;
        }

        /* Alerts */
        .owner-alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .owner-alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        /* User info block (under brand) */
        .owner-user-info {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            background: rgba(88,13,33,0.05);
            border-radius: 10px;
            padding: 0.6rem 0.85rem;
            margin-bottom: 1.5rem;
        }

        .owner-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--brand-maroon);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.82rem;
            color: #fff;
            flex-shrink: 0;
        }

        .owner-user-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--brand-maroon);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .owner-user-role {
            font-size: 0.68rem;
            color: var(--text-warm);
        }
    </style>
</head>
<body>
    <!-- Mobile Header -->
    <div class="owner-mobile-header d-lg-none" id="ownerMobileHeader">
        <div style="font-family: 'Playfair Display', serif; color: var(--brand-maroon); font-size: 1.25rem; font-weight: 700;">Luwungragi</div>
        <button class="btn btn-sm border-0 shadow-none px-0" id="sidebarToggle" aria-label="Buka menu">
            <i class="bi bi-list" style="font-size: 1.75rem; color: var(--brand-maroon);"></i>
        </button>
    </div>

    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex" style="min-height: 100vh;">

        <!-- ─── SIDEBAR ─── -->
        <aside class="owner-sidebar" id="ownerSidebar">

            <!-- Close button (mobile) -->
            <div class="d-lg-none d-flex justify-content-between align-items-center mb-4">
                <div>
                    <div class="owner-brand-logo mb-0" style="display:block!important;">Luwungragi</div>
                    <div class="owner-brand-subtitle mb-0" style="display:block!important;">Portal Pemilik</div>
                </div>
                <button type="button" id="sidebarClose" class="btn-close shadow-none" aria-label="Tutup"></button>
            </div>

            <!-- Brand (desktop) -->
            <a href="{{ route('owner.dashboard') }}" class="owner-brand-logo d-none d-lg-block">Luwungragi</a>
            <div class="owner-brand-subtitle d-none d-lg-block">Portal Pemilik</div>

            <!-- User info -->
            <div class="owner-user-info">
                <div class="owner-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'O', 0, 1)) }}</div>
                <div style="overflow:hidden;">
                    <div class="owner-user-name">{{ auth()->user()->name ?? 'Owner' }}</div>
                    <div class="owner-user-role">Owner / Pemilik</div>
                </div>
            </div>

            <!-- Nav -->
            <nav class="d-flex flex-column mb-4 flex-grow-1">
                @php
                    $currentRoute = request()->route() ? request()->route()->getName() : '';
                @endphp

                <!-- Dashboard -->
                <a href="{{ route('owner.dashboard') }}"
                   class="owner-nav-link {{ $currentRoute === 'owner.dashboard' ? 'active' : '' }}">
                    <i class="bi bi-pie-chart{{ $currentRoute === 'owner.dashboard' ? '-fill' : '' }}"></i>
                    Dashboard Eksekutif
                </a>

                <!-- Laporan section -->
                <div class="owner-nav-section">Laporan</div>

                <a href="{{ route('owner.reports.financial') }}"
                   class="owner-nav-link {{ $currentRoute === 'owner.reports.financial' ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i>
                    Laporan Keuangan
                </a>

                <!-- Laporan sub-menu toggle -->
                <button class="owner-nav-link {{ in_array($currentRoute, ['owner.reports.transactions','owner.reports.top-items','owner.reports.returns']) ? 'active' : '' }}"
                        data-bs-toggle="collapse" data-bs-target="#collapseReportOps"
                        aria-expanded="{{ in_array($currentRoute, ['owner.reports.transactions','owner.reports.top-items','owner.reports.returns']) ? 'true' : 'false' }}">
                    <i class="bi bi-clipboard-data{{ in_array($currentRoute, ['owner.reports.transactions','owner.reports.top-items','owner.reports.returns']) ? '-fill' : '' }}"></i>
                    Laporan
                    <i class="bi bi-chevron-right nav-arrow"></i>
                </button>
                <div class="collapse {{ in_array($currentRoute, ['owner.reports.transactions','owner.reports.top-items','owner.reports.returns']) ? 'show' : '' }}"
                     id="collapseReportOps">
                    <div class="owner-sub-nav">
                        <a href="{{ route('owner.reports.transactions') }}"
                           class="owner-nav-link {{ $currentRoute === 'owner.reports.transactions' ? 'active' : '' }}">
                            <i class="bi bi-receipt{{ $currentRoute === 'owner.reports.transactions' ? '-cutoff' : '' }}"></i>
                            Riwayat Transaksi
                        </a>
                        <a href="{{ route('owner.reports.top-items') }}"
                           class="owner-nav-link {{ $currentRoute === 'owner.reports.top-items' ? 'active' : '' }}">
                            <i class="bi bi-star{{ $currentRoute === 'owner.reports.top-items' ? '-fill' : '' }}"></i>
                            Busana Terlaris
                        </a>
                        <a href="{{ route('owner.reports.returns') }}"
                           class="owner-nav-link {{ $currentRoute === 'owner.reports.returns' ? 'active' : '' }}">
                            <i class="bi bi-arrow-return-left"></i>
                            Riwayat Pengembalian
                        </a>
                    </div>
                </div>

                <!-- Akun section -->
                <div class="owner-nav-section">Akun Saya</div>

                <a href="{{ route('owner.profile') }}"
                   class="owner-nav-link {{ $currentRoute === 'owner.profile' ? 'active' : '' }}">
                    <i class="bi bi-person{{ $currentRoute === 'owner.profile' ? '-fill' : '' }}"></i>
                    Profil Owner
                </a>

            </nav>

            <!-- Footer: Logout -->
            <div class="owner-sidebar-footer">
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="owner-nav-link border-0 w-100 text-start" style="background: none;">
                        <i class="bi bi-box-arrow-right"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- ─── MAIN CONTENT ─── -->
        <main class="flex-grow-1 owner-main-content">
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-4">{{ session('error') }}</div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar toggle
        const ownerSidebar  = document.getElementById('ownerSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarToggle  = document.getElementById('sidebarToggle');
        const sidebarClose   = document.getElementById('sidebarClose');

        function openSidebar() {
            ownerSidebar.classList.add('show');
            sidebarOverlay.classList.add('show');
        }

        function closeSidebar() {
            ownerSidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        }

        if (sidebarToggle)  sidebarToggle.addEventListener('click', openSidebar);
        if (sidebarClose)   sidebarClose.addEventListener('click', closeSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);
    </script>
    @stack('scripts')
</body>
</html>
