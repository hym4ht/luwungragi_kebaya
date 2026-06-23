<x-layouts.app title="Checkout — {{ $costume->name }}">
<style>
    :root {
        --brand-maroon: #580d21;
        --bg-cream: #FDFBF7;
        --text-dark: #2c2c2c;
        --text-muted: #79665e;
        --border-light: rgba(88,13,33,0.1);
    }
    body {
        background-color: var(--bg-cream) !important;
        font-family: 'Montserrat', sans-serif;
    }

    /* ── Page Layout ─────────────────────────────── */
    .checkout-wrap {
        padding: 2.5rem 0 4rem;
        min-height: 80vh;
    }
    .checkout-step-bar {
        display: flex;
        align-items: center;
        gap: 0;
        margin-bottom: 2.5rem;
    }
    .step {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-muted);
    }
    .step.active { color: var(--brand-maroon); }
    .step-num {
        width: 24px; height: 24px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.65rem;
        font-weight: 800;
        background: rgba(88,13,33,0.08);
        color: var(--text-muted);
        flex-shrink: 0;
    }
    .step.active .step-num { background: var(--brand-maroon); color: white; }
    .step.done .step-num { background: #065f46; color: white; }
    .step.done { color: #065f46; }
    .step-divider {
        flex: 1;
        height: 1px;
        background: var(--border-light);
        margin: 0 0.75rem;
    }

    /* ── Panel ─────────────────────────────────── */
    .panel {
        background: white;
        border: 1px solid var(--border-light);
        border-radius: 16px;
        padding: 1.75rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 8px 30px rgba(78,43,26,0.04);
    }
    .panel-title {
        font-size: 0.6rem;
        font-weight: 800;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-light);
    }

    /* ── Product Summary Card ───────────────────── */
    .costume-summary {
        display: flex;
        gap: 1.25rem;
        align-items: flex-start;
    }
    .costume-summary__img {
        width: 80px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
        border: 1px solid var(--border-light);
    }
    .costume-summary__body { flex: 1; min-width: 0; }
    .costume-summary__name {
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.2rem;
        line-height: 1.25;
    }
    .costume-summary__cat {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.6rem;
    }
    .costume-summary__price {
        font-size: 1rem;
        font-weight: 700;
        color: var(--brand-maroon);
    }
    .costume-summary__price span {
        font-size: 0.65rem;
        font-weight: 400;
        color: var(--text-muted);
    }

    /* ── Schedule Grid ─────────────────────────── */
    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    @media (min-width: 768px) { .schedule-grid { grid-template-columns: repeat(4, 1fr); } }
    .schedule-item {}
    .schedule-item__label {
        font-size: 0.58rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.2rem;
    }
    .schedule-item__value {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--text-dark);
    }
    .schedule-item__value.highlight { color: var(--brand-maroon); }

    /* ── Price Breakdown ───────────────────────── */
    .price-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: var(--text-muted);
        padding: 0.4rem 0;
        border-bottom: 1px solid rgba(88,13,33,0.05);
    }
    .price-line:last-child { border-bottom: none; }
    .price-line.total {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--text-dark);
        padding-top: 0.75rem;
        margin-top: 0.25rem;
        border-top: 1.5px solid var(--border-light);
        border-bottom: none;
    }
    .price-line.total span:last-child { color: var(--brand-maroon); }

    /* ── Upload Area ───────────────────────────── */
    .upload-zone {
        border: 2px dashed rgba(88,13,33,0.25);
        border-radius: 12px;
        padding: 2rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        background: rgba(88,13,33,0.02);
        position: relative;
    }
    .upload-zone:hover {
        border-color: var(--brand-maroon);
        background: rgba(88,13,33,0.04);
    }
    .upload-zone.has-file {
        border-color: #065f46;
        background: rgba(6,95,70,0.03);
    }
    .upload-zone.has-error {
        border-color: #991b1b;
        background: rgba(153,27,27,0.03);
    }
    .upload-zone input[type="file"] { display: none; }
    .upload-zone__icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
    }
    .upload-zone__title {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }
    .upload-zone__sub {
        font-size: 0.68rem;
        color: var(--text-muted);
        line-height: 1.4;
    }
    .upload-preview-wrap {
        display: none;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }
    .upload-preview-img {
        max-height: 160px;
        max-width: 100%;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid var(--border-light);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .upload-preview-name {
        font-size: 0.7rem;
        font-weight: 600;
        color: #065f46;
    }
    .upload-change-btn {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--brand-maroon);
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        text-decoration: underline;
    }

    /* ── Info Note ─────────────────────────────── */
    .info-note {
        background: rgba(88,13,33,0.04);
        border-left: 3px solid var(--brand-maroon);
        padding: 0.85rem 1rem;
        border-radius: 0 8px 8px 0;
        font-size: 0.72rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin-bottom: 1.25rem;
    }
    .info-note strong { color: var(--brand-maroon); }

    /* ── Submit Button ─────────────────────────── */
    .btn-confirm {
        display: block;
        width: 100%;
        padding: 1rem 2rem;
        background: var(--brand-maroon);
        color: white;
        border: none;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.25s, transform 0.1s;
        border-radius: 4px;
        margin-top: 1.25rem;
    }
    .btn-confirm:hover { background: #3f0917; }
    .btn-confirm:active { transform: scale(0.99); }
    .btn-confirm:disabled { background: #aaa; cursor: not-allowed; }

    .btn-back-link {
        display: block;
        text-align: center;
        margin-top: 0.85rem;
        font-size: 0.68rem;
        font-weight: 600;
        color: var(--text-muted);
        text-decoration: none;
        letter-spacing: 0.08em;
    }
    .btn-back-link:hover { color: var(--brand-maroon); }

    /* ── Error ─────────────────────────────────── */
    .field-error {
        font-size: 0.68rem;
        color: #991b1b;
        margin-top: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
</style>

<div class="checkout-wrap">
    <div class="container" style="max-width: 900px;">

        {{-- Step Indicator --}}
        <div class="checkout-step-bar">
            <div class="step done">
                <div class="step-num">✓</div>
                <span>Pilih Produk</span>
            </div>
            <div class="step-divider"></div>
            <div class="step done">
                <div class="step-num">✓</div>
                <span>Tentukan Tanggal</span>
            </div>
            <div class="step-divider"></div>
            <div class="step active">
                <div class="step-num">3</div>
                <span>Checkout</span>
            </div>
            <div class="step-divider"></div>
            <div class="step">
                <div class="step-num">4</div>
                <span>Pembayaran</span>
            </div>
        </div>

        <div class="row g-4">
            {{-- LEFT: Form KTP --}}
            <div class="col-lg-7">

                {{-- Identity Card Upload --}}
                <div class="panel">
                    <div class="panel-title">Identitas Jaminan</div>

                    <div class="info-note">
                        <strong>⚠ Wajib:</strong> Pengambilan kostum dilakukan secara offline dan <strong>harus disertai penyerahan identitas asli</strong> (KTP/SIM/Kartu Pelajar) sebagai jaminan selama masa penyewaan. Upload foto identitas terlebih dahulu.
                    </div>

                    <form id="checkoutForm"
                          action="{{ route('customer.checkout.store') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="costume_id"  value="{{ $validated['costume_id'] }}">
                        <input type="hidden" name="event_date"  value="{{ $validated['event_date'] }}">
                        <input type="hidden" name="sessions"    value="{{ $sessions }}">
                        <input type="hidden" name="quantity"    value="{{ $quantity }}">

                        <label style="font-size:0.65rem; font-weight:800; letter-spacing:0.12em; text-transform:uppercase; color:var(--text-muted); display:block; margin-bottom:0.75rem;">
                            Foto KTP / SIM / Kartu Pelajar <span style="color:#991b1b;">*</span>
                        </label>

                        <div class="upload-zone {{ $errors->has('identity_card') ? 'has-error' : '' }}"
                             id="uploadZone"
                             onclick="document.getElementById('identityCardInput').click()">
                            <input type="file"
                                   name="identity_card"
                                   id="identityCardInput"
                                   accept="image/jpeg,image/png,image/jpg,image/webp"
                                   onchange="handleFileSelect(this)">

                            {{-- Default State --}}
                            <div id="uploadDefault">
                                <div class="upload-zone__icon">🪪</div>
                                <div class="upload-zone__title">Klik untuk pilih foto identitas</div>
                                <div class="upload-zone__sub">Format: JPG, PNG, WEBP &middot; Maksimal 5 MB<br>Pastikan foto jelas dan tidak blur</div>
                            </div>

                            {{-- Preview State --}}
                            <div class="upload-preview-wrap" id="uploadPreview">
                                <img id="previewImg" class="upload-preview-img" src="" alt="Preview Identitas">
                                <div class="upload-preview-name" id="previewName"></div>
                                <button type="button" class="upload-change-btn" onclick="event.stopPropagation(); document.getElementById('identityCardInput').click()">
                                    Ganti foto
                                </button>
                            </div>
                        </div>

                        @error('identity_card')
                            <div class="field-error">⚠ {{ $message }}</div>
                        @enderror

                        @error('quantity')
                            <div class="field-error mt-2">⚠ {{ $message }}</div>
                        @enderror

                        <button type="submit" id="btnConfirm" class="btn-confirm">
                            KONFIRMASI PEMESANAN →
                        </button>
                    </form>

                    <a href="{{ route('catalog.show', $costume) }}" class="btn-back-link">← Kembali ke halaman produk</a>
                </div>

            </div>

            {{-- RIGHT: Order Summary --}}
            <div class="col-lg-5">

                {{-- Costume Card --}}
                <div class="panel">
                    <div class="panel-title">Ringkasan Pesanan</div>
                    <div class="costume-summary">
                        @php
                            $imgSrc = $costume->image
                                ? asset('storage/'.$costume->image)
                                : asset('images/kebaya1.png');
                        @endphp
                        <img src="{{ $imgSrc }}"
                             alt="{{ $costume->name }}"
                             class="costume-summary__img"
                             onerror="this.src='{{ asset('images/kebaya1.png') }}'">
                        <div class="costume-summary__body">
                            <div class="costume-summary__name">{{ $costume->name }}</div>
                            <div class="costume-summary__cat">{{ $costume->category }}</div>
                            <div class="costume-summary__price">
                                Rp{{ number_format((float) $costume->rental_price, 0, ',', '.') }}
                                <span>/ sesi</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Schedule --}}
                <div class="panel">
                    <div class="panel-title">Jadwal Sewa</div>
                    <div class="schedule-grid">
                        <div class="schedule-item">
                            <div class="schedule-item__label">Mulai Sewa</div>
                            <div class="schedule-item__value">{{ $schedule['event_date']->format('d M Y') }}</div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-item__label">Selesai Sewa</div>
                            <div class="schedule-item__value">{{ $schedule['usage_end_date']->format('d M Y') }}</div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-item__label">Ambil Offline</div>
                            <div class="schedule-item__value">{{ $schedule['pickup_date']->format('d M Y') }}</div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-item__label">Batas Kembali</div>
                            <div class="schedule-item__value highlight">{{ $schedule['return_date']->format('d M Y') }}</div>
                        </div>
                    </div>

                    <div style="margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid var(--border-light);">
                        <div class="schedule-item__label" style="margin-bottom:0.25rem;">Batas Pelunasan</div>
                        <div class="schedule-item__value">{{ $schedule['payment_due_date']->format('d M Y') }}</div>
                    </div>
                </div>

                {{-- Price Breakdown --}}
                <div class="panel">
                    <div class="panel-title">Rincian Biaya</div>

                    <div class="price-line">
                        <span>{{ $costume->name }}</span>
                        <span>Rp{{ number_format((float) $costume->rental_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="price-line">
                        <span>Jumlah</span>
                        <span>× {{ $quantity }}</span>
                    </div>
                    <div class="price-line">
                        <span>Sesi</span>
                        <span>{{ $sessions }} Sesi ({{ $sessions * \App\Models\Rental::SESSION_DAYS }} hari)</span>
                    </div>
                    <div class="price-line">
                        <span>Denda keterlambatan</span>
                        <span>Rp{{ number_format(\App\Models\Rental::LATE_FEE_PER_DAY, 0, ',', '.') }}/hari</span>
                    </div>
                    <div class="price-line total">
                        <span>Total Pembayaran</span>
                        <span>Rp{{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function handleFileSelect(input) {
        const zone    = document.getElementById('uploadZone');
        const def     = document.getElementById('uploadDefault');
        const preview = document.getElementById('uploadPreview');
        const img     = document.getElementById('previewImg');
        const name    = document.getElementById('previewName');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function (e) {
                img.src = e.target.result;
                name.textContent = file.name;
                def.style.display = 'none';
                preview.style.display = 'flex';
                zone.classList.add('has-file');
                zone.classList.remove('has-error');
            };
            reader.readAsDataURL(file);
        } else {
            def.style.display = 'block';
            preview.style.display = 'none';
            zone.classList.remove('has-file');
        }
    }

    // Prevent form double submit
    document.getElementById('checkoutForm').addEventListener('submit', function () {
        const btn = document.getElementById('btnConfirm');
        btn.disabled = true;
        btn.textContent = 'MEMPROSES...';
    });
</script>
</x-layouts.app>
