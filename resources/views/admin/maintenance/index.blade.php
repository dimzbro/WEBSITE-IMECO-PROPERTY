@extends('layouts.admin')

@section('title', 'Maintenance Requests – Beltway Office Park')
@section('breadcrumb', 'Maintenance')

@section('content')
<div class="space-y-6">

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Metric: Total Request -->
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-2xl font-black text-slate-800">{{ $totalRequests }}</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Total Request</p>
            </div>
        </div>

        <!-- Metric: Kritis -->
        <div class="p-5 bg-rose-50/50 rounded-2xl border border-rose-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-2xl font-black text-slate-850">{{ $kritisRequests }}</h3>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Kritis</p>
            </div>
        </div>

        <!-- Metric: Dalam Proses -->
        <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89M9 11l3 3L22 4"/>
                </svg>
            </div>
            <div>
                <h3 class="text-2xl font-black text-slate-850">{{ $prossesRequests }}</h3>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Dalam Proses</p>
            </div>
        </div>

        <!-- Metric: Selesai -->
        <div class="p-5 bg-emerald-50/50 rounded-2xl border border-emerald-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-2xl font-black text-slate-850">{{ $completedRequests }}</h3>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Selesai</p>
            </div>
        </div>

    </div>

    <!-- Top Action Bar & Filters -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <!-- Search and Filters Form -->
        <form action="{{ route('admin.maintenance.index') }}" method="GET" class="flex-grow flex flex-col sm:flex-row flex-wrap items-center gap-3">
            <!-- Search bar -->
            <div class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tenant, unit, kategori..." 
                       class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all duration-200">
                <svg class="absolute left-3.5 top-2.5 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <!-- Building Filter -->
            <select name="building_id" onchange="this.form.submit()" 
                    class="w-full sm:w-36 lg:w-40 px-3 py-2 text-sm rounded-xl border border-slate-200 bg-white focus:border-[#1E3A8A] outline-none">
                <option value="">Semua Gedung</option>
                @foreach($buildings as $building)
                    <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>{{ $building->name }}</option>
                @endforeach
            </select>

            <!-- Category Filter -->
            <select name="category" onchange="this.form.submit()" 
                    class="w-full sm:w-36 lg:w-40 px-3 py-2 text-sm rounded-xl border border-slate-200 bg-white focus:border-[#1E3A8A] outline-none">
                <option value="">Semua Kategori</option>
                @foreach(['AC / HVAC', 'Listrik', 'Plumbing', 'Lift', 'Internet', 'CCTV', 'Interior', 'Kebersihan', 'Keamanan', 'Lainnya'] as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>

            <!-- Priority Filter -->
            <select name="priority" onchange="this.form.submit()" 
                    class="w-full sm:w-36 lg:w-40 px-3 py-2 text-sm rounded-xl border border-slate-200 bg-white focus:border-[#1E3A8A] outline-none">
                <option value="">Semua Prioritas</option>
                @foreach(['Rendah', 'Sedang', 'Tinggi', 'Kritis'] as $prio)
                    <option value="{{ $prio }}" {{ request('priority') == $prio ? 'selected' : '' }}>{{ $prio }}</option>
                @endforeach
            </select>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" 
                    class="w-full sm:w-36 lg:w-40 px-3 py-2 text-sm rounded-xl border border-slate-200 bg-white focus:border-[#1E3A8A] outline-none">
                <option value="">Semua Status</option>
                @foreach(['Menunggu', 'Dalam Proses', 'Selesai', 'Dibatalkan'] as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>

            @if(request('search') || request('building_id') || request('category') || request('priority') || request('status'))
                <a href="{{ route('admin.maintenance.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 underline">Reset</a>
            @endif
        </form>

        <!-- Tambah Request Button -->
        <button type="button" onclick="openAddModal()" 
           class="w-full lg:w-auto px-5 py-2.5 rounded-xl font-bold text-sm bg-[#1E3A8A] text-white hover:bg-slate-900 shadow-lg shadow-blue-900/10 hover:shadow-none transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Request
        </button>
    </div>

    <!-- Maintenance List Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <h3 class="text-base font-extrabold text-slate-800 pb-2 border-b border-slate-100">Daftar Maintenance Request</h3>
        
        <div class="divide-y divide-slate-100">
            @forelse($maintenanceRequests as $req)
                @php
                    $priorityColors = [
                        'Kritis' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'border-rose-100', 'circle' => 'bg-rose-500 text-white'],
                        'Tinggi' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'border' => 'border-orange-100', 'circle' => 'bg-orange-500 text-white'],
                        'Sedang' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-100', 'circle' => 'bg-amber-500 text-white'],
                        'Rendah' => ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-100', 'circle' => 'bg-slate-500 text-white']
                    ];
                    $statusColors = [
                        'Menunggu' => 'bg-amber-50 text-amber-600 border border-amber-200',
                        'Dalam Proses' => 'bg-blue-50 text-blue-600 border border-blue-200',
                        'Selesai' => 'bg-emerald-50 text-emerald-600 border border-emerald-200',
                        'Dibatalkan' => 'bg-slate-50 text-slate-600 border border-slate-200'
                    ];
                    
                    $pColors = $priorityColors[$req->priority] ?? $priorityColors['Rendah'];
                    $sColor = $statusColors[$req->status] ?? $statusColors['Menunggu'];
                    
                    // Get building & unit names: use specific values if saved, otherwise fall back to all tenant's space allocations (historical records)
                    if ($req->building_id && $req->unit) {
                        $buildingsStr = $req->building->name;
                        $unitsStr = $req->unit;
                    } else {
                        $allocations = $req->tenant->spaceAllocations;
                        $buildingsStr = $allocations->map(fn($a) => $a->building->name)->unique()->implode(', ') ?: '—';
                        $unitsStr = $allocations->map(fn($a) => ($a->floor_number ? 'Lt. ' . $a->floor_number . ' - ' : '') . $a->unit_number)->implode(', ') ?: '—';
                    }
                @endphp
                
                <div class="py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 first:pt-0 last:pb-0 hover:bg-slate-50/20 transition-all duration-200 px-3 rounded-xl">
                    <div class="flex items-start gap-4">
                        <!-- Icon circle based on priority -->
                        <div class="w-11 h-11 rounded-xl {{ $pColors['circle'] }} flex items-center justify-center flex-shrink-0 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            </svg>
                        </div>
                        
                        <div class="space-y-1">
                            <div class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                                <span>{{ $req->tenant->company_name }}</span>
                            </div>
                            <div class="font-black text-slate-900 text-base leading-snug">
                                {{ $req->title }}
                            </div>
                            <p class="text-xs text-slate-500 font-medium max-w-2xl leading-relaxed">
                                {{ Str::limit($req->description, 130) }}
                            </p>
                            <!-- Info badges metadata row -->
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 text-[11px] text-slate-400 font-bold pt-1.5">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-350" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    {{ $buildingsStr }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-350" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16"/>
                                    </svg>
                                    {{ $unitsStr }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-350" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V9a2 2 0 00-2-2H6a2 2 0 00-2 2v9a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $req->category }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-350" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $req->requested_at ? $req->requested_at->format('d M Y') : '—' }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-350" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $req->assigned_to }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Badges & Actions -->
                    <div class="flex items-center md:flex-col md:items-end justify-between md:justify-start gap-4">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $pColors['bg'] }} {{ $pColors['text'] }} border {{ $pColors['border'] }}">
                                • {{ $req->priority }}
                            </span>
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $sColor }}">
                                {{ $req->status }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-2 pt-1.5">
                            <!-- View button -->
                            <button type="button" 
                                    onclick="openViewModal({{ json_encode($req) }}, '{{ $buildingsStr }}', '{{ $unitsStr }}', '{{ $req->requested_at ? $req->requested_at->format('d M Y') : '—' }}', '{{ $req->completed_at ? $req->completed_at->format('d M Y') : '—' }}', '{{ $req->scheduled_at ? $req->scheduled_at->format('d M Y') : '—' }}')"
                                    class="p-2 text-slate-400 hover:text-[#1E3A8A] hover:bg-slate-100 rounded-xl transition-colors cursor-pointer" 
                                    title="Lihat Detail">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            <!-- Edit button -->
                            @if($req->status === 'Selesai')
                                <button type="button" 
                                        class="p-2 text-slate-300 cursor-not-allowed opacity-50" 
                                        disabled
                                        title="Request yang sudah selesai tidak dapat diedit">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                            @else
                                <button type="button" 
                                        onclick="openEditModal({{ json_encode($req) }})"
                                        class="p-2 text-slate-400 hover:text-amber-655 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer" 
                                        title="Ubah Status / Detail">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                            @endif
                            <!-- Delete button -->
                            <form action="{{ route('admin.maintenance.destroy', $req->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus request ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer" title="Hapus">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-slate-400 font-semibold italic bg-slate-50/50 rounded-xl border border-dashed border-slate-200 mt-2">
                    Tidak ada maintenance request ditemukan.
                </div>
            @endforelse
        </div>

        @if($maintenanceRequests->hasPages())
            <div class="pt-4 border-t border-slate-100">
                {{ $maintenanceRequests->links() }}
            </div>
        @endif
    </div>

</div>

<!-- MODAL: ADD REQUEST -->
<div id="add-request-modal" role="dialog" aria-modal="true" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-start justify-center p-4 overflow-y-auto hidden">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8 transform transition-all duration-300 scale-95 opacity-0" id="add-modal-card">
        
        <div class="bg-[#0F172A] text-white p-5 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold">Buat Maintenance Request</h3>
                <p class="text-xs text-slate-400 mt-0.5">Input keluhan dari tenant untuk ditugaskan ke teknisi</p>
            </div>
            <button onclick="closeAddModal()" class="text-slate-400 hover:text-white p-1 rounded-lg cursor-pointer">✕</button>
        </div>

        <form action="{{ route('admin.maintenance.store') }}" method="POST" class="p-6 space-y-4 text-xs font-bold text-slate-650">
            @csrf

            <!-- Searchable Tenant Select Dropdown Component -->
            <div class="relative" id="custom-select-container">
                <label class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Tenant</label>
                <button type="button" id="tenant-select-trigger" onclick="toggleTenantDropdown()"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] bg-white outline-none font-semibold text-left flex items-center justify-between cursor-pointer">
                    <span id="selected-tenant-label" class="text-slate-400 font-semibold">— Pilih Tenant —</span>
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <input type="hidden" name="tenant_id" id="allocate-tenant-id" required>

                <!-- Dropdown panel list -->
                <div id="tenant-dropdown-panel" class="absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 p-2.5 space-y-2 hidden">
                    <div class="relative">
                        <input type="text" id="tenant-search-input" placeholder="Cari nama tenant..." oninput="filterTenants(this.value)"
                               class="w-full pl-9 pr-4 py-2 text-xs rounded-lg border border-slate-200 focus:border-[#1E3A8A] outline-none">
                        <svg class="absolute left-3 top-2.5 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div class="max-h-48 overflow-y-auto divide-y divide-slate-50" id="tenant-options-list" style="max-height: 200px; overflow-y: auto;">
                        @foreach($tenants as $t)
                            @php
                                $allocationsJson = $t->spaceAllocations->map(fn($a) => [
                                    'building_id' => $a->building_id,
                                    'building_name' => $a->building->name,
                                    'unit_text' => ($a->floor_number ? 'Lt. ' . $a->floor_number . ' - ' : '') . $a->unit_number
                                ])->toArray();
                            @endphp
                            <button type="button" 
                                    class="w-full text-left px-3 py-2 text-xs hover:bg-slate-50 rounded-lg transition-colors font-semibold text-slate-700 flex items-center justify-between tenant-option"
                                    data-id="{{ $t->id }}"
                                    data-name="{{ $t->company_name }}"
                                    data-allocations="{{ json_encode($allocationsJson) }}">
                                <span>{{ $t->company_name }}</span>
                                <span class="text-[9px] text-slate-400 font-bold bg-slate-100 px-1.5 py-0.5 rounded">{{ $t->pic_name }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Gedung & Unit (Select Dropdowns filled by JS) -->
            <div class="grid grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Gedung</label>
                    <select name="building_id" id="allocate-gedung" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white outline-none font-semibold">
                        <option value="">— Pilih Gedung —</option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Unit</label>
                    <select name="unit" id="allocate-unit" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white outline-none font-semibold">
                        <option value="">— Pilih Unit —</option>
                    </select>
                </div>
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Kategori</label>
                <select name="category" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] bg-white outline-none font-semibold">
                    <option value="">— Pilih Kategori —</option>
                    @foreach(['AC / HVAC', 'Listrik', 'Plumbing', 'Lift', 'Internet', 'CCTV', 'Interior', 'Kebersihan', 'Keamanan', 'Lainnya'] as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Judul Keluhan -->
            <div>
                <label class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Judul Keluhan</label>
                <input type="text" name="title" required placeholder="Contoh: AC ruang meeting tidak dingin"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none font-semibold">
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Deskripsi Keluhan</label>
                <textarea name="description" required rows="3" placeholder="Detail deskripsi keluhan..."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none font-semibold resize-none"></textarea>
            </div>

            <!-- Priority and Assigned Team -->
            <div class="grid grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Prioritas</label>
                    <select name="priority" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] bg-white outline-none font-semibold">
                        <option value="Rendah">Rendah</option>
                        <option value="Sedang" selected>Sedang</option>
                        <option value="Tinggi">Tinggi</option>
                        <option value="Kritis">Kritis</option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Ditugaskan Kepada</label>
                    <input type="text" name="assigned_to" required placeholder="Contoh: Tim Mechanical"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none font-semibold">
                </div>
            </div>

            <!-- Tanggal Proses -->
            <div>
                <label class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Tanggal Proses</label>
                <input type="date" name="scheduled_at" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none font-semibold">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3 text-center bg-[#1E3A8A] text-white hover:bg-slate-900 rounded-xl font-bold text-xs transition-colors cursor-pointer mt-2">
                Simpan Request
            </button>
        </form>
    </div>
