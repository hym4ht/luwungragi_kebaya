<x-layouts.app title="Daftar">
    <section class="auth-register-simple">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8">
                <div class="auth-panel auth-register-simple__panel">
                    <p class="eyebrow mb-2">Akun Penyewa</p>
                    <h1 class="display-title mb-3">Daftar untuk mulai booking</h1>
                    <p class="auth-register-simple__copy mb-4">
                        Setelah mendaftar, Anda langsung menerima sesi aktif dan bisa memesan busana yang tersedia dengan tampilan yang selaras dengan nuansa landing page.
                    </p>

                    <form action="{{ route('auth.register.store') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label class="form-label auth-register-simple__label">Nama Lengkap</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control auth-register-simple__input @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap Anda"
                                autocomplete="name"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label auth-register-simple__label">Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control auth-register-simple__input @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                autocomplete="email"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label auth-register-simple__label">Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control auth-register-simple__input @error('password') is-invalid @enderror"
                                placeholder="Minimal 8 karakter"
                                autocomplete="new-password"
                                required
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label auth-register-simple__label">Konfirmasi Password</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control auth-register-simple__input"
                                placeholder="Ulangi password"
                                autocomplete="new-password"
                                required
                            >
                        </div>

                        <div class="col-12 d-grid pt-2">
                            <button type="submit" class="btn btn-home-primary auth-register-simple__submit">Buat Akun</button>
                        </div>
                    </form>

                    <div class="auth-register-simple__footer mt-4">
                        Sudah punya akun? <a href="{{ route('auth.login') }}" class="auth-register-simple__link">Login di sini</a>
                    </div>
                </div>
            </div>
        </div>    
    </section>
</x-layouts.app>
