@extends('layouts.admin')

@section('title', 'Visual Denah Gedung – Beltway Office Park')
@section('breadcrumb', 'Building Management')

@section('content')
<div class="space-y-6">

    <!-- Building Selection Tabs -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-2 bg-slate-100 p-1.5 rounded-xl">
            @foreach($buildings as $building)
                <a href="{{ route('admin.buildings.index', ['building_id' => $building->id]) }}" 
                   class="px-5 py-2 rounded-lg font-bold text-xs transition-all duration-200 {{ $activeBuilding->id === $building->id ? 'bg-[#1E3A8A] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200' }}">
                    {{ $building->name }}
                </a>
            @endforeach
        </div>
        
        <div class="text-xs font-semibold text-slate-500 hidden sm:block">
            Menara Terpilih: <span class="text-slate-800 font-bold">{{ $activeBuilding->name }}</span>
        </div>
    </div>

    <!-- Status Legend -->
    <div class="flex flex-wrap items-center gap-5 text-xs font-bold text-slate-600 bg-white px-6 py-3 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-2">
            <span class="w-4.5 h-4.5 rounded bg-emerald-500 border border-emerald-600"></span>
            <span>Terisi (Aktif)</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-4.5 h-4.5 rounded bg-amber-500 border border-amber-600 animate-pulse"></span>
            <span>Hampir Berakhir</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-4.5 h-4.5 rounded bg-rose-500 border border-rose-600"></span>
            <span>Berakhir</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-4.5 h-4.5 rounded bg-slate-200 border border-slate-300"></span>
            <span>Kosong / Tersedia</span>
        </div>
    </div>

    <!-- Visual Floor Map Stack -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        <div>
            <h3 class="text-base font-extrabold text-slate-800">Denah Lantai Per Unit</h3>
            <p class="text-xs text-slate-500 mt-0.5">Klik pada unit untuk mengalokasikan tenant atau melepaskan sewa.</p>
        </div>

        <!-- Vertical Stack representing floors -->
        <div class="space-y-4">
            @forelse($units as $floor => $floorAllocations)
                <div class="flex flex-col md:flex-row md:items-center gap-4 p-3 bg-slate-50 rounded-2xl border border-slate-100 hover:border-slate-200 transition-all">
                    <!-- Floor Label -->
                    <div class="w-24 flex-shrink-0 flex items-center justify-center bg-slate-200/60 rounded-xl py-2 px-3 text-xs font-black text-slate-700 tracking-wide">
                        Lantai {{ $floor }}
                    </div>

                    <!-- Horizontal Grid of units -->
                    <div class="flex-grow grid grid-cols-2 sm:grid-cols-5 gap-3">
                        @foreach($floorAllocations as $alloc)
                            @php
                                $statusClass = 'bg-slate-200 border-slate-300 text-slate-600';
                                if ($alloc->status === 'Terisi') {
                                    $statusClass = 'bg-emerald-500 border-emerald-600 text-white shadow-sm shadow-emerald-500/10';
                                } elseif ($alloc->status === 'Hampir Berakhir') {
                                    $statusClass = 'bg-amber-500 border-amber-600 text-white shadow-sm shadow-amber-500/10 animate-pulse';
                                } elseif ($alloc->status === 'Berakhir') {
                                    $statusClass = 'bg-rose-500 border-rose-600 text-white shadow-sm shadow-rose-500/10';
                                }
                            @endphp
                            
                            <!-- Unit Card Block -->
                            <button type="button" 
                                    class="p-3 border rounded-xl text-left cursor-pointer transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md outline-none {{ $statusClass }}"
                                    onclick="openAllocationModal({{ json_encode($alloc) }}, {{ json_encode($alloc->tenant) }})">
                                <div class="font-extrabold text-sm">{{ $alloc->unit_number }}</div>
                                <div class="text-[10px] opacity-90 mt-0.5 truncate font-semibold">
                                    {{ $alloc->tenant->company_name ?? 'Kosong / Available' }}
                                </div>
                                <div class="text-[9px] opacity-80 mt-1 font-bold">
                                    {{ $alloc->area_size }}m² • Rp {{ number_format($alloc->rent_price / 1000000, 0, ',', '.') }}jt
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-400 font-semibold italic bg-slate-50 rounded-2xl">
                    Tidak ada unit terdaftar pada gedung terpilih.
                </div>
            @endforelse
        </div>
    </div>

