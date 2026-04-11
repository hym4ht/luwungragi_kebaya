<x-layouts.owner title="Busana Terlaris">

    <div class="owner-page-header">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <h1>Busana Terlaris</h1>
                <p>Ranking busana dengan jumlah penyewaan terbanyak berdasarkan periode waktu.</p>
            </div>
            <form method="GET" action="{{ route('owner.reports.top-items') }}" class="d-flex gap-2">
                <input type="month" name="month" class="form-control form-control-sm rounded-pill" value="{{ $selectedMonth }}"
                       style="border:1px solid rgba(88,13,33,0.2); font-size:0.85rem;">
                <button type="submit" class="btn btn-sm btn-dark rounded-pill px-3">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        {{-- Bar Chart --}}
        <div class="col-lg-7">
            <div class="owner-panel h-100">
                <div class="owner-panel-header">
                    <h2 class="owner-panel-title"><i class="bi bi-bar-chart-horizontal-fill me-2" style="color:#580d21;"></i>Grafik Popularitas Busana</h2>
                    <span style="font-size:0.8rem;color:#a49791;">{{ \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->translatedFormat('F Y') }}</span>
                </div>
                <div class="owner-panel-body">
                    <div class="chart-wrap" style="height:300px;">
                        <canvas id="topItemsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ranked List --}}
        <div class="col-lg-5">
            <div class="owner-panel h-100">
                <div class="owner-panel-header">
                    <h2 class="owner-panel-title"><i class="bi bi-trophy-fill me-2" style="color:#580d21;"></i>Ranking Busana</h2>
                </div>
                <div class="owner-panel-body">
                    @forelse ($topItems as $index => $item)
                        @php
                            $maxQty = (int)($topItems->first()['quantity'] ?? 1);
                            $pct    = $maxQty > 0 ? ($item['quantity'] / $maxQty) * 100 : 0;
                        @endphp
                        <div class="mb-3 pb-3" style="{{ !$loop->last ? 'border-bottom:1px solid rgba(88,13,33,0.06);' : '' }}">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width:34px;height:34px;border-radius:9px;background:rgba(88,13,33,0.08);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.85rem;color:#580d21;flex-shrink:0;">
                                        {{ $index < 3 ? ['🥇','🥈','🥉'][$index] : ($index + 1) }}
                                    </div>
                                    <div>
                                        <div style="font-size:0.875rem;font-weight:600;color:#1a0a0e;">{{ $item['name'] }}</div>
                                        <div style="font-size:0.75rem;color:#a49791;">{{ $item['quantity'] }} kali disewa</div>
                                    </div>
                                </div>
                                <div style="background:rgba(88,13,33,0.08);color:#580d21;padding:0.25rem 0.7rem;border-radius:50px;font-size:0.78rem;font-weight:700;white-space:nowrap;">
                                    {{ $item['quantity'] }} pcs
                                </div>
                            </div>
                            <div class="progress" style="height:5px;border-radius:50px;background:rgba(88,13,33,0.07);">
                                <div class="progress-bar" style="width:{{ $pct }}%;background:#580d21;border-radius:50px;"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4" style="color:#a49791;">
                            <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;"></i>
                            Belum ada data busana untuk bulan ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const topItems    = @json($topItems);
        const itemLabels  = topItems.map(i => i.name.length > 22 ? i.name.substring(0, 22) + '…' : i.name);
        const itemValues  = topItems.map(i => i.quantity);

        // Gradient shades of maroon
        const maroonShades = [
            'rgba(88,13,33,0.85)',
            'rgba(88,13,33,0.70)',
            'rgba(88,13,33,0.56)',
            'rgba(88,13,33,0.44)',
            'rgba(88,13,33,0.33)',
            'rgba(88,13,33,0.24)',
        ];

        if (itemValues.length > 0) {
            new Chart(document.getElementById('topItemsChart'), {
                type: 'bar',
                data: {
                    labels: itemLabels,
                    datasets: [{
                        label: 'Jumlah Sewa',
                        data: itemValues,
                        backgroundColor: maroonShades,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1a0a0e',
                            callbacks: { label: ctx => ' ' + ctx.parsed.x + ' pcs disewa' }
                        }
                    },
                    scales: {
                        x: { grid: { color: 'rgba(88,13,33,0.06)' }, ticks: { color: '#a49791', font:{size:11} } },
                        y: { grid: { display: false }, ticks: { color: '#4a3540', font:{size:12, weight:'500'} } }
                    }
                }
            });
        } else {
            document.getElementById('topItemsChart').parentElement.innerHTML = `
                <div style="height:300px;display:flex;align-items:center;justify-content:center;color:#a49791;text-align:center;">
                    <div><i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:0.5rem;"></i>Belum ada data</div>
                </div>`;
        }
    </script>
    @endpush

</x-layouts.owner>