</div>

<!-- MODAL: VIEW DETAILS -->
<div id="view-request-modal" role="dialog" aria-modal="true" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-start justify-center p-4 overflow-y-auto hidden">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8 transform transition-all duration-300 scale-95 opacity-0" id="view-modal-card">
        
        <div class="bg-[#0F172A] text-white p-5 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold">Detail Maintenance Request</h3>
                <p class="text-xs text-slate-400 mt-0.5">Kelola dan lihat informasi keluhan tenant</p>
            </div>
            <button onclick="closeViewModal()" class="text-slate-400 hover:text-white p-1 rounded-lg cursor-pointer">✕</button>
        </div>

        <div class="p-6 space-y-4 text-xs font-bold text-slate-650">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 space-y-3 font-semibold text-slate-600">
                <div class="grid grid-cols-2 gap-3 text-[11px] font-semibold text-slate-600">
                    <div><span class="text-slate-400">Nama Tenant:</span> <span class="text-slate-800 font-bold block mt-0.5" id="view-tenant-name"></span></div>
                    <div><span class="text-slate-400">Kategori:</span> <span class="text-slate-800 font-bold block mt-0.5" id="view-category"></span></div>
                    <div><span class="text-slate-400">Gedung:</span> <span class="text-slate-800 font-bold block mt-0.5" id="view-gedung"></span></div>
                    <div><span class="text-slate-400">Unit:</span> <span class="text-slate-800 font-bold block mt-0.5" id="view-unit"></span></div>
                    <div><span class="text-slate-400">Prioritas:</span> <span class="text-slate-800 font-bold block mt-0.5" id="view-priority"></span></div>
                    <div><span class="text-slate-400">Status:</span> <span class="text-slate-800 font-bold block mt-0.5" id="view-status"></span></div>
                    <div><span class="text-slate-400">Tim Teknisi:</span> <span class="text-slate-800 font-bold block mt-0.5" id="view-assigned-to"></span></div>
                    <div><span class="text-slate-400">Tanggal Request:</span> <span class="text-slate-800 font-bold block mt-0.5" id="view-requested-at"></span></div>
                    <div><span class="text-slate-400">Tanggal Proses:</span> <span class="text-slate-800 font-bold block mt-0.5" id="view-scheduled-at"></span></div>
                    <div id="view-completed-row" class="col-span-2 hidden"><span class="text-slate-400">Tanggal Selesai:</span> <span class="text-slate-800 font-bold block mt-0.5" id="view-completed-at"></span></div>
                </div>
            </div>
            
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 space-y-2">
                <div class="text-slate-400 text-[10px] uppercase tracking-wider">Judul Keluhan</div>
                <div class="text-slate-900 font-extrabold text-sm" id="view-title"></div>
                <div class="text-slate-400 text-[10px] uppercase tracking-wider pt-2">Deskripsi</div>
                <p class="text-slate-700 font-medium leading-relaxed" id="view-description"></p>
            </div>

            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 space-y-2">
                <div class="text-slate-400 text-[10px] uppercase tracking-wider">Catatan Teknis / Resolusi</div>
                <p class="text-slate-750 font-medium leading-relaxed" id="view-notes"></p>
            </div>

            <button type="button" onclick="closeViewModal()" class="w-full py-3 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl font-bold text-xs transition-colors cursor-pointer mt-2 text-center">
                Tutup Detail
            </button>
        </div>
    </div>
