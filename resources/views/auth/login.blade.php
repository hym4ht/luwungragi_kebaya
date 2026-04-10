<x-layouts.app title="Login">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6">
            <div class="auth-panel">
                <p class="eyebrow mb-2">Autentikasi JWT</p>
                <h1 class="display-title mb-3">Login ke sistem Luwungragi</h1>
                <p class="text-muted mb-4">Admin, owner, dan penyewa masuk melalui token JWT yang dijaga custom middleware.</p>

                <form action="{{ route('auth.login.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" autocomplete="email" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" autocomplete="current-password" required>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                                id="remember"
                                class="form-check-input"
                                @checked(old('remember'))
                            >
                            <label for="remember" class="form-check-label">Ingat saya di perangkat ini</label>
                        </div>
                    </div>
                    <div class="col-12 d-grid">
                        <button type="submit" class="btn btn-accent rounded-pill">Login</button>
                    </div>
                </form>

                <hr class="my-4">

                <div class="demo-credentials">
                    <div class="fw-semibold mb-2">Akun demo</div>
                    <div class="small text-muted mb-1">Admin: <code>admin@luwungragi.test</code> / <code>password123</code></div>
                    <div class="small text-muted mb-1">Owner: <code>owner@luwungragi.test</code> / <code>password123</code></div>
                    <div class="small text-muted">Customer: <code>ratri@luwungragi.test</code> / <code>password123</code></div>
                </div>

                <div class="mt-4 small text-muted">
                    Belum punya akun? <a href="{{ route('auth.register') }}" class="link-dark fw-semibold">Daftar sebagai penyewa</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
