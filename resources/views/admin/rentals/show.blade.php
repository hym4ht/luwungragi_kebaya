@php($durationDays = $rental->rental_duration_days)

<x-layouts.admin title="Detail Rental">
    <x-page-header :title="'Detail & Edit '.$rental->invoice_number" subtitle="Kelola status rental, pembayaran, dan pengembalian busana dalam satu form edit.">
        <a href="{{ route('admin.rentals.index') }}" class="btn btn-outline-dark rounded-pill px-4">Kembali</a>
    </x-page-header>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="content-panel mb-4">
                <h2 class="h4 mb-3">Informasi Rental</h2>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="small text-muted">Penyewa</div>
                        <div class="fw-semibold">{{ $rental->user->name }}</div>
                        <div class="small text-muted">{{ $rental->user->email }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Jadwal</div>
                        <div class="fw-semibold">Sewa {{ $rental->usage_date->format('d M Y') }}@if($rental->rental_duration_days > 1) - {{ $rental->usage_end_date->format('d M Y') }}@endif</div>
                        <div class="small text-muted">Order {{ $rental->booking_start_date->format('d M') }} · Lunas {{ $rental->payment_due_date->format('d M') }} · Ambil {{ $rental->pickup_date->format('d M') }} · Kembali {{ $rental->return_due_date->format('d M') }}</div>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Busana</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rental->details as $detail)
                                <tr>
                                    <td class="fw-medium">{{ $detail->costume->name }}</td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-end">Rp{{ number_format((float) $detail->unit_price, 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold">Rp{{ number_format((float) $detail->unit_price * $detail->quantity * $durationDays, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                    <span class="text-muted fw-medium">Total Pembayaran</span>
                    <span class="fs-5 fw-bold text-dark">Rp{{ number_format((float) $rental->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

        </div>

        <div class="col-lg-5">
            <div class="content-panel mb-4">
                <h2 class="h5 mb-4 border-bottom pb-3">Status Saat Ini</h2>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted fw-medium">Rental</span>
                        <span class="badge rounded-pill text-bg-{{ $rental->status->badgeClass() }} px-3 py-2">{{ $rental->status->label() }}</span>
                    </div>
                    @if ($rental->payment)
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <span class="text-muted fw-medium">Pembayaran</span>
                                <small class="text-muted">{{ $rental->payment->payment_type ?? 'Midtrans Snap' }}</small>
                            </div>
                            <span class="badge rounded-pill text-bg-{{ $rental->payment->status->badgeClass() }} px-3 py-2">{{ $rental->payment->status->label() }}</span>
                        </div>
                    @endif
                    @if ($rental->returnRecord)
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <span class="text-muted fw-medium">Pengembalian</span>
                                @if($rental->returnRecord->fine_amount > 0)
                                    <small class="text-danger fw-medium">Denda: Rp{{ number_format((float) $rental->returnRecord->fine_amount, 0, ',', '.') }}</small>
                                @endif
                            </div>
                            <span class="fw-semibold">{{ $rental->returnRecord->return_status }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="content-panel mb-4">
                <div class="mb-4">
                    <h2 class="h5 mb-2">Aksi Edit Rental</h2>
                    <p class="small text-muted mb-0">Ubah status rental, verifikasi pembayaran, atau proses pengembalian di bawah ini lalu simpan dalam satu klik.</p>
                </div>

                <form action="{{ route('admin.rentals.update', $rental) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">Update Status Rental</label>
                        <select name="rental_status" class="form-select">
                            @foreach (\App\Enums\RentalStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected(old('rental_status', $rental->status->value) === $status->value)>{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($rental->payment)
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">Verifikasi Pembayaran</label>
                            <select name="payment_status" class="form-select">
                                @foreach (\App\Enums\PaymentStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(old('payment_status', $rental->payment->status->value) === $status->value)>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">Proses Pengembalian</label>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-muted small mb-1">Tanggal Dikembalikan</label>
                                <input
                                    type="date"
                                    name="returned_date"
                                    class="form-control"
                                    value="{{ old('returned_date', $rental->returnRecord?->returned_date?->toDateString()) }}"
                                >
                                <div class="form-text">Kosongkan jika busana belum dikembalikan.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small mb-1">Biaya Kerusakan Tambahan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="damage_fee" min="0" class="form-control" value="{{ old('damage_fee', 0) }}">
                                </div>
                                <div class="form-text">Denda telat dihitung otomatis Rp{{ number_format(\App\Models\Rental::LATE_FEE_PER_DAY, 0, ',', '.') }} per hari.</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mt-4 pt-2">
                        <button type="submit" class="btn btn-dark rounded-pill py-2">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
