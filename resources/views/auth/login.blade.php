<x-layouts.app title="Login">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-xl-4 col-lg-5 col-md-7 col-sm-10">
            <div class="auth-panel" style="
                background: #fff;
                border-radius: 16px;
                padding: 2.5rem;
                box-shadow: 0 4px 32px rgba(88,13,33,0.08);
                border: 1px solid rgba(88,13,33,0.08);
            ">
                {{-- Logo / Brand --}}
                <div class="text-center mb-4">
                    <span style="
                        display: inline-block;
                        background: #580d21;
                        color: #fff;
                        font-family: 'Playfair Display', serif;
                        font-size: 1.1rem;
                        letter-spacing: 2px;
                        padding: 8px 22px;
                        border-radius: 50px;
                        margin-bottom: 1rem;
                    ">LUWUNGRAGI</span>
                    <h1 style="
                        font-family: 'Playfair Display', serif;
                        font-size: 1.5rem;
                        color: #1a1a1a;
                        font-weight: 700;
                        margin-bottom: 0.25rem;
                    ">Selamat Datang</h1>
                    <p class="text-muted" style="font-size: 0.875rem;">Masuk ke akun Anda untuk melanjutkan</p>
                </div>

                {{-- Form --}}
                <form action="{{ route('auth.login.store') }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-12">
                        <label class="form-label" style="font-size:0.85rem; font-weight:600; color:#580d21;">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            placeholder="contoh@email.com"
                            required
                            style="
                                border-radius: 8px;
                                border: 1.5px solid #e0d6d9;
                                padding: 0.65rem 1rem;
                                font-size: 0.9rem;
                                transition: border-color 0.2s;
                            "
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label" style="font-size:0.85rem; font-weight:600; color:#580d21;">Password</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            required
                            style="
                                border-radius: 8px;
                                border: 1.5px solid #e0d6d9;
                                padding: 0.65rem 1rem;
                                font-size: 0.9rem;
                                transition: border-color 0.2s;
                            "
                        >
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
                                style="border-color: #580d21;"
                            >
                            <label for="remember" class="form-check-label" style="font-size:0.85rem; color:#555;">
                                Ingat saya di perangkat ini
                            </label>
                        </div>
                    </div>

                    <div class="col-12 d-grid mt-1">
                        <button type="submit" style="
                            background: #580d21;
                            color: #fff;
                            border: none;
                            border-radius: 50px;
                            padding: 0.7rem;
                            font-size: 0.9rem;
                            font-weight: 600;
                            letter-spacing: 1px;
                            cursor: pointer;
                            transition: background 0.2s, transform 0.1s;
                        "
                        onmouseover="this.style.background='#7a1230'"
                        onmouseout="this.style.background='#580d21'"
                        >
                            MASUK
                        </button>
                    </div>
                </form>

                <hr style="border-color: rgba(88,13,33,0.1); margin: 1.75rem 0;">

                <div class="text-center" style="font-size:0.85rem; color:#888;">
                    Belum punya akun?
                    <a href="{{ route('auth.register') }}" style="color:#580d21; font-weight:600; text-decoration:none;">
                        Daftar sebagai penyewa
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>