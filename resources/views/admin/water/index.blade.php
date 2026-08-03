@extends('layouts.admin')

@section('title', 'Penggunaan Air Bersih – Admin Portal')
@section('breadcrumb', 'Penggunaan Air Bersih')

@section('content')
<style>
    .gold-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    .gold-scrollbar::-webkit-scrollbar-track {
        background: #F8FAFC;
        border-radius: 9999px;
    }
    .gold-scrollbar::-webkit-scrollbar-thumb {
        background: #D97706;
        border-radius: 9999px;
    }
    .gold-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #B45309;
    }
</style>

<div class="space-y-8 animate-fade-in pb-16">

    {{-- ── Page Header ────────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                <div class="p-2.5 bg-[#1E3A8A] text-white rounded-2xl shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 14c0 3.866-3.582 7-8 7s-8-3.134-8-7c0-2.83 2.128-5.28 5.25-6.38L12 3l2.75 4.62C17.872 8.72 20 11.17 20 14z"/>
                    </svg>
                </div>
                Penggunaan Air Bersih
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Pencatatan, pemantauan, dan analisis penggunaan air bersih (Debet Air m³) setiap gedung per bulan
            </p>
        </div>

        @if($records->total() > 0)
        <div class="flex items-center gap-2">
            <form action="{{ route('admin.water.destroyAll') }}" method="POST"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penggunaan air bersih sesuai filter aktif? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                @if($selectedYear) <input type="hidden" name="tahun" value="{{ $selectedYear }}"> @endif
                @if($selectedGedung) <input type="hidden" name="gedung" value="{{ $selectedGedung }}"> @endif
                <button type="submit" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl font-bold text-xs transition-all flex items-center gap-2 cursor-pointer shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus Data Terfilter
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- ── Duplicate Warning Flash Alert ───────────────────────────────────── --}}
    @if(session('duplicate_error'))
        @php $dup = session('duplicate_error'); @endphp
        <div class="p-5 bg-amber-50 border-2 border-amber-300 rounded-2xl shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4 animate-bounce-short">
            <div class="flex items-start gap-3">
                <div class="p-2 bg-amber-500 text-white rounded-xl flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-extrabold text-amber-900">Data Sudah Ada di Database!</h4>
                    <p class="text-xs text-amber-800 mt-1 leading-relaxed">{{ $dup['message'] }}</p>
                </div>
            </div>
            @if(isset($dup['existing_data']))
                <button type="button" 
                        onclick="openEditModalFromObject({{ json_encode($dup['existing_data']) }})"
                        class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2 flex-shrink-0 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Data Ini Sekarang
                </button>
            @endif
        </div>
    @endif

    {{-- ── 1. Summary Dashboard Cards (Top) ────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Card 1: Total Debet Air --}}
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)] flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Total Tahun {{ $selectedYear }}</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#1E3A8A] flex items-center justify-center">
                    <iconify-icon icon="lucide:droplets" class="text-xl"></iconify-icon>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($totalDebetAirSelectedYear, 2, ',', '.') }}</p>
                <p class="text-[11px] font-semibold text-slate-500 mt-0.5">m³ Debet Air {{ $selectedGedung ? "($selectedGedung)" : '(Semua Gedung)' }}</p>
            </div>
        </div>

        {{-- Card 2: Rata-rata per Bulan --}}
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)] flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Rata-Rata / Bulan</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($avgDebetAirPerMonth, 2, ',', '.') }}</p>
                <p class="text-[11px] font-semibold text-slate-500 mt-0.5">m³ / Bulan Terdaftar</p>
            </div>
        </div>

        {{-- Card 3: Penggunaan Tertinggi (Variabel Bulan) --}}
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)] flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Tertinggi</span>
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                @if($highestMonth)
                    <p class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($highestMonth->total_debet, 2, ',', '.') }}</p>
                    <p class="text-[11px] font-semibold text-rose-600 truncate mt-0.5" title="Bulan {{ $highestMonth->nama_bulan }}">
                        {{ $selectedGedung ? "$selectedGedung – " : '' }}Bulan {{ $highestMonth->nama_bulan }}
                    </p>
                @else
                    <p class="text-2xl font-black text-slate-400">-</p>
                    <p class="text-[11px] font-semibold text-slate-400 mt-0.5">Tidak ada data</p>
                @endif
            </div>
        </div>

        {{-- Card 4: Penggunaan Terendah (Variabel Bulan) --}}
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)] flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Terendah</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                @if($lowestMonth)
                    <p class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($lowestMonth->total_debet, 2, ',', '.') }}</p>
                    <p class="text-[11px] font-semibold text-emerald-600 truncate mt-0.5" title="Bulan {{ $lowestMonth->nama_bulan }}">
                        {{ $selectedGedung ? "$selectedGedung – " : '' }}Bulan {{ $lowestMonth->nama_bulan }}
                    </p>
                @else
                    <p class="text-2xl font-black text-slate-400">-</p>
                    <p class="text-[11px] font-semibold text-slate-400 mt-0.5">Tidak ada data</p>
                @endif
            </div>
        </div>

        {{-- Card 5: Persentase Perubahan vs Tahun Sebelumnya --}}
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)] flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">vs Tahun {{ $previousYear }}</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                @if($overallPercentageChange !== null)
                    @php $isUp = $overallPercentageChange > 0; $isDown = $overallPercentageChange < 0; @endphp
                    <p class="text-2xl font-black tracking-tight {{ $isUp ? 'text-rose-600' : ($isDown ? 'text-emerald-600' : 'text-slate-700') }}">
                        {{ $isUp ? '+' : '' }}{{ number_format($overallPercentageChange, 2, ',', '.') }}%
                    </p>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">
                        {{ $isUp ? 'Kenaikan dibanding' : ($isDown ? 'Penurunan dibanding' : 'Sama dengan') }} {{ $previousYear }}
                    </p>
                @else
                    <p class="text-2xl font-black text-slate-400">-</p>
                    <p class="text-[11px] font-semibold text-slate-400 mt-0.5">Data {{ $previousYear }} belum ada</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ── 2. Form Input Data ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)] p-6 lg:p-8">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#1E3A8A] flex items-center justify-center font-extrabold text-lg">
                +
            </div>
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Form Input Penggunaan Air Bersih</h3>
                <p class="text-xs text-slate-500 mt-0.5">Masukkan data penggunaan air bersih (Debet Air m³) gedung setiap bulan</p>
            </div>
        </div>

        <form action="{{ route('admin.water.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                {{-- Field Gedung --}}
                <div class="space-y-1.5">
                    <label for="form_gedung" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Gedung <span class="text-rose-500">*</span>
                    </label>
                    <select id="form_gedung" name="gedung" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] transition-all">
                        <option value="" disabled {{ old('gedung') ? '' : 'selected' }}>-- Pilih Gedung --</option>
                        @foreach($listGedung as $g)
                            <option value="{{ $g }}" {{ old('gedung') === $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                    @error('gedung')
                        <p class="text-[11px] font-bold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field Tahun (Kalender Year Picker Widget) --}}
                <div class="space-y-1.5 relative">
                    <label for="form_tahun" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Tahun <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative cursor-pointer" onclick="toggleYearPicker('form')">
                        <input type="text" id="form_tahun" name="tahun" required readonly
                               value="{{ old('tahun') }}"
                               placeholder="-- Pilih Tahun --"
                               class="w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] transition-all cursor-pointer">
                        
                        {{-- Calendar Icon (Left) --}}
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#1E3A8A] pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        
                        {{-- Chevron Arrow Icon (Right) --}}
                        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Popover Kalender Tahun --}}
                    <div id="yearPickerPopover_form" class="hidden absolute top-full left-0 mt-2 w-full min-w-[240px] bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 p-4 animate-fade-in">
                        <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                            <button type="button" onclick="navigateDecade('form', -1)" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <span id="decadeTitle_form" class="text-xs font-black text-[#1E3A8A]">2020 - 2031</span>
                            <button type="button" onclick="navigateDecade('form', 1)" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                        <div id="yearGrid_form" class="grid grid-cols-3 gap-2"></div>
                    </div>

                    @error('tahun')
                        <p class="text-[11px] font-bold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field Bulan --}}
                <div class="space-y-1.5">
                    <label for="form_bulan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Bulan <span class="text-rose-500">*</span>
                    </label>
                    <select id="form_bulan" name="bulan" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] transition-all">
                        <option value="" disabled {{ old('bulan') ? '' : 'selected' }}>-- Pilih Bulan --</option>
                        @foreach($listBulan as $num => $nama)
                            <option value="{{ $num }}" {{ (int) old('bulan') === $num ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('bulan')
                        <p class="text-[11px] font-bold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field Debet Air --}}
                <div class="space-y-1.5">
                    <label for="form_debet_air" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Debet Air (m³) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="0.01" min="0.01" id="form_debet_air" name="debet_air" required
                           value="{{ old('debet_air') }}"
                           placeholder="Contoh: 1250.50"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] transition-all">
                    @error('debet_air')
                        <p class="text-[11px] font-bold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" 
                        class="px-6 py-3 bg-[#1E3A8A] hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2 cursor-pointer transform hover:-translate-y-0.5 duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Data Penggunaan
                </button>
            </div>
        </form>
    </div>

    {{-- ── 3. Tabel Rekapitulasi Penggunaan Air Bersih (Matriks Bulanan & Log) ── --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)]">
        
        {{-- Table Header Controls & View Toggle --}}
        <div class="p-6 border-b border-slate-100 space-y-4 relative z-20">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Rekapitulasi Penggunaan Air Bersih (m³)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Tabel rekapitulasi debet air per gedung per bulan untuk Tahun {{ $selectedYear }}</p>
                </div>

                {{-- Mode Switcher & Controls --}}
                <div class="flex items-center gap-3">
                    <div class="bg-slate-100 p-1 rounded-xl flex items-center gap-1 border border-slate-200/60">
                        <a href="{{ route('admin.water.index', array_merge(request()->query(), ['view' => 'matrix'])) }}" 
                           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ $viewMode === 'matrix' ? 'bg-[#1E3A8A] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                            Tabel Matriks (Jan-Des)
                        </a>
                        <a href="{{ route('admin.water.index', array_merge(request()->query(), ['view' => 'list'])) }}" 
                           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ $viewMode === 'list' ? 'bg-[#1E3A8A] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            Tabel Log Rinci
                        </a>
                    </div>
                </div>
            </div>

            {{-- Filter Form --}}
            @if($viewMode === 'matrix')
                {{-- Filter Khusus Mode Tabel Matriks: Hanya Filter Tahun (Widget Kalender) --}}
                <form id="filterForm_matrix" action="{{ route('admin.water.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <input type="hidden" name="view" value="matrix">
                    <div class="flex items-center gap-2">
                        <label for="matrix_tahun" class="text-xs font-bold text-slate-700 whitespace-nowrap">Filter Tahun:</label>
                        
                        <div class="relative cursor-pointer min-w-[170px]" onclick="toggleYearPicker('matrix')">
                            <input type="text" id="matrix_tahun" name="tahun" readonly
                                   value="{{ $selectedYear }}"
                                   placeholder="-- Pilih Tahun --"
                                   class="w-full px-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-extrabold text-[#1E3A8A] text-center focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] shadow-sm cursor-pointer">
                            
                            {{-- Calendar Icon (Left) --}}
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[#1E3A8A] pointer-events-none">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            
                            {{-- Chevron Arrow (Right) --}}
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>

                            {{-- Popover Kalender Tahun Matrix --}}
                            <div id="yearPickerPopover_matrix" class="hidden absolute top-full left-0 mt-2 w-64 bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 p-4 animate-fade-in">
                                <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                                    <button type="button" onclick="navigateDecade('matrix', -1); event.stopPropagation();" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </button>
                                    <span id="decadeTitle_matrix" class="text-xs font-black text-[#1E3A8A]">2020 - 2031</span>
                                    <button type="button" onclick="navigateDecade('matrix', 1); event.stopPropagation();" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                                <div id="yearGrid_matrix" class="grid grid-cols-3 gap-2"></div>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                {{-- Filter Lengkap Mode Tabel Log Rinci --}}
                <form action="{{ route('admin.water.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <input type="hidden" name="view" value="list">

                    {{-- Search Box --}}
                    <div class="relative flex items-center">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari gedung, ID, Debet..."
                               class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A]">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    {{-- Filter Tahun (Kalender) --}}
                    <div class="relative cursor-pointer" onclick="toggleYearPicker('list_filter')">
                        <input type="text" id="list_filter_tahun" name="tahun" readonly
                               value="{{ $selectedYear }}"
                               placeholder="-- Filter Tahun --"
                               class="w-full pl-9 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] cursor-pointer">
                        
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[#1E3A8A] pointer-events-none">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        {{-- Popover Kalender Tahun List Filter --}}
                        <div id="yearPickerPopover_list_filter" class="hidden absolute top-full left-0 mt-2 w-64 bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 p-4 animate-fade-in">
                            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                                <button type="button" onclick="navigateDecade('list_filter', -1); event.stopPropagation();" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                <span id="decadeTitle_list_filter" class="text-xs font-black text-[#1E3A8A]">2020 - 2031</span>
                                <button type="button" onclick="navigateDecade('list_filter', 1); event.stopPropagation();" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                            <div id="yearGrid_list_filter" class="grid grid-cols-3 gap-2"></div>
                        </div>
                    </div>

                    {{-- Filter Bulan --}}
                    <select name="bulan" onchange="this.form.submit()" class="px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A]">
                        <option value="">Semua Bulan</option>
                        @foreach($listBulan as $num => $nama)
                            <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>

                    {{-- Filter Gedung --}}
                    <select name="gedung" onchange="this.form.submit()" class="px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A]">
                        <option value="">Semua Gedung</option>
                        @foreach($listGedung as $g)
                            <option value="{{ $g }}" {{ $selectedGedung == $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-sm transition-all">
                            Terapkan
                        </button>
                        @if($search || $selectedMonth || $selectedGedung || $selectedYear != date('Y'))
                            <a href="{{ route('admin.water.index', ['view' => $viewMode]) }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition-all" title="Reset Filter">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            @endif
        </div>

        {{-- ── TABEL MATRIKS BULANAN (Sesuai Layout Gambar Excel + Nomor ID) ──── --}}
        @if($viewMode === 'matrix')
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-slate-300 text-xs font-medium">
                <thead>
                    {{-- Row 1 Header --}}
                    <tr>
                        <th rowspan="2" 
                            style="background-color: #1E3A8A !important; color: #ffffff !important;"
                            class="border border-blue-900 px-4 py-3 text-center align-middle font-black text-sm uppercase tracking-wider min-w-[200px]">
                            GEDUNG
                        </th>
                        <th colspan="12" 
                            style="background-color: #1E3A8A !important; color: #ffffff !important;"
                            class="border border-blue-900 px-4 py-2 text-center font-black text-xs uppercase tracking-wider">
                            BULAN
                        </th>
                        <th rowspan="2" 
                            style="background-color: #1E3A8A !important; color: #ffffff !important;"
                            class="border border-blue-900 px-4 py-3 text-center align-middle font-black text-xs uppercase tracking-wider min-w-[120px]">
                            TOTAL DEBET AIR (m³)
                        </th>
                    </tr>
                    {{-- Row 2 Month Headers --}}
                    <tr>
                        @php
                            $shortMonthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                        @endphp
                        @foreach($shortMonthNames as $idx => $mName)
                            <th style="background-color: #1E3A8A !important; color: #ffffff !important;"
                                class="border border-blue-900 px-3 py-2 text-center font-black text-xs min-w-[85px]">
                                {{ $mName }}-{{ $shortYear }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @foreach($listGedung as $gedungName)
                        @if(!$selectedGedung || $selectedGedung === $gedungName)
                        <tr class="hover:bg-sky-50/50 transition-colors">
                            {{-- Nama Gedung --}}
                            <td class="border border-slate-300 px-4 py-3 font-extrabold text-slate-800 bg-slate-50/50 whitespace-nowrap">
                                {{ $gedungName }}
                            </td>

                            {{-- 12 Bulan values --}}
                            @for($m = 1; $m <= 12; $m++)
                                @php
                                    $cell = $matrixData[$gedungName][$m] ?? null;
                                @endphp
                                <td class="border border-slate-300 px-3 py-2.5 text-right font-bold transition-all cursor-pointer group hover:bg-blue-100/70"
                                    @if($cell)
                                        onclick="openDetailModal({{ json_encode($cell) }})"
                                        title="{{ $gedungName }} - {{ $shortMonthNames[$m-1] }}-{{ $shortYear }}: {{ number_format($cell['debet_air'], 2, ',', '.') }} m³ (Klik untuk detail)"
                                    @else
                                        onclick="prefillForm('{{ addslashes($gedungName) }}', {{ $selectedYear }}, {{ $m }})"
                                        title="{{ $gedungName }} - {{ $shortMonthNames[$m-1] }}-{{ $shortYear }}: Data Belum Ada (Klik untuk menginput)"
                                    @endif>
                                    
                                    @if($cell)
                                        <span class="text-slate-900 group-hover:text-[#1E3A8A]">
                                            {{ number_format($cell['debet_air'], 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 font-normal group-hover:text-blue-500">-</span>
                                    @endif
                                </td>
                            @endfor

                            {{-- Total Debet Air Per Gedung --}}
                            @php
                                $bTotal = $buildingTotals[$gedungName] ?? 0;
                            @endphp
                            <td class="border border-slate-300 px-3 py-2.5 text-right font-black text-[#1E3A8A] bg-slate-100/80 whitespace-nowrap"
                                title="Total Debet Air {{ $gedungName }} (Tahun {{ $selectedYear }}): {{ number_format($bTotal, 2, ',', '.') }} m³">
                                {{ $bTotal > 0 ? number_format($bTotal, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>

                {{-- Summary Footer Row --}}
                <tfoot>
                    <tr class="bg-slate-100/90 font-extrabold border-t-2 border-slate-300 text-slate-800">
                        <td class="border border-slate-300 px-4 py-3 text-center uppercase tracking-wider bg-slate-200/80">
                            TOTAL DEBET AIR (m³)
                        </td>
                        @for($m = 1; $m <= 12; $m++)
                            <td class="border border-slate-300 px-3 py-3 text-right text-[#1E3A8A]">
                                {{ $monthlyTotals[$m] > 0 ? number_format($monthlyTotals[$m], 0, ',', '.') : '-' }}
                            </td>
                        @endfor
                        <td class="border border-slate-300 px-3 py-3 text-right text-[#1E3A8A] bg-slate-300/80 font-black"
                            title="Grand Total Debet Air Seluruh Gedung (Tahun {{ $selectedYear }}): {{ number_format($grandTotal, 2, ',', '.') }} m³">
                            {{ $grandTotal > 0 ? number_format($grandTotal, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="p-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs font-semibold text-slate-500">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-[#1E3A8A]"></span>
                Tabel Matriks Rekapitulasi Air Bersih & Mapping Nomor ID (Tahun {{ $selectedYear }})
            </div>
            <div class="text-slate-600 font-bold">
                * Klik pada sel angka untuk melihat Detail / Edit data, atau sel kosong untuk menambah data baru.
            </div>
        </div>
        @endif

        {{-- ── TABEL LOG RINCI (Detailed Table View) ────────────────────────── --}}
        @if($viewMode === 'list')
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-black text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4 w-12 text-center">No</th>
                        <th class="px-6 py-4">Gedung</th>
                        <th class="px-4 py-4 text-center">Nomor ID</th>
                        <th class="px-4 py-4 text-center">Tahun</th>
                        <th class="px-4 py-4">Bulan</th>
                        <th class="px-6 py-4 text-right">Debet Air (m³)</th>
                        <th class="px-6 py-4 text-right">Selisih vs {{ $previousYear }}</th>
                        <th class="px-6 py-4 text-right">% Perubahan</th>
                        <th class="px-4 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                    @forelse($records as $index => $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4 text-center font-bold text-slate-400">
                                {{ $records->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 font-extrabold text-slate-900">
                                {{ $item->gedung }}
                            </td>
                            <td class="px-4 py-4 text-center font-bold font-mono text-slate-600">
                                {{ $item->nomor_id }}
                            </td>
                            <td class="px-4 py-4 text-center font-bold text-slate-600">
                                {{ $item->tahun }}
                            </td>
                            <td class="px-4 py-4 font-bold text-slate-800">
                                {{ $item->nama_bulan }}
                            </td>
                            <td class="px-6 py-4 text-right font-black text-[#1E3A8A]">
                                {{ number_format($item->debet_air, 2, ',', '.') }} m³
                            </td>
                            <td class="px-6 py-4 text-right font-bold">
                                @if($item->selisih !== null)
                                    <span class="{{ $item->selisih > 0 ? 'text-rose-600' : ($item->selisih < 0 ? 'text-emerald-600' : 'text-slate-600') }}">
                                        {{ $item->selisih > 0 ? '+' : '' }}{{ number_format($item->selisih, 2, ',', '.') }} m³
                                    </span>
                                @else
                                    <span class="text-slate-400 font-normal">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-bold">
                                @if($item->persentase !== null)
                                    <span class="{{ $item->persentase > 0 ? 'text-rose-600' : ($item->persentase < 0 ? 'text-emerald-600' : 'text-slate-600') }}">
                                        {{ $item->persentase > 0 ? '+' : '' }}{{ number_format($item->persentase, 2, ',', '.') }}%
                                    </span>
                                @else
                                    <span class="text-slate-400 font-normal italic">Data tidak tersedia</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($item->status === 'Naik')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                        </svg>
                                        Naik
                                    </span>
                                @elseif($item->status === 'Turun')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                        </svg>
                                        Turun
                                    </span>
                                @elseif($item->status === 'Tetap')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-slate-100 text-slate-700 border border-slate-200">
                                        = Tetap
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-400">
                                        -
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- Detail --}}
                                    <button type="button" 
                                            onclick="openDetailModal({{ json_encode($item) }})"
                                            class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-colors cursor-pointer" 
                                            title="Detail Record">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    {{-- Edit --}}
                                    <button type="button" 
                                            onclick="openEditModalFromObject({{ json_encode($item) }})"
                                            class="p-2 bg-blue-50 hover:bg-blue-100 text-[#1E3A8A] rounded-lg transition-colors cursor-pointer" 
                                            title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.water.destroy', $item->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus data {{ $item->gedung }} - {{ $item->nama_bulan }} {{ $item->tahun }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition-colors cursor-pointer" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 14c0 3.866-3.582 7-8 7s-8-3.134-8-7c0-2.83 2.128-5.28 5.25-6.38L12 3l2.75 4.62C17.872 8.72 20 11.17 20 14z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-slate-600">Belum ada data penggunaan air bersih</p>
                                    <p class="text-xs text-slate-400">Gunakan form di atas untuk menambahkan data baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $records->links() }}
            </div>
        @endif
        @endif
    </div>

    {{-- ── 4. Chart Visualisation Card (Bottom) ────────────────────────────── --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)] p-6 lg:p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                    Grafik Perbandingan Penggunaan Air Bersih
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Tren debet air bulanan: Tahun <span class="font-bold text-[#1E3A8A]">{{ $selectedYear }}</span> vs Tahun <span class="font-bold text-amber-600">{{ $previousYear }}</span>
                </p>
            </div>
            <div class="flex items-center gap-3 text-xs font-bold">
                <span class="px-3 py-1.5 rounded-xl bg-blue-50 text-[#1E3A8A] border border-blue-100 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#1E3A8A]"></span>
                    Tahun {{ $selectedYear }}
                </span>
                <span class="px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 border border-amber-100 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    Tahun {{ $previousYear }}
                </span>
            </div>
        </div>

        <div class="relative w-full h-[500px]">
            <canvas id="waterLineChart"></canvas>
        </div>
    </div>

</div>

{{-- ── Detail Modal ────────────────────────────────────────────────────────── --}}
<div id="detailModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 w-full max-w-lg overflow-hidden transform transition-all">
        <div class="p-6 bg-slate-900 text-white flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-[#1E3A8A] rounded-xl text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 14c0 3.866-3.582 7-8 7s-8-3.134-8-7c0-2.83 2.128-5.28 5.25-6.38L12 3l2.75 4.62C17.872 8.72 20 11.17 20 14z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-sm" id="detail_title">Detail Penggunaan Air Bersih</h3>
                    <p class="text-[11px] text-slate-400" id="detail_subtitle">Rincian data debet air</p>
                </div>
            </div>
            <button onclick="closeDetailModal()" class="p-1 text-slate-400 hover:text-white rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Gedung</span>
                    <span class="text-xs font-extrabold text-slate-900 mt-1 block truncate" id="detail_gedung">-</span>
                </div>
                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Periode</span>
                    <span class="text-xs font-extrabold text-slate-900 mt-1 block truncate" id="detail_periode">-</span>
                </div>
            </div>

            <div class="p-5 bg-blue-50/60 rounded-2xl border border-blue-100 text-center">
                <span class="text-[10px] font-extrabold text-blue-900 uppercase tracking-wider block">Penggunaan Air Bersih</span>
                <span class="text-3xl font-black text-[#1E3A8A] mt-1 block" id="detail_debet_air">- m³</span>
            </div>

            <div class="space-y-3 pt-2">
                <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider">Perbandingan Tahun Sebelumnya</h4>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-2 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 font-semibold" id="detail_prev_year_label">Penggunaan Tahun -:</span>
                        <span class="font-extrabold text-slate-800" id="detail_prev_debet_air">-</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 font-semibold">Selisih Penggunaan:</span>
                        <span class="font-extrabold" id="detail_selisih">-</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 font-semibold">Persentase Perubahan:</span>
                        <span class="font-extrabold" id="detail_persentase">-</span>
                    </div>
                    <div class="flex justify-between items-center pt-1 border-t border-slate-200">
                        <span class="text-slate-500 font-semibold">Status Tren:</span>
                        <span id="detail_status_badge">-</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
            <button type="button" id="btn_edit_from_detail" onclick="" class="px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-[#1E3A8A] font-bold text-xs rounded-xl transition-all">
                Edit Record Ini
            </button>
            <button onclick="closeDetailModal()" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- ── Edit Modal ──────────────────────────────────────────────────────────── --}}
<div id="editModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 w-full max-w-lg overflow-hidden transform transition-all">
        <div class="p-6 bg-slate-900 text-white flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-600 rounded-xl text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-sm">Edit Data Penggunaan Air Bersih</h3>
                    <p class="text-[11px] text-slate-400">Perbarui rekaman debet air</p>
                </div>
            </div>
            <button onclick="closeEditModal()" class="p-1 text-slate-400 hover:text-white rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div class="space-y-1.5">
                <label for="edit_gedung" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Gedung</label>
                <select id="edit_gedung" name="gedung" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A]">
                    @foreach($listGedung as $g)
                        <option value="{{ $g }}">{{ $g }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5 relative">
                    <label for="edit_tahun" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Tahun</label>
                    <div class="relative cursor-pointer" onclick="toggleYearPicker('edit')">
                        <input type="text" id="edit_tahun" name="tahun" required readonly
                               placeholder="-- Pilih Tahun --"
                               class="w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] transition-all cursor-pointer">
                        
                        {{-- Calendar Icon (Left) --}}
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#1E3A8A] pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        
                        {{-- Chevron Arrow Icon (Right) --}}
                        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Popover Kalender Tahun Edit --}}
                    <div id="yearPickerPopover_edit" class="hidden absolute top-full left-0 mt-2 w-full min-w-[240px] bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 p-4 animate-fade-in">
                        <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                            <button type="button" onclick="navigateDecade('edit', -1); event.stopPropagation();" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <span id="decadeTitle_edit" class="text-xs font-black text-[#1E3A8A]">2020 - 2031</span>
                            <button type="button" onclick="navigateDecade('edit', 1); event.stopPropagation();" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                        <div id="yearGrid_edit" class="grid grid-cols-3 gap-2"></div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="edit_bulan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Bulan</label>
                    <select id="edit_bulan" name="bulan" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A]">
                        @foreach($listBulan as $num => $nama)
                            <option value="{{ $num }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="edit_debet_air" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Debet Air (m³)</label>
                <input type="number" step="0.01" min="0.01" id="edit_debet_air" name="debet_air" required
                       placeholder="Contoh: 1250.50"
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A]">
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-[#1E3A8A] hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
const nomorIdMapping = @json($nomorIdMapping);

// ── Calendar Year Picker System ─────────────────────────────────────────────
let currentStartYears = {
    form: 2020,
    edit: 2020,
    matrix: 2020,
    list_filter: 2020
};

function toggleYearPicker(type) {
    const popover = document.getElementById(`yearPickerPopover_${type}`);
    if (!popover) return;
    
    // Close any other open year pickers first
    document.querySelectorAll('[id^="yearPickerPopover_"]').forEach(el => {
        if (el.id !== `yearPickerPopover_${type}`) el.classList.add('hidden');
    });

    const isHidden = popover.classList.contains('hidden');
    if (isHidden) {
        popover.classList.remove('hidden');
        renderYearGrid(type);
    } else {
        popover.classList.add('hidden');
    }
}

function navigateDecade(type, direction) {
    currentStartYears[type] += direction * 12;
    renderYearGrid(type);
}

function renderYearGrid(type) {
    const startYear = currentStartYears[type];
    const endYear = startYear + 11;
    
    const title = document.getElementById(`decadeTitle_${type}`);
    if (title) {
        title.textContent = `${startYear} – ${endYear}`;
    }

    const grid = document.getElementById(`yearGrid_${type}`);
    if (!grid) return;

    const inputVal = parseInt(document.getElementById(`${type}_tahun`).value) || 0;

    let html = '';
    for (let y = startYear; y <= endYear; y++) {
        const isSelected = inputVal === y;
        
        let btnClasses = "w-full py-2 px-3 text-center text-sm font-extrabold rounded-full transition-all cursor-pointer ";
        if (isSelected) {
            btnClasses += "bg-[#1E3A8A] text-white border-2 border-[#1E3A8A] shadow-sm font-black";
        } else {
            btnClasses += "bg-white text-[#1E3A8A] hover:bg-blue-50 border border-[#1E3A8A]/90 font-extrabold";
        }

        html += `<button type="button" onclick="selectYear('${type}', ${y})" class="${btnClasses}">${y}</button>`;
    }
    grid.innerHTML = html;
}

function selectYear(type, year) {
    const input = document.getElementById(`${type}_tahun`);
    if (input) {
        input.value = year;
    }
    document.getElementById(`yearPickerPopover_${type}`)?.classList.add('hidden');

    // Auto submit filter forms on year selection
    if (type === 'matrix') {
        document.getElementById('filterForm_matrix')?.submit();
    } else if (type === 'list_filter') {
        const form = input.closest('form');
        if (form) form.submit();
    }
}

// Close year pickers when clicking outside
document.addEventListener('click', function (e) {
    ['form', 'edit', 'matrix', 'list_filter'].forEach(type => {
        const popover = document.getElementById(`yearPickerPopover_${type}`);
        const input = document.getElementById(`${type}_tahun`);
        if (popover && input && !popover.contains(e.target) && !input.contains(e.target) && !e.target.closest(`button[onclick*="toggleYearPicker('${type}')"]`)) {
            popover.classList.add('hidden');
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Auto fill Nomor ID when Gedung dropdown changes in Main Form
    const formGedung = document.getElementById('form_gedung');
    const formNomorId = document.getElementById('form_nomor_id');
    if (formGedung && formNomorId) {
        formGedung.addEventListener('change', function () {
            formNomorId.value = nomorIdMapping[this.value] || '';
        });
        if (formGedung.value) {
            formNomorId.value = nomorIdMapping[formGedung.value] || '';
        }
    }

    // Auto fill Nomor ID when Gedung dropdown changes in Edit Modal
    const editGedung = document.getElementById('edit_gedung');
    const editNomorId = document.getElementById('edit_nomor_id');
    if (editGedung && editNomorId) {
        editGedung.addEventListener('change', function () {
            editNomorId.value = nomorIdMapping[this.value] || '';
        });
    }

    // ── Chart.js Initialization ─────────────────────────────────────────────
    const ctx = document.getElementById('waterLineChart');
    if (ctx) {
        const labels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const dataSelectedYear = @json(array_values($chartDataSelectedYear));
        const dataPrevYear     = @json(array_values($chartDataPrevYear));

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Penggunaan Tahun {{ $selectedYear }}',
                        data: dataSelectedYear,
                        borderColor: '#1E3A8A',
                        backgroundColor: 'rgba(30, 58, 138, 0.08)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#1E3A8A',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    },
                    {
                        label: 'Penggunaan Tahun {{ $previousYear }}',
                        data: dataPrevYear,
                        borderColor: '#F59E0B',
                        backgroundColor: 'rgba(245, 158, 11, 0.04)',
                        borderWidth: 2.5,
                        borderDash: [5, 5],
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#F59E0B',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 65,
                        top: 15,
                        bottom: 5
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                        padding: 12,
                        cornerRadius: 12,
                        callbacks: {
                            label: function (context) {
                                let value = context.parsed.y;
                                let formatted = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 }).format(value);
                                return ' ' + context.dataset.label + ': ' + formatted + ' m³';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
                            color: '#64748B'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 11 },
                            color: '#94A3B8',
                            callback: function(value) {
                                return new Intl.NumberFormat('id-ID').format(value) + ' m³';
                            }
                        }
                    }
                }
            }
        });
    }
});

// ── Modal & Prefill Functions ───────────────────────────────────────────────
function prefillForm(gedung, tahun, bulan) {
    const formGedung = document.getElementById('form_gedung');
    if (formGedung) {
        formGedung.value = gedung;
        const formNomorId = document.getElementById('form_nomor_id');
        if (formNomorId) {
            formNomorId.value = nomorIdMapping[gedung] || '';
        }
    }
    const formTahun = document.getElementById('form_tahun');
    if (formTahun) formTahun.value = tahun;
    const formBulan = document.getElementById('form_bulan');
    if (formBulan) formBulan.value = bulan;
    
    const formDebetAir = document.getElementById('form_debet_air');
    if (formDebetAir) formDebetAir.focus();
    
    document.getElementById('form_gedung').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function openDetailModal(item) {
    document.getElementById('detail_gedung').textContent = item.gedung;
    document.getElementById('detail_periode').textContent = (item.nama_bulan || 'Bulan ' + item.bulan) + ' ' + item.tahun;
    
    let debetFormat = new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(item.debet_air);
    document.getElementById('detail_debet_air').textContent = debetFormat + ' m³';

    let prevYear = item.tahun - 1;
    document.getElementById('detail_prev_year_label').textContent = 'Penggunaan Tahun ' + prevYear + ':';

    if (item.prev_debet_air !== null && item.prev_debet_air !== undefined) {
        let prevFormat = new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(item.prev_debet_air);
        document.getElementById('detail_prev_debet_air').textContent = prevFormat + ' m³';

        let selisihFormat = new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Math.abs(item.selisih));
        let prefix = item.selisih > 0 ? '+' : (item.selisih < 0 ? '-' : '');
        document.getElementById('detail_selisih').textContent = prefix + selisihFormat + ' m³';

        let persentaseFormat = new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(item.persentase);
        document.getElementById('detail_persentase').textContent = (item.persentase > 0 ? '+' : '') + persentaseFormat + '%';

        let badgeHtml = '';
        if (item.status === 'Naik') {
            badgeHtml = '<span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200">↑ Naik</span>';
        } else if (item.status === 'Turun') {
            badgeHtml = '<span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">↓ Turun</span>';
        } else {
            badgeHtml = '<span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-slate-100 text-slate-700 border border-slate-200">= Tetap</span>';
        }
        document.getElementById('detail_status_badge').innerHTML = badgeHtml;
    } else {
        document.getElementById('detail_prev_debet_air').textContent = '-';
        document.getElementById('detail_selisih').textContent = '-';
        document.getElementById('detail_persentase').textContent = 'Data tidak tersedia';
        document.getElementById('detail_status_badge').innerHTML = '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-400">-</span>';
    }

    const editBtn = document.getElementById('btn_edit_from_detail');
    if (editBtn) {
        editBtn.onclick = function() {
            closeDetailModal();
            openEditModalFromObject(item);
        };
    }

    document.getElementById('detailModal').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

function openEditModalFromObject(item) {
    const form = document.getElementById('editForm');
    form.action = "{{ url('admin/water') }}/" + item.id;

    document.getElementById('edit_gedung').value = item.gedung;
    const editNomorId = document.getElementById('edit_nomor_id');
    if (editNomorId) {
        editNomorId.value = item.nomor_id || nomorIdMapping[item.gedung] || '';
    }
    document.getElementById('edit_tahun').value = item.tahun;
    document.getElementById('edit_bulan').value = item.bulan;
    document.getElementById('edit_debet_air').value = item.debet_air;

    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection
