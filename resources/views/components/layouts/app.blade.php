@props([
    'title' => 'Luwungragi',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('components.layouts.partials.head', ['title' => $title])
<body>
    <div class="site-shell">
        @include('components.layouts.partials.navbar')

        <main class="container py-4 py-lg-5">
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-4">{{ session('error') }}</div>
            @endif

            {{ $slot }}
        </main>

        @include('components.layouts.partials.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
