<x-layouts.admin title="Pengaturan Akun">
    <div class="bg-light w-100" style="min-height: 100vh;">
        
        <div class="mb-5">
            <h1 class="h2 fw-bold mb-2" style="color: var(--brand-maroon, #580d21); font-family: 'Playfair Display', serif;">Pengaturan Akun</h1>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">Kelola informasi profil dan keamanan akun Anda.</p>
        </div>



        @if (session('status') === 'password-updated')
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert">
                <i class="bi bi-shield-check me-2"></i> Kata sandi berhasil diperbarui.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            
            <!-- Left Side: Profile Information -->
            <div class="col-lg-6">
                <div class="card border-0 rounded-4 bg-white shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-person-badge fs-5" style="color: var(--brand-maroon, #580d21);"></i>
                            <h5 class="fw-bold mb-0" style="color: #3f0917;">Informasi Profil</h5>
                        </div>
                        <p class="text-muted small mb-0">Perbarui informasi akun dan alamat email Anda.</p>
                        <hr class="mb-0 mt-3" style="border-color: #f3f4f6;">
                    </div>
                    
                    <div class="card-body p-4">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4 text-center">
                                <div class="rounded-circle bg-dark overflow-hidden shadow-sm mx-auto mb-3" style="width: 100px; height: 100px;">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1f2937&color=fff&size=100" alt="Avatar" class="w-100 h-100 object-fit-cover">
                                </div>
                                <div class="small fw-semibold text-muted">{{ $user->role->value ?? $user->role }}</div>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Nama Lengkap</label>
                                <input type="text" class="form-control rounded-0 px-3 py-2 border-1 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Alamat Email</label>
                                <input type="email" class="form-control rounded-0 px-3 py-2 border-1 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn text-white fw-bold rounded-0 px-4" style="background-color: var(--brand-maroon, #580d21); letter-spacing: 0.5px;">
                                    SIMPAN PROFIL
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Side: Change Password -->
            <div class="col-lg-6">
                <div class="card border-0 rounded-4 bg-white shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-shield-lock fs-5" style="color: var(--brand-maroon, #580d21);"></i>
                            <h5 class="fw-bold mb-0" style="color: #3f0917;">Ubah Kata Sandi</h5>
                        </div>
                        <p class="text-muted small mb-0">Pastikan akun Anda menggunakan kata sandi panjang dan acak agar tetap aman.</p>
                        <hr class="mb-0 mt-3" style="border-color: #f3f4f6;">
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="current_password" class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Kata Sandi Saat Ini</label>
                                <input type="password" class="form-control rounded-0 px-3 py-2 border-1 @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Kata Sandi Baru</label>
                                <input type="password" class="form-control rounded-0 px-3 py-2 border-1 @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" class="form-control rounded-0 px-3 py-2 border-1" id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <div class="d-flex justify-content-end mt-auto">
                                <button type="submit" class="btn text-dark bg-white border fw-bold rounded-0 px-4" style="letter-spacing: 0.5px; border-color: #e5e7eb !important;">
                                    PERBARUI SANDI
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layouts.admin>
