<x-layouts.app title="Register">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6">
            <div class="auth-panel">
                <p class="eyebrow mb-2">Akun Penyewa</p>
                <h1 class="display-title mb-3">Daftar untuk mulai booking</h1>
                <p class="text-muted mb-4">Setelah mendaftar, Anda langsung menerima sesi berbasis JWT dan bisa memesan busana yang tersedia.</p>

                <form action="{{ route('auth.register.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="col-12 d-grid">
                        <button type="submit" class="btn btn-accent rounded-pill">Buat Akun</button>
                    </div>
                </form>

                <div class="mt-4 small text-muted">
                    Sudah punya akun? <a href="{{ route('auth.login') }}" class="link-dark fw-semibold">Login di sini</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
