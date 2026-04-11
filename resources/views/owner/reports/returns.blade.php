<x-layouts.owner title="Riwayat Pengembalian">

    <div class="owner-page-header">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <h1>Riwayat Pengembalian</h1>
                <p>Daftar seluruh pengembalian busana beserta status dan denda keterlambatan.</p>
            </div>
            <div class="d-flex gap-2">
                <form method="GET" action="{{ route('owner.reports.returns') }}" class="d-flex gap-2">
                    <input type="month" name="month" class="form-control form-control-sm rounded-pill" value="{{ $selectedMonth }}"
                           style="border:1px solid rgba(88,13,33,0.2); font-size:0.85rem;">
                    <button type="submit" class="btn btn-sm btn-dark rounded-pill px-3">
                        <i class="bi bi-funnel-fill"></i> Filter
                    </button>
                </form>
                <button onclick="window.print()" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                    <i class="bi bi-printer-fill"></i> Cetak
                </button>
            </div>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="owner-stat-card" style="--card-accent:#580d21; --icon-bg:rgba(88,13,33,0.08); --icon-color:#580d21;">
                <div class="stat-icon-wrap"><i class="bi bi-arrow-return-left"></i></div>
                <div class="stat-label">Total Pengembalian</div>
                <div class="stat-value">{{ $returns->count() }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="owner-stat-card" style="--card-accent:#580d21; --icon-bg:rgba(88,13,33,0.08); --icon-color:#580d21;">
                <div class="stat-icon-wrap"><i class="bi bi-check2-circle"></i></div>
                <div class="stat-label">Tepat Waktu</div>
                <div class="stat-value">{{ $returns->filter(fn($r) => !$r->fine_amount || $r->fine_amount <= 0)->count() }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="owner-stat-card" style="--card-accent:#9b3a4a; --icon-bg:rgba(155,58,74,0.09); --icon-color:#9b3a4a;">
                <div class="stat-icon-wrap"><i class="bi bi-clock-history"></i></div>
                <div class="stat-label">Terlambat</div>
                <div class="stat-value">{{ $returns->filter(fn($r) => $r->fine_amount > 0)->count() }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="owner-stat-card" style="--card-accent:#9b3a4a; --icon-bg:rgba(155,58,74,0.09); --icon-color:#9b3a4a;">
                <div class="stat-icon-wrap"><i class="bi bi-cash-coin"></i></div>
                <div class="stat-label">Total Denda</div>
                <div class="stat-value" style="font-size:1.05rem;">Rp{{ number_format((float)$returns->sum('fine_amount'), 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Returns Table --}}
    <div class="owner-panel">
        <div class="owner-panel-header">
            <h2 class="owner-panel-title">
                <i class="bi bi-arrow-return-left me-2" style="color:#580d21;"></i>
                Data Pengembalian — {{ \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->translatedFormat('F Y') }}
            </h2>
        </div>
        <div style="overflow-x:auto;">
            <table class="owner-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Invoice</th>
                        <th>Penyewa</th>
                        <th>Busana</th>
                        <th>Tgl Kembali Rencana</th>
                        <th>Tgl Aktual Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th>Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($returns as $index => $returnRecord)
                        <tr>
                            <td style="color:#a49791;font-size:0.78rem;">{{ $index + 1 }}</td>
                            <td>
                                <span style="font-weight:700;color:#1a0a0e;font-family:monospace;font-size:0.78rem;background:#fdfbf7;padding:0.2rem 0.5rem;border-radius:5px;display:inline-block;">
                                    {{ $returnRecord->rental->invoice_number ?? '—' }}
                                </span>
                            </td>
                            <td style="font-weight:600;font-size:0.85rem;color:#1a0a0e;">{{ $returnRecord->rental->user->name ?? '—' }}</td>
                            <td style="font-size:0.82rem;color:#79665e;max-width:160px;">
                                {{ $returnRecord->rental?->details?->pluck('costume.name')->join(', ') ?? '—' }}
                            </td>
                            <td style="font-size:0.82rem;color:#79665e;">
                                {{ $returnRecord->rental?->return_date ? \Carbon\Carbon::parse($returnRecord->rental->return_date)->format('d M Y') : '—' }}
                            </td>
                            <td style="font-size:0.82rem;color:#79665e;">
                                {{ $returnRecord->returned_date ? \Carbon\Carbon::parse($returnRecord->returned_date)->format('d M Y') : '—' }}
                            </td>
                            <td>
                                @if($returnRecord->fine_amount > 0)
                                    <span class="badge rounded-pill bg-warning text-dark px-3 py-2">
                                        <i class="bi bi-clock-history me-1"></i> Terlambat
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-success px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i> Tepat waktu
                                    </span>
                                @endif
                            </td>
                            <td style="font-weight:700;font-size:0.85rem;color:{{ ($returnRecord->fine_amount ?? 0) > 0 ? '#9b3a4a' : '#a49791' }};">
                                @if(($returnRecord->fine_amount ?? 0) > 0)
                                    Rp{{ number_format((float)$returnRecord->fine_amount, 0, ',', '.') }}
                                @else
                                    <span style="color:#a49791;">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2" style="background:rgba(88,13,33,0.07);color:#580d21;font-size:0.72rem;">
                                    {{ $returnRecord->return_status ?? '—' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="text-center py-5" style="color:#a49791;">
                                    <i class="bi bi-inbox d-block mb-2" style="font-size:2.5rem;"></i>
                                    <div style="font-weight:600;margin-bottom:0.25rem;">Tidak ada data pengembalian</div>
                                    <div style="font-size:0.82rem;">Belum ada pengembalian pada bulan yang dipilih.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.owner>
