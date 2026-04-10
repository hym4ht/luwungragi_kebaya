<x-layouts.admin title="Kelola Rental">
    <x-page-header title="Transaksi Penyewaan" subtitle="Cari invoice, cek status pembayaran Midtrans, dan kelola status transaksi penyewaan busana.">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark rounded-pill px-4">Dashboard Admin</a>
    </x-page-header>

    <div class="filter-panel mb-4">
        <form method="GET" action="{{ route('admin.rentals.index') }}" class="row g-3">
            <div class="col-lg-4">
                <label class="form-label">Cari invoice / nama</label>
                <input type="text" name="search" class="form-control" value="{{ $filters['search'] }}" placeholder="RNT-20260408-001">
            </div>
            <div class="col-lg-3">
                <label class="form-label">Status Rental</label>
                <select name="status" class="form-select">
                    <option value="">Semua status</option>
                    @foreach (\App\Enums\RentalStatus::cases() as $status)
                        <option value="{{ $status->value }}" @selected($filters['status'] === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label class="form-label">Status Pembayaran</label>
                <select name="payment_status" class="form-select">
                    <option value="">Semua pembayaran</option>
                    @foreach (\App\Enums\PaymentStatus::cases() as $status)
                        <option value="{{ $status->value }}" @selected($filters['payment_status'] === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 d-flex align-items-end">
                <button type="submit" class="btn btn-dark rounded-pill w-100">Filter</button>
            </div>
        </form>
    </div>

    <div class="content-panel">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Penyewa</th>
                        <th>Item</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rentals as $rental)
                        <tr>
                            <td class="fw-semibold">{{ $rental->invoice_number }}</td>
                            <td>{{ $rental->user->name }}</td>
                            <td>{{ $rental->details->pluck('costume.name')->join(', ') }}</td>
                            <td>Sewa {{ $rental->usage_date->format('d M') }}@if($rental->rental_duration_days > 1)-{{ $rental->usage_end_date->format('d M') }}@endif · Kembali {{ $rental->return_due_date->format('d M Y') }}</td>
                            <td><span class="badge rounded-pill text-bg-{{ $rental->status->badgeClass() }} px-3 py-2">{{ $rental->status->label() }}</span></td>
                            <td>
                                @if ($rental->payment)
                                    <div class="d-flex flex-column gap-1 align-items-start">
                                        <span class="badge rounded-pill text-bg-{{ $rental->payment->status->badgeClass() }} px-3 py-2">{{ $rental->payment->status->label() }}</span>
                                        <small class="text-muted fw-medium">Midtrans</small>
                                    </div>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.rentals.show', ['rental' => $rental] + request()->query()) }}" class="btn btn-sm btn-outline-dark rounded-pill">Detail / Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state text-center">
                                    <h3 class="h4 mb-2">Tidak ada transaksi</h3>
                                    <p class="text-muted mb-0">Belum ada data rental yang sesuai dengan filter saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($editingRental)
        @include('admin.rentals.partials.edit-modal', ['rental' => $editingRental])
    @endif
</x-layouts.admin>

<script>
document.addEventListener('DOMContentLoaded', function () {
    @if ($editingRental || $errors->any())
        const rentalModalEl = document.getElementById('detailRentalModal');
        if (rentalModalEl) {
            const modal = new bootstrap.Modal(rentalModalEl);
            modal.show();
        }
    @endif
});
</script>
