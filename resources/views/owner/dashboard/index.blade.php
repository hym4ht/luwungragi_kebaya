<x-layouts.app title="Dashboard Owner">
    <x-page-header title="Dashboard Owner" subtitle="Monitoring kinerja usaha, tren pendapatan, dan ringkasan laporan penyewaan Luwungragi.">
        <a href="{{ route('reports.index') }}" class="btn btn-dark rounded-pill px-4">Buka Laporan Detail</a>
    </x-page-header>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <x-stat-card title="Pelanggan" :value="$summary['total_customers']" helper="Akun penyewa terdaftar" tone="warm" class="h-100" />
        </div>
        <div class="col-md-3">
            <x-stat-card title="Sewa Selesai" :value="$summary['completed_rentals']" helper="Transaksi selesai" tone="blush" class="h-100" />
        </div>
        <div class="col-md-3">
            <x-stat-card title="Perlu Verifikasi" :value="$summary['pending_verifications']" helper="Pembayaran pending" tone="earth" class="h-100" />
        </div>
        <div class="col-md-3">
            <x-stat-card title="Pendapatan Total" :value="'Rp'.number_format((float) $summary['all_time_revenue'], 0, ',', '.')" helper="Akumulasi settlement + denda" tone="neutral" class="h-100" />
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="content-panel mb-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-3">
                    <div>
                        <h2 class="h4 mb-1">Ringkasan Bulanan</h2>
                        <p class="text-muted mb-0">Data laporan bulan {{ $summary['monthly_report']['selected_month']->translatedFormat('F Y') }}</p>
                    </div>
                    <form method="GET" action="{{ route('owner.dashboard') }}" class="d-flex gap-2">
                        <select name="month" class="form-select">
                            @foreach ($summary['months'] as $month)
                                <option value="{{ $month['value'] }}" @selected($summary['selected_month'] === $month['value'])>{{ $month['label'] }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-dark rounded-pill px-4">Lihat</button>
                    </form>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <x-stat-card title="Total Transaksi" :value="$summary['monthly_report']['total_transactions']" tone="warm" />
                    </div>
                    <div class="col-md-3">
                        <x-stat-card title="Pending" :value="$summary['monthly_report']['pending_transactions']" tone="blush" />
                    </div>
                    <div class="col-md-3">
                        <x-stat-card title="Pendapatan Kotor" :value="'Rp'.number_format((float) $summary['monthly_report']['gross_revenue'], 0, ',', '.')" tone="earth" />
                    </div>
                    <div class="col-md-3">
                        <x-stat-card title="Denda" :value="'Rp'.number_format((float) $summary['monthly_report']['fine_revenue'], 0, ',', '.')" tone="neutral" />
                    </div>
                </div>
            </div>

            <div class="content-panel">
                <h2 class="h4 mb-3">Tren Pendapatan 6 Bulan</h2>
                <div class="trend-list">
                    @foreach ($summary['revenue_trend'] as $trend)
                        <div class="trend-list__item">
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $trend['label'] }}</span>
                                <strong>Rp{{ number_format((float) $trend['total'], 0, ',', '.') }}</strong>
                            </div>
                            <div class="progress" role="progressbar" aria-label="{{ $trend['label'] }}">
                                <div class="progress-bar" style="width: {{ $summary['all_time_revenue'] > 0 ? max(($trend['total'] / $summary['all_time_revenue']) * 100, 8) : 8 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="content-panel mb-4">
                <h2 class="h4 mb-3">Metode Pembayaran</h2>
                @forelse ($summary['monthly_report']['payments_by_method'] as $method => $total)
                    <div class="d-flex justify-content-between border rounded-4 px-3 py-2 mb-2">
                        <span>{{ $method }}</span>
                        <strong>{{ $total }}</strong>
                    </div>
                @empty
                    <div class="empty-state text-center">
                        <h3 class="h4 mb-2">Belum ada pembayaran</h3>
                        <p class="text-muted mb-0">Data akan muncul setelah transaksi dicatat pada bulan yang dipilih.</p>
                    </div>
                @endforelse
            </div>

            <div class="content-panel">
                <h2 class="h4 mb-3">Busana Terlaris</h2>
                @forelse ($summary['monthly_report']['top_costumes'] as $costumeName => $quantity)
                    <div class="d-flex justify-content-between border rounded-4 px-3 py-2 mb-2">
                        <span>{{ $costumeName }}</span>
                        <strong>{{ $quantity }} pcs</strong>
                    </div>
                @empty
                    <div class="empty-state text-center">
                        <h3 class="h4 mb-2">Belum ada data busana</h3>
                        <p class="text-muted mb-0">Top item akan terhitung dari transaksi rental bulan berjalan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>
