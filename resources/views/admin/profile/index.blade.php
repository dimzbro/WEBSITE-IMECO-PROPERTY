@extends('layouts.admin')

@section('title', 'Pengaturan Akun – Beltway Office Park Management System')
@section('breadcrumb', 'Pengaturan Akun')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Page Header --}}
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.03)] flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#1E3A8A] flex items-center justify-center flex-shrink-0 border border-blue-100/80 shadow-xs">
                <iconify-icon icon="lucide:settings" class="text-2xl"></iconify-icon>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Pengaturan Akun Administrator</h2>
                <p class="text-xs font-medium text-slate-500 mt-0.5">Kelola identitas pengguna dan pembaruan keamanan kata sandi akun sistem.</p>
            </div>
        </div>
        <span class="px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-extrabold border border-emerald-200/60 self-start md:self-auto flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            Status: Terautentikasi
        </span>
    </div>

    {{-- ── 1. Section Informasi Profil Pengguna ─────────────────────────── --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] space-y-6">
        <div class="border-b border-slate-100 pb-5 flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#1E3A8A] flex items-center justify-center flex-shrink-0 border border-blue-100/80">
                <iconify-icon icon="lucide:user" class="text-xl"></iconify-icon>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Informasi Profil Pengguna</h3>
                <p class="text-xs font-medium text-slate-500 mt-0.5">Detail identitas dan peran akun administrator sistem yang sedang aktif</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Profile Card Avatar --}}
            <div class="p-5 bg-slate-50/70 rounded-2xl border border-slate-100 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#1E3A8A] text-white flex items-center justify-center text-lg font-black shadow-md flex-shrink-0">
                    AK
                </div>
                <div class="space-y-1">
                    <h4 class="text-base font-extrabold text-slate-900">{{ $user->name ?? 'Admin Kawasan' }}</h4>
                    <span class="inline-block px-2.5 py-0.5 rounded-md bg-blue-100/70 text-[#1E3A8A] text-[11px] font-black uppercase tracking-wider">
                        Property Manager
                    </span>
                    <p class="text-xs text-slate-500 font-medium">Beltway Office Park Management</p>
                </div>
            </div>

            {{-- Detail Info Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 bg-slate-50/70 rounded-2xl border border-slate-100">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Alamat Email</span>
                    <span class="text-xs font-extrabold text-slate-800 block truncate">{{ $user->email ?? 'admin@beltway.co.id' }}</span>
                </div>

                <div class="p-4 bg-slate-50/70 rounded-2xl border border-slate-100">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Peran Akses</span>
                    <span class="text-xs font-extrabold text-slate-800 block">Administrator (Full Access)</span>
                </div>

                <div class="p-4 bg-slate-50/70 rounded-2xl border border-slate-100">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Status Akun</span>
                    <span class="text-xs font-extrabold text-emerald-600 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Aktif
                    </span>
                </div>

                <div class="p-4 bg-slate-50/70 rounded-2xl border border-slate-100">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Terakhir Diperbarui</span>
                    <span class="text-xs font-extrabold text-slate-800 block">
                        {{ $user->updated_at ? $user->updated_at->translatedFormat('d F Y H:i') . ' WIB' : 'Hari ini' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 2. Section Ubah Password (Ditempatkan di bawah informasi profil) ── --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] space-y-6">
        
        {{-- Header Section dengan Icon, Judul 24px semibold, Deskripsi Abu-abu & Divider --}}
        <div class="border-b border-slate-100 pb-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#1E3A8A] flex items-center justify-center flex-shrink-0 border border-blue-100/80">
                <iconify-icon icon="lucide:key-round" class="text-2xl"></iconify-icon>
            </div>
            <div>
                <h3 class="text-2xl font-semibold text-slate-800 tracking-tight">Ubah Password</h3>
                <p class="text-xs font-medium text-slate-500 mt-0.5">Perbarui kata sandi akun Anda secara berkala untuk menjaga keamanan akses sistem.</p>
            </div>
        </div>

        <form action="{{ route('admin.profile.password.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                {{-- Field 1: Password Saat Ini --}}
                <div class="space-y-1.5">
                    <label for="current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Password Saat Ini <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative w-full">
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               required
                               style="padding-right: 56px !important; padding-left: 16px !important;"
                               placeholder="Masukkan password saat ini..."
                               class="w-full h-12 bg-slate-50/70 border @error('current_password') border-rose-400 bg-rose-50/20 @else border-slate-200 @enderror rounded-2xl text-xs font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:border-[#1E3A8A] focus:ring-4 focus:ring-blue-100/60 transition-all outline-none">
                        
                        <button type="button" 
                                onclick="togglePasswordVisibility('current_password', 'icon-current')"
                                style="position: absolute !important; right: 16px !important; top: 50% !important; transform: translateY(-50%) !important; z-index: 20 !important;"
                                class="p-2 text-slate-400 hover:text-[#1E3A8A] transition-colors focus:outline-none cursor-pointer flex items-center justify-center rounded-xl hover:bg-slate-100"
                                title="Tampilkan/Sembunyikan Password">
                            <iconify-icon id="icon-current" icon="lucide:eye" class="text-xl block pointer-events-none"></iconify-icon>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-xs font-bold text-rose-500 mt-1.5 flex items-center gap-1.5">
                            <iconify-icon icon="lucide:alert-circle" class="text-sm"></iconify-icon>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                {{-- Field 2: Password Baru --}}
                <div class="space-y-1.5">
                    <label for="new_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Password Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative w-full">
                        <input type="password" 
                               id="new_password" 
                               name="new_password" 
                               required
                               style="padding-right: 56px !important; padding-left: 16px !important;"
                               placeholder="Masukkan password baru..."
                               class="w-full h-12 bg-slate-50/70 border @error('new_password') border-rose-400 bg-rose-50/20 @else border-slate-200 @enderror rounded-2xl text-xs font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:border-[#1E3A8A] focus:ring-4 focus:ring-blue-100/60 transition-all outline-none">
                        
                        <button type="button" 
                                onclick="togglePasswordVisibility('new_password', 'icon-new')"
                                style="position: absolute !important; right: 16px !important; top: 50% !important; transform: translateY(-50%) !important; z-index: 20 !important;"
                                class="p-2 text-slate-400 hover:text-[#1E3A8A] transition-colors focus:outline-none cursor-pointer flex items-center justify-center rounded-xl hover:bg-slate-100"
                                title="Tampilkan/Sembunyikan Password">
                            <iconify-icon id="icon-new" icon="lucide:eye" class="text-xl block pointer-events-none"></iconify-icon>
                        </button>
                    </div>

                    {{-- Hint Kriteria Password --}}
                    <p class="text-[11px] font-medium text-slate-500 flex items-center gap-1 mt-1">
                        <iconify-icon icon="lucide:info" class="text-[#1E3A8A] text-xs flex-shrink-0"></iconify-icon>
                        <span>Minimal 8 karakter, wajib mengandung minimal <strong>1 huruf besar</strong>, <strong>1 huruf kecil</strong>, dan <strong>1 angka</strong>.</span>
                    </p>

                    @error('new_password')
                        <p class="text-xs font-bold text-rose-500 mt-1.5 flex items-center gap-1.5">
                            <iconify-icon icon="lucide:alert-circle" class="text-sm"></iconify-icon>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                {{-- Field 3: Konfirmasi Password Baru --}}
                <div class="space-y-1.5">
                    <label for="new_password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Konfirmasi Password Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative w-full">
                        <input type="password" 
                               id="new_password_confirmation" 
                               name="new_password_confirmation" 
                               required
                               style="padding-right: 56px !important; padding-left: 16px !important;"
                               placeholder="Ketik ulang password baru..."
                               class="w-full h-12 bg-slate-50/70 border @error('new_password_confirmation') border-rose-400 bg-rose-50/20 @else border-slate-200 @enderror rounded-2xl text-xs font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:border-[#1E3A8A] focus:ring-4 focus:ring-blue-100/60 transition-all outline-none">
                        
                        <button type="button" 
                                onclick="togglePasswordVisibility('new_password_confirmation', 'icon-confirm')"
                                style="position: absolute !important; right: 16px !important; top: 50% !important; transform: translateY(-50%) !important; z-index: 20 !important;"
                                class="p-2 text-slate-400 hover:text-[#1E3A8A] transition-colors focus:outline-none cursor-pointer flex items-center justify-center rounded-xl hover:bg-slate-100"
                                title="Tampilkan/Sembunyikan Password">
                            <iconify-icon id="icon-confirm" icon="lucide:eye" class="text-xl block pointer-events-none"></iconify-icon>
                        </button>
                    </div>
                    @error('new_password_confirmation')
                        <p class="text-xs font-bold text-rose-500 mt-1.5 flex items-center gap-1.5">
                            <iconify-icon icon="lucide:alert-circle" class="text-sm"></iconify-icon>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Submit Action --}}
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" 
                        class="px-6 py-3.5 bg-[#1E3A8A] hover:bg-slate-900 text-white font-extrabold text-xs rounded-2xl shadow-md hover:shadow-lg transition-all flex items-center gap-2 cursor-pointer">
                    <iconify-icon icon="lucide:shield-check" class="text-base"></iconify-icon>
                    <span>Simpan Perubahan Password</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.setAttribute('icon', 'lucide:eye-off');
        } else {
            input.type = 'password';
            icon.setAttribute('icon', 'lucide:eye');
        }
    }
</script>
@endsection
