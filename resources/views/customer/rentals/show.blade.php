<x-layouts.app title="E-Invoice">
<style>
    :root {
        --brand-maroon: #580d21;
        --bg-cream: #FDFBF7;
        --text-dark: #2c2c2c;
        --text-muted: #79665e;
    }

    .invoice-wrap {
        background: var(--bg-cream);
        min-height: 100vh;
        padding: 2.5rem 0;
        font-family: 'Montserrat', sans-serif;
    }

    .invoice-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .invoice-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        color: var(--brand-maroon);
        margin: 0;
    }

    .invoice-sub {
        font-size: 0.7rem;
        color: var(--text-muted);
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .back-link {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-muted);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: color 0.2s;
    }
    .back-link:hover { color: var(--brand-maroon); }

    .panel {
        background: white;
        padding: 1.75rem;
        margin-bottom: 1.25rem;
        border: 1px solid rgba(88,13,33,0.08);
    }

    .panel-title {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(88,13,33,0.08);
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem 2rem;
    }
    @media (max-width: 576px) {
        .info-grid { grid-template-columns: 1fr; }
    }

    .info-label { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 0.2rem; }
    .info-value { font-size: 0.9rem; font-weight: 600; color: var(--text-dark); }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
    }
    .items-table thead th {
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-muted);
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(88,13,33,0.1);
    }
    .items-table tbody td {
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(88,13,33,0.05);
        color: var(--text-dark);
    }
    .items-table tfoot td {
        padding: 0.75rem 0;
        font-weight: 700;
        font-size: 1rem;
        color: var(--text-dark);
        font-family: 'Playfair Display', serif;
    }

    /* Status badges */
    .status-chip {
        display: inline-block;
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0.3rem 0.8rem;
        border-radius: 2px;
    }
    .chip-pending    { background: #fef3c7; color: #92400e; }
    .chip-settlement { background: #d1fae5; color: #065f46; }
    .chip-active     { background: #dbeafe; color: #1e40af; }
    .chip-completed  { background: #f3f4f6; color: #374151; }
    .chip-cancel, .chip-expire { background: #fee2e2; color: #991b1b; }

    .payment-method-icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    /* Pay Now Button (Midtrans) */
    .btn-pay-now {
        display: block;
        width: 100%;
        padding: 1rem;
        background: var(--brand-maroon);
        color: white;
        border: none;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.3s;
        text-align: center;
        margin-top: 1rem;
    }
    .btn-pay-now:hover { background: #3f0917; }
    .btn-pay-now:disabled { background: #aaa; cursor: not-allowed; }

    .proof-img {
        width: 100%;
        max-height: 200px;
        object-fit: contain;
        border: 1px solid rgba(0,0,0,0.1);
        margin-top: 0.75rem;
    }

    .alert-success-custom {
        background: rgba(6,95,70,0.07);
        border-left: 3px solid #065f46;
        color: #065f46;
        font-size: 0.8rem;
        padding: 0.9rem 1rem;
        margin-bottom: 1.5rem;
    }

    /* Payment Selection Styles */
    .payment-cards {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-top: 1rem;
    }
    .payment-card {
        border: 1px solid rgba(88,13,33,0.1);
        padding: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s;
        position: relative;
    }
    .payment-card:hover { border-color: rgba(88,13,33,0.3); }
    .payment-card.selected {
        border-color: var(--brand-maroon);
        background: rgba(88,13,33,0.02);
    }
    .payment-card input[type="radio"] { display: none; }
    .payment-card-icon { font-size: 1.5rem; }
    .payment-card-body {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }
    .payment-card-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-dark);
    }
    .payment-card-desc {
        font-size: 0.65rem;
        color: var(--text-muted);
    }
</style>

@php($durationDays = $rental->rental_duration_days)
@php($processDays = $rental->process_duration_days)

<div class="invoice-wrap">
    <div class="container" style="max-width: 960px;">
        <div class="invoice-header">
            <div>
                <h1 class="invoice-title">E-Invoice</h1>
                <div class="invoice-sub">{{ $rental->invoice_number }}</div>
            </div>
            <a href="{{ route('home') }}" class="back-link">← Kembali ke Catalog</a>
        </div>

        @if(session('success'))
            <div class="alert-success-custom">✓ {{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <!-- Left: Summary + Items -->
            <div class="col-lg-8">
                <!-- Rental Info -->
                <div class="panel">
                    <div class="panel-title">Informasi Penyewaan</div>
                    <div class="info-grid">
                        <div>
                            <div class="info-label">Nama Penyewa</div>
                            <div class="info-value">{{ $rental->user->name }}</div>
                        </div>
                        <div>
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $rental->user->email }}</div>
                        </div>
                        <div>
                            <div class="info-label">Tanggal Mulai Sewa</div>
                            <div class="info-value">{{ $rental->usage_date->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="info-label">Tanggal Selesai Sewa</div>
                            <div class="info-value">{{ $rental->usage_end_date->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="info-label">Tanggal Order</div>
                            <div class="info-value">{{ $rental->booking_start_date->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="info-label">Batas Pelunasan</div>
                            <div class="info-value">{{ $rental->payment_due_date->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="info-label">Tanggal Ambil Offline</div>
                            <div class="info-value">{{ $rental->pickup_date->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="info-label">Tanggal Kembali</div>
                            <div class="info-value">{{ $rental->return_due_date->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="info-label">Durasi Sewa</div>
                            <div class="info-value">{{ $durationDays }} Hari</div>
                        </div>
                        <div>
                            <div class="info-label">Durasi Proses</div>
                            <div class="info-value">{{ $processDays }} Hari</div>
                        </div>
                        <div>
                            <div class="info-label">No. Invoice</div>
                            <div class="info-value">{{ $rental->invoice_number }}</div>
                        </div>
                    </div>
                </div>

                <!-- Items -->
                <div class="panel">
                    <div class="panel-title">Detail Busana</div>
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Nama Busana</th>
                                <th style="text-align:center">Qty</th>
                                <th style="text-align:right">Harga/hari</th>
                                <th style="text-align:right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rental->details as $detail)
                                <tr>
                                    <td>{{ $detail->costume->name }}</td>
                                    <td style="text-align:center">{{ $detail->quantity }}</td>
                                    <td style="text-align:right">Rp{{ number_format((float) $detail->unit_price, 0, ',', '.') }}</td>
                                    <td style="text-align:right">Rp{{ number_format((float) $detail->unit_price * $detail->quantity * $durationDays, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">Total</td>
                                <td style="text-align:right">Rp{{ number_format((float) $rental->total_price, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($rental->returnRecord)
                    <div class="panel">
                        <div class="panel-title">Informasi Pengembalian</div>
                        <div class="info-grid">
                            <div>
                                <div class="info-label">Status Pengembalian</div>
                                <div class="info-value">{{ $rental->returnRecord->return_status }}</div>
                            </div>
                            <div>
                                <div class="info-label">Denda</div>
                                <div class="info-value">Rp{{ number_format((float) $rental->returnRecord->fine_amount, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <div class="info-label">Tanggal Dikembalikan</div>
                                <div class="info-value">{{ $rental->returnRecord->returned_date->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right: Status + Payment -->
            <div class="col-lg-4">
                <!-- Status Panel -->
                <div class="panel">
                    <div class="panel-title">Status</div>
                    <div style="margin-bottom: 1rem;">
                        <div class="info-label" style="margin-bottom:0.4rem;">Status Penyewaan</div>
                        <span class="status-chip chip-{{ strtolower($rental->status->value) }}">{{ $rental->status->label() }}</span>
                    </div>
                    @if($rental->payment)
                        <div>
                            <div class="info-label" style="margin-bottom:0.4rem;">Status Pembayaran</div>
                            <span class="status-chip chip-{{ $rental->payment->status->value }}">{{ $rental->payment->status->label() }}</span>
                        </div>
                    @endif
                </div>

                <!-- Payment Info Panel -->
                @if($rental->payment)
                    <div class="panel">
                        <div class="panel-title">Metode Pembayaran</div>
                        <div class="payment-method-icon">💳</div>
                        <div class="info-value" style="margin-bottom:0.3rem;">Midtrans</div>
                        <div class="info-label">{{ $rental->payment->payment_type ?? 'Midtrans Snap' }}</div>

                        @if($rental->payment->status->value === 'pending')
                            <button id="btnPayNow" class="btn-pay-now" onclick="openPayNow()">
                                BAYAR SEKARANG →
                            </button>
                            <div style="font-size:0.6rem; color:var(--text-muted); margin-top:0.5rem; text-align:center;">
                                Aman & terenkripsi · Powered by Midtrans
                            </div>
                        @endif

                        @if($rental->payment->status->value === 'settlement' && $rental->payment->paid_at)
                            <div style="margin-top:1rem;">
                                <div class="info-label">Lunas Pada</div>
                                <div class="info-value">{{ $rental->payment->paid_at->format('d M Y, H:i') }}</div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(
    $rental->payment && $rental->payment->status->value === 'pending'
)
<script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    function syncMidtransStatus(result = null) {
        return fetch('{{ route('customer.rentals.midtrans.sync', $rental) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                order_id: result?.order_id ?? null,
                transaction_id: result?.transaction_id ?? null,
            }),
        }).then(async (response) => {
            const data = await response.json().catch(() => ({}));
            if (!response.ok) {
                throw new Error(data.error || 'Gagal menyinkronkan status pembayaran.');
            }

            return data;
        });
    }
</script>
@endif

@if($rental->payment && $rental->payment->status->value === 'pending')
<script>
    function openPayNow() {
        const btn = document.getElementById('btnPayNow');
        btn.disabled = true;
        btn.textContent = 'MEMPROSES...';

        fetch('{{ route('customer.rentals.midtrans.token', $rental) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        })
        .then(async (response) => {
            const data = await response.json().catch(() => ({}));
            if (!response.ok) {
                throw new Error(data.error || 'Gagal memuat pembayaran.');
            }

            return data;
        })
        .then(data => {
            if (data.snap_token) {
                snap.pay(data.snap_token, {
                    onSuccess: (result) => {
                        syncMidtransStatus(result).finally(() => window.location.reload());
                    },
                    onPending: () => { window.location.reload(); },
                    onError:   () => { window.location.reload(); },
                    onClose: () => {
                        btn.disabled = false;
                        btn.textContent = 'BAYAR SEKARANG →';
                    },
                });
            } else {
                alert(data.error || 'Gagal memuat pembayaran.');
                btn.disabled = false;
                btn.textContent = 'BAYAR SEKARANG →';
            }
        })
        .catch(err => {
            console.error(err);
            alert(err.message || 'Gagal memuat pembayaran.');
            btn.disabled = false;
            btn.textContent = 'BAYAR SEKARANG →';
        });
    }

    @if($rental->payment->snap_token)
    syncMidtransStatus()
        .then((data) => {
            if (data.status && data.status !== 'pending') {
                window.location.reload();
            }
        })
        .catch((err) => {
            console.error(err);
        });
    @endif
</script>
@endif
</x-layouts.app>
