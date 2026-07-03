@extends('layouts.admin')

@section('title', 'Visual Denah Gedung – Beltway Office Park')
@section('breadcrumb', 'Building Management')

@section('content')
<div class="space-y-6">

    <!-- Building Selection Tabs -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-2 bg-slate-100 p-1.5 rounded-xl overflow-x-auto whitespace-nowrap max-w-full scrollbar-none" style="-ms-overflow-style: none; scrollbar-width: none;">
            @foreach($buildings as $building)
                <a href="{{ route('admin.buildings.index', ['building_id' => $building->id]) }}" 
                   class="px-5 py-2 rounded-lg font-bold text-xs transition-all duration-200 flex-shrink-0 {{ ($activeBuilding && $activeBuilding->id === $building->id) ? 'bg-[#1E3A8A] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200' }}">
                    {{ $building->name }}
                </a>
            @endforeach
        </div>
        
        <div class="text-xs font-semibold text-slate-500 hidden md:block flex-shrink-0">
            Gedung/Fasilitas Terpilih: <span class="text-slate-800 font-bold">{{ $activeBuilding->name ?? 'None' }}</span>
        </div>
    </div>

    <!-- Status Legend -->
    <div class="flex flex-wrap items-center gap-5 text-xs font-bold text-slate-600 bg-white px-6 py-3 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-2">
            <span class="w-4.5 h-4.5 rounded bg-emerald-500 border border-emerald-600"></span>
            <span>Kontrak Aktif (>180 Hari)</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-4.5 h-4.5 rounded bg-amber-500 border border-amber-600"></span>
            <span>Kontrak Mendekati Berakhir (31-180 Hari)</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-4.5 h-4.5 rounded bg-rose-500 border border-rose-600 animate-pulse"></span>
            <span>Hampir Berakhir (0-30 Hari)</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-4.5 h-4.5 rounded bg-slate-900 border border-black"></span>
            <span>Kontrak Habis</span>
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
                        @if($activeBuilding->name === 'Open Yard')
                            Tanah
                        @elseif($activeBuilding->name === 'Workshop')
                            Workshop
                        @else
                            Lantai {{ $floor }}
                        @endif
                    </div>

                    <!-- Horizontal Grid of units -->
                    @php
                        $groupedAllocations = $floorAllocations->groupBy('unit_number');
                        $colsCount = count($groupedAllocations);
                        $gridClass = 'grid-cols-2 sm:grid-cols-4';
                        if ($colsCount === 1) {
                            $gridClass = 'grid-cols-1';
                        } elseif ($colsCount === 2) {
                            $gridClass = 'grid-cols-1 sm:grid-cols-2';
                        } elseif ($colsCount === 3) {
                            $gridClass = 'grid-cols-1 sm:grid-cols-3';
                        }
                    @endphp
                    <div class="flex-grow grid {{ $gridClass }} gap-3">
                        @foreach($groupedAllocations as $unitName => $allocs)
                            @php
                                $occupiedAllocs = $allocs->where('status', '!=', 'Kosong');
                                $isOccupied = $occupiedAllocs->isNotEmpty();
                                
                                if (!$isOccupied) {
                                    $overallStatus = 'Kosong';
                                    $statusClass = 'bg-slate-200 border-slate-300 text-slate-600';
                                } else {
                                    if ($occupiedAllocs->where('status', 'Hampir Berakhir')->isNotEmpty()) {
                                        $overallStatus = 'Hampir Berakhir';
                                        $statusClass = 'bg-rose-500 border-rose-600 text-white shadow-sm shadow-rose-500/10 animate-pulse';
                                    } elseif ($occupiedAllocs->where('status', 'Kontrak Habis')->isNotEmpty()) {
                                        $overallStatus = 'Kontrak Habis';
                                        $statusClass = 'bg-slate-900 border-black text-white shadow-sm';
                                    } elseif ($occupiedAllocs->where('status', 'Kontrak Mendekati Berakhir')->isNotEmpty()) {
                                        $overallStatus = 'Kontrak Mendekati Berakhir';
                                        $statusClass = 'bg-amber-500 border-amber-600 text-white shadow-sm shadow-amber-500/10';
                                    } else {
                                        $overallStatus = 'Kontrak Aktif';
                                        $statusClass = 'bg-emerald-500 border-emerald-600 text-white shadow-sm shadow-emerald-500/10';
                                    }
                                }
                            @endphp
                            
                            <!-- Unit Card Block -->
                            <button type="button" 
                                    class="p-3 border rounded-xl text-left cursor-pointer transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md outline-none {{ $statusClass }}"
                                    onclick="openAllocationModal('{{ $unitName }}', {{ json_encode($allocs->values()) }})">
                                <div class="font-extrabold text-sm flex items-center justify-between">
                                    <span>{{ $unitName }}</span>
                                    @if($isOccupied)
                                        @php
                                            $isGedung = in_array($activeBuilding->name, ['Gedung A', 'Gedung B', 'Gedung C']);
                                            $isAnnex = in_array($activeBuilding->name, ['Annex', 'Annex 1']);
                                            $isWorkshop = $activeBuilding->name === 'Workshop';
                                            $isCanteen = $activeBuilding->name === 'Canteen';
                                            $isOpenYard = $activeBuilding->name === 'Open Yard';
                                            
                                            $maxCap = 1;
                                            if ($isGedung || $isAnnex || $isWorkshop) {
                                                $maxCap = 3;
                                            } elseif ($isCanteen) {
                                                if ($unitName === 'Indoor Lantai 1') {
                                                    $maxCap = 8;
                                                } elseif ($unitName === 'Outdoor') {
                                                    $maxCap = 4;
                                                } elseif ($unitName === 'Indoor Lantai 2') {
                                                    $maxCap = 3;
                                                }
                                            }
                                        @endphp
                                        @if($isOpenYard)
                                            <span class="text-[9px] px-1.5 py-0.5 rounded-md bg-white/20 text-white font-black">
                                                {{ $occupiedAllocs->count() }} Tenant
                                            </span>
                                        @else
                                            <span class="text-[9px] px-1.5 py-0.5 rounded-md bg-white/20 text-white font-black">
                                                {{ $occupiedAllocs->count() }}/{{ $maxCap }} Tenant
                                            </span>
                                        @endif
                                    @endif
                                </div>
                                <div class="text-[10px] opacity-90 mt-0.5 truncate font-semibold">
                                    @if(!$isOccupied)
                                        Kosong / Available
                                    @else
                                        {{ implode(', ', $occupiedAllocs->map(fn($a) => $a->tenant->company_name ?? 'Tenant')->toArray()) }}
                                    @endif
                                </div>
                                <div class="text-[9px] opacity-80 mt-1 font-bold">
                                    @if(!$isOccupied)
                                        @php $firstAlloc = $allocs->first(); @endphp
                                        {{ $firstAlloc->area_size }}m² • Rp {{ number_format($firstAlloc->rent_price / 1000000, 0, ',', '.') }}jt
                                    @else
                                        {{ $occupiedAllocs->sum('area_size') }}m² • Rp {{ number_format($occupiedAllocs->sum('rent_price') / 1000000, 0, ',', '.') }}jt
                                    @endif
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
<div id="allocation-modal" role="dialog" aria-modal="true" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-start justify-center p-4 overflow-y-auto hidden">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8 transform transition-all duration-300 scale-95 opacity-0" id="modal-card">
        
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
            
            <!-- Occupants List (Shown if there are occupied allocations) -->
            <div id="zone-occupants-section" class="space-y-4 hidden">
                <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider">Tenant Saat Ini</h4>
                <div id="occupants-list" class="space-y-3">
                    <!-- Dynamic occupant item -->
                </div>
            </div>

            <!-- Form Section (Vacant / Allocate) -->
            <div id="vacant-details" class="space-y-4 hidden">
                <!-- Toggle between Allocate Existing vs Add New -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider" id="allocation-form-title">Alokasi Unit</h4>
                    <a href="" id="add-tenant-link" class="text-xs font-black text-[#1E3A8A] hover:underline">
                        + Tambah Tenant Baru
                    </a>
                </div>

                <!-- Existing Tenant Allocation Form -->
                <form action="{{ route('admin.buildings.allocate') }}" method="POST" class="space-y-4 text-xs font-bold text-slate-600">
                    @csrf
                    <input type="hidden" name="space_allocation_id" id="allocate-space-id">

                    <!-- Select Tenant -->
                    <div>
                        <label for="allocate-tenant-id" class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Pilih Tenant Yang Sudah Ada</label>
                        <select name="tenant_id" id="allocate-tenant-id" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] bg-white outline-none font-semibold">
                            <option value="">— Pilih Tenant —</option>
                            @foreach($tenants as $t)
                                <option value="{{ $t->id }}">{{ $t->company_name }} ({{ $t->pic_name }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Size and Rent Row -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="allocate-area-size" class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Luas Area (m²)</label>
                            <input type="number" name="area_size" id="allocate-area-size" required min="1"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none font-semibold">
                        </div>
                        <div>
                            <label for="allocate-rent-price" class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Harga Sewa / Bulan (Rp)</label>
                            <input type="number" name="rent_price" id="allocate-rent-price" required min="0"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none font-semibold">
                        </div>
                    </div>

                    <!-- Dates Row -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="allocate-lease-start" class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Kontrak Mulai</label>
                            <input type="date" name="lease_start" id="allocate-lease-start" required
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none font-semibold">
                        </div>
                        <div>
                            <label for="allocate-lease-end" class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Kontrak Selesai</label>
                            <input type="date" name="lease_end" id="allocate-lease-end" required
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none font-semibold">
                        </div>
                    </div>

                    <!-- Statuses Row -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Status Kontrak</label>
                            <input type="hidden" name="status" id="allocate-status" value="Terisi">
                            <div class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 font-bold text-xs select-none">
                                Dihitung Otomatis
                            </div>
                        </div>
                        <div>
                            <label for="allocate-payment-status" class="block text-slate-500 mb-1.5 uppercase tracking-wide text-[10px]">Status Pembayaran</label>
                            <select name="payment_status" id="allocate-payment-status" required
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#1E3A8A] bg-white outline-none font-semibold">
                                <option value="Lunas">Lunas</option>
                                <option value="Menunggu">Menunggu</option>
                                <option value="Tertunggak">Tertunggak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-2.5 text-center bg-[#1E3A8A] text-white hover:bg-slate-900 rounded-xl font-bold text-xs transition-colors cursor-pointer mt-2">
                        Simpan Alokasi Tenant
                    </button>
                </form>
            </div>

            <!-- Capacity Reached Warning Section -->
            <div id="capacity-reached-warning" class="p-4 bg-amber-50 rounded-xl border border-amber-200 text-center text-xs text-amber-800 font-bold hidden">
                Kapasitas Unit Penuh (Maksimal 3 Tenant).
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const modal = document.getElementById('allocation-modal');
    const card = document.getElementById('modal-card');
    
    // Occupied widgets
    const occupantsSec = document.getElementById('zone-occupants-section');
    const occupantsList = document.getElementById('occupants-list');
    
    // Vacant details widget
    const vacantSec = document.getElementById('vacant-details');
    const warningSec = document.getElementById('capacity-reached-warning');
    const addTenantLink = document.getElementById('add-tenant-link');

    function openAllocationModal(unitName, allocs) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 50);

        document.getElementById('modal-title').textContent = unitName;
        
        let subtitleText = "{{ $activeBuilding->name }}";
        if (allocs[0] && allocs[0].floor_number && "{{ $activeBuilding->name }}" !== 'Open Yard') {
            subtitleText += " — Lantai " + allocs[0].floor_number;
        } else if ("{{ $activeBuilding->name }}" === 'Open Yard') {
            subtitleText += " — Area Tanah";
        }
        document.getElementById('modal-subtitle').textContent = subtitleText;

        // Separate occupied and vacant allocations
        const occupiedAllocs = allocs.filter(a => a.status !== 'Kosong' && a.tenant);
        const vacantAllocs = allocs.filter(a => a.status === 'Kosong');

        occupantsList.innerHTML = '';

        if (occupiedAllocs.length > 0) {
            occupantsSec.classList.remove('hidden');
            
            occupiedAllocs.forEach(alloc => {
                const tenant = alloc.tenant;
                const formattedRent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(alloc.rent_price);
                
                let paymentBadgeClass = "bg-rose-100 text-rose-800";
                if (alloc.payment_status === 'Lunas') {
                    paymentBadgeClass = "bg-emerald-100 text-emerald-800";
                } else if (alloc.payment_status === 'Menunggu') {
                    paymentBadgeClass = "bg-amber-100 text-amber-800";
                }

                const itemHtml = `
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3 font-semibold text-xs text-slate-600">
                        <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                            <span class="text-slate-800 font-extrabold text-sm">${tenant.company_name}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-black ${paymentBadgeClass}">${alloc.payment_status}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-[11px] font-semibold text-slate-600">
                            <div><span class="text-slate-400">PIC:</span> <span class="text-slate-800 font-bold">${tenant.pic_name}</span></div>
                            <div><span class="text-slate-400">Sektor:</span> <span class="text-slate-800 font-bold">${tenant.business_sector}</span></div>
                            <div><span class="text-slate-400">Luas:</span> <span class="text-slate-800 font-bold">${alloc.area_size} m²</span></div>
                            <div><span class="text-slate-400">Sewa:</span> <span class="text-slate-800 font-bold">${formattedRent}</span></div>
                            <div class="col-span-2"><span class="text-slate-400">Kontrak:</span> <span class="text-slate-800 font-bold">${alloc.lease_start} s/d ${alloc.lease_end}</span></div>
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <a href="/admin/tenants/${tenant.id}/edit" class="flex-grow text-center py-2 bg-[#1E3A8A] text-white hover:bg-slate-900 rounded-xl text-[10px] transition-colors font-bold">
                                Edit Profil Tenant
                            </a>
                            <form action="/admin/buildings/release/${alloc.id}" method="POST" class="flex-grow inline" onsubmit="return confirm('Apakah Anda yakin ingin melepaskan sewa tenant dari unit ini? Unit akan kosong kembali.')">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="w-full text-center py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl text-[10px] transition-colors font-bold cursor-pointer">
                                    Kosongkan Unit
                                </button>
                            </form>
                        </div>
                    </div>
                `;
                occupantsList.insertAdjacentHTML('beforeend', itemHtml);
            });
        } else {
            occupantsSec.classList.add('hidden');
        }

        // Determine capacity constraint
        const buildingName = "{{ $activeBuilding->name }}";
        const isGedung = ['Gedung A', 'Gedung B', 'Gedung C'].includes(buildingName);
        const isAnnex = ['Annex', 'Annex 1'].includes(buildingName);
        const isWorkshop = buildingName === 'Workshop';
        const isCanteen = buildingName === 'Canteen';
        const isOpenYard = buildingName === 'Open Yard';

        let maxCapacity = 1;
        if (isGedung || isAnnex || isWorkshop) {
            maxCapacity = 3;
        } else if (isCanteen) {
            if (unitName === 'Indoor Lantai 1') {
                maxCapacity = 8;
            } else if (unitName === 'Outdoor') {
                maxCapacity = 4;
            } else if (unitName === 'Indoor Lantai 2') {
                maxCapacity = 3;
            }
        }

        const currentCount = occupiedAllocs.length;

        if (isOpenYard) {
            // Open Yard special handling: check total occupied area size
            const maxArea = 1059.72;
            const occupiedArea = occupiedAllocs.reduce((sum, a) => sum + parseFloat(a.area_size || 0), 0);
            const remainingArea = Math.max(0, maxArea - occupiedArea);

            if (remainingArea <= 0.01) {
                vacantSec.classList.add('hidden');
                warningSec.classList.remove('hidden');
                warningSec.textContent = "Kapasitas Unit Penuh (Maksimal Tanah 1.059,72 m² Habis Tersewa)";
            } else {
                vacantSec.classList.remove('hidden');
                warningSec.classList.add('hidden');

                const spaceIdToPass = vacantAllocs.length > 0 ? vacantAllocs[0].id : occupiedAllocs[0].id;
                document.getElementById('allocate-space-id').value = spaceIdToPass;

                addTenantLink.href = "/admin/tenants/create?space_allocation_id=" + spaceIdToPass;

                // For Open Yard, let the default size be the remaining area
                document.getElementById('allocate-area-size').value = Math.round(remainingArea * 100) / 100;
                document.getElementById('allocate-rent-price').value = Math.round(remainingArea * 33027); // scale price proportionally or keep default
                document.getElementById('allocate-tenant-id').value = '';

                const today = new Date().toISOString().split('T')[0];
                document.getElementById('allocate-lease-start').value = today;
                
                const nextYear = new Date();
                nextYear.setFullYear(nextYear.getFullYear() + 1);
                document.getElementById('allocate-lease-end').value = nextYear.toISOString().split('T')[0];
                
                document.getElementById('allocate-status').value = 'Terisi';
                document.getElementById('allocate-payment-status').value = 'Lunas';

                document.getElementById('allocation-form-title').textContent = "Alokasi Tenant Baru (" + currentCount + " Tenant Terdaftar)";
            }
        } else {
            // Count capacity based handling
            if (currentCount >= maxCapacity) {
                // Capacity reached, hide form and show warning
                vacantSec.classList.add('hidden');
                warningSec.classList.remove('hidden');
                warningSec.textContent = "Kapasitas Unit Penuh (Maksimal " + maxCapacity + " Tenant).";
            } else {
                // Capacity not reached, show form and hide warning
                vacantSec.classList.remove('hidden');
                warningSec.classList.add('hidden');

                const spaceIdToPass = vacantAllocs.length > 0 ? vacantAllocs[0].id : occupiedAllocs[0].id;
                document.getElementById('allocate-space-id').value = spaceIdToPass;

                addTenantLink.href = "/admin/tenants/create?space_allocation_id=" + spaceIdToPass;

                const defaultSize = vacantAllocs.length > 0 ? vacantAllocs[0].area_size : occupiedAllocs[0].area_size;
                const defaultRent = vacantAllocs.length > 0 ? vacantAllocs[0].rent_price : occupiedAllocs[0].rent_price;

                document.getElementById('allocate-area-size').value = defaultSize;
                document.getElementById('allocate-rent-price').value = defaultRent;
                document.getElementById('allocate-tenant-id').value = '';

                const today = new Date().toISOString().split('T')[0];
                document.getElementById('allocate-lease-start').value = today;
                
                const nextYear = new Date();
                nextYear.setFullYear(nextYear.getFullYear() + 1);
                document.getElementById('allocate-lease-end').value = nextYear.toISOString().split('T')[0];
                
                document.getElementById('allocate-status').value = 'Terisi';
                document.getElementById('allocate-payment-status').value = 'Lunas';

                if (currentCount > 0) {
                    document.getElementById('allocation-form-title').textContent = "Alokasi Tenant Tambahan (" + (currentCount + 1) + "/" + maxCapacity + ")";
                } else {
                    document.getElementById('allocation-form-title').textContent = "Alokasi Unit";
                }
            }
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
