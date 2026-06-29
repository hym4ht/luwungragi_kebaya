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
        <style>
            #revenueCardDropdown .dropdown-item {
                transition: all 0.2s ease;
                cursor: pointer;
            }
            #revenueCardDropdown .dropdown-item:hover {
                background-color: rgba(215, 198, 175, 0.15) !important;
                color: #580d21 !important;
            }
            #revenueCardDropdown .dropdown-item.active {
                background-color: rgba(215, 198, 175, 0.3) !important;
                color: #580d21 !important;
            }
        </style>
        <div class="col-md-3">
            <x-stat-card title="Pendapatan" tone="earth" class="h-100">
                <x-slot name="action">
                    <div class="dropdown" id="revenueCardDropdown">
                        <button class="btn btn-sm dropdown-toggle border-0 py-0 px-2 fw-semibold" type="button" id="revenueDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.72rem; color: #79665e; background: transparent; box-shadow: none;">
                            Kotor
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-1" aria-labelledby="revenueDropdown" style="font-size: 0.78rem; min-width: 110px; border-radius: 0.75rem; background: #ffffff; z-index: 1050;">
                            <li><a class="dropdown-item py-2 px-3 fw-semibold active" href="#" data-value="gross">Kotor</a></li>
                            <li><a class="dropdown-item py-2 px-3 fw-semibold text-muted" href="#" data-value="net">Bersih</a></li>
                        </ul>
                    </div>
                </x-slot>
                <x-slot name="content">
                    <div id="grossRevenueVal" class="stat-card__value">Rp{{ number_format((float) $report['gross_revenue'], 0, ',', '.') }}</div>
                    <div id="netRevenueVal" class="stat-card__value d-none">Rp{{ number_format((float) ($report['gross_revenue'] + $report['fine_revenue']), 0, ',', '.') }}</div>
                </x-slot>
            </x-stat-card>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownBtn = document.getElementById('revenueDropdown');
            const dropdownItems = document.querySelectorAll('#revenueCardDropdown .dropdown-item');
            const grossRevenueVal = document.getElementById('grossRevenueVal');
            const netRevenueVal = document.getElementById('netRevenueVal');

            if (dropdownBtn && dropdownItems.length && grossRevenueVal && netRevenueVal) {
                dropdownItems.forEach(item => {
                    item.addEventListener('click', function (e) {
                        e.preventDefault();

                        // Remove active class from all items, add to clicked
                        dropdownItems.forEach(i => {
                            i.classList.remove('active');
                            i.classList.add('text-muted');
                        });
                        this.classList.add('active');
                        this.classList.remove('text-muted');

                        // Update button text
                        dropdownBtn.textContent = this.textContent.trim();

                        // Toggle values
                        const val = this.getAttribute('data-value');
                        if (val === 'gross') {
                            grossRevenueVal.classList.remove('d-none');
                            netRevenueVal.classList.add('d-none');
                        } else {
                            grossRevenueVal.classList.add('d-none');
                            netRevenueVal.classList.remove('d-none');
                        }
                    });
                });
            }
        });
    </script>
</x-dynamic-component>