</div>

<!-- MODAL: EDIT REQUEST -->
<div id="edit-request-modal" role="dialog" aria-modal="true" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-start justify-center p-4 overflow-y-auto hidden">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8 transform transition-all duration-300 scale-95 opacity-0" id="edit-modal-card">
        
        <div class="bg-[#0F172A] text-white p-5 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold">Ubah Maintenance Request</h3>
                <p class="text-xs text-slate-400 mt-0.5">Ubah prioritas, status, teknisi, dan catatan keluhan</p>
            </div>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-white p-1 rounded-lg cursor-pointer">✕</button>
        </div>

        <form action="" id="edit-request-form" method="POST" class="p-6 space-y-4 text-xs font-bold text-slate-650">
            @csrf
            @method('PATCH')

            <!-- Readonly Tenant Name -->
            <div>
                <label class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Nama Tenant</label>
                <input type="text" id="edit-tenant-name" readonly 
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 font-bold outline-none cursor-not-allowed">
            </div>

            <!-- Priority and Status Row -->
            <div class="grid grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Prioritas</label>
                    <select name="priority" id="edit-priority" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] bg-white outline-none font-semibold">
                        <option value="Rendah">Rendah</option>
                        <option value="Sedang">Sedang</option>
                        <option value="Tinggi">Tinggi</option>
                        <option value="Kritis">Kritis</option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Status Kontrak</label>
                    <select name="status" id="edit-status" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] bg-white outline-none font-semibold">
                        <option value="Menunggu">Menunggu</option>
                        <option value="Dalam Proses">Dalam Proses</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                </div>
            </div>

            <!-- Assigned Team -->
            <div>
                <label class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Ditugaskan Kepada</label>
                <input type="text" name="assigned_to" id="edit-assigned-to" required placeholder="Contoh: Tim Mechanical"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none font-semibold">
            </div>

            <!-- Tanggal Proses -->
            <div>
                <label class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Tanggal Proses</label>
                <input type="date" name="scheduled_at" id="edit-scheduled-at" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none font-semibold">
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Catatan / Update Resolusi</label>
                <textarea name="notes" id="edit-notes" rows="3" placeholder="Masukkan catatan update teknis atau solusi di sini..."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none font-semibold resize-none"></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3 text-center bg-[#1E3A8A] text-white hover:bg-slate-900 rounded-xl font-bold text-xs transition-colors cursor-pointer mt-2">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Searchable Select dropdown helper inside create request form
    function toggleTenantDropdown() {
        const panel = document.getElementById('tenant-dropdown-panel');
        panel.classList.toggle('hidden');
        if (!panel.classList.contains('hidden')) {
            document.getElementById('tenant-search-input').focus();
        }
    }

    function filterTenants(query) {
        const options = document.querySelectorAll('.tenant-option');
        const lowerQuery = query.toLowerCase();
        options.forEach(opt => {
            const name = opt.getAttribute('data-name').toLowerCase();
            if (name.includes(lowerQuery)) {
                opt.classList.remove('hidden');
            } else {
                opt.classList.add('hidden');
            }
        });
    }

    // Set dropdown options click listener using event delegation
    document.addEventListener('DOMContentLoaded', function() {
        const optionsList = document.getElementById('tenant-options-list');
        const gedungSelect = document.getElementById('allocate-gedung');
        const unitSelect = document.getElementById('allocate-unit');
        let currentAllocations = [];

        if (optionsList) {
            optionsList.addEventListener('click', function(e) {
                const button = e.target.closest('.tenant-option');
                if (button) {
                    e.preventDefault();
                    const tenantId = button.getAttribute('data-id');
                    const tenantName = button.getAttribute('data-name');
                    const allocationsData = button.getAttribute('data-allocations');

                    // Set hidden input value
                    document.getElementById('allocate-tenant-id').value = tenantId;
                    
                    // Update trigger label
                    const label = document.getElementById('selected-tenant-label');
                    label.textContent = tenantName;
                    label.className = "text-slate-800 font-bold";

                    // Parse allocations
                    currentAllocations = JSON.parse(allocationsData || '[]');

                    // Populate Gedung select
                    gedungSelect.innerHTML = '<option value="">— Pilih Gedung —</option>';
                    unitSelect.innerHTML = '<option value="">— Pilih Unit —</option>';

                    const uniqueBuildings = {};
                    currentAllocations.forEach(alloc => {
                        uniqueBuildings[alloc.building_id] = alloc.building_name;
                    });

                    const buildingKeys = Object.keys(uniqueBuildings);
                    buildingKeys.forEach(bId => {
                        const option = document.createElement('option');
                        option.value = bId;
                        option.textContent = uniqueBuildings[bId];
                        gedungSelect.appendChild(option);
                    });

                    // If only 1 building, auto-select it and trigger unit population
                    if (buildingKeys.length === 1) {
                        gedungSelect.value = buildingKeys[0];
                        populateUnits(buildingKeys[0]);
                    } else if (buildingKeys.length === 0) {
                        gedungSelect.innerHTML = '<option value="">Tidak ada gedung terdaftar</option>';
                        unitSelect.innerHTML = '<option value="">Tidak ada unit terdaftar</option>';
                    }

                    // Close panel
                    document.getElementById('tenant-dropdown-panel').classList.add('hidden');
                }
            });
        }

        // Helper to populate units based on selected building
        function populateUnits(buildingId) {
            unitSelect.innerHTML = '<option value="">— Pilih Unit —</option>';
            if (!buildingId) return;

            const filteredUnits = currentAllocations.filter(alloc => alloc.building_id == buildingId);
            filteredUnits.forEach(alloc => {
                const option = document.createElement('option');
                option.value = alloc.unit_text;
                option.textContent = alloc.unit_text;
                unitSelect.appendChild(option);
            });

            // If only 1 unit, auto-select it
            if (filteredUnits.length === 1) {
                unitSelect.value = filteredUnits[0].unit_text;
            }
        }

        // Listen to Gedung select change
        if (gedungSelect) {
            gedungSelect.addEventListener('change', function() {
                populateUnits(this.value);
            });
        }

        // Close select dropdown on click outside
        document.addEventListener('click', (e) => {
            const container = document.getElementById('custom-select-container');
            if (container && !container.contains(e.target)) {
                document.getElementById('tenant-dropdown-panel').classList.add('hidden');
            }
        });
    });

    // ----------------------------------------------------
    // Modals Control
    // ----------------------------------------------------
    const addModal = document.getElementById('add-request-modal');
    const addCard = document.getElementById('add-modal-card');

    const viewModal = document.getElementById('view-request-modal');
    const viewCard = document.getElementById('view-modal-card');

    const editModal = document.getElementById('edit-request-modal');
    const editCard = document.getElementById('edit-modal-card');

    // Create Modal
    function openAddModal() {
        addModal.classList.remove('hidden');
        setTimeout(() => {
            addCard.classList.remove('scale-95', 'opacity-0');
            addCard.classList.add('scale-100', 'opacity-100');
        }, 50);
        
        // Reset creation form
        document.getElementById('allocate-tenant-id').value = '';
        document.getElementById('selected-tenant-label').textContent = '— Pilih Tenant —';
        document.getElementById('selected-tenant-label').className = 'text-slate-400 font-semibold';
        document.getElementById('allocate-gedung').innerHTML = '<option value="">— Pilih Gedung —</option>';
        document.getElementById('allocate-unit').innerHTML = '<option value="">— Pilih Unit —</option>';
        document.getElementsByName('scheduled_at')[0].value = '';
    }

    function closeAddModal() {
        addCard.classList.remove('scale-100', 'opacity-100');
        addCard.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            addModal.classList.add('hidden');
        }, 200);
    }

    // View Modal
    function openViewModal(req, buildings, units, reqDate, compDate, schedDate) {
        viewModal.classList.remove('hidden');
        setTimeout(() => {
            viewCard.classList.remove('scale-95', 'opacity-0');
            viewCard.classList.add('scale-100', 'opacity-100');
        }, 50);

        document.getElementById('view-tenant-name').textContent = req.tenant.company_name;
        document.getElementById('view-category').textContent = req.category;
        document.getElementById('view-gedung').textContent = buildings;
        document.getElementById('view-unit').textContent = units;
        document.getElementById('view-priority').textContent = req.priority;
        document.getElementById('view-status').textContent = req.status;
        document.getElementById('view-assigned-to').textContent = req.assigned_to;
        document.getElementById('view-requested-at').textContent = reqDate;
        document.getElementById('view-scheduled-at').textContent = schedDate;
        
        const compRow = document.getElementById('view-completed-row');
        if (req.status === 'Selesai' && compDate !== '—') {
            compRow.classList.remove('hidden');
            document.getElementById('view-completed-at').textContent = compDate;
        } else {
            compRow.classList.add('hidden');
        }

        document.getElementById('view-title').textContent = req.title;
        document.getElementById('view-description').textContent = req.description;
        document.getElementById('view-notes').textContent = req.notes || 'Belum ada catatan teknis.';
    }

    function closeViewModal() {
        viewCard.classList.remove('scale-100', 'opacity-100');
        viewCard.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            viewModal.classList.add('hidden');
        }, 200);
    }

    // Edit Modal
    function openEditModal(req) {
        editModal.classList.remove('hidden');
        setTimeout(() => {
            editCard.classList.remove('scale-95', 'opacity-0');
            editCard.classList.add('scale-100', 'opacity-100');
        }, 50);

        document.getElementById('edit-request-form').action = "/admin/maintenance/" + req.id;
        document.getElementById('edit-tenant-name').value = req.tenant.company_name;
        document.getElementById('edit-priority').value = req.priority;
        document.getElementById('edit-status').value = req.status;
        document.getElementById('edit-assigned-to').value = req.assigned_to;
        if (req.scheduled_at) {
            document.getElementById('edit-scheduled-at').value = req.scheduled_at.substring(0, 10);
        } else {
            document.getElementById('edit-scheduled-at').value = '';
        }
        document.getElementById('edit-notes').value = req.notes || '';
    }

    function closeEditModal() {
        editCard.classList.remove('scale-100', 'opacity-100');
        editCard.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            editModal.classList.add('hidden');
        }, 200);
    }

    // Close on overlay click for all modals
    window.addEventListener('click', (e) => {
        if (e.target === addModal) closeAddModal();
        if (e.target === viewModal) closeViewModal();
        if (e.target === editModal) closeEditModal();
    });
</script>
@endsection
