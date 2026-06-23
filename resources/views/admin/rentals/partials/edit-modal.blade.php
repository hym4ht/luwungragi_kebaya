@php
    $closeUrl = route('admin.rentals.index', request()->query());
    $durationDays = $rental->rental_duration_days;
@endphp

<div class="modal fade" id="detailRentalModal" tabindex="-1" aria-labelledby="detailRentalModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pt-4 px-4 pb-0">
                <div>
                    <h5 class="modal-title h4 fw-bold" id="detailRentalModalLabel">Detail & Edit {{ $rental->invoice_number }}</h5>
                    <p class="text-muted mb-0">Kelola status rental, pembayaran, dan pengembalian busana dalam satu modal.</p>
                </div>
                <a href="{{ $closeUrl }}" class="btn-close" aria-label="Close"></a>
            </div>

            <div class="modal-body p-4">
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

                            @if($rental->identity_card)
                                <div class="mb-4 p-3 bg-light rounded border">
                                    <div class="small text-muted fw-semibold mb-2">Jaminan Identitas Diri (KTP/SIM/Kartu Pelajar)</div>
                                    <div>
                                        <a href="{{ asset('storage/' . $rental->identity_card) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $rental->identity_card) }}" alt="Identitas Diri" class="img-fluid rounded border shadow-sm" style="max-height: 150px; object-fit: contain;">
                                        </a>
                                        <div class="form-text mt-1">Klik gambar untuk melihat ukuran penuh.</div>
                                    </div>
                                </div>
                            @endif

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
                                                <td class="text-end fw-semibold">Rp{{ number_format((float) $detail->unit_price * $detail->quantity * $rental->sessions_count, 0, ',', '.') }}</td>
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

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-2">
                                    <a href="{{ $closeUrl }}" class="btn btn-outline-dark rounded-pill px-4">Tutup</a>
                                    <button type="submit" class="btn btn-dark rounded-pill px-4">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
