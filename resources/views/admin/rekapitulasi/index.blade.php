@extends('layouts.admin')

@section('title', 'Rekapitulasi Request – Rekap Maintenance Gedung')
@section('breadcrumb', 'Rekapitulasi Request')

@section('content')
<div class="space-y-6 animate-fade-in pb-12">

    {{-- ── Page Header ──────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Rekapitulasi Request</h1>
            <p class="text-xs text-slate-500 mt-0.5">Rekap maintenance request gedung – diimpor dari file CSV</p>
        </div>
        <div class="flex items-center gap-2">
            {{-- Tombol Upload CSV --}}
            <button onclick="openRekImportModal()"
                class="px-4 py-2.5 bg-[#1E3A8A] hover:bg-slate-900 text-white rounded-xl font-bold text-xs transition-all flex items-center gap-2 shadow-md cursor-pointer transform hover:-translate-y-0.5 duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Upload CSV
            </button>
            {{-- Tombol Hapus Semua --}}
            @if($totalRecords > 0)
            <form action="{{ route('admin.rekapitulasi.destroyAll') }}" method="POST"
                  data-confirm="Yakin ingin menghapus SEMUA {{ $totalRecords }} data Rekapitulasi Request? Tindakan ini tidak dapat dibatalkan.">
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
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {{-- Total Data --}}
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)] flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-[#1E3A8A] flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800">{{ number_format($totalRecords) }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Total Request</p>
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
    </div>

    {{-- ── Search & Filter Bar ───────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('admin.rekapitulasi.index') }}" class="flex flex-wrap items-center gap-3">
        {{-- Search --}}
        <div class="relative flex-grow min-w-[200px]">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input id="rek-search" type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari tenant, no CR, deskripsi..."
                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 focus:outline-none focus:border-[#1E3A8A] transition-colors">
        </div>

        {{-- Filter: Bulan --}}
        <select id="rek-filter-bulan" name="month" onchange="this.form.submit()"
            class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-600 focus:outline-none focus:border-[#1E3A8A] transition-colors">
            <option value="">Semua Bulan</option>
            @foreach($filterMonths as $val => $label)
                <option value="{{ $val }}" {{ request('month') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>

        {{-- Filter: Tahun --}}
        <select id="rek-filter-tahun" name="year" onchange="this.form.submit()"
            class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-600 focus:outline-none focus:border-[#1E3A8A] transition-colors">
            <option value="">Semua Tahun</option>
            @foreach($filterYears as $yearVal)
                <option value="{{ $yearVal }}" {{ request('year') == $yearVal ? 'selected' : '' }}>{{ $yearVal }}</option>
            @endforeach
        </select>

        {{-- Filter: Jenis Pekerjaan --}}
        <select id="rek-filter-jenis" name="jenis_pekerjaan" onchange="this.form.submit()"
            class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-600 focus:outline-none focus:border-[#1E3A8A] transition-colors">
            <option value="">Semua Jenis Pekerjaan</option>
            @foreach($filterJenisPekerjaan as $jenis)
            <option value="{{ $jenis }}" {{ request('jenis_pekerjaan') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
            @endforeach
        </select>

        {{-- Filter: Status --}}
        <select id="rek-filter-status" name="status" onchange="this.form.submit()"
            class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-600 focus:outline-none focus:border-[#1E3A8A] transition-colors">
            <option value="">Semua Status</option>
            @foreach($filterStatus as $st)
            <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>

        @if(request()->hasAny(['search', 'jenis_pekerjaan', 'status', 'tenant', 'month', 'year']))
        <a href="{{ route('admin.rekapitulasi.index') }}"
           class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition-all">
            Reset
        </a>
        @endif
    </form>

    {{-- ── Data Table ────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] overflow-hidden">

        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-extrabold text-slate-800">Daftar Rekapitulasi Request</h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Menampilkan {{ $requests->firstItem() ?? 0 }}–{{ $requests->lastItem() ?? 0 }}
                    dari {{ $requests->total() }} total data
                </p>
            </div>
        </div>

        @if($requests->isEmpty())
        <div class="py-20 text-center">
            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-sm font-bold text-slate-400">Belum ada data Rekapitulasi Request</p>
            <p class="text-xs text-slate-400 mt-1">Klik "Upload CSV" untuk mengimpor data rekap maintenance</p>
            <button onclick="openRekImportModal()"
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
                        <th class="px-4 py-3.5">No. CR</th>
                        <th class="px-4 py-3.5">Tenant</th>
                        <th class="px-4 py-3.5">PIC</th>
                        <th class="px-4 py-3.5">Tanggal</th>
                        <th class="px-4 py-3.5">Deskripsi Request</th>
                        <th class="px-4 py-3.5">Penanganan</th>
                        <th class="px-4 py-3.5">Jenis Pekerjaan</th>
                        <th class="px-4 py-3.5">Handler</th>
                        <th class="px-4 py-3.5">Waktu</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 font-medium">
                    @foreach($requests as $i => $rec)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-4 py-3 text-slate-400">{{ $requests->firstItem() + $i }}</td>
                        <td class="px-4 py-3 whitespace-nowrap font-bold text-[#1E3A8A]">
                            {{ $rec->no_cr ?? '—' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap font-bold text-slate-800">
                            {{ $rec->tenant ?? '—' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $rec->name ?? '—' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $rec->date ? $rec->date->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-4 py-3 whitespace-normal break-words">
                            {{ $rec->request_description ?? '—' }}
                        </td>
                        <td class="px-4 py-3 whitespace-normal break-words">
                            {{ $rec->request_handling ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $jpColor = match(strtolower($rec->jenis_pekerjaan ?? '')) {
                                    'elektrikal', 'electrical' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                    'plumbing'                 => 'bg-cyan-50 text-cyan-700 border-cyan-100',
                                    'ac', 'ac/hvac'            => 'bg-sky-50 text-sky-700 border-sky-100',
                                    'sipil', 'civil'           => 'bg-orange-50 text-orange-700 border-orange-100',
                                    default                    => 'bg-slate-100 text-slate-600 border-slate-200',
                                };
                            @endphp
                            @if($rec->jenis_pekerjaan)
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-black border whitespace-nowrap {{ $jpColor }}">
                                {{ $rec->jenis_pekerjaan }}
                            </span>
                            @else
                            <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $rec->name_handling ?? '—' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $rec->time ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($rec->status)
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-black
                                {{ strtolower($rec->status) === 'done' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">
                                {{ $rec->status }}
                            </span>
                            @else
                            <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('admin.rekapitulasi.destroy', $rec->id) }}" method="POST"
                                  data-confirm="Hapus data Rekapitulasi Request ini ({{ $rec->no_cr }})?">
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
        @if($requests->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">
            {{ $requests->links() }}
        </div>
        @endif
        @endif

    </div>

</div>

{{-- ── Import CSV Modal ─────────────────────────────────────────────────── --}}
<div id="rek-import-modal" role="dialog" aria-modal="true"
     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all duration-300">

        <div class="bg-[#0F172A] text-white p-5 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold">Upload File CSV – Rekapitulasi Request</h3>
                <p class="text-xs text-slate-400 mt-0.5">Import data rekap maintenance dari file Excel (ekspor sebagai CSV)</p>
            </div>
            <button onclick="closeRekImportModal()" class="text-slate-400 hover:text-white p-1 rounded-lg cursor-pointer">✕</button>
        </div>

        <form id="rek-import-form" action="{{ route('admin.rekapitulasi.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            {{-- Upload area --}}
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-2">File CSV Rekapitulasi Request</label>
                <div id="rek-drop-zone"
                     class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center cursor-pointer hover:border-[#1E3A8A] hover:bg-blue-50/30 transition-all duration-200 group"
                     onclick="document.getElementById('rek-csv-input').click()">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 group-hover:bg-blue-100 flex items-center justify-center mx-auto mb-3 transition-colors">
                        <svg class="w-6 h-6 text-slate-400 group-hover:text-[#1E3A8A] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-slate-600 group-hover:text-[#1E3A8A] transition-colors">Klik untuk memilih file Excel / CSV</p>
                    <p class="text-xs text-slate-400 mt-1">Format: .xlsx, .xls, atau .csv</p>
                    <p id="rek-selected-file" class="text-xs font-bold text-[#1E3A8A] mt-2 hidden"></p>
                </div>
                <input type="file" id="rek-csv-input" name="csv_file" accept=".xlsx,.xls,.csv,.ods" class="hidden" onchange="handleRekFileSelect(this)">
            </div>

            {{-- Info template --}}
            <div class="p-3.5 bg-blue-50/50 rounded-xl border border-blue-100 space-y-1.5">
                <p class="text-[10px] font-black text-blue-800 uppercase tracking-wider">Panduan Format File Rekapitulasi</p>
                <p class="text-[11px] text-blue-700 font-semibold leading-relaxed">
                    Kolom yang dibutuhkan:
                    <span class="font-black">No CR | Tenant | Name | Date | Request description | Request handling | Jenis Pekerjaan | Name Handling | Time | Status | Cost</span>
                </p>
                <p class="text-[10px] text-blue-600 font-semibold">✓ Upload langsung file Excel (.xlsx) — tidak perlu convert ke CSV</p>
                <p class="text-[10px] text-blue-600 font-semibold">✓ Format tanggal: DD/MM/YYYY, YYYY-MM-DD, atau format Excel</p>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <button type="button" onclick="closeRekImportModal()"
                    class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" id="rek-import-btn"
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
<script>
    // ── Modal Functions ─────────────────────────────────────────────────────
    function openRekImportModal() {
        document.getElementById('rek-import-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeRekImportModal() {
        document.getElementById('rek-import-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.getElementById('rek-import-modal').addEventListener('click', function(e) {
        if (e.target === this) closeRekImportModal();
    });

    // ── File Select Handler ─────────────────────────────────────────────────
    function handleRekFileSelect(input) {
        const label = document.getElementById('rek-selected-file');
        if (input.files.length > 0) {
            label.textContent = '✓ ' + input.files[0].name;
            label.classList.remove('hidden');
        }
    }

    // ── Drag & Drop ─────────────────────────────────────────────────────────
    const rekDropZone = document.getElementById('rek-drop-zone');
    const rekFileInput = document.getElementById('rek-csv-input');
    if (rekDropZone && rekFileInput) {
        rekDropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            rekDropZone.classList.add('border-[#1E3A8A]', 'bg-blue-50/30');
        });
        rekDropZone.addEventListener('dragleave', () => {
            rekDropZone.classList.remove('border-[#1E3A8A]', 'bg-blue-50/30');
        });
        rekDropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            rekDropZone.classList.remove('border-[#1E3A8A]', 'bg-blue-50/30');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                rekFileInput.files = files;
                handleRekFileSelect(rekFileInput);
            }
        });
    }
</script>
@endsection
