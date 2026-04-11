<x-layouts.app title="Daftar">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-xl-4 col-lg-5 col-md-7 col-sm-10">

            {{-- Card Panel --}}
            <div class="bg-white rounded-4 shadow-sm p-4 p-md-5">

                        {{-- Header --}}
                        <div class="text-center mb-4">
                            <span class="badge rounded-pill px-3 py-2 mb-3"
                                style="background-color: #f5e6ea; color: #7a1230; font-size: 0.75rem; letter-spacing: 0.05em;">
                                AKUN PENYEWA
                            </span>
                            <h1 class="fw-bold mb-2" style="font-size: 1.6rem; color: #1a1a1a;">
                                Daftar untuk mulai booking
                            </h1>
                            <p class="text-muted" style="font-size: 0.875rem; line-height: 1.6;">
                                Setelah mendaftar, Anda langsung menerima sesi aktif dan bisa memesan busana yang tersedia.
                            </p>
                        </div>

                        {{-- Form --}}
                        <form action="{{ route('auth.register.store') }}" method="POST">
                            @csrf

                            {{-- Nama Lengkap --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 0.85rem;">
                                    Nama Lengkap
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control rounded-3 @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
                                    placeholder="Masukkan nama lengkap Anda"
                                    autocomplete="name"
                                    required
                                    style="padding: 0.65rem 1rem; border-color: #e0e0e0; font-size: 0.9rem;"
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 0.85rem;">
                                    Email
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control rounded-3 @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="nama@email.com"
                                    autocomplete="email"
                                    required
                                    style="padding: 0.65rem 1rem; border-color: #e0e0e0; font-size: 0.9rem;"
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Password Row --}}
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark" style="font-size: 0.85rem;">
                                        Password
                                    </label>
                                    <input
                                        type="password"
                                        name="password"
                                        class="form-control rounded-3 @error('password') is-invalid @enderror"
                                        placeholder="Min. 8 karakter"
                                        autocomplete="new-password"
                                        required
                                        style="padding: 0.65rem 1rem; border-color: #e0e0e0; font-size: 0.9rem;"
                                    >
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark" style="font-size: 0.85rem;">
                                        Konfirmasi Password
                                    </label>
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        class="form-control rounded-3"
                                        placeholder="Ulangi password"
                                        autocomplete="new-password"
                                        required
                                        style="padding: 0.65rem 1rem; border-color: #e0e0e0; font-size: 0.9rem;"
                                    >
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="d-grid">
                                <button
                                    type="submit"
                                    class="btn fw-semibold rounded-3 py-2"
                                    style="background-color: #7a1230; color: #fff; font-size: 0.95rem; letter-spacing: 0.02em; transition: opacity 0.2s;"
                                    onmouseover="this.style.opacity='0.88'"
                                    onmouseout="this.style.opacity='1'"
                                >
                                    Buat Akun
                                </button>
                            </div>
                        </form>

                        {{-- Divider --}}
                        <hr class="my-4" style="border-color: #f0f0f0;">

                        {{-- Footer --}}
                        <p class="text-center text-muted mb-0" style="font-size: 0.875rem;">
                            Sudah punya akun?
                            <a href="{{ route('auth.login') }}"
                               class="fw-semibold text-decoration-none"
                               style="color: #7a1230;">
                                Login di sini
                            </a>
                        </p>

                    </div>
            </div>
            {{-- End Card --}}
        </div>
    </div>
</x-layouts.app>