<x-layouts.owner title="Dashboard Eksekutif">

    {{-- Page Header --}}
    <div class="owner-page-header">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <h1>Dashboard Eksekutif</h1>
                <p>Pantau kinerja usaha secara real-time — omset, tren penyewaan, dan pelanggan baru.</p>
            </div>
            <form method="GET" action="{{ route('owner.dashboard') }}" class="d-flex gap-2 align-items-center">
                <select name="month" class="form-select form-select-sm rounded-pill" style="width:auto;" onchange="this.form.submit()">
                    @foreach ($summary['months'] as $month)
                        <option value="{{ $month['value'] }}" @selected($summary['selected_month'] === $month['value'])>
                            {{ $month['label'] }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    {{-- ─── KPI WIDGETS ─── --}}
    <div class="row g-3 mb-4">
        {{-- Total Omset Bulan Ini --}}
        <div class="col-6 col-lg-3">
            <div class="owner-stat-card" style="--card-accent:#580d21; --icon-bg:rgba(88,13,33,0.08); --icon-color:#580d21;">
                <div class="stat-icon-wrap"><i class="bi bi-cash-stack"></i></div>
                <div class="stat-label">Omset Bulan Ini</div>
                <div class="stat-value" style="font-size:1.15rem;">
                    Rp{{ number_format((float)($summary['monthly_report']['gross_revenue'] + $summary['monthly_report']['fine_revenue']), 0, ',', '.') }}
                </div>
                <div class="stat-helper">Sewa + denda</div>
                <span class="stat-trend neutral"><i class="bi bi-calendar3"></i> {{ $summary['monthly_report']['selected_month']->translatedFormat('M Y') }}</span>
            </div>
        </div>

        {{-- Omset dari Denda --}}
        <div class="col-6 col-lg-3">
            <div class="owner-stat-card" style="--card-accent:#9b3a4a; --icon-bg:rgba(155,58,74,0.09); --icon-color:#9b3a4a;">
                <div class="stat-icon-wrap"><i class="bi bi-exclamation-triangle-fill"></i></div>
                <div class="stat-label">Omset dari Denda</div>
                <div class="stat-value" style="font-size:1.15rem;">
                    Rp{{ number_format((float)$summary['monthly_report']['fine_revenue'], 0, ',', '.') }}
                </div>
                <div class="stat-helper">Denda keterlambatan</div>
                <span class="stat-trend neutral"><i class="bi bi-clock-history"></i> Bulan berjalan</span>
            </div>
        </div>

        {{-- Pelanggan Baru --}}
        <div class="col-6 col-lg-3">
            <div class="owner-stat-card" style="--card-accent:#79665e; --icon-bg:rgba(121,102,94,0.1); --icon-color:#79665e;">
                <div class="stat-icon-wrap"><i class="bi bi-people-fill"></i></div>
                <div class="stat-label">Total Pelanggan</div>
                <div class="stat-value">{{ $summary['total_customers'] }}</div>
                <div class="stat-helper">Akun penyewa terdaftar</div>
                <span class="stat-trend up"><i class="bi bi-arrow-up"></i> Terdaftar</span>
            </div>
        </div>

        {{-- Total Transaksi Bulan Ini --}}
        <div class="col-6 col-lg-3">
            <div class="owner-stat-card" style="--card-accent:#580d21; --icon-bg:rgba(88,13,33,0.08); --icon-color:#580d21;">
                <div class="stat-icon-wrap"><i class="bi bi-receipt-cutoff"></i></div>
                <div class="stat-label">Transaksi Bulan Ini</div>
                <div class="stat-value">{{ $summary['monthly_report']['total_transactions'] }}</div>
                <div class="stat-helper">{{ $summary['monthly_report']['completed_transactions'] }} selesai · {{ $summary['monthly_report']['pending_transactions'] }} pending</div>
                <span class="stat-trend neutral"><i class="bi bi-bar-chart"></i> Total transaksi</span>
            </div>
        </div>
    </div>

    {{-- ─── CHARTS ─── --}}
    <div class="row g-3 mb-4">
        {{-- Revenue Trend Line Chart --}}
        <div class="col-12">
            <div class="owner-panel">
                <div class="owner-panel-header">
                    <h2 class="owner-panel-title">
                        <i class="bi bi-graph-up-arrow me-2" style="color:#580d21;"></i>Tren Penyewaan 6 Bulan
                    </h2>
                    <a href="{{ route('owner.reports.financial') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3" style="font-size:0.78rem;">
                        Lihat Detail
                    </a>
                </div>
                <div class="owner-panel-body">
                    <div class="chart-wrap" style="height:230px;">
                        <canvas id="revenueTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── BOTTOM ROW ─── --}}
    <div class="row g-3 mb-3">
        {{-- Tren Pendapatan Progress Bars --}}
        <div class="col-lg-6">
            <div class="owner-panel">
                <div class="owner-panel-header">
                    <h2 class="owner-panel-title">
                        <i class="bi bi-bar-chart-fill me-2" style="color:#580d21;"></i>Tren Pendapatan 6 Bulan
                    </h2>
                </div>
                <div class="owner-panel-body">
                    @forelse ($summary['revenue_trend'] as $trend)
                        @php $pct = $summary['all_time_revenue'] > 0 ? max(($trend['total'] / $summary['all_time_revenue']) * 100, 4) : 4; @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span style="font-size:0.83rem;color:#79665e;font-weight:500;">{{ $trend['label'] }}</span>
                                <span style="font-size:0.83rem;font-weight:700;color:#1a0a0e;">Rp{{ number_format((float)$trend['total'], 0, ',', '.') }}</span>
                            </div>
                            <div class="progress" style="height:7px; border-radius:50px; background:rgba(88,13,33,0.07);">
                                <div class="progress-bar" style="width:{{ $pct }}%; background: var(--brand-maroon, #580d21); border-radius:50px;"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3" style="color:#a49791; font-size:0.875rem;">
                            <i class="bi bi-inbox d-block mb-1" style="font-size:1.5rem;"></i>
                            Belum ada data tren pendapatan
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Top Busana --}}
        <div class="col-lg-6">
            <div class="owner-panel">
                <div class="owner-panel-header">
                    <h2 class="owner-panel-title">
                        <i class="bi bi-star-fill me-2" style="color:#580d21;"></i>Busana Terlaris Bulan Ini
                    </h2>
                    <a href="{{ route('owner.reports.top-items') }}" style="font-size:0.8rem; color:#580d21; text-decoration:none; font-weight:600;">
                        Lihat Semua →
                    </a>
                </div>
                <div class="owner-panel-body">
                    @forelse ($summary['monthly_report']['top_costumes'] as $costumeName => $quantity)
                        @php $loop_index = $loop->index; @endphp
                        <div class="d-flex align-items-center gap-3 py-2" style="{{ !$loop->last ? 'border-bottom:1px solid rgba(88,13,33,0.06);' : '' }}">
                            <div style="width:26px;height:26px;border-radius:7px;background:rgba(88,13,33,0.08);display:flex;align-items:center;justify-content:center;font-size:0.72rem;font-weight:800;color:#580d21;flex-shrink:0;">
                                {{ $loop_index + 1 }}
                            </div>
                            <span style="font-size:0.875rem;color:#4a3540;flex:1;">{{ $costumeName }}</span>
                            <span style="font-size:0.8rem;font-weight:700;color:#580d21;background:rgba(88,13,33,0.06);padding:0.2rem 0.65rem;border-radius:50px;">{{ $quantity }} pcs</span>
                        </div>
                    @empty
                        <div class="text-center py-3" style="color:#a49791; font-size:0.875rem;">
                            <i class="bi bi-inbox d-block mb-1" style="font-size:1.5rem;"></i>
                            Belum ada data busana bulan ini
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ─── QUICK STATS ─── --}}
    <div class="owner-panel">
        <div class="owner-panel-header">
            <h2 class="owner-panel-title"><i class="bi bi-lightning-fill me-2" style="color:#580d21;"></i>Ringkasan Keseluruhan</h2>
        </div>
        <div class="owner-panel-body">
            <div class="row g-3 text-center">
                <div class="col-6 col-md-3">
                    <div style="font-size:1.5rem;font-weight:800;color:#580d21;">{{ $summary['completed_rentals'] }}</div>
                    <div style="font-size:0.75rem;color:#a49791;margin-top:0.2rem;">Total Sewa Selesai</div>
                </div>
                <div class="col-6 col-md-3">
                    <div style="font-size:1.5rem;font-weight:800;color:#9b3a4a;">{{ $summary['pending_verifications'] }}</div>
                    <div style="font-size:0.75rem;color:#a49791;margin-top:0.2rem;">Perlu Verifikasi</div>
                </div>
                <div class="col-6 col-md-3">
                    <div style="font-size:1.5rem;font-weight:800;color:#79665e;">{{ $summary['total_customers'] }}</div>
                    <div style="font-size:0.75rem;color:#a49791;margin-top:0.2rem;">Total Pelanggan</div>
                </div>
                <div class="col-6 col-md-3">
                    <div style="font-size:1.2rem;font-weight:800;color:#580d21;">Rp{{ number_format((float)$summary['all_time_revenue'], 0, ',', '.') }}</div>
                    <div style="font-size:0.75rem;color:#a49791;margin-top:0.2rem;">Akumulasi Pendapatan</div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // ─── Revenue Trend Chart ───
        const trendData   = @json($summary['revenue_trend']);
        const trendLabels = trendData.map(d => d.label);
        const trendValues = trendData.map(d => d.total);

        const ctxTrend   = document.getElementById('revenueTrendChart').getContext('2d');
        const gradTrend  = ctxTrend.createLinearGradient(0, 0, 0, 230);
        gradTrend.addColorStop(0, 'rgba(88,13,33,0.18)');
        gradTrend.addColorStop(1, 'rgba(88,13,33,0)');

        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Pendapatan',
                    data: trendValues,
                    fill: true,
                    backgroundColor: gradTrend,
                    borderColor: '#580d21',
                    borderWidth: 2.5,
                    tension: 0.42,
                    pointBackgroundColor: '#580d21',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a0a0e',
                        titleColor: '#a49791',
                        bodyColor: '#fdfbf7',
                        padding: 10,
                        callbacks: {
                            label: ctx => ' Rp' + ctx.parsed.y.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#a49791', font: { size: 11 } }
                    },
                    y: {
                        grid: { color: 'rgba(88,13,33,0.06)', drawBorder: false },
                        ticks: {
                            color: '#a49791',
                            font: { size: 11 },
                            callback: val => 'Rp' + (val / 1000).toFixed(0) + 'k'
                        }
                    }
                }
            }
        });


    </script>
    @endpush

</x-layouts.owner>
