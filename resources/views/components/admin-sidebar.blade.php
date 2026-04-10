@php
    $currentRoute = request()->route() ? request()->route()->getName() : '';
    $userRole = auth()->check() ? (auth()->user()->role->value ?? auth()->user()->role) : 'admin';
@endphp

<style>
    .admin-sidebar {
        width: 280px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        background-color: var(--bg-cream, #FDFBF7);
        display: flex;
        flex-direction: column;
        padding: 2.5rem 1.5rem;
        z-index: 1000;
        overflow-y: auto;
    }
    .admin-sidebar::-webkit-scrollbar {
        width: 6px;
    }
    .admin-sidebar::-webkit-scrollbar-thumb {
        background-color: rgba(88, 13, 33, 0.2);
        border-radius: 4px;
    }
    .admin-brand {
        font-family: 'Playfair Display', serif;
        color: var(--brand-maroon, #580d21);
        font-size: 1.5rem;
        font-weight: 700;
        text-decoration: none;
        margin-bottom: 0.25rem;
        display: block;
    }
    .admin-brand-subtitle {
        font-size: 0.75rem;
        color: #79665e;
        margin-bottom: 2.5rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .admin-nav-link {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.75rem 1rem;
        color: #79665e;
        text-decoration: none;
        font-weight: 500;
        border-radius: 8px;
        margin-bottom: 0.25rem;
        transition: all 0.2s ease;
        font-size: 0.9rem;
        background: transparent;
    }
    .admin-nav-link:hover {
        background-color: rgba(88, 13, 33, 0.05);
        color: var(--brand-maroon, #580d21);
    }
    .admin-nav-link.active {
        background-color: var(--brand-maroon, #580d21);
        color: #fff !important;
    }
    .admin-nav-link.active i {
        color: #fff;
    }
    .admin-nav-link i {
        font-size: 1.1rem;
        color: #79665e;
        transition: color 0.2s ease;
        width: 20px;
        text-align: center;
    }
    .admin-nav-link:hover i {
        color: var(--brand-maroon, #580d21);
    }
    
    .sidebar-footer {
        margin-top: auto;
        padding-top: 1rem;
    }
    
    .nav-section-title {
        font-size: 0.7rem;
        font-weight: 700;
        color: #a49791;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin: 1.5rem 0 0.5rem 0.5rem;
    }
    
    .badge-pending {
        margin-left: auto;
        font-size: 0.65rem;
        padding: 0.35em 0.6em;
    }
</style>

<div class="admin-sidebar">
    <a href="{{ $userRole === 'admin' ? route('admin.dashboard') : route('owner.dashboard') }}" class="admin-brand">Luwungragi</a>
    <div class="admin-brand-subtitle">{{ $userRole === 'admin' ? 'Panel Manajemen' : 'Portal Pemilik' }}</div>

    <nav class="d-flex flex-column mb-4">
        @if ($userRole === 'admin')
            <!-- ADMIN MENU -->
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ $currentRoute === 'admin.dashboard' ? 'active' : '' }}">
                <i class="bi bi-grid{{ $currentRoute === 'admin.dashboard' ? '-fill' : '' }}"></i>
                Ringkasan
            </a>
            
            <a href="{{ route('admin.costumes.index') }}" class="admin-nav-link {{ str_starts_with($currentRoute, 'admin.costumes') ? 'active' : '' }}">
                <i class="bi bi-box-seam{{ str_starts_with($currentRoute, 'admin.costumes') ? '-fill' : '' }}"></i>
                Kelola Katalog
            </a>
            
            <a href="{{ route('admin.customers.index') }}" class="admin-nav-link {{ str_starts_with($currentRoute, 'admin.customers') ? 'active' : '' }}">
                <i class="bi bi-people{{ str_starts_with($currentRoute, 'admin.customers') ? '-fill' : '' }}"></i>
                Pelanggan
            </a>
            
            <a href="{{ route('admin.rentals.index') }}" class="admin-nav-link {{ str_starts_with($currentRoute, 'admin.rentals') ? 'active' : '' }}">
                <i class="bi bi-receipt{{ str_starts_with($currentRoute, 'admin.rentals') ? '-cutoff' : '' }}"></i>
                Transaksi
                @php
                    // Display badge for pending payments or rentals if desired
                    $pendingCount = \App\Models\Rental::where('status', 'pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="badge bg-danger rounded-pill badge-pending">{{ $pendingCount }}</span>
                @endif
            </a>
            
            <a href="{{ route('reports.index') }}" class="admin-nav-link {{ $currentRoute === 'reports.index' ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line{{ $currentRoute === 'reports.index' ? '-fill' : '' }}"></i>
                Laporan
            </a>

        @else
            <!-- OWNER MENU -->
            <a href="{{ route('owner.dashboard') }}" class="admin-nav-link {{ $currentRoute === 'owner.dashboard' ? 'active' : '' }}">
                <i class="bi bi-pie-chart{{ $currentRoute === 'owner.dashboard' ? '-fill' : '' }}"></i>
                Dashboard Eksekutif
            </a>

            <div class="nav-section-title">Laporan</div>

            <a href="{{ route('reports.index', ['type' => 'revenue']) }}" class="admin-nav-link {{ $currentRoute === 'reports.index' && request('type') !== 'all' ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i>
                Laporan Keuangan
            </a>

            <a href="{{ route('reports.index', ['type' => 'all']) }}" class="admin-nav-link {{ $currentRoute === 'reports.index' && request('type') === 'all' ? 'active' : '' }}">
                <i class="bi bi-clipboard-data"></i>
                Laporan Operasional
            </a>
            
        @endif
    </nav>

    <div class="sidebar-footer border-top border-2" style="border-color: rgba(0,0,0,0.03) !important;">
        <a href="#" class="admin-nav-link" style="margin-bottom: 0.25rem;">
            <i class="bi bi-gear"></i>
            Pengaturan
        </a>
        <form action="{{ route('auth.logout') }}" method="POST">
            @csrf
            <button type="submit" class="admin-nav-link border-0 w-100 text-start" style="background: none;">
                <i class="bi bi-box-arrow-right"></i>
                Keluar
            </button>
        </form>
    </div>
</div>
