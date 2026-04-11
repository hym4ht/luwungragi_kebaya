@props([
    'title' => 'Admin Luwungragi',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('components.layouts.partials.head', ['title' => $title])
<body style="background-color: #ffffff; font-family: 'Plus Jakarta Sans', sans-serif;">
    <style>
        .admin-main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding: 1.5rem;
            width: 100%;
        }
        
        @media (min-width: 992px) {
            .admin-main-content {
                box-shadow: -5px 0 25px rgba(0,0,0,0.03);
                margin-left: 280px;
                padding: 2.5rem 3rem;
                width: calc(100% - 280px);
            }
        }

        .mobile-header {
            display: none;
        }
        
        @media (max-width: 991.98px) {
            .mobile-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background-color: #ffffff;
                padding: 1rem 1.5rem;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                position: sticky;
                top: 0;
                z-index: 999;
            }
        }
    </style>

    <!-- Mobile Header -->
    <div class="mobile-header d-lg-none">
        <div style="font-family: 'Playfair Display', serif; color: var(--brand-maroon, #580d21); font-size: 1.25rem; font-weight: 700;">Luwungragi</div>
        <button class="btn btn-sm border-0 shadow-none px-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar">
            <i class="bi bi-list" style="font-size: 1.75rem; color: var(--brand-maroon, #580d21);"></i>
        </button>
    </div>

    <div class="d-flex" style="min-height: 100vh;">
        
        <!-- Sidebar -->
        <x-admin-sidebar />

        <!-- Main Content -->
        <main class="flex-grow-1 admin-main-content">
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-4">{{ session('error') }}</div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
