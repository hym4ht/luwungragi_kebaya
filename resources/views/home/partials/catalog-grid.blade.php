{{-- Partial: home/partials/catalog-grid.blade.php
     Digunakan untuk render awal DAN respon AJAX filter/pagination --}}

<div class="catalog-results__meta d-flex justify-content-between align-items-center mb-4">
    <span class="catalog-results__count text-muted">
        {{ $totalItems }} KOSTUM DITEMUKAN
        @if($filters['category'])
            &mdash; <em>{{ $filters['category'] }}</em>
        @endif
    </span>
    @if($filters['search'] || $filters['category'])
        <a href="#" class="catalog-results__clear js-clear-filters">
            &times; Hapus Filter
        </a>
    @endif
</div>

@if($totalItems > 0)
    <div class="row g-4 catalog-grid catalog-grid--desktop">
        @foreach ($catalog as $costume)
            @php
                $isAvail     = $costume->available_stock > 0;
                $imgIndex    = ($loop->index % 4) + 1;
                $imgSrc      = asset("images/kebaya{$imgIndex}.png");
                $placeholder = 'https://placehold.co/400x500/f0ece1/580d21?text=' . urlencode($costume->category);
            @endphp
          <div class="col-sm-6 col-lg" style="flex: 0 0 20%; max-width: 20%;">
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
        @endforeach
    </div>

    <div class="catalog-grid--mobile">
        <div class="row g-4 catalog-mobile__grid js-mobile-catalog-grid" data-batch-size="4">
            @foreach ($fullCatalog as $costume)
                @php
                    $isAvail     = $costume->available_stock > 0;
                    $imgIndex    = ($loop->index % 4) + 1;
                    $imgSrc      = asset("images/kebaya{$imgIndex}.png");
                    $placeholder = 'https://placehold.co/400x500/f0ece1/580d21?text=' . urlencode($costume->category);
                @endphp
                <div class="col-12 js-mobile-catalog-item {{ $loop->index >= 4 ? 'is-hidden' : '' }}">
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
            @endforeach
        </div>

        @if($totalItems > 4)
            <div class="catalog-mobile__footer">
                <button type="button" class="catalog-mobile__viewmore js-mobile-view-more">
                    VIEW MORE
                </button>
            </div>
        @endif
    </div>
@else
    <div class="col-12 text-center py-5 catalog-grid__empty">
        <p style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--text-muted);">
            Tidak ada kostum yang sesuai filter.
        </p>
        <a href="#" class="js-clear-filters btn-primary-custom mt-3 d-inline-block">
            LIHAT SEMUA
        </a>
    </div>
@endif