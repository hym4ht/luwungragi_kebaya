<x-layouts.admin title="Dashboard Admin">
    <div class="bg-light w-100" style="min-height: 100vh;">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-5">
            <div>
                <h1 class="h2 fw-bold mb-2" style="color: var(--brand-maroon, #580d21); font-family: 'Playfair Display', serif;">Ringkasan Operasional</h1>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Selamat datang kembali, {{ auth()->user()->name }}. Berikut aktivitas Luwungragi hari ini.</p>
            </div>
            
            <div class="d-flex justify-content-end align-items-center gap-3 w-100 w-md-auto">
                <div class="input-group flex-grow-1 flex-md-grow-0 rounded-0 border" style="max-width: 100%; width: 280px;">
                    <span class="input-group-text bg-white border-0 ps-4 pe-2 rounded-0" id="search-addon">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-0 py-2 pe-4 rounded-0" placeholder="Cari transaksi..." aria-label="Cari transaksi" aria-describedby="search-addon" style="font-size: 0.9rem;">
                </div>
                
                <div class="rounded-0 bg-dark overflow-hidden shadow-sm flex-shrink-0" style="width: 45px; height: 45px;">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=1f2937&color=fff" alt="Admin" class="w-100 h-100 object-fit-cover">
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 rounded-4 bg-white h-100 p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="text-uppercase text-muted fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">Total Penyewaan Aktif</div>
                        <i class="bi bi-calendar-event fs-5" style="color: var(--brand-maroon, #580d21);"></i>
                    </div>
                    <h2 class="display-5 fw-bold mb-2 pb-1" style="color: #3f0917;">{{ $summary['active_rentals'] }}</h2>
                    <div class="text-success small fw-semibold" style="font-size: 0.85rem;">
                        <i class="bi bi-arrow-up-right"></i> +12% dibanding minggu lalu
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 rounded-4 bg-white h-100 p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="text-uppercase text-muted fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">Pembayaran Menunggu</div>
                        <i class="bi bi-wallet2 text-dark fs-5"></i>
                    </div>
                    <h2 class="display-5 fw-bold mb-2 pb-1" style="color: #3f0917;">{{ $summary['pending_payments'] }}</h2>
                    <div class="text-muted small" style="font-size: 0.85rem;">Perlu verifikasi admin</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 rounded-4 h-100 p-4 shadow-sm" style="background-color: var(--brand-maroon, #580d21); color: white;">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.75rem; color: #e6c5a1;">Pendapatan Bulan Ini</div>
                        <i class="bi bi-cash-stack fs-5" style="color: #e6c5a1;"></i>
                    </div>
                    <h2 class="display-5 fw-bold mb-2 pb-1 text-white">Rp {{ number_format((float) $summary['monthly_revenue'] / 1000000, 1, ',', '.') }} Jt</h2>
                    @php
                        $monthlyTarget = 10000000;
                        $revenuePercent = min(100, round(((float) $summary['monthly_revenue'] / $monthlyTarget) * 100));
                    @endphp
                    <div class="progress mt-auto rounded-pill" style="height: 5px; background-color: rgba(255,255,255,0.2);">
                        <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $revenuePercent }}%; background-color: #e6c5a1;" aria-valuenow="{{ $revenuePercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="small mt-3 fw-bold" style="font-size: 0.65rem; color: #e6c5a1; letter-spacing: 0.5px;">{{ $revenuePercent }}% target bulanan tercapai</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 rounded-4 bg-white h-100 p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                        <h5 class="fw-bold mb-0" style="color: var(--brand-maroon, #580d21); font-family: 'Playfair Display', serif;">Transaksi Terbaru</h5>
                        <a href="{{ route('admin.rentals.index') }}" class="text-decoration-none text-muted fw-bold small" style="letter-spacing: 1px; font-size: 0.75rem;">LIHAT SEMUA <i class="bi bi-chevron-right ms-1"></i></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead>
                                <tr class="text-uppercase fw-bold bg-white" style="font-size: 0.7rem; color: #6b7280; letter-spacing: 1px; border-bottom: 2px solid #e5e7eb;">
                                    <th class="py-3 ps-3 rounded-0">Pelanggan</th>
                                    <th class="py-3">Busana</th>
                                    <th class="py-3">Tanggal</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3 text-end pe-3 rounded-0">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentRentals as $rental)
                                    @php
                                        $initials = strtoupper(substr($rental->user->name, 0, 2));
                                        $bgColors = ['bg-danger-subtle text-danger', 'bg-secondary-subtle text-secondary', 'bg-dark-subtle text-dark'];
                                        $bgColor = $bgColors[(crc32($initials) % 3)];
                                        
                                        if ($rental->status->value === 'pending') {
                                            $statusHtml = '<span class="badge rounded-0" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; letter-spacing: 0.5px; font-size: 0.65rem; padding: 0.5em 0.8em; font-weight: 700;">MENUNGGU</span>';
                                        } elseif ($rental->status->value === 'active') {
                                            $statusHtml = '<span class="badge rounded-0" style="background-color: #631024; color: white; letter-spacing: 0.5px; font-size: 0.65rem; padding: 0.5em 0.8em; font-weight: 700;">BERJALAN</span>';
                                        } elseif ($rental->status->value === 'completed' || $rental->status->value === 'returned') {
                                            $statusHtml = '<span class="badge rounded-0" style="background-color: #f3f4f6; color: #4b5563; border: 1px solid #e5e7eb; letter-spacing: 0.5px; font-size: 0.65rem; padding: 0.5em 0.8em; font-weight: 700;">SELESAI</span>';
                                        } else {
                                            $statusHtml = '<span class="badge rounded-0 bg-white text-muted border" style="letter-spacing: 0.5px; font-size: 0.65rem; padding: 0.5em 0.8em; font-weight: 700;">'.strtoupper($rental->status->value).'</span>';
                                        }
                                    @endphp
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td class="py-3 ps-2">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-0 d-flex align-items-center justify-content-center fw-bold {{ $bgColor }}" style="width: 38px; height: 38px; font-size: 0.85rem;">
                                                    {{ $initials }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $rental->user->name }}</div>
                                                    <div class="text-muted" style="font-size: 0.75rem;">#{{ $rental->invoice_number }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 text-muted" style="font-size: 0.85rem;">
                                            <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                                {{ $rental->details->pluck('costume.name')->join(', ') }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-muted" style="font-size: 0.85rem;">
                                            {{ $rental->created_at->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="py-3">
                                            {!! $statusHtml !!}
                                        </td>
                                        <td class="py-3 text-end pe-2">
                                            <a href="{{ route('admin.rentals.show', $rental) }}" class="btn btn-sm btn-link text-danger fs-5 py-0 px-1 text-decoration-none">
                                                <i class="bi bi-three-dots-vertical" style="color: var(--brand-maroon, #580d21);"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 rounded-4 bg-white mb-4 p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0" style="color: #3f0917; font-family: 'Plus Jakarta Sans', sans-serif;">Notifikasi</h5>
                        @if($notifications->count() > 0)
                            <span class="badge rounded-0 bg-danger fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">{{ $notifications->count() }} BARU</span>
                        @endif
                    </div>
                    
                    @forelse($notifications as $notification)
                    <div class="d-flex gap-3 pb-3 mb-3" style="border-bottom: 1px solid #f3f4f6;">
                        <div class="rounded-0 {{ $notification['bg_color'] }} d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                            <i class="bi {{ $notification['icon'] }}"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $notification['title'] }}</div>
                            <p class="text-muted small mb-1" style="font-size: 0.8rem; line-height: 1.4;">{{ $notification['message'] }}</p>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ $notification['time'] }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-bell-slash fs-2 mb-2 d-block text-muted opacity-50"></i>
                        <p class="small mb-0">Belum ada notifikasi baru</p>
                    </div>
                    @endforelse
                </div>

                <div class="card border-0 rounded-4 p-4 position-relative overflow-hidden bg-white shadow-sm">
                    <i class="bi bi-shield-check position-absolute" style="font-size: 10rem; right: -2rem; top: -1rem; opacity: 0.03; color: var(--brand-maroon, #580d21); z-index: 1;"></i>
                    <div class="position-relative" style="z-index: 2;">
                        <h5 class="fw-bold mb-3" style="color: var(--brand-maroon, #580d21);">Verifikasi Pembayaran</h5>
                        <p class="text-dark mb-4" style="font-size: 0.9rem;">Ada <strong style="color: var(--brand-maroon, #580d21);">{{ $summary['pending_payments'] }} bukti pembayaran</strong> yang menunggu verifikasi manual.</p>
                        <a href="{{ route('admin.rentals.index', ['status' => 'pending']) }}" class="btn text-white fw-bold d-inline-flex align-items-center gap-2 rounded-0 w-100 justify-content-center" style="background-color: var(--brand-maroon, #580d21); font-size: 0.75rem; letter-spacing: 0.5px; padding: 0.8rem 1.25rem;">
                            MULAI VERIFIKASI <i class="bi bi-shield-lock-fill ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layouts.admin>