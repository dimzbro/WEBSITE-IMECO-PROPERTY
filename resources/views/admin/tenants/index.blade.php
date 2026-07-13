@extends('layouts.admin')

@section('title', 'Kelola Tenant – Beltway Office Park')
@section('breadcrumb', 'Tenant Management')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar & Filters -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Search and Filters Form -->
        <form action="{{ route('admin.tenants.index') }}" method="GET" class="flex-grow flex flex-col sm:flex-row items-center gap-3">
            <!-- Search bar -->
            <div class="relative w-full sm:w-80">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, unit, jenis usaha, PIC..." 
                       class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all duration-200">
                <svg class="absolute left-3.5 top-2.5 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <!-- Building Filter -->
            <select name="building_id" onchange="this.form.submit()" 
                    class="w-full sm:w-44 px-3 py-2 text-sm rounded-xl border border-slate-200 bg-white focus:border-[#1E3A8A] outline-none">
                <option value="">Semua Gedung</option>
                @foreach($buildings as $building)
                    <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>{{ $building->name }}</option>
                @endforeach
            </select>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" 
                    class="w-full sm:w-44 px-3 py-2 text-sm rounded-xl border border-slate-200 bg-white focus:border-[#1E3A8A] outline-none">
                <option value="">Semua Status</option>
                <option value="Kontrak Aktif" {{ request('status') == 'Kontrak Aktif' ? 'selected' : '' }}>Kontrak Aktif</option>
                <option value="Kontrak Mendekati Berakhir" {{ request('status') == 'Kontrak Mendekati Berakhir' ? 'selected' : '' }}>Kontrak Mendekati Berakhir</option>
                <option value="Hampir Berakhir" {{ request('status') == 'Hampir Berakhir' ? 'selected' : '' }}>Hampir Berakhir</option>
                <option value="Kontrak Habis" {{ request('status') == 'Kontrak Habis' ? 'selected' : '' }}>Kontrak Habis</option>
            </select>

            @if(request('search') || request('building_id') || request('status'))
                <a href="{{ route('admin.tenants.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 underline">Reset</a>
            @endif
        </form>

        <!-- Tambah Tenant Button -->
        <a href="{{ route('admin.tenants.create') }}" 
           class="px-5 py-2.5 rounded-xl font-bold text-sm bg-[#1E3A8A] text-white hover:bg-slate-900 shadow-lg shadow-blue-900/10 hover:shadow-none transition-all duration-300 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Tenant
        </a>
    </div>

    <!-- Tenant List Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Nama Tenant</th>
                        <th class="px-6 py-4">Gedung / Unit</th>
                        <th class="px-6 py-4">Jenis Usaha</th>
                        <th class="px-6 py-4">Status Kontrak</th>
                        <th class="px-6 py-4">Berakhir</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($tenants as $tenant)
                        @php
                            // Get allocations matching filters
                            $allocations = $tenant->spaceAllocations;
                            if (request('building_id')) {
                                $allocations = $allocations->where('building_id', request('building_id'));
                            }
                            if (request('status')) {
                                $allocations = $allocations->where('status', request('status'));
                            }
                            if ($allocations->isEmpty()) {
                                $allocations = collect([null]);
                            }
                        @endphp
                        @foreach($allocations as $alloc)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <!-- Tenant name / PIC details -->
                                <td class="px-6 py-4.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-[#1E3A8A]/10 text-[#1E3A8A] font-bold text-xs flex items-center justify-center flex-shrink-0">
                                            {{ substr($tenant->company_name, 3, 2) ?: 'TN' }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800">{{ $tenant->company_name }}</div>
                                            <div class="text-[11px] text-slate-400 mt-0.5">{{ $tenant->pic_name }} • {{ $tenant->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Building Unit details -->
                                <td class="px-6 py-4.5">
                                    @if($alloc && $alloc->building)
                                        <div>
                                            <div class="font-bold text-slate-800">{{ $alloc->building->name }}</div>
                                            <div class="text-[11px] text-slate-400 mt-0.5">
                                                @if($alloc->building->name === 'Open Yard')
                                                    Area Tanah - {{ $alloc->unit_number }} - {{ $alloc->area_size }}m²
                                                @elseif($alloc->building->name === 'Workshop')
                                                    Area Workshop - {{ $alloc->unit_number }} - {{ $alloc->area_size }}m²
                                                @else
                                                    Lt.{{ $alloc->floor_number }} - {{ $alloc->unit_number }} - {{ $alloc->area_size }}m²
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 font-semibold italic">Belum dialokasi</span>
                                    @endif
                                </td>

                                <!-- Business Sector -->
                                <td class="px-6 py-4.5">
                                    <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg">
                                        {{ $tenant->business_sector }}
                                    </span>
                                </td>

                                <!-- Contract Status Badge -->
                                <td class="px-6 py-4.5">
                                    @if($alloc)
                                        @if($alloc->status === 'Kontrak Aktif')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Kontrak Aktif
                                            </span>
                                        @elseif($alloc->status === 'Kontrak Mendekati Berakhir')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Kontrak Mendekati Berakhir
                                            </span>
                                        @elseif($alloc->status === 'Hampir Berakhir')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 animate-pulse">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Hampir Berakhir
                                            </span>
                                        @elseif($alloc->status === 'Kontrak Habis')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-950 text-white border border-black shadow">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                Kontrak Habis
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-50 text-slate-500 border border-slate-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                Kosong
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-400">Tidak ada</span>
                                    @endif
                                </td>



                                <!-- Lease End Date -->
                                <td class="px-6 py-4.5 text-xs font-bold text-slate-500">
                                    @if($alloc && $alloc->lease_end)
                                        {{ \Carbon\Carbon::parse($alloc->lease_end)->format('Y-m-d') }}
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>

                                <!-- Action buttons -->
                                <td class="px-6 py-4.5 text-center">
                                    <div class="inline-flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="p-1.5 hover:bg-slate-100 text-slate-500 hover:text-slate-800 rounded-lg transition-colors" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        
                                        <a href="{{ route('admin.tenants.edit', $tenant->id) }}" class="p-1.5 hover:bg-slate-100 text-blue-500 hover:text-blue-700 rounded-lg transition-colors" title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <!-- Secure Deletion Protocol Form -->
                                        <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tenant {{ $tenant->company_name }}? Tindakan ini akan mengosongkan unit gedung yang terisi oleh tenant ini.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 hover:bg-rose-50 text-rose-500 hover:text-rose-700 rounded-lg transition-colors cursor-pointer" title="Hapus Tenant">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400 font-semibold italic bg-slate-50/50">
                                Tidak ada data tenant ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($tenants->hasPages())
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
