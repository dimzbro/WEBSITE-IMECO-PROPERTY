@extends('layouts.admin')

@section('title', 'Admin Dashboard – Beltway Office Park')
@section('breadcrumb', 'Dashboard')

@section('content')
@php
    $hour = date('H');
    $greeting = 'Selamat Pagi';
    if ($hour >= 12 && $hour < 15) {
        $greeting = 'Selamat Siang';
    } elseif ($hour >= 15 && $hour < 18) {
        $greeting = 'Selamat Sore';
    } elseif ($hour >= 18 || $hour < 5) {
        $greeting = 'Selamat Malam';
    }
    
    $adminName = Auth::user()->name ?? 'Admin Kawasan';
    $todayDate = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
@endphp

<div class="space-y-8 animate-fade-in pb-12">

    <!-- Smart Hero Panel -->
    <div class="bg-gradient-to-br from-slate-900 via-[#162A5E] to-slate-950 p-6 lg:p-8 rounded-3xl shadow-xl flex flex-col lg:flex-row lg:items-center justify-between gap-6 text-white relative overflow-hidden border border-white/5">
        <div class="absolute -right-16 -top-16 w-48 h-48 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute right-32 -bottom-24 w-64 h-64 rounded-full bg-[#1E3A8A]/20 blur-3xl pointer-events-none"></div>
        
        <div class="space-y-4 z-10">
            <div class="flex items-center gap-3">
                <span id="realtime-clock" class="text-xs font-black bg-white/10 border border-white/10 px-3 py-1 rounded-full backdrop-blur-md shadow-inner tracking-widest text-[#93C5FD]"></span>
                <span class="text-slate-400 text-xs font-bold">{{ $todayDate }}</span>
            </div>
            
            <div class="space-y-1.5">
                <h2 class="text-2xl lg:text-3xl font-black tracking-tight flex items-center gap-2">
                    <span id="dynamic-greeting">{{ $greeting }}</span>, {{ $adminName }}
                </h2>
                <p class="text-slate-350 text-xs font-semibold max-w-xl leading-relaxed">
                    Berikut ringkasan operasional hari ini: 
                    <span class="text-amber-400 font-extrabold">{{ $approachingEndCount }} Kontrak</span> akan habis sewa, dan
                    <span class="text-[#93C5FD] font-extrabold">{{ count($buildingStats) }} Gedung</span> terdaftar aktif.
                </p>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-3 z-10">
            <a href="{{ route('admin.tenants.create') }}" class="px-4 py-2.5 bg-white text-slate-900 hover:bg-slate-50 rounded-xl font-bold text-xs shadow-md transition-all flex items-center gap-2 cursor-pointer transform hover:-translate-y-0.5 duration-200">
                <svg class="w-4 h-4 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Tenant
            </a>
            <a href="{{ route('admin.calendar.index') }}" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white border border-white/15 rounded-xl font-bold text-xs transition-all flex items-center gap-2 cursor-pointer transform hover:-translate-y-0.5 duration-200 backdrop-blur-md">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Kalender Properti
            </a>
        </div>
    </div>

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Metric: Active Tenants -->
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] flex flex-col justify-between hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-[#1E3A8A] transition-transform group-hover:scale-110 duration-200">
                        <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <!-- Sparkline SVG -->
                    <svg class="w-16 h-8 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 100 30">
                        <path d="M0 28 L10 24 L20 25 L30 20 L40 22 L50 14 L60 18 L70 10 L80 12 L90 5 L100 2" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ $activeTenantsCount }}</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1.5">Tenant Aktif</p>
                </div>
            </div>
            
            <div class="pt-4 border-t border-slate-100 mt-4 space-y-2">
                <div class="flex items-center justify-between text-[10px] font-bold text-slate-400">
                    <span>PERSENTASE OKUPANSI</span>
                    <span class="text-slate-700">{{ $occupancyRate }}%</span>
                </div>
                <div class="w-full h-1.5 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full bg-[#1E3A8A] rounded-full transition-all duration-1000 ease-out" style="width: {{ $occupancyRate }}%"></div>
                </div>
                <div class="flex items-center justify-between mt-1 pt-1">
                    <p class="text-[10px] text-slate-500 font-semibold">Mengelola {{ $totalUnits }} total unit ruangan sewa</p>
                    <button onclick="openActiveTenantsModal()" class="text-[10px] font-bold text-[#1E3A8A] hover:text-slate-900 transition-colors flex items-center gap-1 cursor-pointer">
                        Lihat Detail
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Metric: Approaching End Contracts -->
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] flex flex-col justify-between hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 transition-transform group-hover:scale-110 duration-200">
                        <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <!-- Sparkline SVG -->
                    <svg class="w-16 h-8 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 100 30">
                        <path d="M0 5 L10 10 L20 8 L30 15 L40 12 L50 20 L60 17 L70 24 L80 20 L90 28 L100 26" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ $approachingEndCount }}</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1.5">Kontrak Mendekati Habis</p>
                </div>
            </div>
            
            <div class="pt-4 border-t border-slate-100 mt-4 space-y-2 font-semibold">
                <div class="flex items-center justify-between text-[10px] text-slate-400">
                    <span>TENGGAT WAKTU</span>
                    <span class="text-amber-600 font-black">90 HARI</span>
                </div>
                <div class="flex items-center justify-between mt-1 pt-1 gap-2">
                    <p class="text-[10px] text-slate-500 leading-relaxed max-w-[70%]">Segera hubungi tenant bersangkutan untuk negosiasi pembaharuan masa sewa unit.</p>
                    <button onclick="openExpiringContractsModal()" class="text-[10px] font-bold text-amber-600 hover:text-amber-800 transition-colors flex items-center gap-1 cursor-pointer whitespace-nowrap">
                        Lihat Detail
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Grid: Occupancy Breakdown -->
    <div class="grid grid-cols-1 gap-6">
        
        <!-- Occupancy breakdown per building (Dynamic Progress Bars) -->
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Okupansi & Visual Heatmap Gedung</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Persentase ruang disewa per gedung perkantoran</p>
                </div>
                <a href="{{ route('admin.buildings.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-50 text-xs font-bold text-[#1E3A8A] hover:bg-slate-100 transition-colors flex items-center gap-1.5">
                    Detail Map
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                @foreach($buildingStats as $stats)
                @php
                    $hColor = 'bg-rose-500';
                    $hText = 'Sangat Kosong';
                    if ($stats['occupancy_rate'] >= 80) {
                        $hColor = 'bg-emerald-500 animate-pulse';
                        $hText = 'Hampir Penuh';
                    } elseif ($stats['occupancy_rate'] >= 50) {
                        $hColor = 'bg-teal-500';
                        $hText = 'Okupansi Sedang';
                    } elseif ($stats['occupancy_rate'] >= 30) {
                        $hColor = 'bg-amber-500';
                        $hText = 'Okupansi Rendah';
                    }
                @endphp
                <div class="space-y-2.5 p-4 bg-slate-50/50 rounded-2xl border border-slate-100 hover:bg-slate-50 hover:border-slate-200/80 transition-all duration-200 group">
                    <div class="flex items-center justify-between text-xs font-black">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full {{ $hColor }}" title="{{ $hText }}"></span>
                            <span class="text-slate-800">{{ $stats['name'] }}</span>
                        </div>
                        <span class="text-slate-900 bg-white px-2 py-0.5 rounded-lg border border-slate-200/60 shadow-sm">{{ $stats['occupancy_rate'] }}%</span>
                    </div>
                    <!-- Progress Bar container -->
                    <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden relative">
                        <div class="h-full rounded-full transition-all duration-1000 ease-out {{ $stats['occupancy_rate'] > 75 ? 'bg-emerald-500' : ($stats['occupancy_rate'] > 40 ? 'bg-amber-500' : 'bg-rose-500') }}"
                             style="width: {{ $stats['occupancy_rate'] }}%"></div>
                    </div>
                    <div class="flex items-center justify-between text-[10px] text-slate-450 font-bold">
                        <span>{{ $stats['occupied_units'] }} Tenant Aktif</span>
                        <span>{{ $stats['vacant_units'] }} Unit Kosong</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Charts & Timeline activities -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Charts panel -->
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Analisis Pertumbuhan Tenant</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Statistik pertumbuhan tenant</p>
                </div>
            </div>

            <!-- Canvas charts -->
            <div class="grid grid-cols-1 gap-8">
                <!-- Tenant Growth Line Chart -->
                <div class="space-y-3 bg-slate-50/30 p-4 rounded-2xl border border-slate-100">
                    <h4 class="text-xs font-black text-slate-500 uppercase tracking-wider">Tenant Growth</h4>
                    <div class="h-60 relative flex items-center justify-center">
                        <canvas id="tenantGrowthChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Activity Timeline widget -->
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] space-y-5">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Recent Activities</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Aktivitas & tenggat waktu terdekat</p>
                </div>
            </div>

            <div class="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                @forelse($timelineEvents as $event)
                <div class="flex gap-3 items-start p-2 rounded-xl hover:bg-slate-50 transition-colors">
                    <div class="w-2.5 h-2.5 rounded-full {{ $event['color'] }} mt-1.5 flex-shrink-0 shadow-sm"></div>
                    <div class="space-y-0.5">
                        <div class="flex items-center gap-2">
                            <h4 class="text-xs font-black text-slate-800 leading-snug">{{ $event['title'] }}</h4>
                            <span class="text-[8px] font-black uppercase bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded">{{ $event['badge'] }}</span>
                        </div>
                        <p class="text-[11px] font-semibold text-slate-500 leading-relaxed">{{ $event['desc'] }}</p>
                        <span class="text-[9px] font-bold text-[#1E3A8A]/70 block">{{ $event['time'] }}</span>
                    </div>
                </div>
                @empty
                <div class="py-12 text-center text-slate-400 font-semibold italic bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    Belum ada aktivitas baru.
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Quick Insights Panel -->
    <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] space-y-6">
        <div>
            <h3 class="text-base font-extrabold text-slate-800">Quick Insights</h3>
            <p class="text-xs text-slate-500 mt-0.5">Analisis instan kondisi real-estate</p>
        </div>

        @php
            $highestOccupancyBuilding = collect($buildingStats)->sortByDesc('occupancy_rate')->first();
            $lowestOccupancyBuilding = collect($buildingStats)->sortBy('occupancy_rate')->first();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 font-semibold text-xs">
            <!-- Insight 1: Highest Occupancy -->
            <div class="p-4 bg-emerald-50/40 rounded-2xl border border-emerald-100/50 flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 font-bold">
                    ▲
                </div>
                <div>
                    <h4 class="font-extrabold text-emerald-800 text-[11px] uppercase tracking-wider">Okupansi Tertinggi</h4>
                    <p class="text-slate-700 font-bold mt-0.5">{{ $highestOccupancyBuilding['name'] }} ({{ $highestOccupancyBuilding['occupancy_rate'] }}%)</p>
                </div>
            </div>

            <!-- Insight 2: Lowest Occupancy -->
            <div class="p-4 bg-rose-50/40 rounded-2xl border border-rose-100/50 flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0 font-bold">
                    ▼
                </div>
                <div>
                    <h4 class="font-extrabold text-rose-800 text-[11px] uppercase tracking-wider">Okupansi Terendah</h4>
                    <p class="text-slate-700 font-bold mt-0.5">{{ $lowestOccupancyBuilding['name'] }} ({{ $lowestOccupancyBuilding['occupancy_rate'] }}%)</p>
                </div>
            </div>
        </div>

        <!-- Auto Insights Row -->
        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400 font-bold">
            <span class="flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Sistem Normal
            </span>
            <span>Insight: {{ count($buildingStats) }} Gedung Aktif</span>
        </div>
    </div>

    {{-- ── LK3 & Rekapitulasi Dashboard Charts (sumber data realtime bulan terbaru) ── --}}
    @if($lk3TotalRecords > 0 || $rekTotalRecords > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- Bar Chart 1: LK3 per Jenis Pekerjaan --}}
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800">LK3 – Jenis Pekerjaan</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Bulan {{ $lk3MonthLabel ?? 'Terbaru' }}</p>
                    </div>
                    <a href="{{ route('admin.lk3.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-50 text-xs font-bold text-[#1E3A8A] hover:bg-slate-100 transition-colors shrink-0">
                        Lihat Detail
                    </a>
                </div>
                <div class="h-56 relative mt-4">
                    @if($lk3ByJenisPekerjaan->count() > 0)
                        <canvas id="dashLk3ChartJenis"></canvas>
                    @else
                        <div class="h-full flex items-center justify-center text-xs font-semibold text-slate-400 border border-dashed border-slate-200 rounded-2xl">
                            Belum ada data LK3
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Bar Chart 2: LK3 per Departemen Pelapor --}}
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800">Pelapor LK3 – Departemen</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Bulan {{ $lk3MonthLabel ?? 'Terbaru' }}</p>
                    </div>
                    <a href="{{ route('admin.lk3.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-50 text-xs font-bold text-[#1E3A8A] hover:bg-slate-100 transition-colors shrink-0">
                        Lihat Detail
                    </a>
                </div>
                <div class="h-56 relative mt-4">
                    @if($lk3ByDept->count() > 0)
                        <canvas id="dashLk3ChartDept"></canvas>
                    @else
                        <div class="h-full flex items-center justify-center text-xs font-semibold text-slate-400 border border-dashed border-slate-200 rounded-2xl">
                            Belum ada data pelapor
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Bar Chart 3: Rekapitulasi Request per Jenis Pekerjaan --}}
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800">Rekapitulasi Request – Jenis Pekerjaan</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Bulan {{ $rekMonthLabel ?? 'Terbaru' }}</p>
                    </div>
                    <a href="{{ route('admin.rekapitulasi.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-50 text-xs font-bold text-[#1E3A8A] hover:bg-slate-100 transition-colors shrink-0">
                        Lihat Detail
                    </a>
                </div>
                <div class="h-56 relative mt-4">
                    @if($rekByJenisPekerjaan->count() > 0)
                        <canvas id="dashRekChartJenis"></canvas>
                    @else
                        <div class="h-full flex items-center justify-center text-xs font-semibold text-slate-400 border border-dashed border-slate-200 rounded-2xl">
                            Belum ada data request
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
    @endif

