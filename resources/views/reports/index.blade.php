<x-dynamic-component :component="auth()->user()->role->value === 'admin' ? 'layouts.admin' : 'layouts.app'" title="Laporan">
    <x-page-header title="Laporan Penyewaan" subtitle="Rekap transaksi, pengembalian, dan pendapatan bulanan untuk evaluasi usaha.">
        <div class="d-flex gap-2">
            <form method="GET" action="{{ route('reports.index') }}" class="d-flex gap-2">
                <input type="month" name="month" class="form-control" value="{{ $selectedMonth }}">
                <button type="submit" class="btn btn-outline-dark rounded-pill px-4">Filter</button>
            </form>
            <button type="button" onclick="window.print()" class="btn btn-dark rounded-pill px-4">Cetak</button>
        </div>
    </x-page-header>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <x-stat-card title="Total Transaksi" :value="$report['total_transactions']" tone="warm" class="h-100" />
        </div>
        <div class="col-md-3">
            <x-stat-card title="Aktif" :value="$report['active_transactions']" tone="blush" class="h-100" />
        </div>
        <div class="col-md-3">
            <x-stat-card title="Pendapatan" :value="'Rp'.number_format((float) $report['gross_revenue'], 0, ',', '.')" tone="earth" class="h-100" />
        </div>
        <div class="col-md-3">
            <x-stat-card title="Denda" :value="'Rp'.number_format((float) $report['fine_revenue'], 0, ',', '.')" tone="neutral" class="h-100" />
        </div>
    </div>

    <div class="content-panel">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Penyewa</th>
                        <th>Item</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Tagihan</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report['rentals'] as $rental)
                        <tr>
                            <td class="fw-semibold">{{ $rental->invoice_number }}</td>
                            <td>{{ $rental->user->name }}</td>
                            <td>{{ $rental->details->pluck('costume.name')->join(', ') }}</td>
                            <td><span class="badge rounded-pill text-bg-{{ $rental->status->badgeClass() }} px-3 py-2">{{ $rental->status->label() }}</span></td>
                            <td>
                                @if ($rental->payment)
                                    <span class="badge rounded-pill text-bg-{{ $rental->payment->status->badgeClass() }} px-3 py-2">{{ $rental->payment->status->label() }}</span>
                                @endif
                            </td>
                            <td>Rp{{ number_format((float) $rental->total_price, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format((float) ($rental->returnRecord->fine_amount ?? 0), 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state text-center">
                                    <h3 class="h4 mb-2">Belum ada data laporan</h3>
                                    <p class="text-muted mb-0">Tidak ada transaksi pada bulan yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dynamic-component>
