@extends('layouts.admin')

@section('title', 'LK3 – Data Laporan Kerja Seluruh Tenant')
@section('breadcrumb', 'LK3')

@section('content')
<div class="space-y-6 animate-fade-in pb-12">

    {{-- ── Page Header ──────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Data LK3</h1>
            <p class="text-xs text-slate-500 mt-0.5">Laporan Kerja K3 seluruh tenant – diimpor dari file CSV</p>
        </div>
        <div class="flex items-center gap-2">
            {{-- Tombol Upload CSV --}}
            <button onclick="openImportModal()"
                class="px-4 py-2.5 bg-[#1E3A8A] hover:bg-slate-900 text-white rounded-xl font-bold text-xs transition-all flex items-center gap-2 shadow-md cursor-pointer transform hover:-translate-y-0.5 duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Upload CSV
            </button>
            {{-- Tombol Hapus Semua (jika ada data) --}}
            @if($totalRecords > 0)
            <form action="{{ route('admin.lk3.destroyAll') }}" method="POST"
                  data-confirm="Yakin ingin menghapus SEMUA {{ $totalRecords }} data LK3? Tindakan ini tidak dapat dibatalkan.">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl font-bold text-xs transition-all flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus Semua
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- ── Summary Cards ─────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Total Data --}}
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)] flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-[#1E3A8A] flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800">{{ number_format($totalRecords) }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Total Data LK3</p>
            </div>
        </div>
        {{-- Selesai (Done) --}}
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)] flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800">{{ number_format($doneCount) }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Status Done</p>
            </div>
        </div>
        {{-- Jenis Pekerjaan --}}
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)] flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800">{{ $chartJenisPekerjaan->count() }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Jenis Pekerjaan</p>
            </div>
        </div>
    </div>

    {{-- ── Dashboard Charts ──────────────────────────────────────────────── --}}
    @if($totalRecords > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Bar Chart 1: Jumlah per Jenis Pekerjaan --}}
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] space-y-4">
            <div>
                <h3 class="text-sm font-extrabold text-slate-800">Rekap Data LK3 – Jenis Pekerjaan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Jumlah laporan berdasarkan jenis pekerjaan</p>
            </div>
            <div class="h-64 relative">
                <canvas id="lk3ChartJenisPekerjaan"></canvas>
            </div>
        </div>

        {{-- Bar Chart 2: Jumlah per Departemen Pelapor --}}
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] space-y-4">
            <div>
                <h3 class="text-sm font-extrabold text-slate-800">Rekap Data Pelapor LK3 – Departemen</h3>
                <p class="text-xs text-slate-500 mt-0.5">Jumlah laporan berdasarkan departemen pelapor</p>
            </div>
            <div class="h-64 relative">
                <canvas id="lk3ChartDariDept"></canvas>
            </div>
        </div>

    </div>
    @endif

    {{-- ── Search & Filter Bar ───────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('admin.lk3.index') }}" class="flex flex-wrap items-center gap-3">
        {{-- Search --}}
        <div class="relative flex-grow min-w-[200px]">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input id="lk3-search" type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama, nomor laporan, kegiatan..."
                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 focus:outline-none focus:border-[#1E3A8A] transition-colors">
        </div>

        {{-- Filter: Bulan --}}
        <select id="lk3-filter-bulan" name="month" onchange="this.form.submit()"
            class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-600 focus:outline-none focus:border-[#1E3A8A] transition-colors">
            <option value="">Semua Bulan</option>
            @foreach($filterMonths as $val => $label)
                <option value="{{ $val }}" {{ request('month') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>

        {{-- Filter: Tahun --}}
        <select id="lk3-filter-tahun" name="year" onchange="this.form.submit()"
            class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-600 focus:outline-none focus:border-[#1E3A8A] transition-colors">
            <option value="">Semua Tahun</option>
            @foreach($filterYears as $yearVal)
                <option value="{{ $yearVal }}" {{ request('year') == $yearVal ? 'selected' : '' }}>{{ $yearVal }}</option>
            @endforeach
        </select>

        {{-- Filter: Jenis Pekerjaan --}}
        <select id="lk3-filter-jenis" name="jenis_pekerjaan" onchange="this.form.submit()"
            class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-600 focus:outline-none focus:border-[#1E3A8A] transition-colors">
            <option value="">Semua Jenis Pekerjaan</option>
            @foreach($filterJenisPekerjaan as $jenis)
            <option value="{{ $jenis }}" {{ request('jenis_pekerjaan') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
            @endforeach
        </select>

        {{-- Filter: Departemen --}}
        <select id="lk3-filter-dept" name="dari_dept" onchange="this.form.submit()"
            class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-600 focus:outline-none focus:border-[#1E3A8A] transition-colors">
            <option value="">Semua Departemen</option>
            @foreach($filterDariDept as $dept)
            <option value="{{ $dept }}" {{ request('dari_dept') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
            @endforeach
        </select>

        {{-- Filter: Status --}}
        <select id="lk3-filter-status" name="status" onchange="this.form.submit()"
            class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-600 focus:outline-none focus:border-[#1E3A8A] transition-colors">
            <option value="">Semua Status</option>
            @foreach($filterStatus as $st)
            <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>

        @if(request()->hasAny(['search', 'jenis_pekerjaan', 'dari_dept', 'status', 'month', 'year']))
        <a href="{{ route('admin.lk3.index') }}"
           class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition-all">
            Reset
        </a>
        @endif
    </form>

    {{-- ── Data Table ────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] overflow-hidden">

        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-extrabold text-slate-800">Daftar Data LK3</h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Menampilkan {{ $reports->firstItem() ?? 0 }}–{{ $reports->lastItem() ?? 0 }}
                    dari {{ $reports->total() }} total data
                </p>
            </div>
        </div>

        @if($reports->isEmpty())
        <div class="py-20 text-center">
            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-sm font-bold text-slate-400">Belum ada data LK3</p>
            <p class="text-xs text-slate-400 mt-1">Klik "Upload CSV" untuk mengimpor data LK3 dari file Excel</p>
            <button onclick="openImportModal()"
                class="mt-4 px-5 py-2.5 bg-[#1E3A8A] text-white rounded-xl font-bold text-xs hover:bg-slate-900 transition-all cursor-pointer inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Upload CSV Sekarang
            </button>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3.5 w-10">No</th>
                        <th class="px-4 py-3.5">Tanggal</th>
                        <th class="px-4 py-3.5">No. Laporan</th>
                        <th class="px-4 py-3.5">Nama Pelapor</th>
                        <th class="px-4 py-3.5">Dari Dept</th>
                        <th class="px-4 py-3.5">Gedung/Lt &amp; Laporan</th>
                        <th class="px-4 py-3.5">Kegiatan</th>
                        <th class="px-4 py-3.5">Jenis Pekerjaan</th>
                        <th class="px-4 py-3.5">Di Kerjakan</th>
                        <th class="px-4 py-3.5">Jam</th>
                        <th class="px-4 py-3.5">Tgl Selesai</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 font-medium">
                    @foreach($reports as $i => $report)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-4 py-3 text-slate-400">{{ $reports->firstItem() + $i }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $report->tanggal ? $report->tanggal->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap font-bold text-[#1E3A8A]">
                            {{ $report->nomor_laporan ?? '—' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $report->nama_pelapor ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-black bg-blue-50 text-blue-700 border border-blue-100 whitespace-nowrap">
                                {{ $report->dari_dept ?? '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">
                            <div>
                                <span class="font-extrabold text-slate-900">{{ $report->gedung ?? '—' }}</span>
                                @if($report->lantai) <span class="text-slate-400">/ Lt. {{ $report->lantai }}</span>@endif
                            </div>
                            <div class="mt-1 text-slate-700 whitespace-normal break-words leading-relaxed text-xs">
                                {{ $report->laporan ?? '—' }}
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-normal break-words">
                            {{ $report->kegiatan ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $jpColor = match(strtolower($report->jenis_pekerjaan ?? '')) {
                                    'elektrikal', 'electrical' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                    'plumbing'                 => 'bg-cyan-50 text-cyan-700 border-cyan-100',
                                    'ac', 'ac/hvac'            => 'bg-sky-50 text-sky-700 border-sky-100',
                                    'sipil', 'civil'           => 'bg-orange-50 text-orange-700 border-orange-100',
                                    default                    => 'bg-slate-100 text-slate-600 border-slate-200',
                                };
                            @endphp
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-black border whitespace-nowrap {{ $jpColor }}">
                                {{ $report->jenis_pekerjaan ?? '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $report->di_kerjakan ?? '—' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $report->jam ?? '—' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $report->tanggal_selesai ? $report->tanggal_selesai->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($report->status)
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-black
                                {{ strtolower($report->status) === 'done' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">
                                {{ $report->status }}
                            </span>
                            @else
                            <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('admin.lk3.destroy', $report->id) }}" method="POST"
                                  data-confirm="Hapus data LK3 ini ({{ $report->nomor_laporan }})?">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($reports->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">
            {{ $reports->links() }}
        </div>
        @endif
        @endif

    </div>

</div>

{{-- ── Import CSV Modal ─────────────────────────────────────────────────── --}}
<div id="lk3-import-modal" role="dialog" aria-modal="true"
     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all duration-300">

        <div class="bg-[#0F172A] text-white p-5 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold">Upload File CSV – LK3</h3>
                <p class="text-xs text-slate-400 mt-0.5">Import data laporan LK3 dari file Excel (ekspor sebagai CSV)</p>
            </div>
            <button onclick="closeImportModal()" class="text-slate-400 hover:text-white p-1 rounded-lg cursor-pointer">✕</button>
        </div>

        <form id="lk3-import-form" action="{{ route('admin.lk3.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            {{-- Upload area --}}
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-2">File CSV LK3</label>
                <div id="lk3-drop-zone"
                     class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center cursor-pointer hover:border-[#1E3A8A] hover:bg-blue-50/30 transition-all duration-200 group"
                     onclick="document.getElementById('lk3-csv-input').click()">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 group-hover:bg-blue-100 flex items-center justify-center mx-auto mb-3 transition-colors">
                        <svg class="w-6 h-6 text-slate-400 group-hover:text-[#1E3A8A] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-slate-600 group-hover:text-[#1E3A8A] transition-colors">Klik untuk memilih file Excel / CSV</p>
                    <p class="text-xs text-slate-400 mt-1">Format: .xlsx, .xls, atau .csv</p>
                    <p id="lk3-selected-file" class="text-xs font-bold text-[#1E3A8A] mt-2 hidden"></p>
                </div>
                <input type="file" id="lk3-csv-input" name="csv_file" accept=".xlsx,.xls,.csv" class="hidden" onchange="handleLk3FileSelect(this)">
            </div>

            {{-- Info template --}}
            <div class="p-3.5 bg-blue-50/50 rounded-xl border border-blue-100 space-y-1.5">
                <p class="text-[10px] font-black text-blue-800 uppercase tracking-wider">Panduan Format File LK3</p>
                <p class="text-[11px] text-blue-700 font-semibold leading-relaxed">
                    Kolom yang dibutuhkan:
                    <span class="font-black">No | Tanggal | Nomor Laporan | Nama Pelapor | Dari Dept | Laporan | Kegiatan | Jenis Pekerjaan | Departemen Terkait | Di kerjakan | Jam | Tanggal Selesai | Status</span>
                </p>
                <p class="text-[10px] text-blue-600 font-semibold">✓ Upload langsung file Excel (.xlsx) — tidak perlu convert ke CSV</p>
                <p class="text-[10px] text-blue-600 font-semibold">✓ Format tanggal: DD/MM/YYYY, YYYY-MM-DD, atau format Excel</p>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <button type="button" onclick="closeImportModal()"
                    class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" id="lk3-import-btn"
                    class="flex-1 py-2.5 bg-[#1E3A8A] hover:bg-slate-900 text-white rounded-xl font-bold text-xs transition-colors cursor-pointer flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Import Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // ── Modal Functions ─────────────────────────────────────────────────────
    function openImportModal() {
        document.getElementById('lk3-import-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeImportModal() {
        document.getElementById('lk3-import-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.getElementById('lk3-import-modal').addEventListener('click', function(e) {
        if (e.target === this) closeImportModal();
    });

    // ── File Select Handler ─────────────────────────────────────────────────
    function handleLk3FileSelect(input) {
        const label = document.getElementById('lk3-selected-file');
        if (input.files.length > 0) {
            label.textContent = '✓ ' + input.files[0].name;
            label.classList.remove('hidden');
        }
    }

    // ── Drag & Drop ─────────────────────────────────────────────────────────
    const dropZone = document.getElementById('lk3-drop-zone');
    const fileInput = document.getElementById('lk3-csv-input');
    if (dropZone && fileInput) {
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-[#1E3A8A]', 'bg-blue-50/30');
        });
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-[#1E3A8A]', 'bg-blue-50/30');
        });
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-[#1E3A8A]', 'bg-blue-50/30');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleLk3FileSelect(fileInput);
            }
        });
    }

    // ── Chart.js Bar Charts ─────────────────────────────────────────────────
    @if($totalRecords > 0)

    // Color palette
    const navyPalette = [
        '#1E3A8A', '#1D4ED8', '#2563EB', '#3B82F6', '#60A5FA',
        '#93C5FD', '#BFDBFE', '#0F172A', '#1E293B', '#334155'
    ];
    const bluePalette = [
        '#0284C7', '#0369A1', '#0EA5E9', '#38BDF8', '#7DD3FC',
        '#BAE6FD', '#1D4ED8', '#2563EB', '#3B82F6', '#60A5FA'
    ];

    // Chart 1: Jenis Pekerjaan
    @php
        $jpLabels = $chartJenisPekerjaan->pluck('jenis_pekerjaan')->toArray();
        $jpData   = $chartJenisPekerjaan->pluck('total')->toArray();
    @endphp
    const jpLabels = @json($jpLabels);
    const jpData   = @json($jpData);

    const ctx1 = document.getElementById('lk3ChartJenisPekerjaan');
    if (ctx1) {
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: jpLabels,
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: jpData,
                    backgroundColor: navyPalette.slice(0, jpLabels.length),
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
                        callbacks: {
                            label: (ctx) => ` ${ctx.parsed.y} laporan`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: '600' }, color: '#64748B' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' },
                        ticks: { font: { size: 10 }, color: '#94A3B8', precision: 0 }
                    }
                }
            }
        });
    }

    // Chart 2: Departemen Pelapor
    @php
        $deptLabels = $chartDariDept->pluck('dari_dept')->toArray();
        $deptData   = $chartDariDept->pluck('total')->toArray();
    @endphp
    const deptLabels = @json($deptLabels);
    const deptData   = @json($deptData);

    const ctx2 = document.getElementById('lk3ChartDariDept');
    if (ctx2) {
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: deptLabels,
                datasets: [{
                    label: 'Jumlah Pelapor',
                    data: deptData,
                    backgroundColor: bluePalette.slice(0, deptLabels.length),
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
                        callbacks: {
                            label: (ctx) => ` ${ctx.parsed.y} laporan`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: '600' }, color: '#64748B' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' },
                        ticks: { font: { size: 10 }, color: '#94A3B8', precision: 0 }
                    }
                }
            }
        });
    }
    @endif
</script>
@endsection
