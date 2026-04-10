@php
    $homeUrl = route('home');
    $isHome = request()->routeIs('home');
@endphp

<style>
    .brand-logo {
        font-family: 'Playfair Display', serif;
        color: var(--brand-maroon, #580d21);
        font-size: 1.5rem;
        font-weight: 700;
        text-decoration: none;
        letter-spacing: 1px;
    }
    .brand-logo:hover {
        color: var(--brand-maroon, #580d21);
    }
    .nav-link-custom {
        color: var(--text-muted, #79665e);
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        text-decoration: none;
        transition: color 0.3s ease;
        padding: 0.5rem 0 !important;
    }
    .nav-link-custom:hover {
        color: var(--brand-maroon, #580d21);
    }
    .nav-link-custom.active {
        color: var(--brand-maroon, #580d21);
        border-bottom: 2px solid var(--brand-maroon, #580d21);
    }
    .site-header {
        padding: 1.5rem 0;
        background: var(--bg-cream, #FDFBF7);
        border-bottom: none;
        z-index: 1030;
    }
    .btn-join {
        background-color: var(--brand-maroon, #580d21);
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 0.6rem 1.5rem;
        border: 1px solid var(--brand-maroon, #580d21);
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-join:hover {
        background-color: #3f0917;
        color: white;
    }
</style>

<nav class="navbar navbar-expand-lg site-header w-100 sticky-top">
    <div class="container-fluid px-4 px-lg-5">
        <a class="navbar-brand brand-logo" href="{{ $homeUrl }}">Luwungragi</a>
        
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#appNavbar" aria-controls="appNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="appNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-5 align-items-lg-center mt-3 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ $isHome ? 'active' : '' }}" href="{{ $isHome ? '#home' : $homeUrl }}">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="{{ $isHome ? '#about' : $homeUrl.'#about' }}">ABOUT</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="{{ $isHome ? '#catalog' : $homeUrl.'#catalog' }}">CATALOG</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="{{ $isHome ? '#contact' : $homeUrl.'#contact' }}">CONTACT</a>
                </li>
            </ul>

            <div class="d-flex align-items-lg-center gap-4 flex-column flex-lg-row pb-3 pb-lg-0">
                @if (auth()->check())
                    @php $role = auth()->user()->role->value ?? auth()->user()->role; @endphp
                    <span class="text-muted small d-lg-none">
                        Login sebagai: <strong class="text-dark">{{ auth()->user()->name }}</strong>
                    </span>

                    @if($role === 'customer')
                        {{-- Customer: link ke pesanan --}}
                        <a href="{{ route('customer.orders') }}" class="nav-link-custom" style="border-bottom:none; color:var(--brand-maroon,#580d21);">PESANAN SAYA</a>
                    @else
                        {{-- Admin / Owner: ke dashboard --}}
                        <a href="{{ route('dashboard') }}" class="nav-link-custom" style="border-bottom:none; color:var(--brand-maroon,#580d21);">DASHBOARD</a>
                    @endif

                    <form action="{{ route('auth.logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn-join" style="cursor:pointer; border:none; width:100%;">LOGOUT</button>
                    </form>
                @else
                    <a href="{{ route('auth.login') }}" class="nav-link-custom" style="padding: 0 !important;">SIGN IN</a>
                    <a href="{{ route('auth.register') }}" class="btn-join text-center">JOIN</a>
                @endif
            </div>
        </div>
    </div>
</nav>
