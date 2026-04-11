<x-layouts.owner title="Laporan Keuangan">

    <div class="owner-page-header">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <h1>Laporan Keuangan</h1>
                <p>Analisis pendapatan, denda, dan tren keuangan usaha Luwungragi.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <form method="GET" action="{{ route('owner.reports.financial') }}" class="d-flex gap-2">
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

    {{-- KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="owner-stat-card" style="--card-accent:#580d21; --icon-bg:rgba(88,13,33,0.08); --icon-color:#580d21;">
                <div class="stat-icon-wrap"><i class="bi bi-receipt-cutoff"></i></div>
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value">{{ $report['total_transactions'] }}</div>
                <div class="stat-helper">{{ \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->translatedFormat('F Y') }}</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="owner-stat-card" style="--card-accent:#580d21; --icon-bg:rgba(88,13,33,0.08); --icon-color:#580d21;">
                <div class="stat-icon-wrap"><i class="bi bi-graph-up"></i></div>
                <div class="stat-label">Pendapatan Kotor</div>
                <div class="stat-value" style="font-size:1.15rem;">Rp{{ number_format((float)$report['gross_revenue'], 0, ',', '.') }}</div>
                <div class="stat-helper">Dari sewa busana</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="owner-stat-card" style="--card-accent:#9b3a4a; --icon-bg:rgba(155,58,74,0.09); --icon-color:#9b3a4a;">
                <div class="stat-icon-wrap"><i class="bi bi-exclamation-triangle"></i></div>
                <div class="stat-label">Pendapatan Denda</div>
                <div class="stat-value" style="font-size:1.15rem;">Rp{{ number_format((float)$report['fine_revenue'], 0, ',', '.') }}</div>
                <div class="stat-helper">Dari keterlambatan</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="owner-stat-card" style="--card-accent:#580d21; --icon-bg:rgba(88,13,33,0.08); --icon-color:#580d21;">
                <div class="stat-icon-wrap"><i class="bi bi-trophy-fill"></i></div>
                <div class="stat-label">Total Bersih</div>
                <div class="stat-value" style="font-size:1.15rem;">Rp{{ number_format((float)$report['gross_revenue'] + (float)$report['fine_revenue'], 0, ',', '.') }}</div>
                <div class="stat-helper">Sewa + denda</div>
            </div>
        </div>
    </div>

    {{-- CHARTS --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="owner-panel h-100">
                <div class="owner-panel-header">
                    <h2 class="owner-panel-title"><i class="bi bi-bar-chart-fill me-2" style="color:#580d21;"></i>Perbandingan Pendapatan vs Denda</h2>
                </div>
                <div class="owner-panel-body">
                    <div class="chart-wrap" style="height:240px;">
                        <canvas id="revenueComparisonChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="owner-panel h-100">
                <div class="owner-panel-header">
                    <h2 class="owner-panel-title"><i class="bi bi-pie-chart-fill me-2" style="color:#580d21;"></i>Komposisi Pendapatan</h2>
                </div>
                <div class="owner-panel-body">
                    <div class="chart-wrap" style="height:170px; margin-bottom:1rem;">
                        <canvas id="revenueCompositionChart"></canvas>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:10px;height:10px;border-radius:50%;background:#580d21;"></div>
                                <span style="font-size:0.82rem;color:#79665e;">Pendapatan Sewa</span>
                            </div>
                            <span style="font-size:0.82rem;font-weight:700;">Rp{{ number_format((float)$report['gross_revenue'], 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:10px;height:10px;border-radius:50%;background:#9b3a4a;"></div>
                                <span style="font-size:0.82rem;color:#79665e;">Pendapatan Denda</span>
                            </div>
                            <span style="font-size:0.82rem;font-weight:700;">Rp{{ number_format((float)$report['fine_revenue'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TRANSACTION TABLE --}}
    <div class="owner-panel">
        <div class="owner-panel-header">
            <h2 class="owner-panel-title"><i class="bi bi-table me-2" style="color:#580d21;"></i>Detail Transaksi Keuangan</h2>
            <span style="font-size:0.8rem;color:#a49791;">{{ \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->translatedFormat('F Y') }}</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="owner-table">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Penyewa</th>
                        <th>Tgl Transaksi</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Tagihan</th>
                        <th>Denda</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report['rentals'] as $rental)
                        <tr>
                            <td><span style="font-weight:700;color:#1a0a0e;font-family:monospace;font-size:0.8rem;">{{ $rental->invoice_number }}</span></td>
                            <td style="font-weight:600;font-size:0.85rem;">{{ $rental->user->name }}</td>
                            <td style="font-size:0.8rem;color:#79665e;">{{ $rental->created_at->format('d M Y') }}</td>
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
                                    <span style="font-size:0.78rem;color:#a49791;">—</span>
                                @endif
                            </td>
                            <td style="font-weight:600;color:#1a0a0e;font-size:0.85rem;">Rp{{ number_format((float)$rental->total_price, 0, ',', '.') }}</td>
                            <td style="font-size:0.85rem;font-weight:600;color:{{ ($rental->returnRecord->fine_amount ?? 0) > 0 ? '#9b3a4a' : '#a49791' }};">
                                Rp{{ number_format((float)($rental->returnRecord->fine_amount ?? 0), 0, ',', '.') }}
                            </td>
                            <td style="font-weight:800;color:#580d21;font-size:0.85rem;">
                                Rp{{ number_format((float)$rental->total_price + (float)($rental->returnRecord->fine_amount ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="text-center py-4" style="color:#a49791;">
                                    <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;"></i>
                                    Tidak ada transaksi pada bulan yang dipilih.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($report['rentals']->count() > 0)
                <tfoot>
                    <tr style="background:#fdfbf7;">
                        <td colspan="5" style="font-weight:700;font-size:0.85rem;color:#1a0a0e;padding:1rem;">TOTAL</td>
                        <td style="font-weight:800;color:#1a0a0e;padding:1rem;">Rp{{ number_format((float)$report['gross_revenue'], 0, ',', '.') }}</td>
                        <td style="font-weight:800;color:#9b3a4a;padding:1rem;">Rp{{ number_format((float)$report['fine_revenue'], 0, ',', '.') }}</td>
                        <td style="font-weight:800;color:#580d21;padding:1rem;">Rp{{ number_format((float)$report['gross_revenue'] + (float)$report['fine_revenue'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        new Chart(document.getElementById('revenueComparisonChart'), {
            type: 'bar',
            data: {
                labels: ['Pendapatan Sewa', 'Denda', 'Total Bersih'],
                datasets: [{
                    label: 'Jumlah (Rp)',
                    data: [
                        {{ (float)$report['gross_revenue'] }},
                        {{ (float)$report['fine_revenue'] }},
                        {{ (float)$report['gross_revenue'] + (float)$report['fine_revenue'] }}
                    ],
                    backgroundColor: ['rgba(88,13,33,0.75)', 'rgba(155,58,74,0.75)', 'rgba(88,13,33,0.9)'],
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a0a0e',
                        callbacks: { label: ctx => ' Rp' + ctx.parsed.y.toLocaleString('id-ID') }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#a49791', font: { size: 11 } } },
                    y: { grid: { color: 'rgba(88,13,33,0.06)' }, ticks: { color: '#a49791', font:{size:11}, callback: val => 'Rp' + (val/1000).toFixed(0) + 'k' } }
                }
            }
        });

        new Chart(document.getElementById('revenueCompositionChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pendapatan Sewa', 'Denda'],
                datasets: [{
                    data: [{{ (float)$report['gross_revenue'] ?: 1 }}, {{ (float)$report['fine_revenue'] ?: 0 }}],
                    backgroundColor: ['#580d21', '#9b3a4a'],
                    borderWidth: 0, hoverOffset: 4,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a0a0e',
                        callbacks: { label: ctx => ' Rp' + ctx.parsed.toLocaleString('id-ID') }
                    }
                }
            }
        });
    </script>
    @endpush

</x-layouts.owner>
