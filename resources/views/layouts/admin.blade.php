<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Beltway Office Park Admin Portal">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Portal – Beltway Office Park')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Iconify CDN -->
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    @yield('styles')
</head>
<body class="h-full text-slate-800 antialiased flex flex-col lg:flex-row">
    @php
        $headerUnreadCount = \App\Models\Notification::where('is_read', false)->count();
    @endphp

    <!-- Mobile Sidebar Header Toggle -->
    <div class="lg:hidden w-full bg-[#0F172A] text-white px-4 py-3 flex items-center justify-between border-b border-white/10 z-50">
        <a href="/" class="flex items-center gap-2">
            <img src="{{ asset('logo_bop.png') }}" alt="BELTWAY Logo" class="w-8 h-8 object-contain brightness-0 invert">
            <div class="font-extrabold text-sm tracking-wide">BELTWAY</div>
        </a>
        <div class="flex items-center gap-2">
            {{-- Notification Bell Mobile --}}
            <a href="{{ route('admin.notifications.index') }}" class="relative p-2 text-white hover:bg-slate-800 rounded-lg flex items-center justify-center" title="Pusat Notifikasi">
                <svg class="w-6 h-6 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="unread-count-badge-mobile absolute top-1 right-1 flex items-center justify-center w-4 h-4 rounded-full text-white text-[10px] font-black {{ $headerUnreadCount > 0 ? '' : 'hidden' }}"
                      style="background-color: #EF4444 !important; color: #ffffff !important; display: {{ $headerUnreadCount > 0 ? 'flex' : 'none' }} !important;">
                    {{ $headerUnreadCount > 99 ? '99+' : $headerUnreadCount }}
                </span>
            </a>
            <button id="mobile-sidebar-toggle" class="p-2 text-white hover:bg-slate-800 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Sidebar -->
    <aside id="sidebar-nav" class="hidden lg:flex flex-col w-64 bg-[#0F172A] text-slate-300 border-r border-white/5 flex-shrink-0 z-40 fixed inset-y-0 left-0 lg:relative lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <!-- Logo Branding -->
        <div class="p-6 border-b border-white/5 flex items-center gap-3">
            <img src="{{ asset('logo_bop.png') }}" alt="BELTWAY Logo" class="w-10 h-10 object-contain brightness-0 invert">
            <div>
                <div class="text-white font-extrabold text-base tracking-wide leading-none">Beltway Office</div>
                <div class="text-[10px] font-semibold tracking-widest text-[#D4AF37] mt-0.5">PARK MANAGEMENT</div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-grow p-4 space-y-6 overflow-y-auto">
            <!-- Category: MANAJEMEN -->
            <div>
                <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Manajemen</div>
                <div class="space-y-1">
                    <a href="{{ route('admin') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Route::is('admin') ? 'bg-[#1E3A8A] text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.tenants.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is('admin/tenants*') ? 'bg-[#1E3A8A] text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Tenant Management
                    </a>
                    
                    <a href="{{ route('admin.buildings.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is('admin/buildings*') ? 'bg-[#1E3A8A] text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Building Management
                    </a>

                    <a href="{{ route('admin.office_spaces.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is('admin/office-spaces*') ? 'bg-[#1E3A8A] text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                        </svg>
                        Available Spaces
                    </a>

                    <a href="{{ route('admin.gallery.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is('admin/gallery*') ? 'bg-[#1E3A8A] text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Gallery Management
                    </a>

                    <a href="{{ route('admin.news.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is('admin/news*') ? 'bg-[#1E3A8A] text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        News & Articles
                    </a>

                    <a href="{{ route('admin.calendar.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is('admin/calendar*') ? 'bg-[#1E3A8A] text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Kalender
                    </a>
                </div>
            </div>

            {{-- Category: LAPORAN --}}
            <div>
                <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Laporan</div>
                <div class="space-y-1">
                    <a href="{{ route('admin.lk3.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is('admin/lk3*') ? 'bg-[#1E3A8A] text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        LK3
                    </a>

                    <a href="{{ route('admin.rekapitulasi.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is('admin/rekapitulasi*') ? 'bg-[#1E3A8A] text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Rekapitulasi Request
                    </a>

                    <a href="{{ route('admin.electricity.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is('admin/electricity*') ? 'bg-[#1E3A8A] text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <iconify-icon icon="lucide:zap" class="w-5 h-5 flex-shrink-0 text-lg"></iconify-icon>
                        Penggunaan Daya Listrik
                    </a>

                    <a href="{{ route('admin.water.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is('admin/water*') ? 'bg-[#1E3A8A] text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <iconify-icon icon="lucide:droplets" class="w-5 h-5 flex-shrink-0 text-lg"></iconify-icon>
                        Penggunaan Air Bersih
                    </a>

                    <a href="{{ route('admin.bop-leads.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is('admin/bop-leads*') ? 'bg-[#1E3A8A] text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <iconify-icon icon="lucide:building-2" class="w-5 h-5 flex-shrink-0 text-lg"></iconify-icon>
                        Daftar Peminat Office BOP
                    </a>

                    <a href="{{ route('admin.notifications.index') }}" 
                       class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is('admin/notifications*') ? 'bg-[#1E3A8A] text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <iconify-icon icon="lucide:bell" class="w-5 h-5 flex-shrink-0 text-lg"></iconify-icon>
                            <span>Pusat Notifikasi</span>
                        </div>
                        @if($headerUnreadCount > 0)
                            <span class="px-2 py-0.5 text-[10px] font-black rounded-full bg-rose-500 text-white">
                                {{ $headerUnreadCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('admin.profile.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is('admin/profile*') ? 'bg-[#1E3A8A] text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <iconify-icon icon="lucide:settings" class="w-5 h-5 flex-shrink-0 text-lg"></iconify-icon>
                        Pengaturan Akun
                    </a>
                </div>
            </div>
        </nav>

        <!-- Footer Sidebar (Admin Profile Card) -->
        <div class="p-4 border-t border-white/5 bg-[#0F172A] flex items-center justify-between">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-10 h-10 rounded-xl bg-[#1E3A8A] flex items-center justify-center text-white font-bold flex-shrink-0">
                    AK
                </div>
                <div class="overflow-hidden">
                    <div class="text-sm font-semibold text-white truncate">Admin Kawasan</div>
                    <div class="text-xs text-slate-400 truncate">Property Manager</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="p-2 hover:bg-white/5 text-rose-400 hover:text-rose-300 rounded-lg transition-colors cursor-pointer" title="Log Out">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Panel -->
    <div class="flex-grow flex flex-col min-h-screen overflow-hidden">
        <!-- Top Header Bar -->
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shadow-sm z-30">
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                <span>Beltway Office Park</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-slate-800 font-bold">@yield('breadcrumb', 'Dashboard')</span>
            </div>

            <!-- Profile & Notifications -->
            <div class="flex items-center gap-3">
                
                {{-- Notification Center Bell --}}
                <div class="relative" id="notification-center-wrapper">
                    <button type="button" id="notification-bell-btn" onclick="toggleNotificationDropdown()"
                            class="relative w-10 h-10 rounded-xl bg-slate-100/90 hover:bg-slate-200/90 text-slate-700 hover:text-slate-900 transition-all flex items-center justify-center cursor-pointer focus:outline-none shadow-sm"
                            title="Pusat Notifikasi">
                        <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span id="unread-count-badge" 
                              style="background-color: #EF4444 !important; color: #ffffff !important; display: {{ $headerUnreadCount > 0 ? 'flex' : 'none' }} !important;"
                              class="absolute top-0 right-0 transform translate-x-1/3 -translate-y-1/3 flex items-center justify-center w-5 h-5 rounded-full text-white text-[11px] font-black leading-none shadow-md pointer-events-none {{ $headerUnreadCount > 0 ? '' : 'hidden' }} z-30">
                            {{ $headerUnreadCount > 99 ? '99+' : $headerUnreadCount }}
                        </span>
                    </button>

                    {{-- Notification Center Dropdown --}}
                    <div id="notification-dropdown" 
                         class="absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-3xl border border-slate-200/80 shadow-[0_10px_30px_rgb(0,0,0,0.12)] z-50 hidden overflow-hidden animate-fade-in">
                        
                        {{-- Dropdown Header --}}
                        <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="lucide:bell" class="text-base text-blue-400"></iconify-icon>
                                <span class="font-extrabold text-xs">Notifikasi Terbaru</span>
                                <span id="dropdown-unread-label" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-blue-600/40 text-blue-200 border border-blue-400/20">
                                    0 Belum Dibaca
                                </span>
                            </div>
                            <button type="button" onclick="markAllNotificationsAsRead()" class="text-[11px] font-bold text-slate-300 hover:text-white transition-colors cursor-pointer" title="Tandai Semua Dibaca">
                                Tandai Semua Dibaca
                            </button>
                        </div>

                        {{-- Dropdown List Body --}}
                        <div id="notification-dropdown-list" class="max-h-[360px] overflow-y-auto divide-y divide-slate-100">
                            <div class="p-6 text-center text-xs font-semibold text-slate-400">
                                Memuat notifikasi...
                            </div>
                        </div>

                        {{-- Dropdown Footer --}}
                        <div class="p-3 bg-slate-50 border-t border-slate-100 text-center">
                            <a href="{{ route('admin.notifications.index') }}" class="text-xs font-extrabold text-[#1E3A8A] hover:text-slate-900 transition-colors flex items-center justify-center gap-1.5 py-1">
                                <span>Lihat Semua Notifikasi</span>
                                <iconify-icon icon="lucide:arrow-right" class="text-xs"></iconify-icon>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Dropdown profile / Link Pengaturan Akun -->
                <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-3 pl-2 border-l border-slate-200 hover:opacity-80 transition-opacity" title="Pengaturan Akun">
                    <div class="w-9 h-9 rounded-full bg-[#1E3A8A] flex items-center justify-center text-white font-extrabold text-xs shadow-sm">
                        AK
                    </div>
                    <div class="hidden md:block text-left">
                        <div class="text-xs font-bold text-slate-800 flex items-center gap-1">
                            <span>Admin Kawasan</span>
                            <iconify-icon icon="lucide:settings" class="text-[11px] text-slate-400"></iconify-icon>
                        </div>
                        <div class="text-[10px] text-slate-500">Property Manager</div>
                    </div>
                </a>
            </div>
        </header>

        <!-- Main View Area -->
        <main class="flex-grow p-6 overflow-y-auto bg-slate-50">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-start gap-3 shadow-sm animate-fadeIn">
                    <div class="p-1 rounded-lg bg-emerald-500 text-white flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-emerald-800">Berhasil</h4>
                        <p class="text-xs text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-3 shadow-sm animate-fadeIn">
                    <div class="p-1 rounded-lg bg-rose-500 text-white flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-rose-800">Error</h4>
                        <p class="text-xs text-rose-700 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Mobile Navigation Toggle Script -->
    <script>
        const mobileToggle = document.getElementById('mobile-sidebar-toggle');
        const sidebarNav = document.getElementById('sidebar-nav');

        if (mobileToggle && sidebarNav) {
            mobileToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebarNav.classList.toggle('hidden');
                sidebarNav.classList.toggle('flex');
            });

            // Close sidebar on outer click for mobile view
            document.addEventListener('click', (e) => {
                if (window.innerWidth < 1024 && !sidebarNav.contains(e.target) && !mobileToggle.contains(e.target)) {
                    sidebarNav.classList.add('hidden');
                    sidebarNav.classList.remove('flex');
                }
            });
        }
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to convert native confirm to data-confirm
            function convertConfirms(root = document) {
                if (root.nodeType !== Node.ELEMENT_NODE && root.nodeType !== Node.DOCUMENT_NODE) return;
                root.querySelectorAll('form').forEach(function(form) {
                    const onsubmit = form.getAttribute('onsubmit');
                    if (onsubmit && onsubmit.includes('confirm(')) {
                        const match = onsubmit.match(/confirm\(['"](.*?)['"]\)/);
                        if (match && match[1]) {
                            form.setAttribute('data-confirm', match[1]);
                            form.removeAttribute('onsubmit');
                        }
                    }
                });
            }

            // Initial conversion
            convertConfirms();

            // Observe dynamic elements to convert newly added forms
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === Node.ELEMENT_NODE) {
                            convertConfirms(node);
                            // Also check if node itself is a form
                            if (node.tagName === 'FORM') {
                                const onsubmit = node.getAttribute('onsubmit');
                                if (onsubmit && onsubmit.includes('confirm(')) {
                                    const match = onsubmit.match(/confirm\(['"](.*?)['"]\)/);
                                    if (match && match[1]) {
                                        node.setAttribute('data-confirm', match[1]);
                                        node.removeAttribute('onsubmit');
                                    }
                                }
                            }
                        }
                    });
                });
            });
            observer.observe(document.body, { childList: true, subtree: true });

            // Handle custom confirmation dialogs using event delegation
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form.hasAttribute('data-confirm')) {
                    e.preventDefault();
                    e.stopPropagation();

                    const confirmMsg = form.getAttribute('data-confirm');

                    Swal.fire({
                        title: 'Konfirmasi Tindakan',
                        text: confirmMsg,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#1E3A8A',
                        cancelButtonColor: '#EF4444',
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                        background: '#ffffff',
                        customClass: {
                            popup: 'rounded-2xl border border-slate-100 shadow-xl p-6',
                            title: 'text-lg font-bold text-slate-800 mt-2',
                            htmlContainer: 'text-sm text-slate-500 mt-1',
                            confirmButton: 'px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-md transition-all',
                            cancelButton: 'px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.removeAttribute('data-confirm');
                            form.submit();
                        }
                    });
                }
            });
        });

        // ── Notification Center Scripts ──────────────────────────────────────────
        let isNotificationDropdownOpen = false;

        function toggleNotificationDropdown() {
            const dropdown = document.getElementById('notification-dropdown');
            if (!dropdown) return;
            
            isNotificationDropdownOpen = !isNotificationDropdownOpen;
            if (isNotificationDropdownOpen) {
                dropdown.classList.remove('hidden');
                fetchRecentNotifications();
            } else {
                dropdown.classList.add('hidden');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            const wrapper = document.getElementById('notification-center-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                const dropdown = document.getElementById('notification-dropdown');
                if (dropdown) {
                    dropdown.classList.add('hidden');
                    isNotificationDropdownOpen = false;
                }
            }
        });

        function updateUnreadBadge() {
            fetch("{{ route('admin.notifications.unread_count') }}", {
                headers: { 
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Network response not OK');
                return res.json();
            })
            .then(data => {
                if (data && typeof data.unread_count !== 'undefined') {
                    const count = parseInt(data.unread_count);
                    const badges = document.querySelectorAll('#unread-count-badge, .unread-count-badge-mobile');
                    const label = document.getElementById('dropdown-unread-label');
                    
                    badges.forEach(badge => {
                        if (badge) {
                            if (count > 0) {
                                badge.textContent = count > 99 ? '99+' : count;
                                badge.classList.remove('hidden');
                                badge.style.setProperty('display', 'flex', 'important');
                            } else {
                                badge.textContent = '0';
                                badge.classList.add('hidden');
                                badge.style.setProperty('display', 'none', 'important');
                            }
                        }
                    });

                    if (label) {
                        label.textContent = `${count} Belum Dibaca`;
                    }
                }
            })
            .catch(err => {
                console.log('Unread badge status:', err);
            });
        }

        function fetchRecentNotifications() {
            const listContainer = document.getElementById('notification-dropdown-list');
            if (!listContainer) return;

            fetch("{{ route('admin.notifications.recent') }}", {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('unread-count-badge');
                const label = document.getElementById('dropdown-unread-label');
                if (badge) {
                    if (data.unread_count > 0) {
                        badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
                if (label) {
                    label.textContent = `${data.unread_count} Belum Dibaca`;
                }

                if (!data.notifications || data.notifications.length === 0) {
                    listContainer.innerHTML = `
                        <div class="p-8 text-center space-y-2">
                            <iconify-icon icon="lucide:bell-off" class="text-2xl text-slate-300"></iconify-icon>
                            <p class="text-xs font-extrabold text-slate-700">Belum ada notifikasi</p>
                            <p class="text-[11px] text-slate-400">Notifikasi aktivitas akan muncul di sini</p>
                        </div>
                    `;
                    return;
                }

                let html = '';
                data.notifications.forEach(item => {
                    const isUnread = !item.is_read;
                    const bgClass = isUnread ? 'bg-blue-50/60' : 'hover:bg-slate-50/80';
                    const titleColor = isUnread ? 'font-black text-slate-900' : 'font-bold text-slate-700';

                    html += `
                        <div onclick="handleNotificationClick(${item.id})" class="p-3.5 flex items-start gap-3 cursor-pointer transition-all ${bgClass} relative group">
                            <div class="p-2 rounded-xl border flex-shrink-0 text-base ${item.type_badge_color}">
                                <iconify-icon icon="${item.type_icon}"></iconify-icon>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <h4 class="text-xs truncate ${titleColor}">${escapeHtml(item.title)}</h4>
                                    ${isUnread ? '<span class="w-2 h-2 rounded-full bg-[#1E3A8A] flex-shrink-0"></span>' : ''}
                                </div>
                                ${item.description ? `<p class="text-[11px] text-slate-500 line-clamp-2 mt-0.5">${escapeHtml(item.description)}</p>` : ''}
                                <span class="text-[10px] font-semibold text-slate-400 mt-1 block">${item.relative_time}</span>
                            </div>
                        </div>
                    `;
                });
                listContainer.innerHTML = html;
            })
            .catch(err => console.error('Error fetching recent notifications:', err));
        }

        function handleNotificationClick(id) {
            const token = "{{ csrf_token() }}";
            fetch(`{{ url('admin/notifications') }}/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.action_url) {
                    window.location.href = data.action_url;
                } else {
                    fetchRecentNotifications();
                }
            })
            .catch(() => {
                window.location.href = "{{ route('admin.notifications.index') }}";
            });
        }

        function markAllNotificationsAsRead() {
            const token = "{{ csrf_token() }}";
            fetch("{{ route('admin.notifications.read_all') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(() => {
                fetchRecentNotifications();
            });
        }

        function escapeHtml(text) {
            if (!text) return '';
            return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        // Modern In-Web Alert & Confirm Modal (SweetAlert Style)
        window.showWebAlert = function(message, title = 'Peringatan', type = 'warning') {
            const modal = document.getElementById('web-alert-modal');
            const container = document.getElementById('web-alert-container');
            const msgEl = document.getElementById('web-alert-message');
            const titleEl = document.getElementById('web-alert-title');
            const iconText = document.getElementById('web-alert-icon-text');
            const iconContainer = document.getElementById('web-alert-icon-container');
            const confirmBtn = document.getElementById('web-alert-confirm-btn');
            const cancelBtn = document.getElementById('web-alert-cancel-btn');

            if (!modal || !msgEl) {
                alert(message);
                return;
            }

            if (titleEl) titleEl.textContent = title;
            msgEl.textContent = message;

            if (type === 'danger' || type === 'error') {
                iconContainer.className = 'w-20 h-20 rounded-full border-4 border-rose-300/80 bg-rose-50/40 flex items-center justify-center flex-shrink-0';
                iconText.className = 'text-rose-500 text-4xl font-light leading-none';
                iconText.textContent = '!';
            } else if (type === 'success') {
                iconContainer.className = 'w-20 h-20 rounded-full border-4 border-emerald-300/80 bg-emerald-50/40 flex items-center justify-center flex-shrink-0';
                iconText.className = 'text-emerald-500 text-4xl font-light leading-none';
                iconText.textContent = '✓';
            } else {
                iconContainer.className = 'w-20 h-20 rounded-full border-4 border-amber-300/80 bg-amber-50/40 flex items-center justify-center flex-shrink-0';
                iconText.className = 'text-amber-500 text-4xl font-light leading-none';
                iconText.textContent = '!';
            }

            confirmBtn.textContent = 'Mengerti';
            confirmBtn.className = 'px-6 py-2.5 bg-[#1E3A8A] hover:bg-slate-900 text-white font-bold text-sm rounded-xl transition-all shadow-md hover:shadow-lg cursor-pointer';
            confirmBtn.onclick = function() { closeWebAlert(); };
            cancelBtn.classList.add('hidden');

            modal.style.setProperty('z-index', '99999', 'important');
            modal.style.setProperty('display', 'flex', 'important');
            modal.classList.remove('hidden');

            setTimeout(() => {
                if (container) {
                    container.classList.remove('scale-95');
                    container.classList.add('scale-100');
                }
            }, 10);
        };

        window.showWebConfirm = function(message, title = 'Konfirmasi Tindakan', onConfirm = null, confirmText = 'Ya, Lanjutkan', cancelText = 'Batal') {
            const modal = document.getElementById('web-alert-modal');
            const container = document.getElementById('web-alert-container');
            const msgEl = document.getElementById('web-alert-message');
            const titleEl = document.getElementById('web-alert-title');
            const iconText = document.getElementById('web-alert-icon-text');
            const iconContainer = document.getElementById('web-alert-icon-container');
            const confirmBtn = document.getElementById('web-alert-confirm-btn');
            const cancelBtn = document.getElementById('web-alert-cancel-btn');

            if (!modal || !msgEl) {
                if (confirm(message) && onConfirm) onConfirm();
                return;
            }

            if (titleEl) titleEl.textContent = title;
            msgEl.textContent = message;

            iconContainer.className = 'w-20 h-20 rounded-full border-4 border-amber-300/80 bg-amber-50/40 flex items-center justify-center flex-shrink-0';
            iconText.className = 'text-amber-500 text-4xl font-light leading-none';
            iconText.textContent = '!';

            confirmBtn.textContent = confirmText;
            confirmBtn.className = 'px-6 py-2.5 bg-[#1E3A8A] hover:bg-slate-900 text-white font-bold text-sm rounded-xl transition-all shadow-md hover:shadow-lg cursor-pointer';
            confirmBtn.onclick = function() {
                closeWebAlert();
                if (typeof onConfirm === 'function') onConfirm();
            };

            cancelBtn.textContent = cancelText;
            cancelBtn.className = 'px-6 py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-bold text-sm rounded-xl transition-all shadow-md hover:shadow-lg cursor-pointer';
            cancelBtn.classList.remove('hidden');
            cancelBtn.onclick = function() { closeWebAlert(); };

            modal.style.setProperty('z-index', '99999', 'important');
            modal.style.setProperty('display', 'flex', 'important');
            modal.classList.remove('hidden');

            setTimeout(() => {
                if (container) {
                    container.classList.remove('scale-95');
                    container.classList.add('scale-100');
                }
            }, 10);
        };

        window.closeWebAlert = function() {
            const modal = document.getElementById('web-alert-modal');
            const container = document.getElementById('web-alert-container');
            if (!modal) return;

            if (container) {
                container.classList.remove('scale-100');
                container.classList.add('scale-95');
            }
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.style.setProperty('display', 'none', 'important');
            }, 150);
        };

        // Auto poll unread count every 12 seconds
        document.addEventListener('DOMContentLoaded', function() {
            updateUnreadBadge();
            setInterval(updateUnreadBadge, 12000);
        });
    </script>
    
    {{-- Global Web Alert & Confirm Modal (SweetAlert Style) --}}
    <div id="web-alert-modal" 
         style="z-index: 99999 !important;"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center hidden p-4 transition-all duration-300">
        <div id="web-alert-container" 
             class="bg-white rounded-3xl max-w-md w-full p-8 shadow-2xl border border-slate-100 flex flex-col items-center text-center space-y-4 transform transition-all duration-300 scale-95">
            
            {{-- Large Center Circular Icon --}}
            <div id="web-alert-icon-container" class="w-20 h-20 rounded-full border-4 border-amber-300/80 bg-amber-50/40 flex items-center justify-center flex-shrink-0 transition-transform duration-300">
                <span id="web-alert-icon-text" class="text-amber-500 text-4xl font-light leading-none">!</span>
            </div>

            {{-- Title --}}
            <h3 id="web-alert-title" class="text-2xl font-bold text-slate-800 tracking-tight">
                Peringatan
            </h3>

            {{-- Message --}}
            <p id="web-alert-message" class="text-sm font-medium text-slate-500 leading-relaxed max-w-xs">
                Pesan notifikasi
            </p>

            {{-- Buttons Area --}}
            <div id="web-alert-buttons-area" class="pt-2 flex items-center justify-center gap-3 w-full">
                <button type="button" id="web-alert-confirm-btn" onclick="closeWebAlert()" 
                        class="px-6 py-2.5 bg-[#1E3A8A] hover:bg-slate-900 text-white font-bold text-sm rounded-xl transition-all shadow-md hover:shadow-lg cursor-pointer">
                    Mengerti
                </button>
                <button type="button" id="web-alert-cancel-btn" onclick="closeWebAlert()" 
                        class="px-6 py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-bold text-sm rounded-xl transition-all shadow-md hover:shadow-lg cursor-pointer hidden">
                    Batal
                </button>
            </div>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
