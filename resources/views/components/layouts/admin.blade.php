@props([
    'title' => 'Admin Luwungragi',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('components.layouts.partials.head', ['title' => $title])
<body style="background-color: var(--bg-cream, #FDFBF7); font-family: 'Plus Jakarta Sans', sans-serif;">
    <div class="d-flex" style="min-height: 100vh;">
        
        <!-- Sidebar -->
        <x-admin-sidebar />

        <!-- Main Content -->
        <main class="flex-grow-1" style="background-color: #FAF8F5; border-top-left-radius: 40px; border-bottom-left-radius: 40px; box-shadow: -5px 0 25px rgba(0,0,0,0.03); min-height: 100vh; margin-left: 260px; padding: 2.5rem 3rem;">
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
