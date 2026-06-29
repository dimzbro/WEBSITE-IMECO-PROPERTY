@extends('layouts.admin')

@section('title', 'Tambah Tenant Baru – Beltway Office Park')
@section('breadcrumb', 'Tambah Tenant')

@section('content')
<div class="space-y-6">

    <!-- Header navigation -->
    <div>
        <a href="{{ route('admin.tenants.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Batal & Kembali ke Daftar
        </a>
        <h2 class="text-xl font-extrabold text-slate-800 mt-2">Registrasi Tenant Baru</h2>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.tenants.store') }}" method="POST" class="space-y-6">
        @csrf

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
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required placeholder="PT Contoh Indonesia"
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                        </div>

                        <div>
                            <label for="npwp" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NPWP Perusahaan *</label>
                            <input type="text" id="npwp" name="npwp" value="{{ old('npwp') }}" required placeholder="01.234.567.8-901.000"
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                        </div>

                        <div>
                            <label for="pic_name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama PIC *</label>
                            <input type="text" id="pic_name" name="pic_name" value="{{ old('pic_name') }}" required placeholder="Nama Lengkap PIC"
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                        </div>

                        <div>
                            <label for="business_sector" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Sektor Usaha *</label>
                            <select id="business_sector" name="business_sector" required
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] bg-white outline-none">
                                <option value="Korporat" {{ old('business_sector') === 'Korporat' ? 'selected' : '' }}>Korporat</option>
                                <option value="UKM Teknologi" {{ old('business_sector') === 'UKM Teknologi' ? 'selected' : '' }}>UKM Teknologi</option>
                                <option value="Perbankan" {{ old('business_sector') === 'Perbankan' ? 'selected' : '' }}>Perbankan</option>
                                <option value="Logistik" {{ old('business_sector') === 'Logistik' ? 'selected' : '' }}>Logistik</option>
                                <option value="Farmasi" {{ old('business_sector') === 'Farmasi' ? 'selected' : '' }}>Farmasi</option>
                                <option value="Teknologi" {{ old('business_sector') === 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                                <option value="Properti" {{ old('business_sector') === 'Properti' ? 'selected' : '' }}>Properti</option>
                                <option value="Umum" {{ old('business_sector') === 'Umum' ? 'selected' : '' }}>Umum</option>
                            </select>
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor Telepon Kantor *</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="021-XXXXXXX"
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email Perusahaan *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="info@company.co.id"
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="address" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Perusahaan *</label>
                            <textarea id="address" name="address" required rows="3" placeholder="Alamat lengkap kantor..."
                                      class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all"></textarea>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="emergency_contact" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kontak Darurat / Keamanan *</label>
                            <input type="text" id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact') }}" required placeholder="Hubungan & Nomor Telepon"
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
                            <label for="space_allocation_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Unit Gedung Kosong</label>
                            <select id="space_allocation_id" name="space_allocation_id" 
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] bg-white outline-none">
                                <option value="">— Pilih Unit Gedung (Kosong) —</option>
                                @foreach($vacantUnits as $unit)
                                    <option value="{{ $unit->id }}" data-size="{{ $unit->area_size }}" data-rent="{{ $unit->rent_price }}"
                                            {{ old('space_allocation_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->building->name ?? 'Gedung' }} — Lantai {{ $unit->floor_number }} — Unit {{ $unit->unit_number }} (Kosong)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="area_size" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Luas Area (m²)</label>
                            <input type="number" id="area_size" name="area_size" value="{{ old('area_size') }}" placeholder="Luas Area"
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none">
                        </div>

                        <div>
                            <label for="rent_price" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Harga Sewa / Bulan (Rp)</label>
                            <input type="number" id="rent_price" name="rent_price" value="{{ old('rent_price') }}" placeholder="Harga Sewa Rupiah"
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none">
                        </div>

                        <div>
                            <label for="lease_start" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mulai Kontrak Sewa</label>
                            <input type="date" id="lease_start" name="lease_start" value="{{ old('lease_start') }}"
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none">
                        </div>

                        <div>
                            <label for="lease_end" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Selesai Kontrak Sewa</label>
                            <input type="date" id="lease_end" name="lease_end" value="{{ old('lease_end') }}"
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] outline-none">
                        </div>

                        <div>
                            <label for="contract_status" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Kontrak</label>
                            <select id="contract_status" name="contract_status"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] bg-white outline-none">
                                <option value="Terisi" {{ old('contract_status') === 'Terisi' ? 'selected' : '' }}>Aktif (Terisi)</option>
                                <option value="Hampir Berakhir" {{ old('contract_status') === 'Hampir Berakhir' ? 'selected' : '' }}>Hampir Berakhir</option>
                                <option value="Berakhir" {{ old('contract_status') === 'Berakhir' ? 'selected' : '' }}>Berakhir</option>
                            </select>
                        </div>

                        <div>
                            <label for="payment_status" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Pembayaran Awal</label>
                            <select id="payment_status" name="payment_status"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] bg-white outline-none">
                                <option value="Lunas" {{ old('payment_status') === 'Lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="Menunggu" {{ old('payment_status') === 'Menunggu' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="Tertunggak" {{ old('payment_status') === 'Tertunggak' ? 'selected' : '' }}>Tertunggak</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            </div>

                <!-- Submit / Save Card -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                    <button type="submit" class="w-full py-3 rounded-xl font-bold text-sm bg-[#1E3A8A] text-white hover:bg-slate-900 shadow-lg shadow-blue-900/10 transition-all cursor-pointer">
                        Simpan Registrasi Tenant
                    </button>
                    <a href="{{ route('admin.tenants.index') }}" class="block w-full py-3 text-center rounded-xl font-bold text-xs bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
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
        }        }
    });
</script>
@endsection
