@php
    $user = auth()->user();
@endphp

<x-layouts.owner title="Profil Owner">

    <div class="owner-page-header">
        <h1>Profil Owner</h1>
        <p>Kelola informasi akun dan keamanan profil Anda.</p>
    </div>

    <div class="row g-4">
        {{-- Profile Card --}}
        <div class="col-lg-4">
            <div class="owner-panel text-center" style="padding-bottom:2rem;">
                <div style="background: linear-gradient(135deg, #580d21, #9b3a4a); height:75px; border-radius:12px 12px 0 0;"></div>
                <div style="margin-top:-34px; margin-bottom:1rem;">
                    <div style="width:68px;height:68px;border-radius:50%;background:var(--brand-maroon,#580d21);display:inline-flex;align-items:center;justify-content:center;font-weight:800;font-size:1.6rem;color:#fff;border:4px solid #fff;box-shadow:0 4px 14px rgba(88,13,33,0.25);">
                        {{ strtoupper(substr($user->name ?? 'O', 0, 1)) }}
                    </div>
                </div>
                <div style="font-size:1.1rem;font-weight:700;color:#1a0a0e;">{{ $user->name }}</div>
                <div style="font-size:0.82rem;color:#a49791;margin-bottom:0.75rem;">{{ $user->email }}</div>
                <span style="background:rgba(88,13,33,0.08);color:#580d21;font-size:0.72rem;font-weight:700;letter-spacing:0.7px;text-transform:uppercase;padding:0.3rem 0.9rem;border-radius:50px;border:1px solid rgba(88,13,33,0.15);">
                    Owner / Pemilik
                </span>
                <div style="margin-top:1.5rem;padding:0 1.5rem;">
                    <div style="background:#fdfbf7;border-radius:10px;padding:0.8rem 1rem;display:flex;justify-content:space-between;align-items:center;border:1px solid rgba(88,13,33,0.06);">
                        <span style="font-size:0.8rem;color:#79665e;">Bergabung</span>
                        <span style="font-size:0.8rem;font-weight:700;color:#4a3540;">{{ $user->created_at?->translatedFormat('d M Y') ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Profile Form --}}
        <div class="col-lg-8">
            <div class="owner-panel mb-4">
                <div class="owner-panel-header">
                    <h2 class="owner-panel-title"><i class="bi bi-person-fill me-2" style="color:#580d21;"></i>Informasi Pribadi</h2>
                </div>
                <div class="owner-panel-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" style="font-size:0.8rem;font-weight:600;color:#4a3540;">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}"
                                       style="border:1px solid rgba(88,13,33,0.2); font-size:0.875rem;">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label" style="font-size:0.8rem;font-weight:600;color:#4a3540;">Email</label>
                                <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}"
                                       style="border:1px solid rgba(88,13,33,0.2); font-size:0.875rem;">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-dark rounded-pill px-4">
                                    <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Change Password --}}
            <div class="owner-panel">
                <div class="owner-panel-header">
                    <h2 class="owner-panel-title"><i class="bi bi-shield-lock-fill me-2" style="color:#580d21;"></i>Ubah Password</h2>
                </div>
                <div class="owner-panel-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" style="font-size:0.8rem;font-weight:600;color:#4a3540;">Password Saat Ini</label>
                                <input type="password" name="current_password" class="form-control rounded-3 @error('current_password') is-invalid @enderror"
                                       placeholder="Masukkan password lama"
                                       style="border:1px solid rgba(88,13,33,0.2); font-size:0.875rem;">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:0.8rem;font-weight:600;color:#4a3540;">Password Baru</label>
                                <input type="password" name="password" class="form-control rounded-3 @error('password') is-invalid @enderror"
                                       placeholder="Masukkan password baru"
                                       style="border:1px solid rgba(88,13,33,0.2); font-size:0.875rem;">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:0.8rem;font-weight:600;color:#4a3540;">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control rounded-3"
                                       placeholder="Ulangi password baru"
                                       style="border:1px solid rgba(88,13,33,0.2); font-size:0.875rem;">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-dark rounded-pill px-4">
                                    <i class="bi bi-lock-fill me-1"></i> Ubah Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Logout --}}
    <div class="row g-4 mt-0">
        <div class="col-12">
            <div class="owner-panel" style="border-color:rgba(88,13,33,0.15);">
                <div class="owner-panel-header" style="background:#fdfbf7;">
                    <h2 class="owner-panel-title" style="color:#580d21;"><i class="bi bi-door-open-fill me-2"></i>Keluar dari Sistem</h2>
                </div>
                <div class="owner-panel-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <div style="font-size:0.875rem;font-weight:600;color:#4a3540;">Logout Akun</div>
                            <div style="font-size:0.8rem;color:#79665e;">Anda akan keluar dari sesi ini dan diarahkan ke halaman login.</div>
                        </div>
                        <form action="{{ route('auth.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-dark rounded-pill px-4">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.owner>
