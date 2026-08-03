@extends('layouts.admin')

@section('title', 'Daftar Peminat Office BOP – Admin Portal')
@section('breadcrumb', 'Daftar Peminat Office BOP')

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
    @php
        if (!function_exists('formatFollowUpDate')) {
            function formatFollowUpDate($val)
            {
                if (empty($val) || $val === '-')
                    return '-';
                try {
                    return \Carbon\Carbon::parse($val)->translatedFormat('d F Y');
                } catch (\Exception $e) {
                    return $val;
                }
            }
        }

        if (!function_exists('formatRawDateInput')) {
            function formatRawDateInput($val)
            {
                if (empty($val) || $val === '-')
                    return '';
                try {
                    return \Carbon\Carbon::parse($val)->format('Y-m-d');
                } catch (\Exception $e) {
                    return $val;
                }
            }
        }

        if (!function_exists('formatPhoneFax')) {
            function formatPhoneFax($val)
            {
                if (empty($val) || trim($val) === '-')
                    return '-';

                $parts = explode('/', $val);
                $formattedParts = [];

                foreach ($parts as $part) {
                    $trimmed = trim($part);

                    if (str_contains($trimmed, '-')) {
                        $formattedParts[] = $trimmed;
                        continue;
                    }

                    $digitsOnly = preg_replace('/[^\d]/', '', $trimmed);

                    if (strlen($digitsOnly) >= 8 && strlen($digitsOnly) <= 13) {
                        if (strlen($digitsOnly) <= 10) {
                            $formatted = preg_replace('/^(\d{4})(\d{4})(\d{1,4})$/', '$1-$2-$3', $digitsOnly);
                        } else {
                            $formatted = preg_replace('/^(\d{4})(\d{4})(\d{1,5})$/', '$1-$2-$3', $digitsOnly);
                        }
                        $formattedParts[] = $formatted;
                    } else {
                        $formattedParts[] = $trimmed;
                    }
                }

                return implode(' / ', $formattedParts);
            }
        }
    @endphp
    <div class="space-y-8 animate-fade-in pb-16">

        {{-- ── Page Header ────────────────────────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                    <div class="p-2.5 bg-[#1E3A8A] text-white rounded-2xl shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    Daftar Peminat Office BOP
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    Pencatatan, pemantauan status follow-up, dan rekapitulasi matriks peminat sewa ruang kantor BOP per
                    bulan
                </p>
            </div>

            @if($totalPeminat > 0)
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.bop-leads.destroyAll') }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data peminat sesuai filter aktif? Tindakan ini tidak dapat dibatalkan.');">
                        @csrf
                        @method('DELETE')
                        @if($selectedYear) <input type="hidden" name="tahun" value="{{ $selectedYear }}"> @endif
                        @if($selectedMonth) <input type="hidden" name="bulan" value="{{ $selectedMonth }}"> @endif
                        <button type="submit"
                            class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl font-bold text-xs transition-all flex items-center gap-2 cursor-pointer shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Data Terfilter
                        </button>
                    </form>
                </div>
            @endif
        </div>


        {{-- ── 1. Summary Dashboard Cards (Top) ────────────────────────────────── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
            @php
                $periodText = $selectedMonth ? 'Bulan ' . $listBulan[$selectedMonth] . ' ' . $selectedYear : 'Tahun ' . $selectedYear;
            @endphp

            {{-- Card 1: Total Peminat (Biru - #2563EB) --}}
            <div
                class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span style="color: #2563eb;" class="text-xs font-extrabold uppercase tracking-wider block">Total
                            Peminat</span>
                        <span class="text-[11px] font-semibold text-slate-400 mt-0.5 block">{{ $periodText }}</span>
                    </div>
                    <div style="background-color: rgba(37, 99, 235, 0.1); color: #2563eb;"
                        class="w-12 h-12 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <iconify-icon icon="lucide:building-2" class="text-2xl"></iconify-icon>
                    </div>
                </div>

                <div class="mt-4">
                    <span style="color: #2563eb;"
                        class="text-4xl sm:text-5xl font-black tracking-tight block">{{ number_format($totalPeminat) }}</span>

                    @if($totalPeminat === 0)
                        <p style="color: #2563eb;" class="text-xs font-medium italic mt-2 flex items-center gap-1.5 opacity-80">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Belum ada data pada periode ini
                        </p>
                    @endif
                </div>
            </div>

            {{-- Card 2: Dikirim Nomlet (Ungu - #7C3AED) --}}
            <div
                class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span style="color: #7c3aed;" class="text-xs font-extrabold uppercase tracking-wider block">Dikirim
                            Nomlet</span>
                        <span class="text-[11px] font-semibold text-slate-400 mt-0.5 block">{{ $periodText }}</span>
                    </div>
                    <div style="background-color: rgba(124, 58, 237, 0.1); color: #7c3aed;"
                        class="w-12 h-12 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <iconify-icon icon="lucide:send" class="text-2xl"></iconify-icon>
                    </div>
                </div>

                <div class="mt-4">
                    <span style="color: #7c3aed;"
                        class="text-4xl sm:text-5xl font-black tracking-tight block">{{ number_format($totalNomletDikirim) }}</span>

                    @if($totalNomletDikirim === 0)
                        <p style="color: #7c3aed;" class="text-xs font-medium italic mt-2 flex items-center gap-1.5 opacity-80">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Belum ada data pada periode ini
                        </p>
                    @endif
                </div>
            </div>

            {{-- Card 3: Nomlet Disetujui (Hijau - #16A34A) --}}
            <div
                class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span style="color: #16a34a;" class="text-xs font-extrabold uppercase tracking-wider block">Nomlet
                            Disetujui</span>
                        <span class="text-[11px] font-semibold text-slate-400 mt-0.5 block">{{ $periodText }}</span>
                    </div>
                    <div style="background-color: rgba(22, 163, 74, 0.1); color: #16a34a;"
                        class="w-12 h-12 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <iconify-icon icon="heroicons:check-badge" class="text-2xl"></iconify-icon>
                    </div>
                </div>

                <div class="mt-4">
                    <span style="color: #16a34a;"
                        class="text-4xl sm:text-5xl font-black tracking-tight block">{{ number_format($totalNomletDisetujui) }}</span>

                    @if($totalNomletDisetujui === 0)
                        <p style="color: #16a34a;" class="text-xs font-medium italic mt-2 flex items-center gap-1.5 opacity-80">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Belum ada data pada periode ini
                        </p>
                    @endif
                </div>
            </div>

            {{-- Card 4: Kirim LOO (Oranye - #F97316) --}}
            <div
                class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span style="color: #f97316;" class="text-xs font-extrabold uppercase tracking-wider block">Kirim
                            LOO</span>
                        <span class="text-[11px] font-semibold text-slate-400 mt-0.5 block">{{ $periodText }}</span>
                    </div>
                    <div style="background-color: rgba(249, 115, 22, 0.1); color: #f97316;"
                        class="w-12 h-12 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <iconify-icon icon="heroicons:document-text" class="text-2xl"></iconify-icon>
                    </div>
                </div>

                <div class="mt-4">
                    <span style="color: #f97316;"
                        class="text-4xl sm:text-5xl font-black tracking-tight block">{{ number_format($totalLoo) }}</span>

                    @if($totalLoo === 0)
                        <p style="color: #f97316;" class="text-xs font-medium italic mt-2 flex items-center gap-1.5 opacity-80">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Belum ada data pada periode ini
                        </p>
                    @endif
                </div>
            </div>

            {{-- Card 5: Sudah DP (Emerald - #059669) --}}
            <div
                class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span style="color: #059669;" class="text-xs font-extrabold uppercase tracking-wider block">Sudah
                            DP</span>
                        <span class="text-[11px] font-semibold text-slate-400 mt-0.5 block">{{ $periodText }}</span>
                    </div>
                    <div style="background-color: rgba(5, 150, 105, 0.1); color: #059669;"
                        class="w-12 h-12 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <iconify-icon icon="heroicons:banknotes" class="text-2xl"></iconify-icon>
                    </div>
                </div>

                <div class="mt-4">
                    <span style="color: #059669;"
                        class="text-4xl sm:text-5xl font-black tracking-tight block">{{ number_format($totalDp) }}</span>

                    @if($totalDp === 0)
                        <p style="color: #059669;" class="text-xs font-medium italic mt-2 flex items-center gap-1.5 opacity-80">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Belum ada data pada periode ini
                        </p>
                    @endif
                </div>
            </div>

            {{-- Card 6: Serah Terima (Indigo - #4F46E5) --}}
            <div
                class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span style="color: #4f46e5;" class="text-xs font-extrabold uppercase tracking-wider block">Serah
                            Terima</span>
                        <span class="text-[11px] font-semibold text-slate-400 mt-0.5 block">{{ $periodText }}</span>
                    </div>
                    <div style="background-color: rgba(79, 70, 229, 0.1); color: #4f46e5;"
                        class="w-12 h-12 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <iconify-icon icon="heroicons:key" class="text-2xl"></iconify-icon>
                    </div>
                </div>

                <div class="mt-4">
                    <span style="color: #4f46e5;"
                        class="text-4xl sm:text-5xl font-black tracking-tight block">{{ number_format($totalSerah) }}</span>

                    @if($totalSerah === 0)
                        <p style="color: #4f46e5;" class="text-xs font-medium italic mt-2 flex items-center gap-1.5 opacity-80">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Belum ada data pada periode ini
                        </p>
                    @endif
                </div>
            </div>

            {{-- Card 7: Fitting Out (Teal - #0D9488) --}}
            <div
                class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span style="color: #0d9488;" class="text-xs font-extrabold uppercase tracking-wider block">Fitting
                            Out</span>
                        <span class="text-[11px] font-semibold text-slate-400 mt-0.5 block">{{ $periodText }}</span>
                    </div>
                    <div style="background-color: rgba(13, 148, 136, 0.1); color: #0d9488;"
                        class="w-12 h-12 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <iconify-icon icon="lucide:hammer" class="text-2xl"></iconify-icon>
                    </div>
                </div>

                <div class="mt-4">
                    <span style="color: #0d9488;"
                        class="text-4xl sm:text-5xl font-black tracking-tight block">{{ number_format($totalFitting) }}</span>

                    @if($totalFitting === 0)
                        <p style="color: #0d9488;" class="text-xs font-medium italic mt-2 flex items-center gap-1.5 opacity-80">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Belum ada data pada periode ini
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── 2. Form Input Peminat Office BOP ───────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Form Input Daftar Peminat Office BOP</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Masukkan data calon penyewa ruang kantor BOP dan status
                        follow-up awal</p>
                </div>
                <span
                    class="px-3 py-1 bg-blue-50 text-[#1E3A8A] text-[11px] font-bold rounded-lg border border-blue-200/60">
                    Pencatatan Baru
                </span>
            </div>

            <form action="{{ route('admin.bop-leads.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Tanggal (Type Date Input) --}}
                    <div class="space-y-1.5">
                        <label for="form_tanggal_entry"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Tanggal <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="form_tanggal_entry" name="tanggal_entry" required
                            value="{{ old('tanggal_entry') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] transition-all">
                    </div>

                    {{-- Nama Peminat --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Peminat
                            <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" required value="{{ old('nama') }}"
                            placeholder="Contoh: Ririk Suriyana"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 transition-all outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Alamat Email --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Email
                            <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required value="{{ old('email') }}"
                            placeholder="Contoh: ririksuriyana@gmail.com"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 transition-all outline-none">
                    </div>

                    {{-- Nama Perusahaan --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Perusahaan
                            (PT/CV) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_perusahaan" required value="{{ old('nama_perusahaan') }}"
                            placeholder="Contoh: PT Surya Nusantara"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 transition-all outline-none">
                    </div>

                    {{-- Telpon & FAX --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Telpon & FAX
                            <span class="text-rose-500">*</span></label>
                        <input type="text" name="telpon_fax" required value="{{ old('telpon_fax') }}"
                            placeholder="Contoh: 0812-3456-7890 / (021) 555-1234"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 transition-all outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Alamat --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat
                            Perusahaan / Pemohon <span class="text-rose-500">*</span></label>
                        <textarea name="alamat" required rows="2"
                            placeholder="Alamat lengkap lokasi atau domisili perusahaan..."
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 transition-all outline-none">{{ old('alamat') }}</textarea>
                    </div>

                    {{-- Kategori yang Diminati --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Yg
                            Diminati (Detail Space) <span class="text-rose-500">*</span></label>
                        <textarea name="kategori_diminati" required rows="2"
                            placeholder="Contoh: Penawaran Space: Building A; 2nd floor 130 sqm, 3rd floor 90 sqm, Building C..."
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 transition-all outline-none">{{ old('kategori_diminati') }}</textarea>
                    </div>
                </div>

                {{-- Optional Follow-Up Early Inputs --}}
                <div class="pt-3 border-t border-slate-100">
                    <p class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span> Status / Tanggal Follow Up (Opsional)
                    </p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1">Kit Marketing</label>
                            <input type="date" name="kit_marketing"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 focus:bg-white focus:border-[#1E3A8A] outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1">Nomlet Dikirim</label>
                            <input type="date" name="nomlet_dikirim"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 focus:bg-white focus:border-[#1E3A8A] outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1">Nomlet Disetujui</label>
                            <input type="date" name="nomlet_disetujui"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 focus:bg-white focus:border-[#1E3A8A] outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1">LOO (Tanggal)</label>
                            <input type="date" name="loo"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 focus:bg-white focus:border-[#1E3A8A] outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1">Nomor Surat LOO</label>
                            <input type="text" name="nomor_surat_loo" value="{{ old('nomor_surat_loo') }}"
                                placeholder="No. Surat LOO..."
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:bg-white focus:border-[#1E3A8A] outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1">DP (Down Payment)</label>
                            <input type="date" name="dp"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 focus:bg-white focus:border-[#1E3A8A] outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1">Serah Terima</label>
                            <input type="date" name="serah_terima"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 focus:bg-white focus:border-[#1E3A8A] outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1">Fitting Out</label>
                            <input type="date" name="fitting_out"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 focus:bg-white focus:border-[#1E3A8A] outline-none">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                        class="px-6 py-3 bg-[#1E3A8A] hover:bg-[#152a65] text-white font-bold text-xs rounded-xl shadow-md hover:shadow-lg transition-all cursor-pointer flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Simpan Data Peminat
                    </button>
                </div>
            </form>
        </div>

        {{-- ── 3. Main Rekapitulasi Table Card (Identik Gambar 2) ──────────────── --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)] relative z-20">

            {{-- Table Header Controls & View Toggle (Directly matching Gambar 2) --}}
            <div class="p-6 border-b border-slate-100 space-y-4 relative z-20">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Rekapitulasi Daftar Peminat Office BOP</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Tabel rekapitulasi peminat per bulan untuk Tahun
                            {{ $selectedYear }}</p>
                    </div>

                    {{-- Mode Switcher & Controls (Pill container top right) --}}
                    <div class="flex items-center gap-3">
                        <div class="bg-slate-100 p-1 rounded-2xl flex items-center gap-1 border border-slate-200/60">
                            <a href="{{ route('admin.bop-leads.index', array_merge(request()->query(), ['view' => 'matrix'])) }}"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $viewMode === 'matrix' ? 'bg-[#1E3A8A] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                Tabel Matriks (Jan-Des)
                            </a>
                            <a href="{{ route('admin.bop-leads.index', array_merge(request()->query(), ['view' => 'list'])) }}"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $viewMode === 'list' ? 'bg-[#1E3A8A] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                                Tabel Log Rinci
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Filter Form Row (Filter Tahun Widget + Search + Month Tabs) --}}
                <form action="{{ route('admin.bop-leads.index') }}" method="GET"
                    class="flex flex-wrap items-center justify-between gap-4 pt-1 w-full">
                    <input type="hidden" name="view" value="{{ $viewMode }}">

                    <div class="flex flex-wrap items-center gap-3 w-full">
                        @if($viewMode === 'list')
                            {{-- ── Log Rinci View Filter Row (Search + Year + Month Dropdown) ── --}}
                            {{-- 1. Search Box --}}
                            <div class="relative min-w-[300px] sm:min-w-[450px] lg:min-w-[550px] flex-1">
                                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, PT..."
                                    class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] transition-all">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            {{-- 2. Year Picker Widget --}}
                            <div class="relative cursor-pointer min-w-[150px]" onclick="toggleYearPicker('matrix')">
                                <input type="text" id="matrix_tahun" name="tahun" readonly value="{{ $selectedYear }}"
                                    placeholder="-- Pilih Tahun --"
                                    class="w-full pl-10 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] transition-all cursor-pointer text-left">

                                {{-- Calendar Icon (Left) --}}
                                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#1E3A8A] pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                {{-- Chevron Arrow (Right) --}}
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                {{-- Popover Kalender Tahun --}}
                                <div id="yearPickerPopover_matrix"
                                    class="hidden absolute top-full left-0 mt-2 w-full min-w-[220px] max-w-[250px] bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 p-4 animate-fade-in">
                                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                                        <button type="button" onclick="navigateDecade('matrix', -1); event.stopPropagation();"
                                            class="p-1 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                                            <svg class="w-4 h-4 text-[#1E3A8A]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <span id="decadeTitle_matrix" class="text-xs font-black text-[#1E3A8A]">2004 –
                                            2015</span>
                                        <button type="button" onclick="navigateDecade('matrix', 1); event.stopPropagation();"
                                            class="p-1 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                                            <svg class="w-4 h-4 text-[#1E3A8A]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div id="yearGrid_matrix"
                                        class="flex flex-col gap-2.5 max-h-[300px] overflow-y-auto pr-1.5 gold-scrollbar"></div>
                                </div>
                            </div>

                            {{-- 3. Month Picker Dropdown Widget --}}
                            <div class="relative cursor-pointer min-w-[170px]" onclick="toggleMonthPicker('matrix')">
                                <input type="text" id="matrix_bulan_display" readonly
                                    value="{{ $selectedMonth ? ($listBulan[$selectedMonth] ?? '') : 'Semua Bulan' }}"
                                    placeholder="Semua Bulan"
                                    class="w-full pl-10 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] transition-all cursor-pointer text-left">
                                <input type="hidden" id="matrix_bulan" name="bulan" value="{{ $selectedMonth }}">

                                {{-- Calendar Icon (Left) --}}
                                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#1E3A8A] pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                {{-- Chevron Arrow (Right) --}}
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                {{-- Popover Kalender Bulan --}}
                                <div id="monthPickerPopover_matrix"
                                    class="hidden absolute top-full left-0 mt-2 w-full min-w-[220px] max-w-[250px] bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 p-4 animate-fade-in">
                                    <div class="pb-3 mb-3 border-b border-slate-100 text-center">
                                        <span class="text-xs font-black text-[#1E3A8A]">Pilih Bulan</span>
                                    </div>
                                    <div id="monthGrid_matrix"
                                        class="flex flex-col gap-2.5 max-h-[300px] overflow-y-auto pr-1.5 gold-scrollbar">
                                        <button type="button"
                                            onclick="selectMonth('matrix', '', 'Semua Bulan'); event.stopPropagation();"
                                            class="month-btn-matrix-0 w-full py-2 px-3 text-center text-sm font-extrabold rounded-full transition-all cursor-pointer {{ !$selectedMonth ? 'bg-[#1E3A8A] text-white border-2 border-[#1E3A8A] shadow-sm font-black' : 'bg-white text-[#1E3A8A] hover:bg-blue-50 border border-[#1E3A8A]/90' }}">
                                            Semua Bulan
                                        </button>
                                        @foreach($listBulan as $num => $nama)
                                            <button type="button"
                                                onclick="selectMonth('matrix', {{ $num }}, '{{ $nama }}'); event.stopPropagation();"
                                                class="month-btn-matrix-{{ $num }} w-full py-2 px-3 text-center text-sm font-extrabold rounded-full transition-all cursor-pointer {{ $selectedMonth == $num ? 'bg-[#1E3A8A] text-white border-2 border-[#1E3A8A] shadow-sm font-black' : 'bg-white text-[#1E3A8A] hover:bg-blue-50 border border-[#1E3A8A]/90' }}">
                                                {{ $nama }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- 4. Terapkan Filter Button --}}
                            <button type="submit"
                                class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl shadow-xs transition-all cursor-pointer">
                                Terapkan
                            </button>
                        @else
                            {{-- ── Matrix View Filter Row (Search + Year + Month Dropdown) ── --}}
                            {{-- 1. Search Box --}}
                            <div class="relative min-w-[300px] sm:min-w-[450px] lg:min-w-[550px] flex-1">
                                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, PT..."
                                    class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] transition-all">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            {{-- 2. Filter Tahun (Calendar Popover Widget) --}}
                            <div class="relative cursor-pointer min-w-[150px]" onclick="toggleYearPicker('matrix')">
                                <input type="text" id="matrix_tahun" name="tahun" readonly value="{{ $selectedYear }}"
                                    placeholder="-- Pilih Tahun --"
                                    class="w-full pl-10 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] transition-all cursor-pointer text-left">

                                {{-- Calendar Icon (Left) --}}
                                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#1E3A8A] pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                {{-- Chevron Arrow (Right) --}}
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                {{-- Popover Kalender Tahun --}}
                                <div id="yearPickerPopover_matrix"
                                    class="hidden absolute top-full left-0 mt-2 w-full min-w-[220px] max-w-[250px] bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 p-4 animate-fade-in">
                                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                                        <button type="button" onclick="navigateDecade('matrix', -1); event.stopPropagation();"
                                            class="p-1 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                                            <svg class="w-4 h-4 text-[#1E3A8A]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <span id="decadeTitle_matrix" class="text-xs font-black text-[#1E3A8A]">2004 –
                                            2015</span>
                                        <button type="button" onclick="navigateDecade('matrix', 1); event.stopPropagation();"
                                            class="p-1 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                                            <svg class="w-4 h-4 text-[#1E3A8A]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div id="yearGrid_matrix"
                                        class="flex flex-col gap-2.5 max-h-[300px] overflow-y-auto pr-1.5 gold-scrollbar"></div>
                                </div>
                            </div>

                            {{-- 3. Filter Bulan (Calendar Popover Widget) --}}
                            <div class="relative cursor-pointer min-w-[170px]" onclick="toggleMonthPicker('matrix')">
                                <input type="text" id="matrix_bulan_display" readonly
                                    value="{{ $selectedMonth ? ($listBulan[$selectedMonth] ?? '') : 'Semua Bulan' }}"
                                    placeholder="Semua Bulan"
                                    class="w-full pl-10 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] transition-all cursor-pointer text-left">
                                <input type="hidden" id="matrix_bulan" name="bulan" value="{{ $selectedMonth }}">

                                {{-- Calendar Icon (Left) --}}
                                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#1E3A8A] pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                {{-- Chevron Arrow (Right) --}}
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                {{-- Popover Kalender Bulan --}}
                                <div id="monthPickerPopover_matrix"
                                    class="hidden absolute top-full left-0 mt-2 w-full min-w-[220px] max-w-[250px] bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 p-4 animate-fade-in">
                                    <div class="pb-3 mb-3 border-b border-slate-100 text-center">
                                        <span class="text-xs font-black text-[#1E3A8A]">Pilih Bulan</span>
                                    </div>
                                    <div id="monthGrid_matrix"
                                        class="flex flex-col gap-2.5 max-h-[300px] overflow-y-auto pr-1.5 gold-scrollbar">
                                        <button type="button"
                                            onclick="selectMonth('matrix', '', 'Semua Bulan'); event.stopPropagation();"
                                            class="month-btn-matrix-0 w-full py-2 px-3 text-center text-sm font-extrabold rounded-full transition-all cursor-pointer {{ !$selectedMonth ? 'bg-[#1E3A8A] text-white border-2 border-[#1E3A8A] shadow-sm font-black' : 'bg-white text-[#1E3A8A] hover:bg-blue-50 border border-[#1E3A8A]/90' }}">
                                            Semua Bulan
                                        </button>
                                        @foreach($listBulan as $num => $nama)
                                            <button type="button"
                                                onclick="selectMonth('matrix', {{ $num }}, '{{ $nama }}'); event.stopPropagation();"
                                                class="month-btn-matrix-{{ $num }} w-full py-2 px-3 text-center text-sm font-extrabold rounded-full transition-all cursor-pointer {{ $selectedMonth == $num ? 'bg-[#1E3A8A] text-white border-2 border-[#1E3A8A] shadow-sm font-black' : 'bg-white text-[#1E3A8A] hover:bg-blue-50 border border-[#1E3A8A]/90' }}">
                                                {{ $nama }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- 4. Terapkan Filter Button --}}
                            <button type="submit"
                                class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl shadow-xs transition-all cursor-pointer">
                                Terapkan
                            </button>
                        @endif

                        @if($search || $selectedMonth)
                            <a href="{{ route('admin.bop-leads.index', ['tahun' => $selectedYear, 'view' => $viewMode]) }}"
                                class="text-xs font-semibold text-rose-600 hover:underline flex items-center gap-1">
                                Reset Filter
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if($viewMode === 'matrix')

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse border border-slate-300">
                            <thead>
                                {{-- Top Header Row (Deep Navy Blue #1E3A8A as in Air Bersih matrix table) --}}
                                <tr style="background-color: #1E3A8A !important; color: #ffffff !important;"
                                    class="font-extrabold uppercase text-[11px] tracking-wider text-center">
                                    <th rowspan="2" class="p-2.5 border border-blue-900 w-12"
                                        style="background-color: #1E3A8A !important; color: #ffffff !important;">NO.</th>
                                    <th rowspan="2" class="p-2.5 border border-blue-900 min-w-[200px]"
                                        style="background-color: #1E3A8A !important; color: #ffffff !important;">NAMA & ALAMAT EMAIL
                                    </th>
                                    <th colspan="3" class="p-2.5 border border-blue-900"
                                        style="background-color: #1E3A8A !important; color: #ffffff !important;">PT / PERUSAHAAN
                                    </th>
                                    <th rowspan="2" class="p-2.5 border border-blue-900 min-w-[220px]"
                                        style="background-color: #1E3A8A !important; color: #ffffff !important;">KATEGORI YG
                                        DIMINATI</th>
                                    <th colspan="7" class="p-2.5 border border-blue-900"
                                        style="background-color: #1E3A8A !important; color: #ffffff !important;">FOLLOW UP</th>
                                    <th rowspan="2" class="p-2.5 border border-blue-900 w-24"
                                        style="background-color: #1E3A8A !important; color: #ffffff !important;">AKSI</th>
                                </tr>
                                {{-- Sub Header Row --}}
                                <tr style="background-color: #1E3A8A !important; color: #ffffff !important;"
                                    class="font-extrabold uppercase text-[10px] tracking-wider text-center">
                                    <th class="p-2 border border-blue-900 min-w-[140px]"
                                        style="background-color: #1E3A8A !important; color: #ffffff !important;">NAMA PERUSAHAAN
                                    </th>
                                    <th class="p-2 border border-blue-900 min-w-[160px]"
                                        style="background-color: #1E3A8A !important; color: #ffffff !important;">ALAMAT</th>
                                    <th class="p-2 border border-blue-900 min-w-[120px]"
                                        style="background-color: #1E3A8A !important; color: #ffffff !important;">TELPON & FAX</th>
                                    <th class="p-2 border border-blue-900 min-w-[110px]"
                                        style="background-color: #1E3A8A !important; color: #ffffff !important;">KIT MARKETING</th>
                                    <th class="p-2 border border-blue-900 min-w-[110px]"
                                        style="background-color: #1E3A8A !important; color: #ffffff !important;">NOMLET DIKIRIM</th>
                                    <th class="p-2 border border-blue-900 min-w-[110px]"
                                        style="background-color: #1E3A8A !important; color: #ffffff !important;">NOMLET DISETUJUI
                                    </th>
                                    <th class="p-2 border border-blue-900 min-w-[110px]"
                                        style="background-color: #1E3A8A !important; color: #ffffff !important;">LOO</th>
                                    <th class="p-2 border border-blue-900 min-w-[110px]"
                                        style="background-color: #1E3A8A !important; color: #ffffff !important;">DP</th>
                                    <th class="p-2 border border-blue-900 min-w-[110px]"
                                        style="background-color: #1E3A8A !important; color: #ffffff !important;">SERAH TERIMA</th>
                                    <th class="p-2 border border-blue-900 min-w-[110px]"
                                        style="background-color: #1E3A8A !important; color: #ffffff !important;">FITTING OUT</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $allRecordsList = [];
                                    foreach ($matrixData as $mNum => $mData) {
                                        foreach ($mData['records'] as $lead) {
                                            $allRecordsList[] = $lead;
                                        }
                                    }
                                @endphp

                                @forelse($allRecordsList as $idx => $lead)
                                    <tr class="hover:bg-amber-50/40 transition-all border-b border-slate-200 text-slate-800">
                                        {{-- NO --}}
                                        <td class="p-2.5 border border-slate-300 text-center font-bold bg-slate-50/50">
                                            {{ $idx + 1 }}
                                        </td>

                                        {{-- NAMA & ALAMAT EMAIL --}}
                                        <td class="p-2.5 border border-slate-300 align-top">
                                            <div class="font-bold text-slate-900 text-xs">{{ $lead->nama }}</div>
                                            @if($lead->email)
                                                <div class="text-[11px] text-slate-600 underline mt-0.5 break-all">
                                                    Email: <a href="mailto:{{ $lead->email }}"
                                                        class="hover:text-blue-700">'{{ $lead->email }}'</a>
                                                </div>
                                            @else
                                                <div class="text-[10px] text-slate-400 italic mt-0.5">Email: -</div>
                                            @endif
                                        </td>

                                        {{-- NAMA PERUSAHAAN --}}
                                        <td class="p-2.5 border border-slate-300 align-top font-semibold text-slate-800">
                                            {{ $lead->nama_perusahaan ?: '-' }}
                                        </td>

                                        {{-- ALAMAT --}}
                                        <td class="p-2.5 border border-slate-300 align-top text-slate-700 leading-relaxed text-[11px]">
                                            {{ $lead->alamat ?: '-' }}
                                        </td>

                                        {{-- TELPON & FAX --}}
                                        <td
                                            class="p-2.5 border border-slate-300 align-top text-slate-700 whitespace-pre-line text-[11px]">
                                            {{ formatPhoneFax($lead->telpon_fax) }}
                                        </td>

                                        {{-- KATEGORI YG DIMINATI --}}
                                        <td class="p-2.5 border border-slate-300 align-top text-slate-800 text-[11px] leading-relaxed">
                                            {{ $lead->kategori_diminati ?: '-' }}
                                        </td>

                                        {{-- FOLLOW UP: KIT MARKETING --}}
                                        <td class="p-2 border border-slate-300 align-top text-center bg-blue-50/10">
                                            <div class="group relative">
                                                <button type="button"
                                                    onclick="quickEditFollowUp({{ $lead->id }}, 'kit_marketing', '{{ addslashes(formatRawDateInput($lead->kit_marketing)) }}', 'Kit Marketing')"
                                                    class="w-full py-1 px-1.5 rounded hover:bg-blue-100/60 font-semibold text-[11px] text-slate-800 flex items-center justify-between gap-1 border border-transparent hover:border-blue-300 transition-all cursor-pointer">
                                                    <span class="truncate {{ $lead->kit_marketing ? 'font-extrabold' : '' }}"
                                                        style="{{ $lead->kit_marketing ? 'color: #2563eb;' : '' }}">{{ formatFollowUpDate($lead->kit_marketing) }}</span>
                                                    <svg class="w-3 h-3 text-slate-400 opacity-0 group-hover:opacity-100 flex-shrink-0"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>

                                        {{-- FOLLOW UP: NOMLET DIKIRIM --}}
                                        <td class="p-2 border border-slate-300 align-top text-center bg-purple-50/10">
                                            <div class="group relative">
                                                <button type="button"
                                                    onclick="quickEditFollowUp({{ $lead->id }}, 'nomlet_dikirim', '{{ addslashes(formatRawDateInput($lead->nomlet_dikirim)) }}', 'Nomlet Dikirim')"
                                                    class="w-full py-1 px-1.5 rounded hover:bg-purple-100/60 font-semibold text-[11px] text-slate-800 flex items-center justify-between gap-1 border border-transparent hover:border-purple-300 transition-all cursor-pointer">
                                                    <span class="truncate {{ $lead->nomlet_dikirim ? 'font-extrabold' : '' }}"
                                                        style="{{ $lead->nomlet_dikirim ? 'color: #7c3aed;' : '' }}">{{ formatFollowUpDate($lead->nomlet_dikirim) }}</span>
                                                    <svg class="w-3 h-3 text-slate-400 opacity-0 group-hover:opacity-100 flex-shrink-0"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>

                                        {{-- FOLLOW UP: NOMLET DISETUJUI --}}
                                        <td class="p-2 border border-slate-300 align-top text-center bg-green-50/10">
                                            <div class="group relative">
                                                <button type="button"
                                                    onclick="quickEditFollowUp({{ $lead->id }}, 'nomlet_disetujui', '{{ addslashes(formatRawDateInput($lead->nomlet_disetujui)) }}', 'Nomlet Disetujui')"
                                                    class="w-full py-1 px-1.5 rounded hover:bg-green-100/60 font-semibold text-[11px] text-slate-800 flex items-center justify-between gap-1 border border-transparent hover:border-green-300 transition-all cursor-pointer">
                                                    <span class="truncate {{ $lead->nomlet_disetujui ? 'font-extrabold' : '' }}"
                                                        style="{{ $lead->nomlet_disetujui ? 'color: #16a34a;' : '' }}">{{ formatFollowUpDate($lead->nomlet_disetujui) }}</span>
                                                    <svg class="w-3 h-3 text-slate-400 opacity-0 group-hover:opacity-100 flex-shrink-0"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>

                                        {{-- FOLLOW UP: LOO --}}
                                        <td class="p-2 border border-slate-300 align-top text-center bg-orange-50/10">
                                            <div class="group relative">
                                                <button type="button"
                                                    onclick="quickEditFollowUp({{ $lead->id }}, 'loo', '{{ addslashes(formatRawDateInput($lead->loo)) }}', 'Letter of Offer (LOO)', '{{ addslashes($lead->nomor_surat_loo ?? '') }}')"
                                                    class="w-full py-1 px-1.5 rounded hover:bg-orange-100/60 font-semibold text-[11px] text-slate-800 flex items-center justify-between gap-1 border border-transparent hover:border-orange-300 transition-all cursor-pointer">
                                                    <div class="flex flex-col text-left truncate">
                                                        <span class="truncate {{ $lead->loo ? 'font-extrabold' : '' }}"
                                                            style="{{ $lead->loo ? 'color: #f97316;' : '' }}">{{ formatFollowUpDate($lead->loo) }}</span>
                                                        @if($lead->nomor_surat_loo)
                                                            <span class="text-[10px] font-bold truncate" style="color: #f97316;"
                                                                title="No. Surat LOO: {{ $lead->nomor_surat_loo }}">No:
                                                                {{ $lead->nomor_surat_loo }}</span>
                                                        @endif
                                                    </div>
                                                    <svg class="w-3 h-3 text-slate-400 opacity-0 group-hover:opacity-100 flex-shrink-0"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>

                                        {{-- FOLLOW UP: DP --}}
                                        <td class="p-2 border border-slate-300 align-top text-center bg-emerald-50/10">
                                            <div class="group relative">
                                                <button type="button"
                                                    onclick="quickEditFollowUp({{ $lead->id }}, 'dp', '{{ addslashes(formatRawDateInput($lead->dp)) }}', 'Down Payment (DP)')"
                                                    class="w-full py-1 px-1.5 rounded hover:bg-emerald-100/60 font-semibold text-[11px] text-slate-800 flex items-center justify-between gap-1 border border-transparent hover:border-emerald-300 transition-all cursor-pointer">
                                                    <span class="truncate {{ $lead->dp ? 'font-extrabold' : '' }}"
                                                        style="{{ $lead->dp ? 'color: #059669;' : '' }}">{{ formatFollowUpDate($lead->dp) }}</span>
                                                    <svg class="w-3 h-3 text-slate-400 opacity-0 group-hover:opacity-100 flex-shrink-0"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>

                                        {{-- FOLLOW UP: SERAH TERIMA --}}
                                        <td class="p-2 border border-slate-300 align-top text-center bg-indigo-50/10">
                                            <div class="group relative">
                                                <button type="button"
                                                    onclick="quickEditFollowUp({{ $lead->id }}, 'serah_terima', '{{ addslashes(formatRawDateInput($lead->serah_terima)) }}', 'Serah Terima')"
                                                    class="w-full py-1 px-1.5 rounded hover:bg-indigo-100/60 font-semibold text-[11px] text-slate-800 flex items-center justify-between gap-1 border border-transparent hover:border-indigo-300 transition-all cursor-pointer">
                                                    <span class="truncate {{ $lead->serah_terima ? 'font-extrabold' : '' }}"
                                                        style="{{ $lead->serah_terima ? 'color: #4f46e5;' : '' }}">{{ formatFollowUpDate($lead->serah_terima) }}</span>
                                                    <svg class="w-3 h-3 text-slate-400 opacity-0 group-hover:opacity-100 flex-shrink-0"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>

                                        {{-- FOLLOW UP: FITTING OUT --}}
                                        <td class="p-2 border border-slate-300 align-top text-center bg-teal-50/10">
                                            <div class="group relative">
                                                <button type="button"
                                                    onclick="quickEditFollowUp({{ $lead->id }}, 'fitting_out', '{{ addslashes(formatRawDateInput($lead->fitting_out)) }}', 'Fitting Out')"
                                                    class="w-full py-1 px-1.5 rounded hover:bg-teal-100/60 font-semibold text-[11px] text-slate-800 flex items-center justify-between gap-1 border border-transparent hover:border-teal-300 transition-all cursor-pointer">
                                                    <span class="truncate {{ $lead->fitting_out ? 'font-extrabold' : '' }}"
                                                        style="{{ $lead->fitting_out ? 'color: #0d9488;' : '' }}">{{ formatFollowUpDate($lead->fitting_out) }}</span>
                                                    <svg class="w-3 h-3 text-slate-400 opacity-0 group-hover:opacity-100 flex-shrink-0"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>

                                        {{-- AKSI --}}
                                        <td class="p-2 border border-slate-300 align-top text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button type="button" onclick="openEditModal({{ json_encode($lead) }})"
                                                    title="Edit Data Peminat"
                                                    class="p-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg border border-amber-200 transition-all cursor-pointer">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>

                                                <form action="{{ route('admin.bop-leads.destroy', $lead->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus data peminat ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Hapus"
                                                        class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg border border-rose-200 transition-all cursor-pointer">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-slate-50/30">
                                        <td colspan="13" class="p-8 text-center text-slate-400 italic text-[12px] font-medium">
                                            Belum ada data peminat yang sesuai filter.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
            {{-- ── 5. Detailed List Table View ────────────────────────────────────── --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-white flex items-center justify-between">
                    <h3 class="text-base font-extrabold text-slate-900">Daftar Log Detail Peminat</h3>
                    <span class="text-xs text-slate-500 font-semibold">Total {{ $records->total() }} record</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-black text-slate-500 uppercase tracking-wider">
                                <th class="px-6 py-4 w-12 text-center">NO</th>
                                <th class="px-6 py-4">TANGGAL ENTRY</th>
                                <th class="px-6 py-4">NAMA & EMAIL</th>
                                <th class="px-6 py-4">PERUSAHAAN</th>
                                <th class="px-4 py-4">TELPON & FAX</th>
                                <th class="px-6 py-4">KATEGORI DIMINATI</th>
                                <th class="px-4 py-4 text-center">STATUS FOLLOW UP</th>
                                <th class="px-6 py-4 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                            @forelse($records as $index => $r)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-6 py-4 text-center font-bold text-slate-400">
                                        {{ $records->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 font-extrabold text-slate-900 whitespace-nowrap">
                                        <div class="text-xs font-black text-slate-900">{{ formatFollowUpDate($r->tanggal_entry) }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-semibold mt-0.5">{{ $r->nama_bulan }}
                                            {{ $r->tahun }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-extrabold text-slate-900">
                                        <div class="font-bold text-slate-900 text-xs">{{ $r->nama }}</div>
                                        @if($r->email)
                                            <div class="text-[11px] text-slate-400 font-normal mt-0.5 break-all">{{ $r->email }}</div>
                                        @else
                                            <div class="text-[11px] text-slate-400 italic mt-0.5">-</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-800">
                                        {{ $r->nama_perusahaan ?: '-' }}
                                    </td>
                                    <td class="px-4 py-4 font-semibold text-slate-600 font-mono text-[11px] whitespace-pre-line">
                                        {{ formatPhoneFax($r->telpon_fax) }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-700 font-medium max-w-xs leading-relaxed">
                                        {{ $r->kategori_diminati ?: '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="inline-flex flex-col gap-1 text-[10px] text-left">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-slate-400 w-20">Kit:</span>
                                                @if($r->kit_marketing)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">{{ formatFollowUpDate($r->kit_marketing) }}</span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-400">-</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-purple-600 w-20">Nomlet Kirim:</span>
                                                @if($r->nomlet_dikirim)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">{{ formatFollowUpDate($r->nomlet_dikirim) }}</span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-400">-</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-green-600 w-20">Nomlet Setuju:</span>
                                                @if($r->nomlet_disetujui)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-50 text-green-700 border border-green-200">{{ formatFollowUpDate($r->nomlet_disetujui) }}</span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-400">-</span>
                                                @endif
                                            </div>
                                            <div class="flex flex-col gap-0.5">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="font-bold text-orange-500 w-20">LOO:</span>
                                                    @if($r->loo)
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-orange-50 text-orange-700 border border-orange-200">{{ formatFollowUpDate($r->loo) }}</span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-400">-</span>
                                                    @endif
                                                </div>
                                                @if($r->nomor_surat_loo)
                                                    <div class="text-[10px] text-orange-600 font-bold pl-21">No:
                                                        {{ $r->nomor_surat_loo }}</div>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-emerald-600 w-20">DP:</span>
                                                @if($r->dp)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">{{ formatFollowUpDate($r->dp) }}</span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-400">-</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-indigo-600 w-20">Serah Terima:</span>
                                                @if($r->serah_terima)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">{{ formatFollowUpDate($r->serah_terima) }}</span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-400">-</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-teal-600 w-20">Fitting Out:</span>
                                                @if($r->fitting_out)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200">{{ formatFollowUpDate($r->fitting_out) }}</span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-400">-</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            {{-- Detail Record Icon --}}
                                            <button type="button" onclick="openEditModal({{ json_encode($r) }})"
                                                class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-colors cursor-pointer"
                                                title="Detail Record">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>

                                            {{-- Edit Data Icon --}}
                                            <button type="button" onclick="openEditModal({{ json_encode($r) }})"
                                                class="p-2 bg-blue-50 hover:bg-blue-100 text-[#1E3A8A] rounded-lg transition-colors cursor-pointer"
                                                title="Edit Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>

                                            {{-- Hapus Data Icon --}}
                                            <form action="{{ route('admin.bop-leads.destroy', $r->id) }}" method="POST"
                                                class="inline" onsubmit="return confirm('Hapus data peminat ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition-colors cursor-pointer"
                                                    title="Hapus Data">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-slate-400">
                                        Belum ada data peminat yang sesuai filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($records->hasPages())
                    <div class="p-4 border-t border-slate-200">
                        {{ $records->links() }}
                    </div>
                @endif
            </div>
        @endif

        {{-- ── 6. Monthly Analytics Chart (Daftar Peminat, LOO, LOI, DP) ──────── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-[0_4px_20px_rgb(0,0,0,0.04)] space-y-5">
            <div class="border-b border-slate-100 pb-4 space-y-3">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                        Grafik Analisis Tren Monthly Peminat Office BOP (Tahun {{ $selectedYear }})
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Perbandingan setiap bulan berdasarkan Tanggal Follow Up: Total Peminat, Dikirim Nomlet, Nomlet
                        Disetujui, Kirim LOO, Sudah DP, Serah Terima, dan Fitting Out
                    </p>
                </div>

                {{-- Legend Indicators (Di bawah teks judul & deskripsi) --}}
                <div style="display: flex; flex-wrap: wrap; align-items: center; padding-top: 6px;">
                    <div
                        style="display: inline-flex; align-items: center; margin-right: 24px; margin-bottom: 8px; white-space: nowrap; height: 24px;">
                        <span
                            style="width: 11px; height: 11px; min-width: 11px; min-height: 11px; background-color: #2563eb; display: inline-block; border-radius: 9999px; margin-right: 8px; flex-shrink: 0;"></span>
                        <span style="font-size: 14px; font-weight: 600; color: #334155;">Peminat</span>
                    </div>
                    <div
                        style="display: inline-flex; align-items: center; margin-right: 24px; margin-bottom: 8px; white-space: nowrap; height: 24px;">
                        <span
                            style="width: 11px; height: 11px; min-width: 11px; min-height: 11px; background-color: #7c3aed; display: inline-block; border-radius: 9999px; margin-right: 8px; flex-shrink: 0;"></span>
                        <span style="font-size: 14px; font-weight: 600; color: #334155;">Dikirim Nomlet</span>
                    </div>
                    <div
                        style="display: inline-flex; align-items: center; margin-right: 24px; margin-bottom: 8px; white-space: nowrap; height: 24px;">
                        <span
                            style="width: 11px; height: 11px; min-width: 11px; min-height: 11px; background-color: #16a34a; display: inline-block; border-radius: 9999px; margin-right: 8px; flex-shrink: 0;"></span>
                        <span style="font-size: 14px; font-weight: 600; color: #334155;">Nomlet Disetujui</span>
                    </div>
                    <div
                        style="display: inline-flex; align-items: center; margin-right: 24px; margin-bottom: 8px; white-space: nowrap; height: 24px;">
                        <span
                            style="width: 11px; height: 11px; min-width: 11px; min-height: 11px; background-color: #f97316; display: inline-block; border-radius: 9999px; margin-right: 8px; flex-shrink: 0;"></span>
                        <span style="font-size: 14px; font-weight: 600; color: #334155;">Kirim LOO</span>
                    </div>
                    <div
                        style="display: inline-flex; align-items: center; margin-right: 24px; margin-bottom: 8px; white-space: nowrap; height: 24px;">
                        <span
                            style="width: 11px; height: 11px; min-width: 11px; min-height: 11px; background-color: #059669; display: inline-block; border-radius: 9999px; margin-right: 8px; flex-shrink: 0;"></span>
                        <span style="font-size: 14px; font-weight: 600; color: #334155;">Sudah DP</span>
                    </div>
                    <div
                        style="display: inline-flex; align-items: center; margin-right: 24px; margin-bottom: 8px; white-space: nowrap; height: 24px;">
                        <span
                            style="width: 11px; height: 11px; min-width: 11px; min-height: 11px; background-color: #4f46e5; display: inline-block; border-radius: 9999px; margin-right: 8px; flex-shrink: 0;"></span>
                        <span style="font-size: 14px; font-weight: 600; color: #334155;">Serah Terima</span>
                    </div>
                    <div
                        style="display: inline-flex; align-items: center; margin-bottom: 8px; white-space: nowrap; height: 24px;">
                        <span
                            style="width: 11px; height: 11px; min-width: 11px; min-height: 11px; background-color: #0d9488; display: inline-block; border-radius: 9999px; margin-right: 8px; flex-shrink: 0;"></span>
                        <span style="font-size: 14px; font-weight: 600; color: #334155;">Fitting Out</span>
                    </div>
                </div>
            </div>

            <div class="h-80 relative">
                <canvas id="bopLeadsChart"></canvas>
            </div>
        </div>

    </div>

    {{-- ── Edit Modal ────────────────────────────────────────────────────────── --}}
    <div id="editModal"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4 animate-fade-in">
        <div
            class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-200 p-6 space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-extrabold text-slate-900">Edit Data Peminat Office BOP</h3>
                <button type="button" onclick="closeEditModal()"
                    class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
            </div>

            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Tanggal (Type Date Input) --}}
                    <div class="space-y-1">
                        <label for="edit_tanggal_entry" class="block text-xs font-bold text-slate-700 mb-1">
                            Tanggal <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="edit_tanggal_entry" name="tanggal_entry" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:border-[#1E3A8A] focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Peminat <span
                                class="text-rose-500">*</span></label>
                        <input type="text" name="nama" id="edit_nama" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:border-[#1E3A8A]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email <span
                                class="text-rose-500">*</span></label>
                        <input type="email" name="email" id="edit_email" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:border-[#1E3A8A]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Perusahaan <span
                                class="text-rose-500">*</span></label>
                        <input type="text" name="nama_perusahaan" id="edit_nama_perusahaan" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:border-[#1E3A8A]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Telpon & FAX <span
                                class="text-rose-500">*</span></label>
                        <input type="text" name="telpon_fax" id="edit_telpon_fax" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:border-[#1E3A8A]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat <span
                                class="text-rose-500">*</span></label>
                        <textarea name="alamat" id="edit_alamat" required rows="2"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:border-[#1E3A8A]"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Yg Diminati <span
                                class="text-rose-500">*</span></label>
                        <textarea name="kategori_diminati" id="edit_kategori_diminati" required rows="2"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:border-[#1E3A8A]"></textarea>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100">
                    <p class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">Follow Up Status (Tanggal)</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1">Kit Marketing</label>
                            <input type="date" name="kit_marketing" id="edit_kit_marketing"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:border-[#1E3A8A]">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1">Nomlet Dikirim</label>
                            <input type="date" name="nomlet_dikirim" id="edit_nomlet_dikirim"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:border-[#1E3A8A]">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1">Nomlet Disetujui</label>
                            <input type="date" name="nomlet_disetujui" id="edit_nomlet_disetujui"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:border-[#1E3A8A]">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1">LOO (Tanggal)</label>
                            <input type="date" name="loo" id="edit_loo"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:border-[#1E3A8A]">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1">Nomor Surat LOO</label>
                            <input type="text" name="nomor_surat_loo" id="edit_nomor_surat_loo"
                                placeholder="No. Surat LOO..."
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 outline-none focus:border-[#1E3A8A]">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1">DP</label>
                            <input type="date" name="dp" id="edit_dp"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:border-[#1E3A8A]">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1">Serah Terima</label>
                            <input type="date" name="serah_terima" id="edit_serah_terima"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:border-[#1E3A8A]">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1">Fitting Out</label>
                            <input type="date" name="fitting_out" id="edit_fitting_out"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:border-[#1E3A8A]">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-200 transition-all cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-[#1E3A8A] text-white font-bold text-xs rounded-xl shadow hover:bg-[#152a65] transition-all cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Quick Edit Follow-Up Modal ─────────────────────────────────────── --}}
    <div id="quickFollowUpModal"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4 animate-fade-in">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 id="quickModalTitle" class="text-sm font-extrabold text-slate-900">Update Follow Up</h3>
                <button type="button" onclick="closeQuickFollowUpModal()"
                    class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
            </div>

            <form id="quickFollowUpForm" method="POST" onsubmit="submitQuickFollowUp(event)" class="space-y-4">
                @csrf
                <input type="hidden" id="quick_lead_id">
                <input type="hidden" id="quick_field_name" name="field">

                <div>
                    <label id="quickFieldLabel" class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Follow
                        Up</label>
                    <input type="date" id="quick_field_value" name="value"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:border-[#1E3A8A] focus:bg-white">
                    <p class="text-[11px] text-slate-400 mt-1">Kosongkan jika ingin menghapus tanggal kolom ini.</p>
                </div>

                <div id="quickNomorSuratLooContainer" class="hidden">
                    <label class="block text-xs font-bold text-amber-800 mb-1.5">Nomor Surat LOO (Opsional)</label>
                    <input type="text" id="quick_nomor_surat_loo" name="nomor_surat_loo"
                        placeholder="Contoh: 001/LOO/IMECO/2026"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:border-[#1E3A8A] focus:bg-white">
                    <p class="text-[11px] text-slate-400 mt-1">Isi nomor surat resmi Letter of Offer (LOO).</p>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeQuickFollowUpModal()"
                        class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-200 transition-all cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-[#1E3A8A] text-white font-bold text-xs rounded-xl shadow hover:bg-[#152a65] transition-all cursor-pointer">
                        Update Matriks
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Chart.js Integration & Scripts ─────────────────────────────────── --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('bopLeadsChart');
            if (!ctx) return;

            const chartMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agus', 'Sep', 'Okt', 'Nov', 'Des'];
            const chartDataPeminat = Object.values(@json($chartData['peminat']));
            const chartDataNomletDikirim = Object.values(@json($chartData['nomlet_dikirim']));
            const chartDataNomletDisetujui = Object.values(@json($chartData['nomlet_disetujui']));
            const chartDataLoo = Object.values(@json($chartData['loo']));
            const chartDataDp = Object.values(@json($chartData['dp']));
            const chartDataSerah = Object.values(@json($chartData['serah_terima']));
            const chartDataFitting = Object.values(@json($chartData['fitting_out']));

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartMonths,
                    datasets: [
                        {
                            label: 'Daftar Peminat',
                            data: chartDataPeminat,
                            backgroundColor: '#2563eb',
                            borderRadius: 6,
                            barPercentage: 0.7,
                            categoryPercentage: 0.7,
                        },
                        {
                            label: 'Dikirim Nomlet',
                            data: chartDataNomletDikirim,
                            backgroundColor: '#7c3aed',
                            borderRadius: 6,
                            barPercentage: 0.7,
                            categoryPercentage: 0.7,
                        },
                        {
                            label: 'Nomlet Disetujui',
                            data: chartDataNomletDisetujui,
                            backgroundColor: '#16a34a',
                            borderRadius: 6,
                            barPercentage: 0.7,
                            categoryPercentage: 0.7,
                        },
                        {
                            label: 'Kirim LOO',
                            data: chartDataLoo,
                            backgroundColor: '#f97316',
                            borderRadius: 6,
                            barPercentage: 0.7,
                            categoryPercentage: 0.7,
                        },
                        {
                            label: 'Sudah DP',
                            data: chartDataDp,
                            backgroundColor: '#059669',
                            borderRadius: 6,
                            barPercentage: 0.7,
                            categoryPercentage: 0.7,
                        },
                        {
                            label: 'Serah Terima',
                            data: chartDataSerah,
                            backgroundColor: '#4f46e5',
                            borderRadius: 6,
                            barPercentage: 0.7,
                            categoryPercentage: 0.7,
                        },
                        {
                            label: 'Fitting Out',
                            data: chartDataFitting,
                            backgroundColor: '#0d9488',
                            borderRadius: 6,
                            barPercentage: 0.7,
                            categoryPercentage: 0.7,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            padding: 12,
                            cornerRadius: 12,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, precision: 0, font: { size: 11, weight: 'bold' } },
                            grid: { color: '#f1f5f9' }
                        },
                        x: {
                            ticks: { font: { size: 11, weight: 'bold' } },
                            grid: { display: false }
                        }
                    }
                }
            });
        });

        function formatDateForInput(str) {
            if (!str || str === '-') return '';
            str = String(str).trim();
            if (/^\d{4}-\d{2}-\d{2}$/.test(str)) return str;
            const d = new Date(str);
            if (!isNaN(d.getTime())) {
                const year = d.getFullYear();
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }
            return '';
        }

        const listBulanMap = {
            1: 'Januari', 2: 'Februari', 3: 'Maret', 4: 'April', 5: 'Mei', 6: 'Juni',
            7: 'Juli', 8: 'Agustus', 9: 'September', 10: 'Oktober', 11: 'November', 12: 'Desember'
        };

        function openEditModal(lead) {
            document.getElementById('editForm').action = "/admin/bop-leads/" + lead.id;
            document.getElementById('edit_tanggal_entry').value = formatDateForInput(lead.tanggal_entry) || (lead.tahun && lead.bulan ? `${lead.tahun}-${String(lead.bulan).padStart(2, '0')}-01` : '');

            document.getElementById('edit_nama').value = lead.nama || '';
            document.getElementById('edit_email').value = lead.email || '';
            document.getElementById('edit_nama_perusahaan').value = lead.nama_perusahaan || '';
            document.getElementById('edit_telpon_fax').value = lead.telpon_fax || '';
            document.getElementById('edit_alamat').value = lead.alamat || '';
            document.getElementById('edit_kategori_diminati').value = lead.kategori_diminati || '';
            document.getElementById('edit_kit_marketing').value = formatDateForInput(lead.kit_marketing);
            document.getElementById('edit_loo').value = formatDateForInput(lead.loo);
            document.getElementById('edit_nomor_surat_loo').value = lead.nomor_surat_loo || '';
            document.getElementById('edit_nomlet_dikirim').value = formatDateForInput(lead.nomlet_dikirim);
            document.getElementById('edit_nomlet_disetujui').value = formatDateForInput(lead.nomlet_disetujui);
            document.getElementById('edit_dp').value = formatDateForInput(lead.dp);
            document.getElementById('edit_serah_terima').value = formatDateForInput(lead.serah_terima);
            document.getElementById('edit_fitting_out').value = formatDateForInput(lead.fitting_out);

            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function quickEditFollowUp(id, field, currentValue, labelText, extraValue = '') {
            document.getElementById('quick_lead_id').value = id;
            document.getElementById('quick_field_name').value = field;
            document.getElementById('quick_field_value').value = formatDateForInput(currentValue);
            document.getElementById('quickModalTitle').innerText = 'Update ' + labelText;
            document.getElementById('quickFieldLabel').innerText = 'Tanggal ' + labelText;

            const looContainer = document.getElementById('quickNomorSuratLooContainer');
            if (field === 'loo') {
                if (looContainer) looContainer.classList.remove('hidden');
                document.getElementById('quick_nomor_surat_loo').value = extraValue || '';
            } else {
                if (looContainer) looContainer.classList.add('hidden');
            }

            document.getElementById('quickFollowUpModal').classList.remove('hidden');
            document.getElementById('quick_field_value').focus();
        }

        function closeQuickFollowUpModal() {
            document.getElementById('quickFollowUpModal').classList.add('hidden');
        }

        function submitQuickFollowUp(e) {
            e.preventDefault();
            const id = document.getElementById('quick_lead_id').value;
            const field = document.getElementById('quick_field_name').value;
            const value = document.getElementById('quick_field_value').value;
            const nomorSuratLoo = document.getElementById('quick_nomor_surat_loo')?.value || '';

            if (field === 'loo') {
                const looVal = value ? value.trim() : '';
                const noSuratVal = nomorSuratLoo ? nomorSuratLoo.trim() : '';
                if (looVal && !noSuratVal) {
                    showWebAlert('Jika Tanggal LOO diisi, maka Nomor Surat LOO juga wajib diisi.', 'Peringatan Validasi');
                    return;
                }
                if (!looVal && noSuratVal) {
                    showWebAlert('Jika Nomor Surat LOO diisi, maka Tanggal LOO juga wajib diisi.', 'Peringatan Validasi');
                    return;
                }
            }

            const p1 = fetch(`/admin/bop-leads/${id}/follow-up`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ field: field, value: value })
            });

            const promises = [p1];

            if (field === 'loo') {
                const p2 = fetch(`/admin/bop-leads/${id}/follow-up`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ field: 'nomor_surat_loo', value: nomorSuratLoo })
                });
                promises.push(p2);
            }

            Promise.all(promises)
                .then(() => {
                    window.location.reload();
                })
                .catch(err => {
                    console.error(err);
                    window.location.reload();
                });
        }

        // ── Interactive Month Picker Popover Functions ────────────────────────────
        function toggleMonthPicker(type) {
            const popover = document.getElementById(`monthPickerPopover_${type}`);
            if (!popover) return;

            document.querySelectorAll('[id^="monthPickerPopover_"]').forEach(el => {
                if (el.id !== `monthPickerPopover_${type}`) el.classList.add('hidden');
            });

            const isHidden = popover.classList.contains('hidden');
            if (isHidden) {
                popover.classList.remove('hidden');
                highlightSelectedMonth(type);
            } else {
                popover.classList.add('hidden');
            }
        }

        function selectMonth(type, num, nama) {
            const hiddenInput = document.getElementById(`${type}_bulan`);
            const displayInput = document.getElementById(`${type}_bulan_display`);

            if (hiddenInput) hiddenInput.value = num;
            if (displayInput) displayInput.value = nama;

            document.getElementById(`monthPickerPopover_${type}`)?.classList.add('hidden');
            highlightSelectedMonth(type);
        }

        function highlightSelectedMonth(type) {
            const val = parseInt(document.getElementById(`${type}_bulan`)?.value) || 0;
            const container = document.getElementById(`monthGrid_${type}`);
            if (!container) return;

            container.querySelectorAll('button').forEach(btn => {
                btn.className = "w-full py-2 px-3 text-center text-sm font-extrabold rounded-full transition-all cursor-pointer bg-white text-[#1E3A8A] hover:bg-blue-50 border border-[#1E3A8A]/90";
            });

            const selectedBtn = container.querySelector(`.month-btn-${type}-${val}`);
            if (selectedBtn) {
                selectedBtn.className = "w-full py-2 px-3 text-center text-sm font-extrabold rounded-full transition-all cursor-pointer bg-[#1E3A8A] text-white border-2 border-[#1E3A8A] shadow-sm font-black";
            }
        }

        // ── Interactive Year Picker Popover Functions ─────────────────────────────
        let currentStartYears = {
            matrix: 2020,
            form: 2020,
            edit: 2020
        };

        function toggleYearPicker(type) {
            const popover = document.getElementById(`yearPickerPopover_${type}`);
            if (!popover) return;
            const isHidden = popover.classList.contains('hidden');
            if (isHidden) {
                popover.classList.remove('hidden');
                renderYearGrid(type);
            } else {
                popover.classList.add('hidden');
            }
        }

        function navigateDecade(type, direction) {
            if (currentStartYears[type] === undefined) {
                currentStartYears[type] = 2020;
            }
            currentStartYears[type] += direction * 12;
            renderYearGrid(type);
        }

        function renderYearGrid(type) {
            const startYear = currentStartYears[type] !== undefined ? currentStartYears[type] : 2020;
            const endYear = startYear + 11;

            const title = document.getElementById(`decadeTitle_${type}`);
            if (title) {
                title.textContent = `${startYear} – ${endYear}`;
            }

            const grid = document.getElementById(`yearGrid_${type}`);
            if (!grid) return;

            const inputVal = parseInt(document.getElementById(`${type}_tahun`)?.value) || {{ $selectedYear }};

            let html = '';
            for (let y = startYear; y <= endYear; y++) {
                const isSelected = inputVal === y;

                let btnClasses = "w-full py-2.5 px-4 text-center text-sm font-extrabold rounded-2xl transition-all cursor-pointer ";
                if (isSelected) {
                    btnClasses += "bg-blue-50/90 text-[#1E3A8A] border border-blue-300 shadow-2xs font-black";
                } else {
                    btnClasses += "bg-slate-50/60 text-slate-800 hover:bg-blue-50/50 hover:text-[#1E3A8A] border border-slate-100/90 font-extrabold";
                }

                html += `<button type="button" onclick="selectYear('${type}', ${y}); event.stopPropagation();" class="${btnClasses}">${y}</button>`;
            }
            grid.innerHTML = html;
        }

        function selectYear(type, year) {
            const input = document.getElementById(`${type}_tahun`);
            if (input) {
                input.value = year;
            }
            document.getElementById(`yearPickerPopover_${type}`)?.classList.add('hidden');

            if (type === 'matrix') {
                const form = input ? input.closest('form') : null;
                if (form) {
                    form.submit();
                }
            }
        }

        document.addEventListener('click', function (e) {
            ['matrix', 'form', 'edit'].forEach(type => {
                const popoverYear = document.getElementById(`yearPickerPopover_${type}`);
                const inputYear = document.getElementById(`${type}_tahun`);
                if (popoverYear && inputYear && !popoverYear.contains(e.target) && !inputYear.contains(e.target) && !e.target.closest(`[onclick*="toggleYearPicker('${type}')"]`)) {
                    popoverYear.classList.add('hidden');
                }

                const popoverMonth = document.getElementById(`monthPickerPopover_${type}`);
                const inputMonth = document.getElementById(`${type}_bulan_display`);
                if (popoverMonth && inputMonth && !popoverMonth.contains(e.target) && !inputMonth.contains(e.target) && !e.target.closest(`[onclick*="toggleMonthPicker('${type}')"]`)) {
                    popoverMonth.classList.add('hidden');
                }
            });
        });
    </script>
@endsection