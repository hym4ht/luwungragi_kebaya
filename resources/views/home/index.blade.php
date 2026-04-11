<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The Grace of Tradition | Luwungragi</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-maroon: #580d21;
            --bg-cream: #FDFBF7;
            --text-dark: #2c2c2c;
            --text-muted: #79665e;
        }
        body {
            background-color: var(--bg-cream);
            font-family: 'Montserrat', sans-serif;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .hero-section {
            display: flex;
            align-items: center;
            min-height: calc(100vh - 90px);
            padding: 2rem 0;
        }
        .hero-eyebrow {
            color: var(--brand-maroon);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
        }
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(3rem, 6vw, 5rem);
            font-weight: 400;
            line-height: 1.1;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }
        .hero-title em {
            color: var(--brand-maroon);
            font-style: italic;
        }
        .hero-desc {
            color: var(--text-muted);
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
            max-width: 480px;
        }
        .btn-primary-custom {
            background-color: var(--brand-maroon);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 1rem 2rem;
            border: 1px solid var(--brand-maroon);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-primary-custom:hover {
            background-color: #3f0917;
            color: white;
        }
        .btn-outline-custom {
            color: var(--brand-maroon);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 1rem 2rem;
            border: transparent;
            border-bottom: 1px solid rgba(88, 13, 33, 0.3);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-outline-custom:hover {
            border-bottom: 1px solid var(--brand-maroon);
        }
        .hero-image {
            width: 100%;
            max-width: 650px;
            height: auto;
            max-height: 80vh;
            object-fit: contain;
            margin: 0 auto;
            display: inline-block;
        }
        @media (min-width: 992px) {
            .hero-image {
                max-width: 100%;
                margin-right: 1rem;
            }
        }
        #heroCarousel .carousel-item {
            transition: opacity 1.5s ease-in-out;
        }
        .about-section {
            background-color: #fffaf5;
            padding: 6rem 0;
            border-top: 1px solid rgba(88, 13, 33, 0.05);
        }
        .about-eyebrow {
            color: var(--brand-maroon);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        .about-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }
        .about-text {
            color: var(--text-muted);
            font-size: 1.15rem;
            line-height: 1.8;
            max-width: 800px;
            margin: 0 auto;
        }
        .about-location {
            color: var(--text-dark);
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(88, 13, 33, 0.1);
            font-size: 1rem;
            line-height: 1.6;
        }
        /* Catalog Section Styles */
        .catalog-section {
            padding: 6rem 0;
            background-color: var(--bg-cream);
        }
        .catalog-header {
            margin-bottom: 4rem;
        }
        .catalog-desktop-stage {
            position: relative;
        }
        .catalog-desktop-shell {
            position: relative;
        }
        .catalog-workspace {
            row-gap: 2rem;
        }
        .catalog-sidebar-column,
        .catalog-results-column {
            min-height: 0;
        }
        .catalog-sidebar__form {
            width: 100%;
        }
        .catalog-sidebar__filters {
            width: 100%;
        }
        .catalog-results-column {
            display: flex;
        }
        .catalog-results-panel {
            width: 100%;
            transition: opacity 0.25s ease;
            min-height: 300px;
        }
        .catalog-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--brand-maroon);
            margin-bottom: 1rem;
        }
        .catalog-subtitle {
            color: var(--text-muted);
            font-size: 1rem;
            max-width: 600px;
            line-height: 1.6;
        }
        .search-input-container {
            position: relative;
        }
        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid rgba(88, 13, 33, 0.1);
            border-radius: 0;
            background-color: #fff;
            font-size: 0.85rem;
            color: var(--text-dark);
        }
        .search-input:focus {
            outline: none;
            border-color: var(--brand-maroon);
            box-shadow: none;
        }
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            width: 14px;
            height: 14px;
        }
        .filter-label {
            color: var(--brand-maroon);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            margin-bottom: 0.75rem;
            display: block;
        }
        .filter-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: none;
            background-color: #f6f3eb;
            font-size: 0.85rem;
            color: var(--text-dark);
            border-radius: 0;
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="%2379665e" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 10px;
        }
        .filter-checkbox {
            margin-bottom: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        /* Checkbox */
        .filter-checkbox input[type="checkbox"] {
            appearance: none;
            width: 16px;
            height: 16px;
            border: 1px solid rgba(88, 13, 33, 0.2);
            border-radius: 2px;
            background-color: white;
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
        }
        .filter-checkbox input[type="checkbox"]:checked {
            background-color: var(--brand-maroon);
            border-color: var(--brand-maroon);
        }
        .filter-checkbox input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 1px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        /* Radio (kategori) */
        .filter-checkbox input[type="radio"] {
            appearance: none;
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border: 1.5px solid rgba(88, 13, 33, 0.3);
            border-radius: 50%;
            background-color: white;
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
            transition: border-color 0.2s ease;
        }
        .filter-checkbox input[type="radio"]:checked {
            border-color: var(--brand-maroon);
            border-width: 4px;
            background-color: white;
        }
        .filter-checkbox input[type="radio"]:hover {
            border-color: rgba(88, 13, 33, 0.6);
        }
        .filter-checkbox label {
            font-size: 0.85rem;
            color: var(--text-dark);
            cursor: pointer;
        }
        .size-btn-group {
            display: flex;
            gap: 0.5rem;
        }
        .size-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(88, 13, 33, 0.1);
            background-color: white;
            color: var(--text-dark);
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .size-btn.active {
            background-color: var(--brand-maroon);
            color: white;
            border-color: var(--brand-maroon);
        }
        .clear-filters {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            color: var(--text-muted);
            text-transform: uppercase;
            text-decoration: none;
            text-align: center;
            display: block;
            margin-top: 2rem;
        }
        .clear-filters:hover {
            color: var(--brand-maroon);
        }

        /* Product Card */
        .product-card {
            background: transparent;
            border: none;
            margin-bottom: 0;
            height: 100%;
        }
        .product-img-wrapper {
            position: relative;
            overflow: hidden;
            aspect-ratio: 4/5;
            background-color: #f0ece1;
            margin-bottom: 1rem;
        }
        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .product-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            padding: 0.35rem 0.65rem;
            font-size: 0.55rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: white;
            z-index: 2;
        }
        .badge-available {
            background-color: rgba(88, 13, 33, 0.9);
        }
        .badge-rented {
            background-color: rgba(121, 102, 94, 0.9);
        }
        .product-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        .product-desc {
            color: var(--text-muted);
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }
        .product-price {
            font-size: 0.9rem;
            color: var(--brand-maroon);
            font-weight: 600;
        }
        .product-price-unit {
            color: var(--text-muted);
            font-size: 0.7rem;
            font-weight: 400;
        }

        /* Pagination */
        .catalog-pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 3rem;
            flex-wrap: wrap;
        }
        .page-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(88, 13, 33, 0.1);
            background-color: white;
            color: var(--text-dark);
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .page-btn:hover {
            border-color: var(--brand-maroon);
            color: var(--brand-maroon);
        }
        .page-btn.active {
            background-color: var(--brand-maroon);
            color: white;
            border-color: var(--brand-maroon);
        }
        .catalog-results__count {
            font-size: 0.8rem;
            letter-spacing: 0.05em;
        }
        .catalog-results__clear {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            text-decoration: none;
            text-transform: uppercase;
        }
        .catalog-results__clear:hover {
            color: var(--brand-maroon);
        }
        .catalog-grid--mobile {
            display: none;
        }
        .catalog-mobile__grid .is-hidden {
            display: none;
        }
        .catalog-mobile__footer {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
        }
        .catalog-mobile__viewmore {
            border: 1px solid var(--brand-maroon);
            background-color: transparent;
            color: var(--brand-maroon);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            padding: 0.9rem 1.5rem;
            text-transform: uppercase;
            transition: all 0.2s ease;
        }
        .catalog-mobile__viewmore:hover,
        .catalog-mobile__viewmore:focus-visible,
        .catalog-mobile__viewmore:active {
            background-color: #3f0917;
            border-color: #3f0917;
            color: white;
        }
        .form-control-date {
            width: 100%;
            padding: 0.75rem 1rem;
            border: none;
            background-color: #f6f3eb;
            font-size: 0.85rem;
            color: var(--text-muted);
            border-radius: 0;
            font-family: inherit;
        }
        .form-control-date::placeholder {
            color: var(--text-muted);
        }

        /* Contact Section */
        .contact-section {
            padding: 6rem 0;
            background-color: #fff;
        }
        .contact-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            color: var(--brand-maroon);
            margin-bottom: 1rem;
        }
        .contact-subtitle {
            color: var(--text-muted);
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 3rem;
            max-width: 500px;
        }
        .contact-info-item {
            margin-bottom: 1.5rem;
        }
        .contact-info-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--brand-maroon);
            margin-bottom: 0.5rem;
            display: block;
        }
        .contact-info-text {
            font-size: 1rem;
            color: var(--text-dark);
            text-decoration: none;
        }
        .contact-info-text a {
            color: var(--text-dark);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .contact-info-text a:hover {
            color: var(--brand-maroon);
        }
        .form-control-custom {
            border: none;
            border-bottom: 1px solid rgba(88, 13, 33, 0.2);
            border-radius: 0;
            padding: 1rem 0;
            background: transparent;
            font-size: 0.95rem;
            color: var(--text-dark);
        }
        .form-control-custom:focus {
            outline: none;
            box-shadow: none;
            border-bottom-color: var(--brand-maroon);
            background: transparent;
        }
        .btn-submit {
            background-color: var(--brand-maroon);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 1rem 2.5rem;
            border: 1px solid var(--brand-maroon);
            transition: all 0.3s ease;
            width: 100%;
        }
        .btn-submit:hover {
            background-color: #3f0917;
            color: white;
        }
        @media (min-width: 992px) {
            .catalog-section {
                padding: 4.5rem 0 6rem;
            }
            .catalog-desktop-shell {
                position: sticky;
                display: flex;
                flex-direction: column;
                gap: 2rem;
            }
            .catalog-header {
                margin-bottom: 0;
                flex: 0 0 auto;
            }
            .catalog-workspace {
                flex: 1 1 auto;
                min-height: 0;
                align-items: stretch;
            }
            .catalog-sidebar-column,
            .catalog-results-column {
                height: 100%;
            }
            .catalog-sidebar__form {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 2rem;
                height: 100%;
            }
            .catalog-sidebar__filters {
                flex: 1 1 auto;
            }
            .catalog-results-panel {
                flex: 1 1 auto;
                min-height: 0;
                overflow: hidden;
                padding-right: 0.75rem;
            }
        }
        @media (max-width: 991.98px) {
            .catalog-header {
                margin-bottom: 2.5rem;
            }
            .catalog-subtitle br {
                display: none;
            }
        }
        @media (max-width: 767.98px) {
            .catalog-section {
                padding: 4.5rem 0;
            }
            .catalog-title {
                font-size: 2rem;
            }
            .catalog-subtitle {
                font-size: 0.92rem;
            }
            .catalog-results__meta {
                gap: 0.75rem;
                align-items: flex-start !important;
                flex-wrap: wrap;
            }
            .catalog-results__count {
                font-size: 0.68rem;
                line-height: 1.5;
            }
            .catalog-results__clear {
                font-size: 0.62rem;
                letter-spacing: 0.08em;
            }
            .catalog-grid--desktop,
            .catalog-pagination {
                display: none;
            }
            .catalog-grid--mobile {
                display: block;
            }
            .catalog-mobile__grid {
                --bs-gutter-x: 1.5rem;
                --bs-gutter-y: 1.5rem;
            }
            .catalog-mobile__viewmore {
                width: 100%;
            }
            .product-card {
                margin-bottom: 0.25rem;
            }
        }
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target="#appNavbar" data-bs-offset="100">
    @include('components.layouts.partials.navbar')

    <main id="home" class="hero-section">
        <div class="container-fluid px-4 px-lg-5">
            <div class="row align-items-center">
                <div class="col-lg-5 col-xl-4 offset-xl-1 mb-5 mb-lg-0 z-1">
                    <div class="hero-eyebrow">LUWUNGRAGI HERITAGE — BREBES</div>
                    <h1 class="hero-title">
                        Keanggunan<br>
                        <em>Tradisi</em>
                    </h1>
                    <p class="hero-desc">
                        Rasakan pesona keanggunan abadi bersama Luwungragi. Penyewaan kebaya istimewa dan busana khas Nusantara pilihan untuk gaya elegan Anda.
                    </p>
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <a href="#catalog" class="btn-primary-custom">LIHAT KOLEKSI</a>
                        <a href="#about" class="btn-outline-custom">TENTANG KAMI</a>
                    </div>
                </div>
                <div class="col-lg-7 col-xl-6 text-center text-lg-end relative top-0 end-0">
                    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3200" data-bs-pause="false">
                        <div class="carousel-inner">
                            @foreach (range(1, 4) as $index)
                                <div class="carousel-item {{ $index === 1 ? 'active' : '' }}">
                                    <img src="{{ asset("images/kebaya{$index}.png") }}" alt="Luwungragi Kebaya {{ $index }}" class="hero-image">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <section id="about" class="about-section text-center">
        <div class="container px-4 px-lg-5">
            <div class="about-eyebrow">Tentang Kami</div>
            <h2 class="about-title">Pesona Tradisi, Keanggunan Masa Kini</h2>
            <p class="about-text">
                Selamat datang di <strong>Sewa Kebaya Luwungragi</strong>, destinasi utama Anda untuk penyewaan kebaya eksklusif dan busana warisan Nusantara. Kami hadir untuk menyempurnakan setiap momen perayaan dan hari istimewa Anda dengan koleksi mahakarya bernuansa tradisional yang dirancang untuk menonjolkan keanggunan, dipadukan sentuhan modern yang mewah dan premium.
            </p>
            <div class="about-location mx-auto" style="max-width: 500px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="var(--brand-maroon)" class="bi bi-geo-alt-fill mb-3" viewBox="0 0 16 16">
                  <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                </svg>
                <br>
                <strong>Kunjungi Galeri Kami:</strong><br>
                Karanganyar, Siasem, Kec. Wanasari, <br>Kabupaten Brebes, Jawa Tengah.
            </div>
        </div>
    </section>

    <section id="catalog" class="catalog-section">
        <div class="container px-4 px-lg-5">
            <div class="catalog-desktop-stage js-catalog-desktop-stage">
                <div class="catalog-desktop-shell js-catalog-desktop-shell">
                    <!-- Header -->
                    <div class="row catalog-header align-items-end">
                        <div class="col-lg-7 mb-4 mb-lg-0">
                            <h2 class="catalog-title">The Heritage Collection</h2>
                            <p class="catalog-subtitle mb-0">
                                Explore our curated archive of artisanal costumes, where every<br>thread weaves a story of tradition and timeless elegance.
                            </p>
                        </div>
                        <div class="col-lg-5">
                            <form method="GET" action="{{ url()->current() }}#catalog">
                                <input type="hidden" name="category" value="{{ $filters['category'] }}">
                                <input type="hidden" name="event_date" value="{{ $filters['event_date'] }}">
                                <div class="search-input-container">
                                    <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                    <input type="text" name="search" class="search-input"
                                           placeholder="Cari nama kostum..."
                                           value="{{ $filters['search'] }}"
                                           autocomplete="off">
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row catalog-workspace">
                        <!-- Sidebar Filters -->
                        <div class="col-lg-3 pe-lg-5 mb-5 mb-lg-0 catalog-sidebar-column">
                            <form method="GET" action="{{ url()->current() }}#catalog" class="catalog-sidebar__form">
                                <input type="hidden" name="search" value="{{ $filters['search'] }}">

                                <div class="catalog-sidebar__filters">
                                    <div class="mb-5">
                                        <span class="filter-label">KATEGORI</span>
                                        <div class="filter-checkbox">
                                            <input type="radio" name="category" id="cat-all" value=""
                                                   {{ empty($filters['category']) ? 'checked' : '' }}>
                                            <label for="cat-all">Semua Kategori</label>
                                        </div>
                                        @foreach ($categories as $cat)
                                            <div class="filter-checkbox">
                                                <input type="radio" name="category" id="cat-{{ Str::slug($cat) }}" value="{{ $cat }}"
                                                       {{ $filters['category'] === $cat ? 'checked' : '' }}>
                                                <label for="cat-{{ Str::slug($cat) }}">{{ $cat }}</label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mb-5">
                                        <span class="filter-label">TANGGAL ACARA</span>
                                        <input type="date" name="event_date" class="form-control-date"
                                               value="{{ $filters['event_date'] }}"
                                               min="{{ now()->addDays(\App\Models\Rental::BOOKING_BUFFER_DAYS)->toDateString() }}">
                                    </div>
                                </div>

                                <a href="{{ url()->current() }}#catalog" class="clear-filters">RESET FILTER</a>
                            </form>
                        </div>

                        <!-- Product Grid -->
                        <div class="col-lg-9 catalog-results-column">
                            <div id="catalog-results" class="catalog-results-panel">
                                @include('home.partials.catalog-grid')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact-section">
        <div class="container px-4 px-lg-5">
            <div class="row gx-lg-5 align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <h2 class="contact-title">Hubungi Kami</h2>
                    <p class="contact-subtitle">
                        Mari berdiskusi tentang bagaimana kami bisa melengkapi momen istimewa Anda dengan koleksi warisan terbaik kami.
                    </p>
                    
                    <div class="contact-info-item">
                        <span class="contact-info-label">WhatsApp</span>
                        <div class="contact-info-text">
                            <a href="https://wa.me/6281234567890" target="_blank">+62 812-3456-7890</a>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <span class="contact-info-label">Email Address</span>
                        <div class="contact-info-text">
                            <a href="mailto:hello@luwungragi.com">hello@luwungragi.com</a>
                        </div>
                    </div>

                    <div class="contact-info-item mt-4">
                        <span class="contact-info-label">Jam Operasional</span>
                        <div class="contact-info-text" style="font-size: 0.9rem;">
                            Senin - Sabtu: 09:00 - 17:00 WIB<br>
                            Minggu & Hari Libur: Tutup
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <form id="contactForm" action="https://formsubmit.co/{{ config('app.contact_email', env('CONTACT_EMAIL', 'hello@luwungragi.com')) }}" method="POST">
                        <!-- Keamanan agar tidak kena spam bot -->
                        <input type="hidden" name="_captcha" value="false">
                        <input type="text" name="_honey" style="display:none">
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <input type="text" name="Nama_Lengkap" class="form-control-custom" placeholder="Nama Lengkap" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="Email" class="form-control-custom" placeholder="Alamat Email" required>
                            </div>
                            <div class="col-12">
                                <input type="text" name="_subject" class="form-control-custom" placeholder="Subjek" required>
                            </div>
                            <div class="col-12">
                                <textarea name="Pesan" class="form-control-custom" rows="4" placeholder="Pesan Anda" required></textarea>
                            </div>
                            <div class="col-12 mt-4 text-end">
                                <button type="submit" class="btn-submit" style="width: auto;">Kirim Pesan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @include('components.layouts.partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // ─── State awal dari server ───────────────────────────────────────────────
    var catalogState = {!! json_encode([
        'category'    => $filters['category'],
        'search'      => $filters['search'],
        'event_date'  => $filters['event_date'],
        'page'        => $currentPage,
    ]) !!};

    var searchTimer = null;
    var resultsEl   = document.getElementById('catalog-results');
    var siteHeaderEl = document.querySelector('.site-header');
    var catalogDesktopStageEl = document.querySelector('.js-catalog-desktop-stage');
    var catalogDesktopShellEl = document.querySelector('.js-catalog-desktop-shell');
    var catalogScrollFrame = null;
    var catalogLayoutFrame = null;
    var catalogDesktopState = {
        enabled: false,
        stickyTop: 0,
        maxInternalScroll: 0,
    };

    function isDesktopCatalogMode() {
        return window.innerWidth >= 992;
    }

    function bindCatalogResultImages() {
        if (!resultsEl) return;

        resultsEl.querySelectorAll('img').forEach(function (img) {
            if (img.complete) return;

            img.addEventListener('load', scheduleCatalogDesktopLayout, { once: true });
            img.addEventListener('error', scheduleCatalogDesktopLayout, { once: true });
        });
    }

    function syncCatalogDesktopScroll() {
        if (!catalogDesktopState.enabled || !catalogDesktopStageEl || !resultsEl) return;

        var stageRect = catalogDesktopStageEl.getBoundingClientRect();
        var nextScrollTop = Math.max(
            0,
            Math.min(catalogDesktopState.stickyTop - stageRect.top, catalogDesktopState.maxInternalScroll)
        );

        resultsEl.scrollTop = nextScrollTop;
    }

    function applyCatalogDesktopLayout() {
        if (!catalogDesktopStageEl || !catalogDesktopShellEl || !resultsEl) return;

        if (!isDesktopCatalogMode()) {
            catalogDesktopState.enabled = false;
            catalogDesktopStageEl.style.height = '';
            catalogDesktopShellEl.style.top = '';
            catalogDesktopShellEl.style.height = '';
            resultsEl.scrollTop = 0;
            return;
        }

        var headerHeight = siteHeaderEl ? siteHeaderEl.offsetHeight : 0;
        var stickyTop = headerHeight + 24;
        var shellHeight = Math.max(window.innerHeight - stickyTop - 24, 420);

        catalogDesktopShellEl.style.top = stickyTop + 'px';
        catalogDesktopShellEl.style.height = shellHeight + 'px';

        var maxInternalScroll = Math.max(resultsEl.scrollHeight - resultsEl.clientHeight, 0);

        catalogDesktopStageEl.style.height = (shellHeight + maxInternalScroll) + 'px';
        catalogDesktopState.enabled = true;
        catalogDesktopState.stickyTop = stickyTop;
        catalogDesktopState.maxInternalScroll = maxInternalScroll;

        syncCatalogDesktopScroll();
    }

    function scheduleCatalogDesktopLayout() {
        if (catalogLayoutFrame) cancelAnimationFrame(catalogLayoutFrame);

        catalogLayoutFrame = requestAnimationFrame(function () {
            catalogLayoutFrame = null;
            applyCatalogDesktopLayout();
        });
    }

    function scheduleCatalogDesktopScroll() {
        if (catalogScrollFrame) return;

        catalogScrollFrame = requestAnimationFrame(function () {
            catalogScrollFrame = null;
            syncCatalogDesktopScroll();
        });
    }

    function initializeMobileCatalog() {
        var mobileGrid = resultsEl.querySelector('.js-mobile-catalog-grid');
        var viewMoreButton = resultsEl.querySelector('.js-mobile-view-more');

        if (!mobileGrid || !viewMoreButton) return;

        var items = Array.prototype.slice.call(mobileGrid.querySelectorAll('.js-mobile-catalog-item'));
        var batchSize = parseInt(mobileGrid.dataset.batchSize || '4', 10);
        var visibleCount = items.filter(function (item) {
            return !item.classList.contains('is-hidden');
        }).length;

        function syncButtonState() {
            viewMoreButton.style.display = visibleCount >= items.length ? 'none' : '';
        }

        viewMoreButton.addEventListener('click', function () {
            var nextVisible = Math.min(visibleCount + batchSize, items.length);

            for (var i = visibleCount; i < nextVisible; i++) {
                items[i].classList.remove('is-hidden');
            }

            visibleCount = nextVisible;
            syncButtonState();
        });

        syncButtonState();
    }

    // ─── fetchCatalog: kirim AJAX, replace #catalog-results ──────────────────
    function fetchCatalog(patch) {
        Object.assign(catalogState, patch);

        var params = new URLSearchParams();
        Object.keys(catalogState).forEach(function (k) {
            if (catalogState[k] !== '' && catalogState[k] !== null) {
                params.set(k, catalogState[k]);
            }
        });

        // Loading state
        resultsEl.style.opacity        = '0.35';
        resultsEl.style.pointerEvents  = 'none';

        fetch(window.location.pathname + '?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.text(); })
        .then(function (html) {
            resultsEl.innerHTML            = html;
            resultsEl.style.opacity        = '1';
            resultsEl.style.pointerEvents  = '';
            initializeMobileCatalog();
            bindCatalogResultImages();
            scheduleCatalogDesktopLayout();
            // Update URL tanpa reload
            history.replaceState(
                null, '',
                window.location.pathname + (params.toString() ? '?' + params.toString() : '')
            );
        })
        .catch(function () {
            resultsEl.style.opacity        = '1';
            resultsEl.style.pointerEvents  = '';
        });
    }

    initializeMobileCatalog();
    bindCatalogResultImages();
    scheduleCatalogDesktopLayout();
    window.addEventListener('load', scheduleCatalogDesktopLayout);
    window.addEventListener('resize', scheduleCatalogDesktopLayout);
    window.addEventListener('scroll', scheduleCatalogDesktopScroll, { passive: true });

    // ─── Radio kategori ────────────────────────────────────────────────────────
    document.querySelectorAll('input[name="category"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            fetchCatalog({ category: this.value, page: 1 });
        });
    });

    // ─── Tanggal acara ─────────────────────────────────────────────────────────
    document.querySelectorAll('input[name="event_date"]').forEach(function (inp) {
        inp.addEventListener('change', function () {
            fetchCatalog({ [this.name]: this.value, page: 1 });
        });
    });

    // ─── Search dengan debounce 600ms ──────────────────────────────────────────
    (function () {
        var searchInput = document.querySelector('input[name="search"]');
        if (!searchInput) return;
        searchInput.addEventListener('input', function () {
            var val = this.value;
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                fetchCatalog({ search: val, page: 1 });
            }, 600);
        });
        // Enter juga trigger
        searchInput.closest('form').addEventListener('submit', function (e) {
            e.preventDefault();
            clearTimeout(searchTimer);
            fetchCatalog({ search: searchInput.value, page: 1 });
        });
    })();

    // ─── Event delegation di #catalog-results (pagination + hapus filter) ──────
    resultsEl.addEventListener('click', function (e) {
        // Pagination
        var pageLink = e.target.closest('.js-page-link');
        if (pageLink) {
            e.preventDefault();
            fetchCatalog({ page: parseInt(pageLink.dataset.page, 10) });
            return;
        }
        // Hapus filter
        var clearBtn = e.target.closest('.js-clear-filters');
        if (clearBtn) {
            e.preventDefault();
            document.querySelectorAll('input[name="category"]').forEach(function (r) {
                r.checked = (r.value === '');
            });
            var si = document.querySelector('input[name="search"]');
            if (si) si.value = '';
            fetchCatalog({ category: '', search: '', page: 1 });
        }
    });

    // ─── Contact Form AJAX ──────────────────────────────────────────
    var contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var btn = this.querySelector('button[type="submit"]');
            var originalText = btn.innerText;
            btn.innerText = "Mengirim...";
            btn.disabled = true;

            // Ubah URL action ke tipe AJAX untuk FormSubmit
            var ajaxUrl = this.action.replace('formsubmit.co/', 'formsubmit.co/ajax/');

            fetch(ajaxUrl, {
                method: this.method,
                body: new FormData(this),
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(function(response) { 
                if (!response.ok) throw new Error("Gagal mengirim");
                return response.json(); 
            })
            .then(function(data) {
                if (data.success || data.success === "true") {
                    alert("Pesan Anda berhasil terkirim!");
                    contactForm.reset();
                } else {
                    alert("Terjadi kesalahan, pastikan Anda sudah mengaktivasi email.");
                }
                btn.innerText = originalText;
                btn.disabled = false;
            })
            .catch(function(error) {
                alert("Maaf, terjadi masalah jaringan saat mengirim pesan.");
                btn.innerText = originalText;
                btn.disabled = false;
            });
        });
    }
    </script>
</body>
</html>