</div>

<!-- Interactive Modal for Space Management -->
<div id="allocation-modal" role="dialog" aria-modal="true" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modal-card">
        
        <!-- Modal Header -->
        <div class="bg-[#0F172A] text-white p-5 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold" id="modal-title">Kelola Unit</h3>
                <p class="text-xs text-slate-400 mt-0.5" id="modal-subtitle">Gedung / Unit Info</p>
            </div>
            <button onclick="closeAllocationModal()" class="text-slate-400 hover:text-white p-1 rounded-lg">
                ✕
            </button>
        </div>

        <!-- Modal Body (Dynamic Layout: Allocating vs Occupied) -->
        <div class="p-6 space-y-6">
            
            <!-- Details Section (Occupied) -->
            <div id="occupied-details" class="space-y-4 hidden">
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-3 font-semibold text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nama Penyewa</span>
                        <a href="#" id="tenant-detail-link" class="text-[#1E3A8A] font-bold hover:underline">PT Contoh</a>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Sektor Usaha</span>
                        <span class="text-slate-800" id="occupied-sector">Korporat</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Dimensi / Ukuran</span>
                        <span class="text-slate-800" id="occupied-size">450 m²</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Rasio Harga / Bulan</span>
                        <span class="text-slate-800" id="occupied-rent">Rp 45.000.000</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Kontrak Sewa</span>
                        <span class="text-slate-800" id="occupied-lease">2022-03-15 s/d 2025-03-15</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Status Pembayaran</span>
                        <span id="occupied-payment" class="px-2 py-0.5 rounded text-[10px] font-bold">Lunas</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="#" id="occupied-edit-btn" class="flex-grow py-2.5 text-center bg-[#1E3A8A] text-white hover:bg-slate-900 rounded-xl font-bold text-xs transition-colors">
                        Edit Profil Tenant
                    </a>
                    
                    <!-- Deallocate (Release) Action Form -->
                    <form action="" method="POST" id="release-form" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin melepaskan sewa tenant dari unit ini? Unit akan kosong kembali.')">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl font-bold text-xs transition-colors cursor-pointer">
                            Kosongkan Unit
                        </button>
                    </form>
                </div>
            </div>

            <!-- Form Section (Vacant / Allocate) -->
            <form action="{{ route('admin.buildings.allocate') }}" method="POST" id="allocate-form" class="space-y-4 hidden">
                @csrf
                <input type="hidden" name="space_allocation_id" id="allocate-space-id">
                
                <div>
                    <label for="tenant_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Tenant Terdaftar *</label>
                    <select name="tenant_id" id="tenant_id" required
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white outline-none">
                        <option value="">— Pilih Tenant —</option>
                        @foreach($tenants as $t)
                            <option value="{{ $t->id }}">{{ $t->company_name }} (PIC: {{ $t->pic_name }})</option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1">Tenant belum terdaftar? <a href="{{ route('admin.tenants.create') }}" class="text-[#1E3A8A] font-bold hover:underline">Registrasi Baru</a></p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="alloc-size" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Luas Area (m²) *</label>
                        <input type="number" id="alloc-size" name="area_size" required min="1"
                               class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 outline-none">
                    </div>
                    <div>
                        <label for="alloc-rent" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Harga Sewa / Bulan (Rp) *</label>
                        <input type="number" id="alloc-rent" name="rent_price" required min="0"
                               class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="alloc-start" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mulai Kontrak *</label>
                        <input type="date" id="alloc-start" name="lease_start" required
                               class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 outline-none">
                    </div>
                    <div>
                        <label for="alloc-end" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Selesai Kontrak *</label>
                        <input type="date" id="alloc-end" name="lease_end" required
                               class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="alloc-status" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Kontrak</label>
                        <select id="alloc-status" name="status" required
                                class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white outline-none">
                            <option value="Terisi">Aktif (Terisi)</option>
                            <option value="Hampir Berakhir">Hampir Berakhir</option>
                            <option value="Berakhir">Berakhir</option>
                        </select>
                    </div>
                    <div>
                        <label for="alloc-payment" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pembayaran Awal</label>
                        <select id="alloc-payment" name="payment_status" required
                                class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white outline-none">
                            <option value="Lunas">Lunas</option>
                            <option value="Menunggu">Menunggu Pembayaran</option>
                            <option value="Tertunggak">Tertunggak</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-[#1E3A8A] text-white hover:bg-slate-900 rounded-xl font-bold text-sm shadow-md transition-colors cursor-pointer">
                    Alokasikan Unit Sekarang
                </button>
            </form>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const modal = document.getElementById('allocation-modal');
    const card = document.getElementById('modal-card');
    
    // Occupied widgets
    const occupiedSec = document.getElementById('occupied-details');
    const tenantLink = document.getElementById('tenant-detail-link');
    const occupiedSector = document.getElementById('occupied-sector');
    const occupiedSize = document.getElementById('occupied-size');
    const occupiedRent = document.getElementById('occupied-rent');
    const occupiedLease = document.getElementById('occupied-lease');
    const occupiedPayment = document.getElementById('occupied-payment');
    const editBtn = document.getElementById('occupied-edit-btn');
    const releaseForm = document.getElementById('release-form');
    
    // Vacant form widgets
    const allocateForm = document.getElementById('allocate-form');
    const allocSpaceId = document.getElementById('allocate-space-id');
    const allocSize = document.getElementById('alloc-size');
    const allocRent = document.getElementById('alloc-rent');

    function openAllocationModal(alloc, tenant) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 50);

        document.getElementById('modal-title').textContent = "Unit " + alloc.unit_number;
        document.getElementById('modal-subtitle').textContent = "{{ $activeBuilding->name }} — Lantai " + alloc.floor_number;

        if (alloc.status !== 'Kosong' && tenant) {
            // SHOW OCCUPIED STATE
            occupiedSec.classList.remove('hidden');
            allocateForm.classList.add('hidden');

            tenantLink.textContent = tenant.company_name;
            tenantLink.href = "/admin/tenants/" + tenant.id;
            occupiedSector.textContent = tenant.business_sector;
            occupiedSize.textContent = alloc.area_size + " m²";
            
            // Format Rupiah
            const formattedRent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(alloc.rent_price);
            occupiedRent.textContent = formattedRent;
            occupiedLease.textContent = alloc.lease_start + " s/d " + alloc.lease_end;
            
            occupiedPayment.textContent = alloc.payment_status;
            occupiedPayment.className = "px-2 py-0.5 rounded text-[10px] font-bold";
            if (alloc.payment_status === 'Lunas') {
                occupiedPayment.classList.add('bg-emerald-100', 'text-emerald-800');
            } else if (alloc.payment_status === 'Menunggu') {
                occupiedPayment.classList.add('bg-amber-100', 'text-amber-800');
            } else {
                occupiedPayment.classList.add('bg-rose-100', 'text-rose-800');
            }

            editBtn.href = "/admin/tenants/" + tenant.id + "/edit";
            releaseForm.action = "/admin/buildings/release/" + alloc.id;
        } else {
            // SHOW VACANT ALLOCATE STATE
            occupiedSec.classList.add('hidden');
            allocateForm.classList.remove('hidden');

            allocSpaceId.value = alloc.id;
            allocSize.value = alloc.area_size;
            allocRent.value = alloc.rent_price;
        }
    }

    function closeAllocationModal() {
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    // Close on overlay click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeAllocationModal();
        }
    });
</script>
@endsection
