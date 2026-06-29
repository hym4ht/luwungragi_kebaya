<style>
    .site-footer {
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 2.5rem 0 2rem 0;
        margin-top: auto;
        overflow: hidden;
    }
    .footer-brand {
        font-family: 'Playfair Display', serif;
        color: var(--brand-maroon, #580d21);
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.2rem;
    }
    .footer-copyright {
        color: #999;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
    }
    .footer-link {
        color: #999;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .footer-link:hover {
        color: var(--brand-maroon, #580d21);
    }
    .social-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border: 1px solid rgba(88, 13, 33, 0.15);
        border-radius: 50%;
        color: var(--brand-maroon, #580d21);
        transition: all 0.3s ease;
        text-decoration: none;
        background: transparent;
    }
    .social-btn:hover {
        background-color: var(--brand-maroon, #580d21);
        color: white;
        border-color: var(--brand-maroon, #580d21);
    }
    .footer-parallax-inner {
        will-change: transform, opacity;
    }
</style>

<footer class="site-footer" id="site-footer">
    <div class="container-fluid px-4 px-lg-5 footer-parallax-inner" id="footer-parallax-inner">
        <div class="row align-items-center">
            <div class="col-lg-4 mb-4 mb-lg-0 text-center text-lg-start">
                <div class="footer-brand">Luwungragi Heritage</div>
                <div class="footer-copyright">&copy; {{ date('Y') }} LUWUNGRAGI HERITAGE. X Universitas Harkat Negeri Tegal ALL RIGHTS RESERVED .</div>
            </div>
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="d-flex justify-content-center flex-wrap gap-4">
                    <a href="#" class="footer-link">TERMS OF SERVICE</a>
                    <a href="#" class="footer-link">PRIVACY POLICY</a>
                    <a href="#" class="footer-link">RENTAL AGREEMENT</a>
                    <a href="#" class="footer-link">CARE INSTRUCTIONS</a>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="d-flex justify-content-center justify-content-lg-end gap-3">
                    <a href="#" class="social-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M13.5 1a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5zm-8.5 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm11 5.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.708 2.825L15 11.105V5.383zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741zM1 11.105l4.708-2.897L1 5.383v5.722z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const footer = document.getElementById('site-footer');
    const inner = document.getElementById('footer-parallax-inner');
    
    if(!footer || !inner) return;

    let ticking = false;

    function updateFooterParallax() {
        const rect = footer.getBoundingClientRect();
        const windowHeight = window.innerHeight;
        
        // Cek apakah footer berada dalam viewport
        if (rect.top <= windowHeight && rect.bottom >= 0) {
            // Seberapa banyak footer yang terlihat di layar
            const visibleHeight = windowHeight - rect.top;
            const footerHeight = footer.offsetHeight;
            
            // Konversi menjadi angka progress antara 0.0 sampai 1.0
            let progress = visibleHeight / footerHeight;
            if (progress > 1) progress = 1;
            if (progress < 0) progress = 0;
            
            // Efek parallax: saat baru muncul (progress 0) elemen digeser ke bawah 60px
            // Saat progress mencapai 1, geser menjadi 0px
            const maxOffset = 60;
            const yOffset = maxOffset * (1 - progress);
            
            // Efek fadeIn untuk animasi yang lebih smooth
            const opacity = 0.3 + (progress * 0.7);
            
            inner.style.transform = `translateY(${yOffset}px)`;
            inner.style.opacity = opacity;
        }
        
        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(updateFooterParallax);
            ticking = true;
        }
    }, { passive: true });
    
    window.addEventListener('resize', function() {
        if (!ticking) {
            window.requestAnimationFrame(updateFooterParallax);
            ticking = true;
        }
    }, { passive: true });

    // Panggil fungsi saat pramuat
    updateFooterParallax();
});
</script>