</div>

<!-- Active Tenants Modal -->
<div id="active-tenants-modal" role="dialog" aria-modal="true" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-start justify-center p-4 overflow-y-auto hidden">
    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8 transform transition-all duration-300 scale-95 opacity-0 flex flex-col max-h-[85vh]" id="active-tenants-card">
        
        <div class="bg-[#0F172A] text-white p-5 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold">Daftar Tenant Aktif</h3>
                <p class="text-xs text-slate-400 mt-0.5">Seluruh tenant yang memiliki alokasi unit sewa aktif</p>
            </div>
            <button onclick="closeActiveTenantsModal()" class="text-slate-400 hover:text-white p-1 rounded-lg cursor-pointer">✕</button>
        </div>

        <div class="p-6 overflow-y-auto flex-grow">
            <div class="overflow-x-auto rounded-xl border border-slate-150">
                <table class="w-full border-collapse text-left text-xs text-slate-655">
                    <thead class="bg-slate-50 border-b border-slate-150 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5">Nama Tenant</th>
                            <th class="px-5 py-3.5">Gedung / Unit</th>
                            <th class="px-5 py-3.5">Luas</th>
                            <th class="px-5 py-3.5">PIC / Kontak</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($activeTenantsList as $tenant)
                            @foreach($tenant->spaceAllocations as $alloc)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-5 py-3.5">
                                        <div class="font-bold text-slate-800">{{ $tenant->company_name }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">{{ $tenant->business_sector }}</div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        @if($alloc->building)
                                            <div class="font-bold text-slate-700">{{ $alloc->building->name }}</div>
                                            <div class="text-[10px] text-slate-400 mt-0.5">
                                                @if($alloc->building->name === 'Open Yard' || $alloc->building->name === 'Workshop')
                                                    {{ $alloc->unit_number }}
                                                @else
                                                    Lt. {{ $alloc->floor_number }} — {{ $alloc->unit_number }}
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="font-bold text-slate-750">{{ $alloc->area_size }} m²</div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="font-bold text-slate-700">{{ $tenant->pic_name }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">{{ $tenant->phone }} • {{ $tenant->email }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-slate-400 font-semibold italic bg-slate-50/50">
                                    Tidak ada data tenant aktif.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-5 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
            <button onclick="closeActiveTenantsModal()" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer">
                Tutup
            </button>
            <a href="{{ route('admin.tenants.index') }}" class="px-4 py-2 bg-[#1E3A8A] hover:bg-slate-900 text-white font-bold text-xs rounded-xl transition-colors flex items-center gap-1.5 cursor-pointer">
                Kelola Tenant
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Expiring Contracts Modal -->
<div id="expiring-contracts-modal" role="dialog" aria-modal="true" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-start justify-center p-4 overflow-y-auto hidden">
    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8 transform transition-all duration-300 scale-95 opacity-0 flex flex-col max-h-[85vh]" id="expiring-contracts-card">
        
        <div class="bg-[#0F172A] text-white p-5 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold">Kontrak Mendekati Habis</h3>
                <p class="text-xs text-slate-400 mt-0.5">Daftar unit sewa dengan sisa masa kontrak kurang dari 90 hari</p>
            </div>
            <button onclick="closeExpiringContractsModal()" class="text-slate-400 hover:text-white p-1 rounded-lg cursor-pointer">✕</button>
        </div>

        <div class="p-6 overflow-y-auto flex-grow">
            <div class="overflow-x-auto rounded-xl border border-slate-150">
                <table class="w-full border-collapse text-left text-xs text-slate-655">
                    <thead class="bg-slate-50 border-b border-slate-150 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5">Nama Tenant</th>
                            <th class="px-5 py-3.5">Gedung / Unit</th>
                            <th class="px-5 py-3.5">Masa Kontrak</th>
                            <th class="px-5 py-3.5">Sisa Hari</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($expiringContractsList as $alloc)
                            @php
                                $leaseEnd = \Carbon\Carbon::parse($alloc->lease_end);
                                $remainingDays = \Carbon\Carbon::now()->startOfDay()->diffInDays($leaseEnd->startOfDay(), false);
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-slate-800">{{ $alloc->tenant->company_name ?? 'Tenant' }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ $alloc->tenant->pic_name ?? '—' }} • {{ $alloc->tenant->phone ?? '—' }}</div>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($alloc->building)
                                        <div class="font-bold text-slate-700">{{ $alloc->building->name }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">
                                            @if($alloc->building->name === 'Open Yard' || $alloc->building->name === 'Workshop')
                                                {{ $alloc->unit_number }} ({{ $alloc->area_size }} m²)
                                            @else
                                                Lt. {{ $alloc->floor_number }} — {{ $alloc->unit_number }} ({{ $alloc->area_size }} m²)
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="text-slate-700">Mulai: <span class="font-bold">{{ \Carbon\Carbon::parse($alloc->lease_start)->translatedFormat('d M Y') }}</span></div>
                                    <div class="text-slate-700 mt-0.5">Selesai: <span class="font-black text-amber-700">{{ $leaseEnd->translatedFormat('d M Y') }}</span></div>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($remainingDays < 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-100 text-rose-800">
                                            Lewat {{ abs($remainingDays) }} hari
                                        </span>
                                    @elseif($remainingDays == 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-500 text-white animate-pulse">
                                            Hari ini habis
                                        </span>
                                    @elseif($remainingDays <= 30)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-100 text-rose-800">
                                            {{ $remainingDays }} hari lagi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800">
                                            {{ $remainingDays }} hari lagi
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if($alloc->status === 'Hampir Berakhir' || $remainingDays <= 30)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Hampir Berakhir
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Mendekati Berakhir
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400 font-semibold italic bg-slate-50/50">
                                    Tidak ada data kontrak mendekati habis.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-5 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
            <button onclick="closeExpiringContractsModal()" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer">
                Tutup
            </button>
            <a href="{{ route('admin.tenants.index', ['status' => 'Hampir Berakhir']) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl transition-colors flex items-center gap-1.5 cursor-pointer">
                Tindak Lanjuti
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Load ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Line Chart: Tenant Growth
        const ctxGrowth = document.getElementById('tenantGrowthChart').getContext('2d');
        
        // Gradient Fill for line
        const gradientBlue = ctxGrowth.createLinearGradient(0, 0, 0, 240);
        gradientBlue.addColorStop(0, 'rgba(30, 58, 138, 0.2)');
        gradientBlue.addColorStop(1, 'rgba(30, 58, 138, 0.0)');

        new Chart(ctxGrowth, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyUtilization['labels']) !!},
                datasets: [{
                    label: 'Jumlah Tenant',
                    data: {!! json_encode($monthlyUtilization['growth']) !!},
                    borderColor: '#1E3A8A',
                    backgroundColor: gradientBlue,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#1E3A8A',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#1E3A8A',
                    pointHoverBorderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: { color: 'rgba(0,0,0,0.03)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });



        // Realtime Clock & Greeting Functionality
        const clockEl = document.getElementById('realtime-clock');
        const greetingEl = document.getElementById('dynamic-greeting');
        if (clockEl) {
            function updateClock() {
                const now = new Date();
                clockEl.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                
                const hour = now.getHours();
                let greeting = 'Selamat Pagi';
                if (hour >= 12 && hour < 15) {
                    greeting = 'Selamat Siang';
                } else if (hour >= 15 && hour < 18) {
                    greeting = 'Selamat Sore';
                } else if (hour >= 18 || hour < 5) {
                    greeting = 'Selamat Malam';
                }
                
                if (greetingEl) {
                    greetingEl.textContent = greeting;
                }
            }
            setInterval(updateClock, 1000);
            updateClock();
        }
    });

    // ----------------------------------------------------
    // Active Tenants Modal Script
    const activeModal = document.getElementById('active-tenants-modal');
    const activeCard = document.getElementById('active-tenants-card');

    window.openActiveTenantsModal = function() {
        if (!activeModal || !activeCard) return;
        activeModal.classList.remove('hidden');
        setTimeout(() => {
            activeCard.classList.remove('scale-95', 'opacity-0');
            activeCard.classList.add('scale-100', 'opacity-100');
        }, 50);
    };

    window.closeActiveTenantsModal = function() {
        if (!activeModal || !activeCard) return;
        activeCard.classList.remove('scale-100', 'opacity-100');
        activeCard.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            activeModal.classList.add('hidden');
        }, 200);
    };

    if (activeModal) {
        activeModal.addEventListener('click', function(e) {
            if (e.target === activeModal) {
                window.closeActiveTenantsModal();
            }
        });
    }

    // Expiring Contracts Modal Script
    const expiringModal = document.getElementById('expiring-contracts-modal');
    const expiringCard = document.getElementById('expiring-contracts-card');

    window.openExpiringContractsModal = function() {
        if (!expiringModal || !expiringCard) return;
        expiringModal.classList.remove('hidden');
        setTimeout(() => {
            expiringCard.classList.remove('scale-95', 'opacity-0');
            expiringCard.classList.add('scale-100', 'opacity-100');
        }, 50);
    };

    window.closeExpiringContractsModal = function() {
        if (!expiringModal || !expiringCard) return;
        expiringCard.classList.remove('scale-100', 'opacity-100');
        expiringCard.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            expiringModal.classList.add('hidden');
        }, 200);
    };

    if (expiringModal) {
        expiringModal.addEventListener('click', function(e) {
            if (e.target === expiringModal) {
                window.closeExpiringContractsModal();
            }
        });
    }
</script>

{{-- ── LK3 & Rekapitulasi Bar Charts Script ─────────────────────────────────── --}}
@if($lk3TotalRecords > 0 || $rekTotalRecords > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const lk3NavyPalette = ['#1E3A8A','#1D4ED8','#2563EB','#3B82F6','#60A5FA','#93C5FD','#0F172A','#1E293B','#334155','#475569'];
    const lk3BluePalette = ['#0284C7','#0369A1','#0EA5E9','#38BDF8','#7DD3FC','#BAE6FD','#1D4ED8','#2563EB','#3B82F6','#60A5FA'];
    const rekVioletPalette = ['#4F46E5','#6366F1','#818CF8','#A5B4FC','#C7D2FE','#3730A3','#312E81','#1E1B4B','#0D9488','#14B8A6'];

    // Chart 1 – LK3 Jenis Pekerjaan
    const ctxJenis = document.getElementById('dashLk3ChartJenis');
    if (ctxJenis) {
        new Chart(ctxJenis, {
            type: 'bar',
            data: {
                labels: @json($lk3ByJenisPekerjaan->pluck('jenis_pekerjaan')->toArray()),
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: @json($lk3ByJenisPekerjaan->pluck('total')->toArray()),
                    backgroundColor: lk3NavyPalette.slice(0, {{ $lk3ByJenisPekerjaan->count() }}),
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { size: 11, weight: 'bold' },
                        bodyFont: { size: 11 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: { label: (ctx) => ` ${ctx.parsed.y} laporan` }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10, weight: '600' }, color: '#64748B' } },
                    y: { beginAtZero: true, grid: { color: '#F1F5F9' }, ticks: { font: { size: 10 }, color: '#94A3B8', precision: 0 } }
                }
            }
        });
    }

    // Chart 2 – LK3 Departemen Pelapor
    const ctxDept = document.getElementById('dashLk3ChartDept');
    if (ctxDept) {
        new Chart(ctxDept, {
            type: 'bar',
            data: {
                labels: @json($lk3ByDept->pluck('dari_dept')->toArray()),
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: @json($lk3ByDept->pluck('total')->toArray()),
                    backgroundColor: lk3BluePalette.slice(0, {{ $lk3ByDept->count() }}),
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { size: 11, weight: 'bold' },
                        bodyFont: { size: 11 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: { label: (ctx) => ` ${ctx.parsed.y} laporan` }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10, weight: '600' }, color: '#64748B' } },
                    y: { beginAtZero: true, grid: { color: '#F1F5F9' }, ticks: { font: { size: 10 }, color: '#94A3B8', precision: 0 } }
                }
            }
        });
    }

    // Chart 3 – Rekapitulasi Request Jenis Pekerjaan
    const ctxRek = document.getElementById('dashRekChartJenis');
    if (ctxRek) {
        new Chart(ctxRek, {
            type: 'bar',
            data: {
                labels: @json($rekByJenisPekerjaan->pluck('jenis_pekerjaan')->toArray()),
                datasets: [{
                    label: 'Jumlah Request',
                    data: @json($rekByJenisPekerjaan->pluck('total')->toArray()),
                    backgroundColor: rekVioletPalette.slice(0, {{ $rekByJenisPekerjaan->count() }}),
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { size: 11, weight: 'bold' },
                        bodyFont: { size: 11 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: { label: (ctx) => ` ${ctx.parsed.y} request` }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10, weight: '600' }, color: '#64748B' } },
                    y: { beginAtZero: true, grid: { color: '#F1F5F9' }, ticks: { font: { size: 10 }, color: '#94A3B8', precision: 0 } }
                }
            }
        });
    }
})();
</script>
@endif
@endsection
