@extends('layouts.admin')

@section('title', 'Semua Notifikasi – Beltway Office Park')
@section('breadcrumb', 'Notifikasi')

@section('content')
<div class="space-y-6">

    {{-- Top Action & Summary Bar --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)] flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-[#1E3A8A] rounded-2xl text-white shadow-md">
                    <iconify-icon icon="lucide:bell" class="text-xl"></iconify-icon>
                </div>
                <div>
                    <h2 class="text-lg font-black text-slate-900">Pusat Notifikasi & Pengingat</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Riwayat notifikasi aktivitas dan pengingat jadwal Beltway Office Park</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <div class="flex items-center gap-2 px-3.5 py-2 bg-slate-100 rounded-xl border border-slate-200 text-xs font-bold text-slate-700">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                <span>{{ $unreadCount }} Belum Dibaca</span>
            </div>

            @if($unreadCount > 0)
                <form action="{{ route('admin.notifications.read_all') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-[#1E3A8A] hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2 cursor-pointer">
                        <iconify-icon icon="lucide:check-check" class="text-base"></iconify-icon>
                        Tandai Semua Sudah Dibaca
                    </button>
                </form>
            @endif

            @if($totalCount > 0)
                <form action="{{ route('admin.notifications.destroy_all') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh riwayat notifikasi?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-xl transition-colors flex items-center gap-1.5 cursor-pointer">
                        <iconify-icon icon="lucide:trash-2" class="text-base"></iconify-icon>
                        Hapus Semua
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Filter & Control Bar --}}
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)]">
        <form action="{{ route('admin.notifications.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            
            {{-- Search Box --}}
            <div class="relative flex items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari notifikasi..."
                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A]">
                <iconify-icon icon="lucide:search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
            </div>

            {{-- Filter Jenis --}}
            <select name="type" onchange="this.form.submit()" class="px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] cursor-pointer">
                <option value="all">-- Semua Jenis --</option>
                <option value="calendar" {{ request('type') === 'calendar' ? 'selected' : '' }}>Kalender & Agenda</option>
                <option value="bop_lead" {{ request('type') === 'bop_lead' ? 'selected' : '' }}>Daftar Peminat BOP</option>
                <option value="electricity" {{ request('type') === 'electricity' ? 'selected' : '' }}>Penggunaan Daya Listrik</option>
                <option value="water" {{ request('type') === 'water' ? 'selected' : '' }}>Penggunaan Air Bersih</option>
                <option value="lk3" {{ request('type') === 'lk3' ? 'selected' : '' }}>LK3 Safety</option>
            </select>

            {{-- Filter Status --}}
            <select name="status" onchange="this.form.submit()" class="px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] cursor-pointer">
                <option value="all">-- Semua Status --</option>
                <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
            </select>

            {{-- Filter Tanggal --}}
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()"
                   class="px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] cursor-pointer">

            {{-- Sorting & Action --}}
            <div class="flex items-center gap-2">
                <select name="sort" onchange="this.form.submit()" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] cursor-pointer">
                    <option value="desc" {{ request('sort', 'desc') === 'desc' ? 'selected' : '' }}>Terbaru</option>
                    <option value="asc" {{ request('sort') === 'asc' ? 'selected' : '' }}>Terlama</option>
                </select>
                @if(request()->hasAny(['search', 'type', 'status', 'tanggal', 'sort']))
                    <a href="{{ route('admin.notifications.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition-all whitespace-nowrap">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Notification List Container --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="divide-y divide-slate-100">
            @forelse($notifications as $item)
                <div class="p-5 flex items-start justify-between gap-4 transition-all duration-200 hover:bg-slate-50/80 {{ !$item->is_read ? 'bg-blue-50/40 border-l-4 border-l-[#1E3A8A]' : '' }}">
                    <div class="flex items-start gap-4 flex-1">
                        {{-- Icon Box --}}
                        <div class="p-3 rounded-2xl border flex-shrink-0 {{ $item->type_badge_color }}">
                            <iconify-icon icon="{{ $item->type_icon }}" class="text-xl"></iconify-icon>
                        </div>

                        {{-- Main Info --}}
                        <div class="space-y-1 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <form action="{{ route('admin.notifications.read', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-left font-black text-sm text-slate-900 hover:text-[#1E3A8A] transition-colors cursor-pointer">
                                        {{ $item->title }}
                                    </button>
                                </form>

                                @if(!$item->is_read)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-[#1E3A8A] border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#1E3A8A]"></span>
                                        Belum Dibaca
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">
                                        Sudah Dibaca
                                    </span>
                                @endif
                            </div>

                            @if($item->description)
                                <p class="text-xs font-medium text-slate-600 leading-relaxed">
                                    {{ $item->description }}
                                </p>
                            @endif

                            <div class="flex items-center gap-3 pt-1 text-[11px] font-semibold text-slate-400">
                                <span class="flex items-center gap-1">
                                    <iconify-icon icon="lucide:clock" class="text-xs"></iconify-icon>
                                    {{ $item->relative_time }}
                                </span>
                                <span>•</span>
                                <span>{{ $item->created_at ? $item->created_at->translatedFormat('d F Y, H:i') : '' }} WIB</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if($item->action_url)
                            <form action="{{ route('admin.notifications.read', $item->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-[#1E3A8A] hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                                    <iconify-icon icon="lucide:external-link" class="text-xs"></iconify-icon>
                                    Buka
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.notifications.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus notifikasi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 rounded-xl hover:bg-rose-50 transition-colors cursor-pointer" title="Hapus Notifikasi">
                                <iconify-icon icon="lucide:trash-2" class="text-base"></iconify-icon>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center space-y-3">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                        <iconify-icon icon="lucide:bell-off" class="text-3xl"></iconify-icon>
                    </div>
                    <h3 class="text-sm font-extrabold text-slate-800">Tidak ada notifikasi ditemukan</h3>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto">
                        Belum ada riwayat notifikasi atau tidak ada data yang cocok dengan filter pencarian Anda.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Pagination Footer --}}
        @if($notifications->hasPages())
            <div class="p-4 bg-slate-50 border-t border-slate-100">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
