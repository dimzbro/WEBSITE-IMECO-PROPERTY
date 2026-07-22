<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Beltway Office Park – Premium office space in South Jakarta. World-class facilities, strategic location, modern architecture for your business excellence.">
    <meta name="keywords" content="office park, Jakarta Selatan, sewa kantor premium, office tower, commercial real estate Jakarta">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Beltway Office Park – Premium Office Space South Jakarta">
    <meta property="og:description" content="World-class office environments designed for productivity, growth, and professional excellence in South Jakarta.">
    <meta property="og:type" content="website">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Beltway Office Park – Premium Commercial Real Estate')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Iconify CDN -->
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    <style>
        .feature-icon svg,
        .facility-icon-wrap svg,
        .feature-icon iconify-icon,
        .facility-icon-wrap iconify-icon {
            transition: color 0.3s ease;
        }
        .feature-card:hover .feature-icon svg,
        .facility-card:hover .facility-icon-wrap svg,
        .feature-card:hover .feature-icon iconify-icon,
        .facility-card:hover .facility-icon-wrap iconify-icon {
            color: white !important;
        }
    </style>
</head>
<body class="{{ session('admin_logged_in') ? 'admin-mode' : '' }}">

    @if(session('admin_logged_in'))
    <!-- Admin Bar -->
    <div class="fixed top-0 left-0 right-0 h-9 bg-slate-900 border-b border-white/10 text-white px-6 lg:px-12 flex justify-between items-center text-xs z-[60]">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
            <span class="font-semibold text-white/90">Logged in as Administrator</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin') }}" class="hover:text-gold transition-colors font-bold uppercase tracking-wider no-underline text-white/90">Go to Dashboard</a>
            <span class="text-white/20">|</span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="hover:text-red-400 transition-colors font-bold uppercase tracking-wider bg-transparent border-none p-0 cursor-pointer">Logout</button>
            </form>
        </div>
    </div>
    @endif

    <!-- ========================
         STICKY NAVBAR
         ======================== -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 py-4 px-6 lg:px-12">
        <div class="max-w-7xl mx-auto flex items-center justify-between">

            <!-- Logo -->
            <a href="#hero" class="flex items-center gap-3 no-underline">
                <img src="{{ asset('logo_bop.png') }}" alt="BELTWAY Logo" class="w-10 h-10 object-contain nav-logo-img" style="filter: brightness(0) invert(1); transition: filter 0.4s ease;">
                <div>
                    <div class="nav-logo-text text-white font-extrabold text-lg leading-none tracking-tight">BELTWAY</div>
                    <div class="text-xs font-semibold tracking-widest" style="color: #D4AF37;">OFFICE PARK</div>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <div class="hidden lg:flex items-center gap-7">
                <a href="#hero"       class="nav-link text-white" id="nav-home">Home</a>
                <a href="#about"      class="nav-link text-white" id="nav-about">About</a>
                <a href="#towers"     class="nav-link text-white" id="nav-towers">Towers</a>
                <a href="#facilities" class="nav-link text-white" id="nav-facilities">Facilities</a>
                <a href="#location"   class="nav-link text-white" id="nav-location">Location</a>
                <a href="#gallery"    class="nav-link text-white" id="nav-gallery">Gallery</a>
                <a href="#tenants"    class="nav-link text-white" id="nav-tenants">Tenants</a>
                <a href="#news"       class="nav-link text-white" id="nav-news">News</a>
                <a href="#contact"    class="nav-link text-white" id="nav-contact">Contact</a>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center gap-4">
                <button id="hamburger" class="lg:hidden flex flex-col gap-1.5 p-2" aria-label="Open menu">
                    <span class="block w-6 h-0.5 bg-white transition-all duration-300" id="hb-1"></span>
                    <span class="block w-6 h-0.5 bg-white transition-all duration-300" id="hb-2"></span>
                    <span class="block w-4 h-0.5 bg-white transition-all duration-300" id="hb-3"></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu" role="dialog" aria-modal="true">
        <button id="mobile-close" class="absolute top-6 right-6 text-white" aria-label="Close menu">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div style="color: #D4AF37; font-size: 0.8rem; font-weight: 800; letter-spacing: 0.15em; margin-bottom: 1rem;">BELTWAY OFFICE PARK</div>
        <a href="#hero"       class="mobile-nav-link" onclick="closeMobileMenu()">Home</a>
        <a href="#about"      class="mobile-nav-link" onclick="closeMobileMenu()">About</a>
        <a href="#towers"     class="mobile-nav-link" onclick="closeMobileMenu()">Towers</a>
        <a href="#facilities" class="mobile-nav-link" onclick="closeMobileMenu()">Facilities</a>
        <a href="#location"   class="mobile-nav-link" onclick="closeMobileMenu()">Location</a>
        <a href="#gallery"    class="mobile-nav-link" onclick="closeMobileMenu()">Gallery</a>
        <a href="#tenants"    class="mobile-nav-link" onclick="closeMobileMenu()">Tenants</a>
        <a href="#news"       class="mobile-nav-link" onclick="closeMobileMenu()">News</a>
        <a href="#contact"    class="mobile-nav-link" onclick="closeMobileMenu()">Contact</a>

    </div>

    <!-- Main Content -->
    @yield('content')

    <!-- Lightbox -->
    <div id="lightbox" role="dialog" aria-modal="true">
        <button id="lightbox-close" aria-label="Close lightbox">&#x2715;</button>
        <img id="lightbox-img" src="" alt="Gallery Image">
    </div>

    <!-- Footer -->
    <footer id="footer" class="py-16 px-6 lg:px-12">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12" style="border-bottom: 1px solid rgba(255,255,255,0.1);">

                <!-- Brand Column -->
                <div class="lg:col-span-1">
                    <div class="flex items-center gap-3 mb-5">
                        <img src="{{ asset('logo_bop.png') }}" alt="BELTWAY Logo" class="w-10 h-10 object-contain brightness-0 invert">
                        <div>
                            <div class="text-white font-extrabold text-lg leading-none tracking-tight">BELTWAY</div>
                            <div class="text-xs font-semibold tracking-widest" style="color: #D4AF37;">OFFICE PARK</div>
                        </div>
                    </div>
                    <p class="text-sm leading-relaxed mb-5" style="color: rgba(255,255,255,0.6);">
                        Premium office park delivering world-class environments for modern businesses in the heart of South Jakarta.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-5">Quick Links</h4>
                    <nav aria-label="Footer navigation">
                        <a href="#hero"       class="footer-link">Home</a>
                        <a href="#about"      class="footer-link">About Us</a>
                        <a href="#towers"     class="footer-link">Office Towers</a>
                        <a href="#facilities" class="footer-link">Facilities</a>
                        <a href="#gallery"    class="footer-link">Gallery</a>
                        <a href="#news"       class="footer-link">News & Articles</a>
                        <a href="#contact"    class="footer-link">Contact Us</a>
                    </nav>
                </div>

                <!-- Our Towers -->
                <div>
                    <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-5">Our Towers</h4>
                    <a href="#towers" class="footer-link">Tower A – Middle Office Space</a>
                    <a href="#towers" class="footer-link">Tower B – Executive Office Space</a>
                    <a href="#towers" class="footer-link">Tower C – Middle Office Space</a>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-5">Contact Us</h4>
                    <div class="space-y-3 text-sm" style="color: rgba(255,255,255,0.65);">
                        <div class="flex gap-2.5">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: #D4AF37;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Jl. Ampera Raya 9-10 & Jl. TB Simatupang No. 41 Jakarta selatan.</span>
                        </div>
                        <div class="flex gap-2.5">
                            <svg class="w-4 h-4 flex-shrink-0" style="color: #D4AF37;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>-</span>
                        </div>
                        <div class="flex gap-2.5">
                            <svg class="w-4 h-4 flex-shrink-0" style="color: #D4AF37;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <div>-</div>
                            </div>
                        </div>
                        <div class="flex gap-2.5">
                            <svg class="w-4 h-4 flex-shrink-0" style="color: #D4AF37;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <div>Mon – Fri: 08:00 – 17:00</div>
                                <div>Sat: 08:00 – 13:00</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-3">
                <p class="text-sm" style="color: rgba(255,255,255,0.5);">
                    © 2026 <span style="color: #D4AF37;">Beltway Office Park</span>. All Rights Reserved. 
                    <span class="block md:inline mt-1 md:mt-0 text-xs opacity-75">
                        | Created by 
                        <a href="https://www.instagram.com/ramadwiprstya/" target="_blank" class="hover:text-white transition-colors" style="color: #D4AF37; text-decoration: underline;">Rama Dwi Prasetya</a> & 
                        <a href="https://www.instagram.com/dimasberlian/" target="_blank" class="hover:text-white transition-colors" style="color: #D4AF37; text-decoration: underline;">Dimas Berlian Moeslim</a>
                    </span>
                    <a href="{{ route('login') }}" class="opacity-0 cursor-default select-none pointer-events-auto" style="font-size: 1px;" aria-hidden="true" tabindex="-1">.</a>
                </p>
                <div class="flex items-center gap-5 text-xs" style="color: rgba(255,255,255,0.4);">
                    <span>Privacy Policy</span>
                    <span>•</span>
                    <span>Terms of Service</span>
                    <span>•</span>
                    <span>Sitemap</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
    // ===== NAVBAR SCROLL =====
    const navbar = document.getElementById('navbar');
    const navLinks = document.querySelectorAll('.nav-link');
    const logoImg = document.querySelector('.nav-logo-img');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 60) {
            navbar.classList.add('scrolled');
            navLinks.forEach(l => l.style.color = '#0F172A');
            if (logoImg) logoImg.style.filter = 'none';
        } else {
            navbar.classList.remove('scrolled');
            navLinks.forEach(l => l.style.color = 'white');
            if (logoImg) logoImg.style.filter = 'brightness(0) invert(1)';
        }
    });

    // ===== MOBILE MENU =====
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileClose = document.getElementById('mobile-close');
    const hb1 = document.getElementById('hb-1');
    const hb2 = document.getElementById('hb-2');
    const hb3 = document.getElementById('hb-3');

    hamburger.addEventListener('click', () => {
        mobileMenu.classList.add('open');
        document.body.style.overflow = 'hidden';
    });
    mobileClose.addEventListener('click', closeMobileMenu);

    function closeMobileMenu() {
        mobileMenu.classList.remove('open');
        document.body.style.overflow = '';
    }

    // ===== PARALLAX HERO =====
    const heroBg = document.getElementById('hero-bg');
    window.addEventListener('scroll', () => {
        if (heroBg) {
            const scrolled = window.scrollY;
            heroBg.style.transform = `translateY(${scrolled * 0.4}px) scale(1.1)`;
        }
    });

    // ===== SCROLL ANIMATIONS =====
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-up, .fade-left, .fade-right, .fade-in').forEach(el => {
        observer.observe(el);
    });

    // ===== COUNTER ANIMATION =====
    function animateCounter(el, target, duration = 2000, suffix = '') {
        let start = 0;
        const step = target / (duration / 16);
        const timer = setInterval(() => {
            start += step;
            if (start >= target) {
                start = target;
                clearInterval(timer);
            }
            el.textContent = Math.floor(start).toLocaleString() + suffix;
        }, 16);
    }

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.dataset.counted) {
                entry.target.dataset.counted = 'true';
                const target = parseInt(entry.target.dataset.target);
                const suffix = entry.target.dataset.suffix || '';
                animateCounter(entry.target, target, 2000, suffix);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('[data-counter]').forEach(el => counterObserver.observe(el));

    // ===== LIGHTBOX =====
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxClose = document.getElementById('lightbox-close');

    document.querySelectorAll('.gallery-item').forEach(item => {
        item.addEventListener('click', () => {
            const img = item.querySelector('img');
            if (img) {
                lightboxImg.src = img.src;
                lightboxImg.alt = img.alt;
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    document.querySelectorAll('.space-listing-img').forEach(img => {
        img.addEventListener('click', () => {
            if (lightboxImg) {
                lightboxImg.src = img.src;
                lightboxImg.alt = img.alt;
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    lightboxClose.addEventListener('click', () => {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    });
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            lightbox.classList.remove('active');
            mobileMenu.classList.remove('open');
            document.body.style.overflow = '';
        }
    });

    // ===== SPACE FILTER =====
    const filterBtns = document.querySelectorAll('.space-filter-btn');
    const spaceCards = document.querySelectorAll('.space-listing-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.dataset.filter;
            let visibleCount = 0;

            spaceCards.forEach(card => {
                if (filter === 'all' || card.dataset.filter === filter) {
                    card.classList.remove('hidden');
                    card.style.animation = 'fadeInUp 0.4s ease forwards';
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            const noSpacesMsg = document.getElementById('no-spaces-message');
            if (noSpacesMsg) {
                if (visibleCount === 0) {
                    noSpacesMsg.classList.remove('hidden');
                } else {
                    noSpacesMsg.classList.add('hidden');
                }
            }
        });
    });

    // ===== SECRET LOGIN PORTAL =====
    // Double click the logo to go to login
    const logoLink = document.querySelector('nav a[href="#hero"]');
    if (logoLink) {
        logoLink.addEventListener('dblclick', (e) => {
            e.preventDefault();
            window.location.href = "{{ route('login') }}";
        });
    }

    // Ctrl + Shift + L to go to login
    document.addEventListener('keydown', (e) => {
        if (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'l') {
            e.preventDefault();
            window.location.href = "{{ route('login') }}";
        }
    });
    </script>

    @yield('scripts')
</body>
</html>
