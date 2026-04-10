{{-- Partial: home/partials/catalog-grid.blade.php
     Digunakan untuk render awal DAN respon AJAX filter/pagination --}}

<div class="d-flex justify-content-between align-items-center mb-4">
    <span class="text-muted" style="font-size:0.8rem;letter-spacing:0.05em;">
        {{ $totalItems }} KOSTUM DITEMUKAN
        @if($filters['category'])
            &mdash; <em>{{ $filters['category'] }}</em>
        @endif
    </span>
    @if($filters['search'] || $filters['category'])
        <a href="#" class="js-clear-filters"
           style="font-size:0.7rem;font-weight:600;letter-spacing:0.1em;color:var(--text-muted);text-decoration:none;text-transform:uppercase;">
            &times; Hapus Filter
        </a>
    @endif
</div>

<div class="row g-4">
    @forelse ($catalog as $costume)
        @php
            $isAvail     = $costume->available_stock > 0;
            $imgIndex    = ($loop->index % 4) + 1;
            $imgSrc      = asset("images/kebaya{$imgIndex}.png");
            $placeholder = 'https://placehold.co/400x500/f0ece1/580d21?text=' . urlencode($costume->category);
        @endphp
        <div class="col-sm-6 col-lg-4">
            <div class="product-card">
                <div class="product-img-wrapper">
                    <span class="product-badge {{ $isAvail ? 'badge-available' : 'badge-rented' }}">
                        {{ $isAvail ? 'TERSEDIA' : 'HABIS' }}
                    </span>
                    <a href="{{ route('catalog.show', $costume) }}">
                        <img src="{{ $costume->image ? asset('storage/'.$costume->image) : $imgSrc }}"
                             alt="{{ $costume->name }}"
                             class="product-img"
                             onerror="this.src='{{ $placeholder }}'">
                    </a>
                </div>
                <h3 class="product-title">
                    <a href="{{ route('catalog.show', $costume) }}" style="text-decoration:none; color:inherit;">{{ $costume->name }}</a>
                </h3>
                <p class="product-desc">
                    {{ $costume->category }}
                    @if($isAvail)
                        &middot; {{ $costume->available_stock }} tersisa
                    @else
                        &middot; Stok penuh
                    @endif
                </p>
                <div class="product-price">
                    Rp {{ number_format((float) $costume->rental_price, 0, ',', '.') }}
                    <span class="product-price-unit">/ sewa</span>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <p style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--text-muted);">
                Tidak ada kostum yang sesuai filter.
            </p>
            <a href="#" class="js-clear-filters btn-primary-custom mt-3 d-inline-block">
                LIHAT SEMUA
            </a>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($totalPages > 1)
<div class="catalog-pagination" style="display:flex;justify-content:center;gap:0.5rem;margin-top:3rem;flex-wrap:wrap;">
    @if($currentPage > 1)
        <a href="#" class="page-btn js-page-link" data-page="{{ $currentPage - 1 }}" aria-label="Halaman sebelumnya">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
            </svg>
        </a>
    @endif

    @php
        $start = max(1, $currentPage - 2);
        $end   = min($totalPages, $currentPage + 2);
    @endphp

    @if($start > 1)
        <a href="#" class="page-btn js-page-link" data-page="1">1</a>
        @if($start > 2)<span class="page-btn" style="border:none;cursor:default;">…</span>@endif
    @endif

    @for($p = $start; $p <= $end; $p++)
        <a href="#" class="page-btn js-page-link {{ $p === $currentPage ? 'active' : '' }}" data-page="{{ $p }}">
            {{ $p }}
        </a>
    @endfor

    @if($end < $totalPages)
        @if($end < $totalPages - 1)
            <span class="page-btn" style="border:none;cursor:default;">…</span>
        @endif
        <a href="#" class="page-btn js-page-link" data-page="{{ $totalPages }}">{{ $totalPages }}</a>
    @endif

    @if($currentPage < $totalPages)
        <a href="#" class="page-btn js-page-link" data-page="{{ $currentPage + 1 }}" aria-label="Halaman berikutnya">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
            </svg>
        </a>
    @endif
</div>
@endif
