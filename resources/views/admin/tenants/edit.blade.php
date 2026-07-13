@extends('layouts.admin')

@section('title', 'Edit Tenant – Beltway Office Park')
@section('breadcrumb', 'Edit Tenant')

@section('content')
<div class="space-y-6">

    <!-- Header navigation -->
    <div>
        <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Batal & Kembali ke Detail
        </a>
        <h2 class="text-xl font-extrabold text-slate-800 mt-2">Edit Detail Tenant: {{ $tenant->company_name }}</h2>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.tenants.update', $tenant->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl">
                <h4 class="text-sm font-bold text-rose-800">Mohon perbaiki kesalahan berikut:</h4>
                <ul class="list-disc list-inside text-xs text-rose-700 mt-2 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Panel: Profile & Space Allocations (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Section 1: Profil Perusahaan -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-base font-extrabold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                        Profil Perusahaan
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="company_name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Perusahaan *</label>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $tenant->company_name) }}" required 
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                        </div>

                        <div>
                            <label for="npwp" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NPWP Perusahaan *</label>
                            <input type="text" id="npwp" name="npwp" value="{{ old('npwp', $tenant->npwp) }}" required 
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                        </div>

                        <div>
                            <label for="pic_name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama PIC *</label>
                            <input type="text" id="pic_name" name="pic_name" value="{{ old('pic_name', $tenant->pic_name) }}" required 
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                        </div>

                        <div>
                            <label for="business_sector" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Sektor Usaha *</label>
                            <select id="business_sector" name="business_sector" required
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] bg-white outline-none">
                                @foreach(['Korporat', 'UKM Teknologi', 'Perbankan', 'Logistik', 'Farmasi', 'Teknologi', 'Properti', 'Umum'] as $sector)
                                    <option value="{{ $sector }}" {{ old('business_sector', $tenant->business_sector) === $sector ? 'selected' : '' }}>{{ $sector }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor Telepon Kantor *</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $tenant->phone) }}" required 
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email Perusahaan *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $tenant->email) }}" required 
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="address" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Perusahaan *</label>
                            <textarea id="address" name="address" required rows="3"
                                      class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">{{ old('address', $tenant->address) }}</textarea>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="emergency_contact" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kontak Darurat / Keamanan *</label>
                            <input type="text" id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact', $tenant->emergency_contact) }}" required 
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Alokasi Unit Ruang Gedung -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-base font-extrabold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                        Alokasi Ruang & Rincian Sewa
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="space_allocation_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Unit Gedung Teralokasi</label>
                            <select id="space_allocation_id" name="space_allocation_id" 
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] bg-white outline-none">
                                <option value="">— Kosongkan / Bebaskan Unit —</option>
                                @foreach($vacantUnits as $unit)
                                    <option value="{{ $unit->id }}" data-size="{{ $unit->area_size }}" data-rent="{{ $unit->rent_price }}"
                                            {{ old('space_allocation_id', $currentAllocation->id ?? '') == $unit->id ? 'selected' : '' }}>
                                        @if($unit->building->name === 'Open Yard')
                                            {{ $unit->building->name }} - Area Tanah - {{ $unit->unit_number }}
                                        @elseif($unit->building->name === 'Workshop')
                                            {{ $unit->building->name }} - Area Workshop - {{ $unit->unit_number }}
                                        @else
                                            {{ $unit->building->name ?? 'Gedung' }} - Lantai {{ $unit->floor_number }} - {{ $unit->unit_number }}
                                        @endif 
                                        @if($unit->tenant_id === $tenant->id)
                                            (unit saat ini)
                                        @else
                                            (kosong)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="area_size" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Luas Area (m²)</label>
                            <input type="number" id="area_size" name="area_size" value="{{ old('area_size', $currentAllocation->area_size ?? '') }}" placeholder="Luas Area"
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none">
                        </div>

                        <input type="hidden" id="rent_price" name="rent_price" value="{{ old('rent_price', $currentAllocation->rent_price ?? 0) }}">

                        <div>
                            <label for="lease_start" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mulai Kontrak Sewa</label>
                            <input type="date" id="lease_start" name="lease_start" value="{{ old('lease_start', $currentAllocation->lease_start ?? '') }}"
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none">
                        </div>

                        <div>
                            <label for="lease_end" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Selesai Kontrak Sewa</label>
                            <input type="date" id="lease_end" name="lease_end" value="{{ old('lease_end', $currentAllocation->lease_end ?? '') }}"
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Kontrak</label>
                            <input type="hidden" name="contract_status" id="contract_status" value="{{ old('contract_status', $currentAllocation->status ?? '') }}">
                            <div class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-slate-50 text-slate-500 font-bold select-none">
                                Dihitung Otomatis
                            </div>
                        </div>

                        <input type="hidden" id="payment_status" name="payment_status" value="{{ old('payment_status', $currentAllocation->payment_status ?? 'Lunas') }}">
                    </div>
                </div>

            </div>

            </div>

                <!-- Submit / Save Card -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                    <button type="submit" class="w-full py-3 rounded-xl font-bold text-sm bg-[#1E3A8A] text-white hover:bg-slate-900 shadow-lg shadow-blue-900/10 transition-all cursor-pointer">
                        Perbarui Data Tenant
                    </button>
                    <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="block w-full py-3 text-center rounded-xl font-bold text-xs bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                        Batal
                    </a>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Auto-fill values on vacant unit select change
        const selectSpace = document.getElementById('space_allocation_id');
        const inputSize = document.getElementById('area_size');
        const inputRent = document.getElementById('rent_price');

        if (selectSpace && inputSize && inputRent) {
            selectSpace.addEventListener('change', function() {
                const selectedOption = selectSpace.options[selectSpace.selectedIndex];
                const size = selectedOption.getAttribute('data-size');
                const rent = selectedOption.getAttribute('data-rent');
                
                if (size) inputSize.value = size;
                if (rent) inputRent.value = rent;
            });
        }
    });
</script>
@endsection
