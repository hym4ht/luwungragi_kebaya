<x-layouts.app title="Pesanan Saya">
<style>
    :root {
        --brand-maroon: #580d21;
        --bg-cream: #FDFBF7;
        --text-dark: #2c2c2c;
        --text-muted: #79665e;
    }
    body { background-color: var(--bg-cream) !important; font-family: 'Montserrat', sans-serif; }

    .orders-wrap { padding: 3rem 0; }

    .page-heading {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: var(--brand-maroon);
        margin-bottom: 0.25rem;
    }
    .page-sub {
        font-size: 0.7rem;
        color: var(--text-muted);
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 2.5rem;
    }

    /* Stats bar */
    .stats-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2.5rem;
    }
    @media(max-width:768px){ .stats-bar { grid-template-columns: repeat(2,1fr); } }
    .stat-tile {
        background: white;
        border: 1px solid rgba(88,13,33,0.08);
        padding: 1.25rem;
        text-align: center;
    }
    .stat-tile-value {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        color: var(--brand-maroon);
        line-height: 1;
        margin-bottom: 0.35rem;
    }
    .stat-tile-label {
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-muted);
    }

    /* Order cards */
    .order-card {
        background: white;
        border: 1px solid rgba(88,13,33,0.08);
        margin-bottom: 1rem;
        padding: 1.5rem;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 1rem;
        align-items: start;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .order-card:hover {
        border-color: rgba(88,13,33,0.25);
        box-shadow: 0 4px 20px rgba(88,13,33,0.06);
    }
    .order-invoice {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }
    .order-costumes {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }
    .order-meta {
        font-size: 0.72rem;
        color: var(--text-muted);
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    .order-meta-item { display: flex; align-items: center; gap: 0.35rem; }
    .order-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.75rem;
    }
    .order-total {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        color: var(--text-dark);
        font-weight: 700;
        white-space: nowrap;
    }

    /* Status chips */
    .chip {
        display: inline-block;
        font-size: 0.58rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0.28rem 0.7rem;
    }
    .chip-pending    { background: #fef3c7; color: #92400e; }
    .chip-active     { background: #dbeafe; color: #1e40af; }
    .chip-completed  { background: #f0fdf4; color: #166534; }
    .chip-cancelled  { background: #fee2e2; color: #991b1b; }
    .chip-settlement { background: #d1fae5; color: #065f46; }
    .chip-expire, .chip-cancel { background: #fee2e2; color: #991b1b; }

    .btn-view-order {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--brand-maroon);
        text-decoration: none;
        border: 1.5px solid var(--brand-maroon);
        padding: 0.45rem 1rem;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-view-order:hover {
        background: var(--brand-maroon);
        color: white;
    }

    .btn-pay-chip {
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        background: var(--brand-maroon);
        color: white;
        padding: 0.35rem 0.8rem;
        text-decoration: none;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .btn-pay-chip:hover { background: #3f0917; color: white; }

    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        color: var(--text-muted);
    }
    .empty-state .empty-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.4;
    }
    .empty-state h3 {
        font-family: 'Playfair Display', serif;
        color: var(--brand-maroon);
        margin-bottom: 0.5rem;
    }
    .empty-state p { font-size: 0.85rem; margin-bottom: 1.5rem; }
    .btn-browse {
        display: inline-block;
        background: var(--brand-maroon);
        color: white;
        padding: 0.75rem 2rem;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-browse:hover { background: #3f0917; color: white; }
</style>

<div class="orders-wrap">
    <div class="container" style="max-width: 900px;">
        <h1 class="page-heading">Pesanan Saya</h1>
        <p class="page-sub">Halo, {{ auth()->user()->name }} · Riwayat & status sewa busana Anda</p>

        {{-- Stats bar --}}
        <div class="stats-bar">
            <div class="stat-tile">
                <div class="stat-tile-value">{{ $summary['pending'] }}</div>
                <div class="stat-tile-label">Menunggu</div>
            </div>
            <div class="stat-tile">
                <div class="stat-tile-value">{{ $summary['active'] }}</div>
                <div class="stat-tile-label">Aktif</div>
            </div>
            <div class="stat-tile">
                <div class="stat-tile-value">{{ $summary['completed'] }}</div>
                <div class="stat-tile-label">Selesai</div>
            </div>
            <div class="stat-tile">
                <div class="stat-tile-value">{{ 'Rp'.number_format($summary['total_spent'], 0, ',', '.') }}</div>
                <div class="stat-tile-label">Total Dibayar</div>
            </div>
        </div>

        {{-- Orders List --}}
        @if($rentals->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">🎭</div>
                <h3>Belum Ada Pesanan</h3>
                <p>Jelajahi koleksi busana kami dan buat pesanan pertama Anda.</p>
                <a href="{{ route('home') }}#catalog" class="btn-browse">JELAJAHI CATALOG →</a>
            </div>
        @else
            @foreach($rentals as $rental)
                <div class="order-card">
                    <div>
                        <div class="order-invoice">
                            {{ $rental->invoice_number }} ·
                            {{ $rental->created_at->format('d M Y') }}
                        </div>
                        <div class="order-costumes">
                            @foreach($rental->details as $detail)
                                {{ $detail->costume->name }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </div>
                        <div class="order-meta">
                            <span class="order-meta-item">
                                📅 Sewa {{ $rental->usage_date->format('d M Y') }}@if($rental->rental_duration_days > 1)-{{ $rental->usage_end_date->format('d M Y') }}@endif · Kembali {{ $rental->return_due_date->format('d M Y') }}
                            </span>
                            <span class="order-meta-item">
                                🎭 Status:
                                <span class="chip chip-{{ strtolower($rental->status->value) }}">
                                    {{ $rental->status->label() }}
                                </span>
                            </span>
                            @if($rental->payment)
                                <span class="order-meta-item">
                                    💳 Bayar:
                                    <span class="chip chip-{{ $rental->payment->status->value }}">
                                        {{ $rental->payment->status->label() }}
                                    </span>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="order-actions">
                        <div class="order-total">
                            Rp{{ number_format((float) $rental->total_price, 0, ',', '.') }}
                        </div>
                        @if($rental->payment?->status?->value === 'pending')
                            <a href="{{ route('customer.rentals.show', $rental) }}" class="btn-pay-chip">
                                💳 BAYAR SEKARANG
                            </a>
                        @endif
                        <a href="{{ route('customer.rentals.show', $rental) }}" class="btn-view-order">
                            LIHAT DETAIL →
                        </a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
</x-layouts.app>
