@extends('layouts.app')

@section('title', 'Beltway Office Park – Premium Office Space South Jakarta')

@section('content')

    {{-- =============================================
    SECTION 1: HERO
    ============================================= --}}
    <section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden">

        {{-- Background Image with Parallax --}}
        <div class="absolute inset-0 overflow-hidden">
            <img id="hero-bg" src="{{ $images['heroBg'] }}" alt="Beltway Office Park - Premium Corporate Building"
                class="w-full h-full object-cover scale-110" loading="eager">
            <div class="hero-overlay absolute inset-0"></div>
        </div>

        {{-- Decorative elements --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-20 right-20 w-64 h-64 rounded-full opacity-10"
                style="background: radial-gradient(circle, #D4AF37, transparent);"></div>
            <div class="absolute bottom-40 left-10 w-48 h-48 rounded-full opacity-8"
                style="background: radial-gradient(circle, #1E3A8A, transparent);"></div>
        </div>

        {{-- Hero Content --}}
        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-12 text-center pt-24">



            {{-- Main Headline --}}
            <h1 class="hero-title mb-6 fade-up delay-100">
                Premium Office Space<br>
                for <span>Modern Business</span>
            </h1>

            {{-- Subheadline --}}
            <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto mb-10 leading-relaxed fade-up delay-200">
                Beltway Office Park provides world-class office environments designed for
                productivity, growth, and professional excellence.
            </p>

            {{-- CTA Buttons --}}
            <div class="flex flex-wrap items-center justify-center gap-4 mb-16 fade-up delay-300">
                <a href="#towers" class="btn-primary" id="hero-explore">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Explore Spaces
                </a>
                <a href="#contact" class="btn-outline-white" id="hero-contact">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Contact Us
                </a>
            </div>

            {{-- Quick Stats Row --}}
            <div class="flex flex-wrap justify-center gap-8 fade-up delay-400">
                <div class="text-center">
                    <div class="text-3xl font-black text-white">4,5</div>
                    <div class="text-xs text-white/60 font-semibold uppercase tracking-widest">Hectares</div>
                </div>
                <div class="w-px h-10 self-center" style="background: rgba(255,255,255,0.2);"></div>
                <div class="text-center">
                    <div class="text-3xl font-black" style="color: #D4AF37;">3</div>
                    <div class="text-xs text-white/60 font-semibold uppercase tracking-widest">Towers</div>
                </div>
                <div class="w-px h-10 self-center" style="background: rgba(255,255,255,0.2);"></div>
                <div class="text-center">
                    <div class="text-3xl font-black text-white">8</div>
                    <div class="text-xs text-white/60 font-semibold uppercase tracking-widest">Floors Each</div>
                </div>
                <div class="w-px h-10 self-center" style="background: rgba(255,255,255,0.2);"></div>
                <div class="text-center">
                    <div class="text-3xl font-black" style="color: #D4AF37;">100<span class="text-xl">+</span></div>
                    <div class="text-xs text-white/60 font-semibold uppercase tracking-widest">Tenants</div>
                </div>
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="scroll-indicator">
            <span class="text-xs font-semibold tracking-widest uppercase">Scroll</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </section>


    {{-- =============================================
    SECTION 2: ABOUT
    ============================================= --}}
    <section id="about" class="py-24 px-6 lg:px-12 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                {{-- Left: Image --}}
                <div class="relative fade-left">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl"
                        style="box-shadow: 0 32px 80px rgba(30, 58, 138, 0.2);">
                        <img src="{{ $images['aerial'] }}" alt="Beltway Office Park Aerial View"
                            class="w-full h-[500px] object-cover">
                        <div class="absolute inset-0"
                            style="background: linear-gradient(to top right, rgba(30,58,138,0.3), transparent);"></div>
                    </div>
                    {{-- Floating badge --}}
                    <div class="absolute -bottom-6 -right-6 p-5 rounded-2xl shadow-xl z-10"
                        style="background: linear-gradient(135deg, #1E3A8A, #0F172A);">
                        <div class="text-center">
                            <div class="text-4xl font-black text-white">22<span style="color: #D4AF37;">+</span></div>
                            <div class="text-xs font-bold uppercase tracking-widest mt-1"
                                style="color: rgba(255,255,255,0.7);">Years Excellence</div>
                        </div>
                    </div>
                    {{-- Decorative square --}}
                    <div class="absolute -top-6 -left-6 w-32 h-32 rounded-2xl opacity-20 -z-10"
                        style="background: #D4AF37;"></div>
                </div>

                {{-- Right: Content --}}
                <div class="fade-right">
                    <div class="section-tag">
                        About Beltway Office Park
                    </div>
                    <h2 class="section-title mb-4">
                        The Premier Destination for<br>
                        <span>Corporate Excellence</span>
                    </h2>
                    <div class="section-divider"></div>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Beltway Office Park stands as South Jakarta's most prestigious commercial real estate development,
                        spanning 45.000 sqm of meticulously planned business ecosystem. Our three iconic towers
                        redefine the standard of premium office environments in Indonesia.
                    </p>
                    <p class="text-gray-600 leading-relaxed mb-8">
                        Strategically located at the heart of Jakarta Selatan's business corridor, we provide world-class
                        facilities, cutting-edge smart building technology, and an unparalleled professional community
                        that fosters growth, innovation, and excellence.
                    </p>

                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="stat-card">
                            <div class="stat-number">
                                <span data-counter data-target="45000" data-suffix="">45.000</span>
                            </div>
                            <div class="stat-suffix">sqm</div>
                            <div class="stat-label">Total Area</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">
                                <span data-counter data-target="3" data-suffix="">3</span>
                            </div>
                            <div class="stat-suffix"></div>
                            <div class="stat-label">Office Towers</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">
                                <span data-counter data-target="8" data-suffix="">8</span>
                            </div>
                            <div class="stat-suffix">Lt</div>
                            <div class="stat-label">Floors Each Tower</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">
                                <span data-counter data-target="100" data-suffix="+">100+</span>
                            </div>
                            <div class="stat-suffix"></div>
                            <div class="stat-label">Business Tenants</div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>


    {{-- =============================================
    SECTION 3: OFFICE TOWERS
    ============================================= --}}
    <section id="towers" class="py-24 px-6 lg:px-12 bg-section-alt overflow-hidden">
        <div class="max-w-7xl mx-auto">

            {{-- Section Header --}}
            <div class="text-center mb-16">
                <div class="flex justify-center">
                    <div class="section-tag fade-up">Our Office Towers</div>
                </div>
                <h2 class="section-title fade-up delay-100">Three Iconic Towers,<br><span>Endless Possibilities</span></h2>
                <div class="section-divider mx-auto fade-up delay-200"></div>
                <p class="text-gray-600 max-w-xl mx-auto fade-up delay-300">
                    Each tower is architecturally distinct, purpose-built to serve different business needs with world-class
                    amenities and premium finishes.
                </p>
            </div>

            {{-- Tower Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($towers as $index => $tower)
                    <div class="tower-card fade-up delay-{{ ($index + 1) * 100 }}" id="tower-card-{{ $index }}">
                        <div class="relative overflow-hidden" style="height: 260px;">
                            <img src="{{ $tower['image'] }}" alt="{{ $tower['name'] }} - {{ $tower['subtitle'] }}"
                                class="tower-card-img">
                            <div class="tower-card-overlay"></div>
                            <div class="absolute bottom-3 left-4 z-10">
                                <div class="text-white font-black text-xl">{{ $tower['name'] }}</div>
                                <div class="text-sm font-semibold" style="color: #D4AF37;">{{ $tower['subtitle'] }}</div>
                            </div>
                        </div>
                        <div class="tower-card-body">
                            <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ $tower['description'] }}</p>
                            <div class="flex gap-4 mb-4">
                                <div class="flex items-center gap-1.5 text-xs font-semibold text-gray-500">
                                    <svg class="w-3.5 h-3.5" style="color: #1E3A8A;" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16" />
                                    </svg>
                                    {{ $tower['floors'] }}
                                </div>
                                <div class="flex items-center gap-1.5 text-xs font-semibold text-gray-500">
                                    <svg class="w-3.5 h-3.5" style="color: #1E3A8A;" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                    </svg>
                                    {{ $tower['area'] }}
                                </div>
                            </div>
                            <a href="#contact" class="btn-detail" id="tower-detail-{{ $index }}">
                                Request Detail
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- =============================================
    SECTION 4: WHY CHOOSE BELTWAY
    ============================================= --}}
    <section id="why" class="py-24 px-6 lg:px-12 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">

            {{-- Header --}}
            <div class="text-center mb-16">
                <div class="flex justify-center">
                    <div class="section-tag fade-up">Why Choose Us</div>
                </div>
                <h2 class="section-title fade-up delay-100">Why Beltway Office Park is<br><span>The Right Choice</span></h2>
                <div class="section-divider mx-auto fade-up delay-200"></div>
            </div>

            {{-- Feature Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($features as $i => $feature)
                    <div class="feature-card fade-up delay-{{ min(($i + 1) * 100, 600) }}" id="feature-{{ $i }}">
                        <div class="feature-icon">
                            @if($feature['icon'] === 'location')
                                <svg class="w-6 h-6" style="color: #1E3A8A;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            @elseif($feature['icon'] === 'shield')
                                <svg class="w-6 h-6" style="color: #1E3A8A;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            @elseif($feature['icon'] === 'charging')
                                <svg class="w-6 h-6" style="color: #1E3A8A;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            @elseif($feature['icon'] === 'wifi')
                                <svg class="w-6 h-6" style="color: #1E3A8A;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                                </svg>
                            @elseif($feature['icon'] === 'car')
                                <svg class="w-6 h-6" style="color: #1E3A8A;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            @elseif($feature['icon'] === 'briefcase')
                                <svg class="w-6 h-6" style="color: #1E3A8A;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            @elseif($feature['icon'] === 'users')
                                <svg class="w-6 h-6" style="color: #1E3A8A;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            @elseif($feature['icon'] === 'sports')
                                <svg class="w-6 h-6" style="color: #1E3A8A;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M6 8v8M9 6v12M15 6v12M18 8v8" />
                                </svg>
                            @else
                                <svg class="w-6 h-6" style="color: #1E3A8A;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-900 text-base mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- =============================================
    SECTION 5: FACILITIES
    ============================================= --}}
    <section id="facilities" class="py-24 px-6 lg:px-12 overflow-hidden" style="background: #F8FAFC;">
        <div class="max-w-7xl mx-auto">

            <div class="text-center mb-16">
                <div class="flex justify-center">
                    <div class="section-tag fade-up">Facilities & Amenities</div>
                </div>
                <h2 class="section-title fade-up delay-100">World-Class<br><span>Facilities for You</span></h2>
                <div class="section-divider mx-auto fade-up delay-200"></div>
                <p class="text-gray-600 max-w-lg mx-auto fade-up delay-300">
                    Everything you need to elevate your business experience, all within the Beltway ecosystem.
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($facilities as $i => $facility)
                    <div class="facility-card fade-up delay-{{ min(($i + 1) * 100, 500) }}" id="facility-{{ $i }}">
                        <div class="facility-icon-wrap">
                            @php
                                $facilityIcons = [
                                    'sofa' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M20 12a2 2 0 002-2V6a2 2 0 00-2-2H4a2 2 0 00-2 2v4a2 2 0 002 2M20 12v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6"/>',
                                    'presentation' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>',
                                    'mic' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>',
                                    'utensils' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>',
                                    'coffee' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
                                    'credit-card' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                                    'parking' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z M9 5v14 M9 5h5a3.5 3.5 0 010 7H9"/>',
                                    'shield-check' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
                                    'key' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>',
                                    'clinic' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 100-18 9 9 0 000 18z M12 8v8 M8 12h8"/>',
                                    'charging' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
                                ];
                            @endphp
                            <svg class="w-7 h-7" style="color: #1E3A8A;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $facilityIcons[$facility['icon']] ?? '' !!}
                            </svg>
                        </div>
                        <div class="facility-name">{{ $facility['name'] }}</div>
                    </div>
                @endforeach
            </div>


        </div>
    </section>


    {{-- =============================================
    SECTION 6: LOCATION
    ============================================= --}}
    <section id="location" class="py-24 px-6 lg:px-12 relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ $images['cityView'] }}" alt="Jakarta City View" class="w-full h-full object-cover">
            <div class="absolute inset-0"
                style="background: linear-gradient(135deg, rgba(15,23,42,0.92) 0%, rgba(30,58,138,0.85) 100%);"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto">

            <div class="text-center mb-14">
                <div class="flex justify-center">
                    <div class="section-tag" style="color: #D4AF37;">Location Advantage</div>
                </div>
                <h2 class="text-white fade-up delay-100"
                    style="font-size: clamp(1.75rem, 4vw, 3rem); font-weight: 800; line-height: 1.15; letter-spacing: -0.02em;">
                    Strategically Located at<br><span style="color: #D4AF37;">the Heart of Jakarta Selatan</span>
                </h2>
                <div class="section-divider mx-auto fade-up delay-200"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                {{-- Location Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @php
                        $locationBenefits = [
                            ['icon' => 'road', 'title' => 'Toll Road Access', 'desc' => 'Direct access to JORR Toll Road, connecting to all major city arteries within minutes.'],
                            ['icon' => 'train', 'title' => 'MRT Station', 'desc' => 'Walking distance to Fatmawati MRT Station for convenient public transport connectivity.'],
                            ['icon' => 'building', 'title' => 'Business District', 'desc' => 'Surrounded by major corporate headquarters, financial institutions, and business hubs.'],
                            ['icon' => 'plane', 'title' => 'Airport Access', 'desc' => '45 minutes to Soekarno-Hatta International Airport via toll road express.'],
                            ['icon' => 'hotel', 'title' => 'Nearby Hotels', 'desc' => 'Premium 5-star hotels within 2km for client entertainment and corporate events.'],
                            ['icon' => 'shop', 'title' => 'Shopping Malls', 'desc' => 'Adjacent to Pondok Indah Mall and other major retail destinations.'],
                        ];
                    @endphp
                    @foreach($locationBenefits as $i => $benefit)
                        <div class="location-card fade-up delay-{{ min(($i + 1) * 100, 500) }}" id="location-benefit-{{ $i }}">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                    style="background: rgba(212,175,55,0.15); border: 1px solid rgba(212,175,55,0.3);">
                                    <svg class="w-5 h-5" style="color: #D4AF37;" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        @if($benefit['icon'] === 'road')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                        @elseif($benefit['icon'] === 'train')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        @elseif($benefit['icon'] === 'building')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        @elseif($benefit['icon'] === 'plane')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        @elseif($benefit['icon'] === 'hotel')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        @endif
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-white font-bold text-sm mb-0.5">{{ $benefit['title'] }}</div>
                                    <div class="text-xs leading-relaxed" style="color: rgba(255,255,255,0.65);">
                                        {{ $benefit['desc'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Google Maps Embed --}}
                <div class="fade-right">
                    <div class="map-container">
                        <iframe
                            src="https://maps.google.com/maps?q=Beltway%20Office%20Park,%20Jl.%20Ampera%20Raya%20No.%209-10,%20RT.7/RW.2%20Ragunan,%20Pasar%20Minggu,%20Jakarta%20Selatan%2012550&t=&z=17&ie=UTF8&iwloc=&output=embed"
                            width="100%" height="400" style="border:0; display:block;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade" title="Beltway Office Park Location Map"
                            id="location-map">
                        </iframe>
                    </div>
                    <div class="mt-4 p-4 rounded-xl flex items-center gap-3"
                        style="background: rgba(212,175,55,0.1); border: 1px solid rgba(212,175,55,0.25);">
                        <svg class="w-5 h-5 flex-shrink-0" style="color: #D4AF37;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>
                            <div class="text-white font-bold text-sm">Beltway Office Park, Jl. Ampera Raya No. 9–10, RT.7/RW.2</div>
                            <div class="text-xs" style="color: rgba(255,255,255,0.65);">Ragunan, Pasar Minggu, Jakarta Selatan 12550
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- =============================================
    SECTION 7: AVAILABLE OFFICE SPACES
    ============================================= --}}
    <section id="spaces" class="py-24 px-6 lg:px-12 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">

            {{-- Section Header --}}
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12 fade-up">
                <div>
                    <div class="section-tag mb-3">Available Spaces</div>
                    <h2 class="text-gray-900 font-extrabold"
                        style="font-size: clamp(1.75rem, 4vw, 2.5rem); line-height: 1.15; letter-spacing: -0.02em;">
                        Find Your Ideal Office Space
                    </h2>
                </div>
                {{-- Filter Buttons --}}
                <div class="flex flex-wrap items-center gap-2 flex-shrink-0" id="space-filters">
                    <button class="space-filter-btn active" data-filter="all" id="filter-all">All</button>
                    @foreach($buildings as $building)
                        @php
                            $towerName = $building->name;
                            if ($towerName === 'Gedung A') $towerName = 'Tower A';
                            elseif ($towerName === 'Gedung B') $towerName = 'Tower B';
                            elseif ($towerName === 'Gedung C') $towerName = 'Tower C';
                        @endphp
                        <button class="space-filter-btn" data-filter="{{ \Illuminate\Support\Str::slug($towerName) }}" id="filter-{{ \Illuminate\Support\Str::slug($towerName) }}">{{ $towerName }}</button>
                    @endforeach
                </div>
            </div>

            {{-- Space Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" id="spaces-grid">
                @foreach($officeSpaces as $i => $space)
                    <div class="space-listing-card fade-up delay-{{ min(($i + 1) * 100, 500) }}"
                        data-filter="{{ $space['filter'] }}" id="space-listing-{{ $i }}">

                        {{-- Image with Status Badge --}}
                        <div class="relative overflow-hidden" style="height: 230px; border-radius: 12px 12px 0 0;">
                            <img src="{{ $space['image'] }}" alt="{{ $space['tower'] }} – {{ $space['floor'] }}"
                                class="w-full h-full object-cover space-listing-img cursor-pointer" loading="lazy">
                            <span class="space-status-badge">
                                <span class="space-status-dot"></span>
                                {{ $space['status'] }}
                            </span>
                        </div>

                        {{-- Card Body --}}
                        <div class="space-listing-body">
                            {{-- Tower & Floor --}}
                            <div class="flex items-center gap-1.5 text-sm text-gray-500 mb-2">
                                <svg class="w-4 h-4 flex-shrink-0" style="color: #1E3A8A;" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="font-semibold" style="color: #1E3A8A;">{{ $space['tower'] }}</span>
                                <span class="text-gray-400">·</span>
                                <span>{{ $space['floor'] }}</span>
                            </div>

                            {{-- Size --}}
                            <div class="text-2xl font-black text-gray-900 mb-0.5">{{ $space['sqm'] }}</div>

                            {{-- Price --}}
                            <div class="text-sm text-gray-500 mb-5">{{ $space['price'] }}</div>

                            {{-- Buttons --}}
                            <div class="flex gap-3 mt-auto">
                                <button class="space-btn-solid w-full"
                                    onclick="document.getElementById('contact').scrollIntoView({behavior:'smooth'})"
                                    id="reqinfo-btn-{{ $i }}">
                                    Request Info
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- No Spaces Placeholder --}}
                <div id="no-spaces-message" class="hidden col-span-full py-16 text-center text-gray-500 font-bold bg-slate-50 border border-slate-100 rounded-2xl w-full">
                    Space not available
                </div>
            </div>

        </div>
    </section>



    {{-- =============================================
    SECTION 8: GALLERY
    ============================================= --}}
    <section id="gallery" class="py-24 px-6 lg:px-12 overflow-hidden" style="background: #F8FAFC;">
        <div class="max-w-7xl mx-auto">

            <div class="text-center mb-16">
                <div class="flex justify-center">
                    <div class="section-tag fade-up">Gallery</div>
                </div>
                <h2 class="section-title fade-up delay-100">A Glimpse of<br><span>Our World</span></h2>
                <div class="section-divider mx-auto fade-up delay-200"></div>
            </div>

            {{-- Masonry Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="gallery-grid">
                @foreach($gallery as $i => $item)
                    <div class="gallery-item fade-up delay-{{ min(($i + 1) * 100, 500) }} {{ $item['span'] === 'large' ? 'md:col-span-2 row-span-1' : '' }}"
                        style="height: {{ $item['span'] === 'large' ? '320px' : '240px' }};" id="gallery-item-{{ $i }}"
                        role="button" tabindex="0" aria-label="View {{ $item['title'] }}">
                        <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover"
                            loading="lazy">
                        <div class="gallery-overlay">
                            <div>
                                <div class="text-white font-bold text-base">{{ $item['title'] }}</div>
                                <div class="flex items-center gap-1 text-sm mt-0.5" style="color: #D4AF37;">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                    View Full Image
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- =============================================
    SECTION 9: TENANT CAROUSEL
    ============================================= --}}
    <section id="tenants" class="py-20 px-6 lg:px-12 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">

            <div class="text-center mb-12">
                <div class="flex justify-center">
                    <div class="section-tag fade-up">Our Tenants</div>
                </div>
                <h2 class="section-title fade-up delay-100">Trusted by<br><span>Leading Companies</span></h2>
                <div class="section-divider mx-auto fade-up delay-200"></div>
            </div>

            {{-- Tenant Logo Showcase Marquee --}}
            <div class="marquee-container fade-up delay-300">
                <div class="marquee-track">
                    <!-- Group 1 -->
                    <div class="marquee-group">
                        @foreach($tenants as $i => $tenant)
                            @php
                                $isZoomed = in_array($tenant['logo'], ['kopken.png', 'kapal api.jpg', 'spklu.jpeg', 'mp.png', 'ck.png']);
                                $cardClass = $isZoomed ? 'tenant-logo-card p-2' : 'tenant-logo-card p-4';
                                $imgClass = $isZoomed ? 'h-14 w-auto object-contain max-w-full' : 'h-10 w-auto object-contain max-w-full';
                            @endphp
                            <div class="{{ $cardClass }}" id="tenant-{{ $i }}">
                                <img src="{{ asset($tenant['logo']) }}" alt="{{ $tenant['name'] }}"
                                    class="{{ $imgClass }}" loading="lazy">
                            </div>
                        @endforeach
                    </div>
                    <!-- Group 2 (Duplicate for Seamless Loop) -->
                    <div class="marquee-group" aria-hidden="true">
                        @foreach($tenants as $i => $tenant)
                            @php
                                $isZoomed = in_array($tenant['logo'], ['kopken.png', 'kapal api.jpg', 'spklu.jpeg', 'mp.png', 'ck.png']);
                                $cardClass = $isZoomed ? 'tenant-logo-card p-2' : 'tenant-logo-card p-4';
                                $imgClass = $isZoomed ? 'h-14 w-auto object-contain max-w-full' : 'h-10 w-auto object-contain max-w-full';
                            @endphp
                            <div class="{{ $cardClass }}" id="tenant-dup-{{ $i }}">
                                <img src="{{ asset($tenant['logo']) }}" alt="{{ $tenant['name'] }}"
                                    class="{{ $imgClass }}" loading="lazy">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </section>


    {{-- =============================================
    SECTION 10: NEWS & ARTICLES
    ============================================= --}}
    <section id="news" class="py-24 px-6 lg:px-12 bg-section-alt overflow-hidden">
        <div class="max-w-7xl mx-auto">

            <div class="flex flex-col md:flex-row items-end justify-between gap-6 mb-14">
                <div>
                    <div class="section-tag fade-up">News & Articles</div>
                    <h2 class="section-title fade-up delay-100">Latest News &<br><span>Insights</span></h2>
                    <div class="section-divider fade-up delay-200"></div>
                </div>
                <a href="#" class="btn-detail self-end fade-up delay-300" id="news-view-all">
                    View All Articles
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($news as $i => $article)
                    <article
                        class="news-card fade-up delay-{{ (($i % 3) + 1) * 100 }} {{ $i >= 3 ? 'hidden' : '' }} cursor-pointer"
                        id="news-card-{{ $i }}" data-title="{{ $article->title }}" data-category="{{ $article->category }}"
                        data-date="{{ $article->published_at->format('F d, Y') }}"
                        data-image="{{ $article->image ? (filter_var($article->image, FILTER_VALIDATE_URL) ? $article->image : asset('storage/' . $article->image)) : '' }}"
                        data-content="{{ $article->content ?: $article->excerpt }}">
                        <div class="overflow-hidden" style="height: 220px;">
                            @if($article->image)
                                @if(filter_var($article->image, FILTER_VALIDATE_URL))
                                    <img src="{{ $article->image }}" alt="{{ $article->title }}" loading="lazy">
                                @else
                                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" loading="lazy">
                                @endif
                            @else
                                <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400 text-sm">No
                                    Image</div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="news-category">{{ $article->category }}</span>
                                <span class="text-xs text-gray-400">{{ $article->published_at->format('F d, Y') }}</span>
                            </div>
                            <h3 class="font-bold text-gray-900 text-base leading-snug mb-3 line-clamp-2">{{ $article->title }}
                            </h3>
                            <p class="text-gray-500 text-sm leading-relaxed line-clamp-3">{{ $article->excerpt }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>


    {{-- =============================================
    SECTION 11: CONTACT
    ============================================= --}}
    <section id="contact" class="py-24 px-6 lg:px-12 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">

            <div class="text-center mb-16">
                <div class="flex justify-center">
                    <div class="section-tag fade-up">Get in Touch</div>
                </div>
                <h2 class="section-title fade-up delay-100">Start Your Journey<br><span>With Us Today</span></h2>
                <div class="section-divider mx-auto fade-up delay-200"></div>
                <p class="text-gray-600 max-w-xl mx-auto fade-up delay-300">
                    Ready to elevate your business? Contact our team and let's find the perfect office solution for you.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-14">

                {{-- Left: Contact Info --}}
                <div class="fade-left">
                    <h3 class="text-xl font-extrabold text-gray-900 mb-6">Contact Information</h3>
                    <div class="space-y-4 mb-10">
                        <div class="contact-info-item">
                            <div class="contact-icon">
                                <svg class="w-5 h-5" style="color: #1E3A8A;" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 text-sm">Office Address</div>
                                <div class="text-gray-500 text-sm mt-0.5">Beltway Office Park, Jl. Ampera Raya No. 9–10, RT.7/RW.2, Ragunan, Pasar Minggu, Jakarta Selatan 12550</div>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="contact-icon">
                                <svg class="w-5 h-5" style="color: #1E3A8A;" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 text-sm">Phone Number</div>
                                <div class="text-gray-500 text-sm mt-0.5">+62 812 9559 059</div>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="contact-icon">
                                <svg class="w-5 h-5" style="color: #1E3A8A;" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 text-sm">Email Address</div>
                                <div class="text-gray-500 text-sm mt-0.5">Fauzan.abadi@imeco.co.id</div>
                                <div class="text-gray-500 text-sm">Heru.wiistono@imeco.co.id</div>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="contact-icon">
                                <svg class="w-5 h-5" style="color: #1E3A8A;" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 text-sm">Business Hours</div>
                                <div class="text-gray-500 text-sm mt-0.5">Monday – Friday: 08:00 – 17:00 WIB</div>
                                <div class="text-gray-500 text-sm">Saturday: 08:00 – 13:00 WIB</div>
                            </div>
                        </div>
                    </div>

                    {{-- Office Interior Preview --}}
                    <div class="relative rounded-2xl overflow-hidden" style="height: 200px;">
                        <img src="{{ $images['officeInterior'] }}" alt="Office Interior" class="w-full h-full object-cover">
                        <div class="absolute inset-0"
                            style="background: linear-gradient(135deg, rgba(30,58,138,0.7), rgba(212,175,55,0.3));"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-white font-black text-xl">Visit Our Office</div>
                                <div class="text-sm" style="color: #D4AF37;">Experience the space firsthand</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Contact Form --}}
                <div class="fade-right">
                    <div class="p-8 rounded-2xl border"
                        style="box-shadow: 0 8px 40px rgba(15,23,42,0.1); border-color: rgba(30,58,138,0.1);">
                        <h3 class="text-xl font-extrabold text-gray-900 mb-6">Send Us a Message</h3>

                        {{-- Success Message --}}
                        @if(session('success'))
                            <div class="success-alert mb-6" id="contact-success">
                                <svg class="w-5 h-5 flex-shrink-0 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>{{ session('success') }}</div>
                            </div>
                        @endif

                        <form action="{{ route('contact') }}" method="POST" id="contact-form" novalidate>
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                                {{-- Name --}}
                                <div>
                                    <label for="contact-name" class="form-label">Full Name <span
                                            style="color: #dc2626;">*</span></label>
                                    <input type="text" id="contact-name" name="name" value="{{ old('name') }}"
                                        placeholder="John Doe"
                                        class="form-input {{ $errors->has('name') ? 'border-red-400' : '' }}" required>
                                    @error('name')
                                        <div class="error-msg" id="error-name">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="contact-email" class="form-label">Email Address <span
                                            style="color: #dc2626;">*</span></label>
                                    <input type="email" id="contact-email" name="email" value="{{ old('email') }}"
                                        placeholder="john@company.com"
                                        class="form-input {{ $errors->has('email') ? 'border-red-400' : '' }}" required>
                                    @error('email')
                                        <div class="error-msg" id="error-email">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                                {{-- Company --}}
                                <div>
                                    <label for="contact-company" class="form-label">Company Name</label>
                                    <input type="text" id="contact-company" name="company" value="{{ old('company') }}"
                                        placeholder="PT. Example Tbk" class="form-input">
                                </div>

                                {{-- Phone --}}
                                <div>
                                    <label for="contact-phone" class="form-label">Phone Number</label>
                                    <input type="tel" id="contact-phone" name="phone" value="{{ old('phone') }}"
                                        placeholder="+62 812 XXXX XXXX" class="form-input">
                                </div>
                            </div>

                            {{-- Message --}}
                            <div class="mb-6">
                                <label for="contact-message" class="form-label">Message <span
                                        style="color: #dc2626;">*</span></label>
                                <textarea id="contact-message" name="message" rows="5"
                                    placeholder="Tell us about your office space requirements, preferred location, size, and any special needs..."
                                    class="form-input resize-none {{ $errors->has('message') ? 'border-red-400' : '' }}"
                                    required minlength="10" maxlength="2000">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="error-msg" id="error-message">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn-submit" id="contact-submit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- News Detail Modal --}}
    <div id="news-modal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0"
        aria-modal="true" role="dialog">
        <div id="news-modal-content"
            class="bg-white rounded-3xl overflow-hidden max-w-2xl w-full border border-slate-200/80 shadow-2xl flex flex-col max-h-[85vh] scale-95 transition-transform duration-300">
            <!-- Modal Header -->
            <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
                <span id="modal-category" class="news-category"></span>
                <button id="close-news-modal"
                    class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-50 transition-all cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-6 overflow-y-auto space-y-6">
                <!-- Image -->
                <div id="modal-image-container"
                    class="rounded-2xl overflow-hidden bg-slate-50 border border-slate-200/60 flex-shrink-0 flex items-center justify-center"
                    style="max-height: 380px; display: none;">
                    <img id="modal-image" src="" alt="" class="max-w-full max-h-[380px] object-contain">
                </div>

                <!-- Title & Date -->
                <div class="space-y-2">
                    <div id="modal-date" class="text-xs text-slate-400 font-bold"></div>
                    <h3 id="modal-title" class="text-xl md:text-2xl font-black text-slate-900 leading-tight"></h3>
                </div>

                <!-- Content -->
                <div id="modal-content" class="text-slate-600 text-sm leading-relaxed space-y-4 whitespace-pre-line">
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-5 border-t border-slate-100 flex justify-end bg-slate-50 flex-shrink-0">
                <button id="close-news-modal-btn"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs bg-slate-200 text-slate-700 hover:bg-slate-300 transition-all cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // View all news articles dynamically
        const viewAllBtn = document.getElementById('news-view-all');
        if (viewAllBtn) {
            let showingAll = false;
            viewAllBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const articles = document.querySelectorAll('.news-card');

                if (!showingAll) {
                    articles.forEach((card, index) => {
                        if (index >= 3) {
                            card.classList.remove('hidden');
                            // Retrigger entrance animations cleanly
                            card.style.animation = 'fadeInUp 0.5s ease forwards';
                            card.style.animationDelay = `${((index % 3) + 1) * 100}ms`;
                        }
                    });
                    // Change button content to "Show Less"
                    viewAllBtn.innerHTML = `
                                        Show Less
                                        <svg class="w-3.5 h-3.5 rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                        </svg>
                                    `;
                    showingAll = true;
                } else {
                    articles.forEach((card, index) => {
                        if (index >= 3) {
                            card.classList.add('hidden');
                            card.style.animation = '';
                            card.style.animationDelay = '';
                        }
                    });
                    // Scroll back to news section top
                    document.getElementById('news')?.scrollIntoView({ behavior: 'smooth' });
                    // Change button content to "View All Articles"
                    viewAllBtn.innerHTML = `
                                        View All Articles
                                        <svg class="w-3.5 h-3.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                        </svg>
                                    `;
                    showingAll = false;
                }
            });
        }

        // Keyboard accessibility for gallery items
        document.querySelectorAll('.gallery-item').forEach(item => {
            item.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    item.click();
                }
            });
        });

        // Contact form WhatsApp redirection (Method A)
        const contactForm = document.getElementById('contact-form');
        const submitBtn = document.getElementById('contact-submit');
        if (contactForm && submitBtn) {
            contactForm.addEventListener('submit', (e) => {
                e.preventDefault();

                // Perform native browser validation check
                if (!contactForm.checkValidity()) {
                    contactForm.reportValidity();
                    return;
                }

                const name = document.getElementById('contact-name').value.trim();
                const email = document.getElementById('contact-email').value.trim();
                const company = document.getElementById('contact-company').value.trim() || '-';
                const phone = document.getElementById('contact-phone').value.trim() || '-';
                const message = document.getElementById('contact-message').value.trim();

                // Update button status to indicate redirecting
                submitBtn.innerHTML = `
                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Redirecting to WhatsApp...
                                `;
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.8';

                // Prepare the formatted message
                const waMessage = `Halo Beltway Office Park,

                        Saya ingin mengirimkan pesan:
                        * Nama: ${name}
                        * Email: ${email}
                        * Perusahaan: ${company}
                        * No. HP: ${phone}
                        * Pesan: ${message}`;

                // URL encode the message and point to the requested WhatsApp number
                const waUrl = `https://wa.me/6281213115450?text=${encodeURIComponent(waMessage)}`;

                // Open WhatsApp in a new tab
                window.open(waUrl, '_blank');

                // Reset the form and button after redirecting
                setTimeout(() => {
                    submitBtn.innerHTML = `
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        Send Message
                                    `;
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                    contactForm.reset();
                }, 1000);
            });
        }

        // Auto-scroll to contact if success message
        @if(session('success'))
            document.getElementById('contact')?.scrollIntoView({ behavior: 'smooth' });
        @endif

                        // News Detail Modal Logic
                        const newsModal = document.getElementById('news-modal');
        const modalContent = document.getElementById('news-modal-content');

        function showNewsModal(title, category, date, image, content) {
            if (!newsModal) return;

            // Set values
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-category').textContent = category;
            document.getElementById('modal-date').textContent = date;

            const imgEl = document.getElementById('modal-image');
            if (image) {
                imgEl.src = image;
                imgEl.alt = title;
                document.getElementById('modal-image-container').style.display = 'flex';
            } else {
                document.getElementById('modal-image-container').style.display = 'none';
            }

            document.getElementById('modal-content').textContent = content;

            // Open modal animation
            newsModal.classList.remove('hidden');
            // Force a reflow
            newsModal.offsetWidth;
            newsModal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');

            // Lock body scroll
            document.body.style.overflow = 'hidden';
        }

        function hideNewsModal() {
            if (!newsModal) return;

            newsModal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');

            setTimeout(() => {
                newsModal.classList.add('hidden');
                // Restore body scroll
                document.body.style.overflow = '';
            }, 300);
        }

        // Attach event listeners for each news card
        document.querySelectorAll('.news-card').forEach(card => {
            card.addEventListener('click', () => {
                const title = card.getAttribute('data-title');
                const category = card.getAttribute('data-category');
                const date = card.getAttribute('data-date');
                const image = card.getAttribute('data-image');
                const content = card.getAttribute('data-content');

                showNewsModal(title, category, date, image, content);
            });
        });

        document.getElementById('close-news-modal')?.addEventListener('click', hideNewsModal);
        document.getElementById('close-news-modal-btn')?.addEventListener('click', hideNewsModal);

        // Close on clicking backdrop
        newsModal?.addEventListener('click', (e) => {
            if (e.target === newsModal) {
                hideNewsModal();
            }
        });

        // Close on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !newsModal.classList.contains('hidden')) {
                hideNewsModal();
            }
        });
    </script>
@endsection