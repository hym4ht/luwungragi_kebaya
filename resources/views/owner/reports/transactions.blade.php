<x-layouts.owner title="Riwayat Semua Transaksi">

    <div class="owner-page-header">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <h1>Riwayat Semua Transaksi</h1>
                <p>Daftar lengkap seluruh transaksi penyewaan busana Luwungragi.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <form method="GET" action="{{ route('owner.reports.transactions') }}" class="d-flex gap-2">
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
                <div class="stat-icon-wrap"><i class="bi bi-receipt-cutoff"></i></div>
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value">{{ $report['total_transactions'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="owner-stat-card" style="--card-accent:#79665e; --icon-bg:rgba(121,102,94,0.1); --icon-color:#79665e;">
                <div class="stat-icon-wrap"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-label">Aktif</div>
                <div class="stat-value">{{ $report['active_transactions'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="owner-stat-card" style="--card-accent:#580d21; --icon-bg:rgba(88,13,33,0.08); --icon-color:#580d21;">
                <div class="stat-icon-wrap"><i class="bi bi-check-circle-fill"></i></div>
                <div class="stat-label">Selesai</div>
                <div class="stat-value">{{ $report['completed_transactions'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="owner-stat-card" style="--card-accent:#9b3a4a; --icon-bg:rgba(155,58,74,0.09); --icon-color:#9b3a4a;">
                <div class="stat-icon-wrap"><i class="bi bi-clock-history"></i></div>
                <div class="stat-label">Pending</div>
                <div class="stat-value">{{ $report['pending_transactions'] }}</div>
            </div>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="owner-panel">
        <div class="owner-panel-header">
            <h2 class="owner-panel-title">
                <i class="bi bi-list-ul me-2" style="color:#580d21;"></i>
                Daftar Transaksi — {{ \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->translatedFormat('F Y') }}
            </h2>
            <span style="font-size:0.8rem;color:#a49791;">{{ $report['total_transactions'] }} transaksi</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="owner-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Invoice</th>
                        <th>Penyewa</th>
                        <th>Busana</th>
                        <th>Tgl Sewa</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Tagihan</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report['rentals'] as $index => $rental)
                        <tr>
                            <td style="color:#a49791;font-size:0.78rem;">{{ $index + 1 }}</td>
                            <td>
                                <span style="font-weight:700;color:#1a0a0e;font-family:monospace;font-size:0.78rem;background:#fdfbf7;padding:0.2rem 0.5rem;border-radius:5px;display:inline-block;">
                                    {{ $rental->invoice_number }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight:600;font-size:0.85rem;color:#1a0a0e;">{{ $rental->user->name }}</div>
                                <div style="font-size:0.75rem;color:#a49791;">{{ $rental->user->email }}</div>
                            </td>
                            <td style="font-size:0.82rem;color:#79665e;max-width:180px;">
                                {{ $rental->details->pluck('costume.name')->join(', ') }}
                            </td>
                            <td style="font-size:0.8rem;color:#79665e;">{{ $rental->rental_date?->format('d M Y') ?? '—' }}</td>
                            <td style="font-size:0.8rem;color:#79665e;">{{ $rental->return_date?->format('d M Y') ?? '—' }}</td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2 text-bg-{{ $rental->status->badgeClass() }}">
                                    {{ $rental->status->label() }}
                                </span>
                            </td>
                            <td>
                                @if($rental->payment)
                                    <span class="badge rounded-pill px-3 py-2 text-bg-{{ $rental->payment->status->badgeClass() }}">
                                        {{ $rental->payment->status->label() }}
                                    </span>
                                @else
                                    <span style="font-size:0.78rem;color:#a49791;">Belum bayar</span>
                                @endif
                            </td>
                            <td style="font-weight:700;color:#1a0a0e;font-size:0.85rem;">
                                Rp{{ number_format((float)$rental->total_price, 0, ',', '.') }}
                            </td>
                            <td style="font-weight:600;font-size:0.85rem;color:{{ ($rental->returnRecord->fine_amount ?? 0) > 0 ? '#9b3a4a' : '#a49791' }};">
                                @if(($rental->returnRecord->fine_amount ?? 0) > 0)
                                    Rp{{ number_format((float)$rental->returnRecord->fine_amount, 0, ',', '.') }}
                                @else —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="text-center py-5" style="color:#a49791;">
                                    <i class="bi bi-inbox d-block mb-2" style="font-size:2.5rem;"></i>
                                    <div style="font-weight:600;margin-bottom:0.25rem;">Tidak ada transaksi</div>
                                    <div style="font-size:0.82rem;">Belum ada transaksi pada bulan yang dipilih.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.owner>
