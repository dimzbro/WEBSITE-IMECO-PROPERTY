@extends('layouts.admin')

@section('title', 'Detail Tenant – Beltway Office Park')
@section('breadcrumb', 'Detail Tenant')

@section('content')
<div class="space-y-6">

    <!-- Top header navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.tenants.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Tenant
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.tenants.edit', $tenant->id) }}" class="px-4 py-2 text-xs font-bold bg-[#1E3A8A] text-white hover:bg-slate-900 rounded-xl transition-all duration-300">
                Edit Profil Tenant
            </a>
        </div>
    </div>

    <!-- Tenant Banner Card -->
    @php
        $alloc = $tenant->spaceAllocations->first();
    @endphp
    <div class="bg-gradient-to-r from-[#0F172A] to-[#1E3A8A] text-white p-6 rounded-2xl border border-white/10 shadow-lg relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/20 text-white font-extrabold text-2xl flex items-center justify-center">
                    {{ substr($tenant->company_name, 3, 2) ?: 'TN' }}
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold tracking-tight">{{ $tenant->company_name }}</h2>
                    <p class="text-xs text-white/70 mt-1">
                        {{ $tenant->business_sector }} 
                        @if($alloc)
                            • {{ $alloc->building->name ?? 'Gedung' }} • Lt.{{ $alloc->floor_number }} - {{ $alloc->unit_number }}
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="text-left md:text-right">
                @if($alloc)
                    <div class="text-slate-300 text-xs font-semibold uppercase tracking-wider">Harga Sewa / Bulan</div>
                    <div class="text-2xl font-black text-[#D4AF37] mt-0.5">Rp {{ number_format($alloc->rent_price, 0, ',', '.') }}</div>
                    <div class="text-[10px] text-white/60 mt-1">Selesai Kontrak: {{ $alloc->lease_end }}</div>
                @else
                    <span class="text-xs text-white/50 italic">Belum dialokasi unit</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Tenant & Unit Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Section 1: Core Profile Info -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-base font-extrabold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Informasi Tenant & Legalitas
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm font-medium">
                    <div>
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">NPWP Perusahaan</div>
                        <div class="text-slate-800 mt-1 font-bold">{{ $tenant->npwp }}</div>
                    </div>
                    <div>
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Penanggung Jawab (PIC)</div>
                        <div class="text-slate-800 mt-1 font-bold">{{ $tenant->pic_name }}</div>
                    </div>
                    <div>
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Telepon Kantor</div>
                        <div class="text-[#1E3A8A] mt-1">{{ $tenant->phone }}</div>
                    </div>
                    <div>
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Alamat Email</div>
                        <div class="text-[#1E3A8A] mt-1">{{ $tenant->email }}</div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Alamat Perusahaan</div>
                        <div class="text-slate-800 mt-1 leading-relaxed">{{ $tenant->address }}</div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Kontak Darurat / Kemanan</div>
                        <div class="text-slate-800 mt-1">{{ $tenant->emergency_contact }}</div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Space Allocation Details -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-base font-extrabold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Alokasi Ruang & Kontrak
                </h3>

                @if($alloc)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm font-medium">
                        <div>
                            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Gedung / Menara</div>
                            <div class="text-slate-800 mt-1 font-bold">{{ $alloc->building->name ?? 'Beltway Office Park' }}</div>
                        </div>
                        <div>
                            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Lantai / Nomor Unit</div>
                            <div class="text-slate-800 mt-1 font-bold">Lantai {{ $alloc->floor_number }} — {{ $alloc->unit_number }}</div>
                        </div>
                        <div>
                            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Luas Area Kantor</div>
                            <div class="text-slate-800 mt-1">{{ $alloc->area_size }} m²</div>
                        </div>
                        <div>
                            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Status Pembayaran Sewa</div>
                            <div class="mt-1">
                                @if($alloc->payment_status === 'Lunas')
                                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-800">Lunas</span>
                                @elseif($alloc->payment_status === 'Menunggu')
                                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold bg-amber-100 text-amber-800">Menunggu Tagihan</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold bg-rose-100 text-rose-800 animate-pulse">Tertunggak</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Mulai Kontrak Sewa</div>
                            <div class="text-slate-800 mt-1">{{ $alloc->lease_start }}</div>
                        </div>
                        <div>
                            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Berakhir Kontrak Sewa</div>
                            <div class="text-slate-800 mt-1">{{ $alloc->lease_end }}</div>
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center text-slate-400 italic">
                        Tidak ada alokasi gedung aktif untuk tenant ini.
                    </div>
                @endif
            </div>

        </div>

        <!-- Right Col: History Log Details -->
        <div class="space-y-6">
            
            <!-- Section 4: History/Logs of updates, maintenance events -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-base font-extrabold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Riwayat Aktivitas
                </h3>

                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @foreach($logs as $log)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center ring-8 ring-white text-slate-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="flex-grow pt-1.5 flex justify-between gap-4 text-xs">
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $log['title'] }}</p>
                                            <span class="text-[10px] text-slate-400 font-semibold mt-0.5 block">Oleh: {{ $log['pic'] }}</span>
                                        </div>
                                        <div class="text-right whitespace-nowrap text-slate-500 font-bold">
                                            <time>{{ $log['date'] }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Action Delete helper block -->
            <div class="bg-rose-50 p-6 rounded-2xl border border-rose-200 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-rose-800">Zona Bahaya</h3>
                <p class="text-xs text-rose-700 leading-relaxed">Menghapus data tenant akan menghapus seluruh data relasi termasuk sewa furnitur dan mengosongkan unit ruang gedung yang saat ini teralokasi.</p>
                
                <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" class="w-full" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tenant {{ $tenant->company_name }}? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-2.5 rounded-xl font-bold text-xs bg-rose-600 text-white hover:bg-rose-700 transition-colors shadow-sm cursor-pointer">
                        Hapus Permanen Tenant
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection
