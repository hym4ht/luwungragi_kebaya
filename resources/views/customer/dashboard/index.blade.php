<x-layouts.app title="Pesanan Saya">
<style>
    /* Reset & Variabel */
    :root {
        --bg-body: #f8f9fa;
        --bg-card: #ffffff;
        --text-main: #111827;
        --text-muted: #6b7280;
        --brand-maroon: #580d21;
        --border-soft: #f1f1f1;
    }

    /* Memastikan body tidak tembus pandang */
    body { 
        background-color: var(--bg-body) !important; 
        font-family: 'Inter', system-ui, sans-serif;
    }

    .orders-wrapper {
        padding: 2rem 1rem;
        min-height: 100vh;
    }

    /* Main White Sheet */
    .orders-card {
        background: var(--bg-card);
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        max-width: 900px;
        margin: 0 auto;
        overflow: hidden;
    }

    .inner-content {
        padding: 1.5rem;
    }
    @media (min-width: 768px) {
        .inner-content { padding: 3rem; }
    }

    /* Header */
    .header-section { margin-bottom: 2.5rem; }
    .title-text { 
        font-size: 1.75rem; 
        font-weight: 800; 
        color: var(--text-main);
        letter-spacing: -0.03em;
        margin-bottom: 0.5rem;
    }
    .user-greet { font-size: 0.95rem; color: var(--text-muted); }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 3rem;
    }
    @media (min-width: 768px) {
        .stats-grid { grid-template-columns: repeat(4, 1fr); gap: 2rem; }
    }

    .stat-node {
        padding: 1rem;
        background: #fafafa;
        border-radius: 8px;
    }
    .stat-label { font-size: 0.7rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; display: block; }
    .stat-val { font-size: 1.1rem; font-weight: 700; color: var(--brand-maroon); }

    /* List Item */
    .order-row {
        display: flex;
        flex-direction: column;
        padding: 1.5rem 0;
        border-bottom: 1px solid var(--border-soft);
        gap: 1rem;
    }
    @media (min-width: 640px) {
        .order-row { flex-direction: row; align-items: center; justify-content: space-between; }
    }
    .order-row:last-child { border-bottom: none; }

    .info-box { flex: 1; }
    .inv-no { font-size: 0.75rem; color: var(--text-muted); font-family: monospace; }
    .costume-list { 
        display: block; 
        font-size: 1.05rem; 
        font-weight: 600; 
        color: var(--text-main);
        margin: 0.25rem 0 0.75rem 0;
    }

    .pill-group { display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; }
    .pill { 
        font-size: 0.7rem; font-weight: 700; padding: 0.25rem 0.6rem; 
        border-radius: 6px; background: #f3f4f6; color: #4b5563; 
    }
    
    /* Specific status colors */
    .pill-pending { background: #fffbeb; color: #b45309; }
    .pill-active { background: #eff6ff; color: #1d4ed8; }
    .pill-completed { background: #f0fdf4; color: #15803d; }

    .price-action { text-align: left; }
    @media (min-width: 640px) { .price-action { text-align: right; } }

    .total-price { font-weight: 800; font-size: 1.1rem; color: var(--text-main); display: block; margin-bottom: 0.75rem; }
    
    .btn-outline {
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--brand-maroon);
        border: 1.5px solid var(--brand-maroon);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .btn-outline:hover { background: var(--brand-maroon); color: white; }

    .btn-primary {
        background: var(--brand-maroon);
        color: white;
        padding: 0.55rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        margin-right: 0.5rem;
    }

    .empty-msg { text-align: center; padding: 4rem 0; color: var(--text-muted); }
</style>

<div class="orders-wrapper">
    <div class="orders-card">
        <div class="inner-content">
            
            <header class="header-section">
                <h1 class="title-text">Pesanan Saya</h1>
                <p class="user-greet">Halo {{ explode(' ', auth()->user()->name)[0] }}, kelola jadwal sewa busana Anda di sini.</p>
            </header>

            <div class="stats-grid">
                <div class="stat-node">
                    <span class="stat-label">Menunggu</span>
                    <span class="stat-val">{{ $summary['pending'] }}</span>
                </div>
                <div class="stat-node">
                    <span class="stat-label">Aktif</span>
                    <span class="stat-val">{{ $summary['active'] }}</span>
                </div>
                <div class="stat-node">
                    <span class="stat-label">Selesai</span>
                    <span class="stat-val">{{ $summary['completed'] }}</span>
                </div>
                <div class="stat-node">
                    <span class="stat-label">Total Dibayar</span>
                    <span class="stat-val">Rp{{ number_format($summary['total_spent'], 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="order-list">
                @forelse($rentals as $rental)
                    <div class="order-row">
                        <div class="info-box">
                            <span class="inv-no">#{{ $rental->invoice_number }} &bull; {{ $rental->created_at->format('d M Y') }}</span>
                            <span class="costume-list">{{ $rental->details->pluck('costume.name')->implode(', ') }}</span>
                            <div class="pill-group">
                                <span class="pill pill-{{ strtolower($rental->status->value) }}">
                                    {{ $rental->status->label() }}
                                </span>
                                <span style="font-size: 0.8rem; color: var(--text-muted)">
                                    📅 {{ $rental->usage_date->format('d M') }} — {{ $rental->return_due_date->format('d M') }}
                                </span>
                            </div>
                        </div>

                        <div class="price-action">
                            <span class="total-price">Rp{{ number_format((float) $rental->total_price, 0, ',', '.') }}</span>
                            <div style="display: flex; align-items: center; justify-content: inherit;">
                                @if($rental->payment?->status?->value === 'pending')
                                    <a href="{{ route('customer.rentals.show', $rental) }}" class="btn-primary">Bayar</a>
                                @endif
                                <a href="{{ route('customer.rentals.show', $rental) }}" class="btn-outline">Detail →</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-msg">
                        <p>Belum ada riwayat pemesanan.</p>
                        <a href="{{ route('home') }}" style="color: var(--brand-maroon); font-weight: 600;">Cari Busana →</a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>
</x-layouts.app>