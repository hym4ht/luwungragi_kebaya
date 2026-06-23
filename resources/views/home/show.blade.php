<x-layouts.app title="{{ $costume->name }}">
<style>
    :root {
        --brand-maroon: #580d21;
        --bg-cream: #FDFBF7;
        --text-dark: #2c2c2c;
        --text-muted: #79665e;
    }
    body {
        background-color: var(--bg-cream) !important;
        font-family: 'Montserrat', sans-serif;
    }
    .product-detail-container {
        padding: 2rem 0;
    }
    .breadcrumb-custom {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        margin-bottom: 1.5rem;
    }
    .breadcrumb-custom a {
        color: var(--text-muted);
        text-decoration: none;
    }
    .breadcrumb-custom span {
        color: var(--text-dark);
    }
    .gallery-col {
        display: flex;
        gap: 1rem;
    }
    .thumbnail-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        width: 70px;
    }
    .thumbnail-img {
        width: 100%;
        aspect-ratio: 4/5;
        object-fit: cover;
        background-color: transparent;
        cursor: pointer;
        opacity: 0.6;
        transition: opacity 0.3s;
    }
    .thumbnail-img:hover, .thumbnail-img.active {
        opacity: 1;
    }
    .main-img-wrapper {
        flex: 1;
        background-color: transparent;
        position: relative;
        max-width: 450px;
    }
    .main-img {
        width: 100%;
        height: auto;
        object-fit: cover;
        aspect-ratio: 4/5;
    }
    .product-info-col {
        padding-left: 2rem;
    }
    @media (max-width: 991px) {
        .product-info-col {
            padding-left: 0;
            margin-top: 2rem;
        }
        .gallery-col {
            flex-direction: column-reverse;
        }
        .thumbnail-list {
            width: 100%;
            flex-direction: row;
        }
        .thumbnail-img {
            width: 70px;
        }
        .main-img-wrapper {
            max-width: 100%;
        }
    }
    .badge-collection {
        background-color: #8c6e4e;
        color: white;
        font-size: 0.55rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        padding: 0.25rem 0.5rem;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 0.75rem;
    }
    .product-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        color: var(--brand-maroon);
        line-height: 1.1;
        margin-bottom: 0.5rem;
    }
    .price-wrap {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .price {
        font-size: 1.25rem;
        color: var(--text-dark);
        font-weight: 600;
    }
    .price span {
        font-size: 0.75rem;
        color: var(--text-muted);
    }
    .rating {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-left: 1rem;
        border-left: 1px solid #ddd;
        padding-left: 1rem;
    }
    .product-desc {
        color: var(--text-muted);
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 1.5rem;
    }
    .section-label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
        display: flex;
        justify-content: space-between;
    }
    .section-label a {
        color: var(--text-muted);
        text-decoration: underline;
        font-size: 0.65rem;
    }
    .size-selector {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .size-btn {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(88,13,33,0.2);
        background: transparent;
        font-size: 0.8rem;
        color: var(--text-dark);
        cursor: pointer;
        transition: all 0.2s;
    }
    .size-btn:hover, .size-btn.active {
        border-color: var(--brand-maroon);
        border-width: 2px;
        font-weight: 600;
    }
    .stock-warning {
        font-size: 0.65rem;
        color: var(--brand-maroon);
        font-weight: 600;
        letter-spacing: 0.05em;
        margin-bottom: 1.5rem;
    }
    .stock-warning::before {
        content: '● ';
    }
    .rental-box {
        background-color: #f6f3eb;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .date-row {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding-bottom: 1rem;
    }
    .qty-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .qty-label {
        font-size: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--text-muted);
        flex: 1;
    }
    .qty-control {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .qty-btn {
        width: 28px; height: 28px;
        border: 1px solid rgba(88,13,33,0.3);
        background: transparent;
        color: var(--brand-maroon);
        font-size: 1rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .qty-btn:hover { background: var(--brand-maroon); color: white; }
    .qty-display {
        font-size: 0.9rem;
        font-weight: 600;
        min-width: 24px;
        text-align: center;
    }
    .date-col {
        flex: 1;
    }
    .date-label {
        font-size: 0.55rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
        display: block;
    }
    .date-input {
        background: transparent;
        border: none;
        border-bottom: 1px solid rgba(0,0,0,0.1);
        width: 100%;
        padding: 0.25rem 0;
        font-size: 0.8rem;
        color: var(--text-dark);
    }
    .date-input:focus {
        outline: none;
        border-color: var(--brand-maroon);
    }
    .fee-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
    }
    .total-row {
        display: flex;
        justify-content: space-between;
        font-size: 1.1rem;
        color: var(--text-dark);
        font-weight: 600;
        margin-top: 1rem;
        align-items: center;
    }
    .total-row span {
        font-family: 'Playfair Display', serif;
    }

    /* Payment Method Cards */
    .payment-section-title {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
    }
    .payment-cards {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }
    .payment-card {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border: 1.5px solid rgba(88,13,33,0.15);
        background: white;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
    }
    .payment-card:hover {
        border-color: rgba(88,13,33,0.4);
    }
    .payment-card.selected {
        border-color: var(--brand-maroon);
        background: rgba(88,13,33,0.03);
    }
    .payment-card input[type="radio"] {
        accent-color: var(--brand-maroon);
        width: 16px; height: 16px;
        cursor: pointer;
    }
    .payment-card-icon {
        font-size: 1.25rem;
        width: 28px;
        text-align: center;
    }
    .payment-card-body {
        flex: 1;
    }
    .payment-card-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-dark);
        display: block;
    }
    .payment-card-desc {
        font-size: 0.65rem;
        color: var(--text-muted);
    }
    .payment-card-badge {
        font-size: 0.55rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.15rem 0.4rem;
        border-radius: 2px;
    }
    .badge-midtrans { background: #003f5c; color: white; }
    .badge-transfer { background: #c59b2b; color: white; }
    .badge-onsite   { background: #3a3a3a; color: white; }

    /* Upload area */
    .upload-area {
        border: 1.5px dashed rgba(88,13,33,0.3);
        background: white;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s;
        margin-bottom: 1rem;
    }
    .upload-area:hover { border-color: var(--brand-maroon); }
    .upload-area input { display: none; }
    .upload-area-label {
        font-size: 0.7rem;
        color: var(--text-muted);
        cursor: pointer;
    }
    .upload-preview {
        max-width: 100%;
        max-height: 120px;
        object-fit: contain;
        display: none;
        margin: 0.5rem auto;
    }

    /* Login prompt */
    .login-prompt {
        background: rgba(88,13,33,0.05);
        border: 1px solid rgba(88,13,33,0.15);
        padding: 1rem;
        text-align: center;
        margin-bottom: 1rem;
    }
    .login-prompt p {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }
    .login-prompt a {
        color: var(--brand-maroon);
        font-weight: 600;
        text-decoration: none;
    }

    .btn-book {
        background-color: var(--brand-maroon);
        color: white;
        width: 100%;
        padding: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-book:hover { background-color: #3f0917; }
    .btn-book:disabled { background-color: #aaa; cursor: not-allowed; }

    .accordion.mt-5 { margin-top: 2rem !important; }
    .accordion-item {
        background: transparent;
        border: none;
        border-bottom: 1px solid rgba(88,13,33,0.1);
    }
    .accordion-button {
        background: transparent !important;
        color: var(--text-dark) !important;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        padding: 1rem 0;
        text-transform: uppercase;
        box-shadow: none !important;
    }
    .accordion-button::after { background-size: 0.7rem; }
    .accordion-body {
        padding: 0 0 1rem 0;
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.6;
    }

    /* Alert */
    .alert-booking {
        font-size: 0.75rem;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        border-left: 3px solid var(--brand-maroon);
        background: rgba(88,13,33,0.05);
        color: var(--text-dark);
    }
</style>

<div class="product-detail-container">
    <div class="container-fluid px-4 px-lg-5">
        <div class="breadcrumb-custom">
            <a href="{{ route('home') }}">HOME</a> > 
            <a href="{{ route('home') }}#catalog">CATALOG</a> > 
            <span>{{ strtoupper($costume->name) }}</span>
        </div>

        <div class="row">
            <!-- Left: Gallery -->
            <div class="col-lg-6 gallery-col">
                @php
                    $images = [];
                    if($costume->image) $images[] = asset('storage/'.$costume->image);
                    if($costume->image_2) $images[] = asset('storage/'.$costume->image_2);
                    if($costume->image_3) $images[] = asset('storage/'.$costume->image_3);
                    if($costume->image_4) $images[] = asset('storage/'.$costume->image_4);

                    if(empty($images)) {
                        $images[] = 'https://placehold.co/400x500/f0ece1/580d21?text='.urlencode($costume->category);
                    }
                    $mainImg = $images[0];
                @endphp
                <div class="thumbnail-list">
                    @foreach($images as $idx => $imgSrc)
                        <img src="{{ $imgSrc }}" class="thumbnail-img {{ $idx === 0 ? 'active' : '' }}" alt="Thumb {{ $idx + 1 }}" onclick="changeMainImage('{{ $imgSrc }}', this)">
                    @endforeach
                </div>
                <!-- Main Image -->
                <div class="main-img-wrapper">
                    <img src="{{ $mainImg }}" class="main-img" id="mainImageDisplay" alt="{{ $costume->name }}">
                    <span style="position: absolute; bottom: 1rem; left: 1rem; background: rgba(255,255,255,0.8); padding: 0.5rem 1rem; font-size: 0.7rem; font-weight: 600; letter-spacing: 0.1em; color: var(--text-muted);">ARTISANAL COLLECTION</span>
                </div>
            </div>

            <!-- Right: Info -->
            <div class="col-lg-5 product-info-col">
                <span class="badge-collection">{{ $costume->category }}</span>
                <h1 class="product-title">{{ $costume->name }}</h1>
                
                <div class="price-wrap">
                    <div class="price">IDR {{ number_format((float)$costume->rental_price, 0, ',', '.') }} <span>/ sesi ({{ \App\Models\Rental::SESSION_DAYS }} hari)</span></div>
                    <div class="rating">
                        <span style="color: #d4af37;">★</span> 4.9 (128 Reviews)
                    </div>
                </div>

                <p class="product-desc">
                    {{ $costume->description ?? 'A masterpiece of Javanese sartorial tradition. Crafted from premium materials with exquisite detailing, ensuring an elegant silhouette.' }}
                </p>

                <!-- Size Selector -->
                <div class="section-label">
                    SELECT SIZE <a href="#">SIZE GUIDE</a>
                </div>
                <div class="size-selector">
                    @php
                        $sizes = $costume->sizes ? explode(',', $costume->sizes) : ['S', 'M', 'L', 'XL'];
                    @endphp
                    @foreach($sizes as $index => $size)
                        <button type="button" class="size-btn {{ $index === 1 ? 'active' : '' }}">{{ trim($size) }}</button>
                    @endforeach
                </div>
                <div class="stock-warning">
                    @if($costume->stock > 0)
                        ONLY {{ $costume->stock }} PIECES LEFT IN STOCK
                    @else
                        OUT OF STOCK
                    @endif
                </div>

                @if(session('success'))
                    <div class="alert-booking">✓ {{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert-booking" style="border-color: #c0392b; background: rgba(192,57,43,0.05);">
                        @foreach($errors->all() as $error)
                            <div>⚠ {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- Booking Form -->
                @guest
                    <div class="login-prompt">
                        <p>Silakan login untuk melakukan pemesanan busana.</p>
                        <a href="{{ route('auth.login') }}">LOGIN</a> &nbsp;·&nbsp; <a href="{{ route('auth.register') }}">DAFTAR AKUN</a>
                    </div>
                @endguest

                @auth
                <form id="bookingForm" action="{{ route('customer.checkout.show') }}" method="GET">
                    <input type="hidden" name="costume_id" value="{{ $costume->id }}">
                    <input type="hidden" name="quantity" id="quantityInput" value="1">
                    <input type="hidden" name="sessions" id="sessions" value="1">
                    
                    <div class="rental-box">
                        <!-- Date Row -->
                        <div class="date-row">
                            <div class="date-col">
                                <label class="date-label" for="event_date">TANGGAL MULAI SEWA</label>
                                <input
                                    type="date"
                                    name="event_date"
                                    id="event_date"
                                    class="date-input"
                                    value="{{ old('event_date', now()->addDays(\App\Models\Rental::BOOKING_BUFFER_DAYS)->toDateString()) }}"
                                    min="{{ now()->addDays(\App\Models\Rental::BOOKING_BUFFER_DAYS)->toDateString() }}"
                                    required
                                >
                            </div>
                            <div class="date-col">
                                <label class="date-label">JUMLAH SESI</label>
                                <div class="date-input mt-1" style="border-bottom: none; font-size: 0.8rem; font-weight: 600;">
                                    1 Sesi ({{ \App\Models\Rental::SESSION_DAYS }} hari)
                                </div>
                            </div>
                        </div>

                        <!-- Quantity Row -->
                        <div class="qty-row">
                            <span class="qty-label">JUMLAH SEWA</span>
                            <div class="qty-control">
                                <button type="button" class="qty-btn" id="qtyMinus">−</button>
                                <span class="qty-display" id="qtyDisplay">1</span>
                                <button type="button" class="qty-btn" id="qtyPlus">+</button>
                            </div>
                        </div>

                        <!-- Price Summary -->
                        <div class="fee-row">
                            <span>Harga per sesi × qty</span>
                            <span>IDR <span id="base_price_display">{{ number_format((float)$costume->rental_price, 0, ',', '.') }}</span></span>
                        </div>
                        <div class="total-row">
                            <span>Total</span>
                            <span>IDR <span id="total_price_display">{{ number_format((float) $costume->rental_price, 0, ',', '.') }}</span></span>
                        </div>
                        <div class="fee-row mt-2">
                            <span>Alur otomatis</span>
                            <span id="schedule_preview">Order H-3 · Lunas H-2 · Ambil H-1 · Kembali H+{{ \App\Models\Rental::SESSION_DAYS }}</span>
                        </div>
                        <div class="fee-row">
                            <span>Denda keterlambatan</span>
                            <span>Rp{{ number_format(\App\Models\Rental::LATE_FEE_PER_DAY, 0, ',', '.') }}/hari</span>
                        </div>
                    </div>

                    <button type="submit" id="btnBook" class="btn-book" {{ $costume->stock < 1 ? 'disabled' : '' }}>
                        {{ $costume->stock < 1 ? 'HABIS DISEWA' : 'LANJUT KE CHECKOUT →' }}
                    </button>
                </form>
                @endauth

                <!-- Accordions -->
                <div class="accordion mt-5" id="productAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMaterials">
                                MATERIALS & CRAFTSMANSHIP
                            </button>
                        </h2>
                        <div id="collapseMaterials" class="accordion-collapse collapse" data-bs-parent="#productAccordion">
                            <div class="accordion-body">
                                {!! nl2br(e($costume->materials ?? 'Dibuat dengan bahan pilihan berkualitas premium yang nyaman dipakai harian.')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCare">
                                CARE INSTRUCTIONS
                            </button>
                        </h2>
                        <div id="collapseCare" class="accordion-collapse collapse" data-bs-parent="#productAccordion">
                            <div class="accordion-body">
                                {!! nl2br(e($costume->care_instructions ?? 'Simpan di tempat kering dan sejuk. Gunakan teknik dry clean untuk perawatan maksimal, atau serahkan sepenuhnya pada tim Luwungragi saat pengembalian.')) !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@auth
<!-- Midtrans Snap.js -->
<script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endauth

<script>
    // ─── Gallery ───────────────────────────────────────────────
    function changeMainImage(src, thumbElement) {
        document.getElementById('mainImageDisplay').src = src;
        document.querySelectorAll('.thumbnail-img').forEach(el => el.classList.remove('active'));
        if (thumbElement) thumbElement.classList.add('active');
    }

    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // ─── Quantity ──────────────────────────────────────────────
    let qty = 1;
    const maxQty = {{ $costume->stock }};
    const qtyDisplay   = document.getElementById('qtyDisplay');
    const quantityInput = document.getElementById('quantityInput');

    function updateQty(val) {
        qty = Math.max(1, Math.min(maxQty, val));
        if (qtyDisplay) qtyDisplay.textContent = qty;
        if (quantityInput) quantityInput.value = qty;
        calculateTotal();
    }

    document.getElementById('qtyMinus')?.addEventListener('click', () => updateQty(qty - 1));
    document.getElementById('qtyPlus')?.addEventListener('click',  () => updateQty(qty + 1));

    // ─── Price Calculation ─────────────────────────────────────
    const basePrice     = {{ floatval($costume->rental_price) }};
    const sessionDays   = {{ \App\Models\Rental::SESSION_DAYS }};
    const eventInput    = document.getElementById('event_date');
    const sessionsInput = document.getElementById('sessions');
    const totalDisplay  = document.getElementById('total_price_display');
    const daysDisplay   = document.getElementById('days_display');
    const schedulePreview = document.getElementById('schedule_preview');

    function formatShortDate(date) {
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    }

    function getSessions() {
        return Math.max({{ \App\Models\Rental::MIN_SESSIONS }}, parseInt(sessionsInput?.value || '1', 10) || 1);
    }

    function updateSchedulePreview() {
        if (!eventInput || !schedulePreview || !eventInput.value) return;

        const eventDate = new Date(eventInput.value + 'T00:00:00');
        if (isNaN(eventDate)) {
            schedulePreview.textContent = 'Order H-3 · Lunas H-2 · Ambil H-1 · Kembali H+{{ \App\Models\Rental::SESSION_DAYS }}';
            return;
        }

        const sessions   = getSessions();
        const totalDays  = sessions * sessionDays;

        const bookingDate = new Date(eventDate);
        bookingDate.setDate(bookingDate.getDate() - {{ \App\Models\Rental::BOOKING_BUFFER_DAYS }});

        const paymentDate = new Date(eventDate);
        paymentDate.setDate(paymentDate.getDate() - {{ \App\Models\Rental::PAYMENT_BUFFER_DAYS }});

        const pickupDate = new Date(eventDate);
        pickupDate.setDate(pickupDate.getDate() - {{ \App\Models\Rental::PICKUP_BUFFER_DAYS }});

        const usageEndDate = new Date(eventDate);
        usageEndDate.setDate(usageEndDate.getDate() + totalDays - 1);

        const returnDate = new Date(usageEndDate);
        returnDate.setDate(returnDate.getDate() + {{ \App\Models\Rental::RETURN_BUFFER_DAYS }});

        if (daysDisplay) daysDisplay.textContent = totalDays;

        schedulePreview.textContent =
            `Order ${formatShortDate(bookingDate)} · Lunas max ${formatShortDate(paymentDate)} · Ambil ${formatShortDate(pickupDate)} · Sewa s/d ${formatShortDate(usageEndDate)} · Kembali ${formatShortDate(returnDate)}`;
    }

    function calculateTotal() {
        const sessions  = getSessions();
        const total     = basePrice * qty * sessions;
        if (totalDisplay) totalDisplay.textContent = total.toLocaleString('id-ID');
        updateSchedulePreview();
    }

    function previewIdentityCard(input) {
        const preview = document.getElementById('identityPreview');
        const label = document.getElementById('uploadLabel');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                label.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none';
            label.style.display = 'block';
        }
    }

    eventInput?.addEventListener('change', calculateTotal);
    sessionsInput?.addEventListener('change', calculateTotal);
    calculateTotal();
</script>
</x-layouts.app>
