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

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    @yield('styles')
</head>
<body class="h-full text-slate-800 antialiased flex flex-col lg:flex-row">

    <!-- Mobile Sidebar Header Toggle -->
    <div class="lg:hidden w-full bg-[#0F172A] text-white px-4 py-3 flex items-center justify-between border-b border-white/10 z-50">
        <a href="/" class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-gradient-to-tr from-[#1E3A8A] to-[#D4AF37]">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="font-extrabold text-sm tracking-wide">BELTWAY</div>
        </a>
        <button id="mobile-sidebar-toggle" class="p-2 text-white hover:bg-slate-800 rounded-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
            </svg>
        </button>
    </div>

    <!-- Sidebar -->
    <aside id="sidebar-nav" class="hidden lg:flex flex-col w-64 bg-[#0F172A] text-slate-300 border-r border-white/5 flex-shrink-0 z-40 fixed inset-y-0 left-0 lg:relative lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <!-- Logo Branding -->
        <div class="p-6 border-b border-white/5 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-gradient-to-tr from-[#1E3A8A] to-[#D4AF37] shadow-lg shadow-[#1E3A8A]/30">
                <svg class="w-5.5 h-5.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
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

                    <a href="{{ route('admin.news.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is('admin/news*') ? 'bg-[#1E3A8A] text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        News & Articles
                    </a>
                </div>
            </div>

            <!-- Optional: Static category helpers for aesthetics -->
            <div>
                <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Operasional</div>
                <div class="space-y-1">
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium opacity-60 cursor-not-allowed hover:bg-white/5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Billing & Payments
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium opacity-60 cursor-not-allowed hover:bg-white/5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        </svg>
                        Maintenance
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

            <!-- Profile / Actions -->
            <div class="flex items-center gap-4">
                <!-- Simple Dynamic Search Box for aesthetic representation -->
                <div class="relative hidden sm:block">
                    <input type="text" placeholder="Cari tenant, unit, gedung..." class="w-64 px-4 py-2 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all duration-200">
                    <svg class="absolute right-3.5 top-2.5 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <div class="w-px h-6 bg-slate-200"></div>

                <!-- Dropdown profile -->
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-slate-900 flex items-center justify-center text-white font-extrabold text-xs">
                        AK
                    </div>
                    <div class="hidden md:block text-left">
                        <div class="text-xs font-bold text-slate-800">Admin Kawasan</div>
                        <div class="text-[10px] text-slate-500">Property Manager</div>
                    </div>
                </div>
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
    
    @yield('scripts')
</body>
</html>
