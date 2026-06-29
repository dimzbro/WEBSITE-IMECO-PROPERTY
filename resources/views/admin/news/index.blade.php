@extends('layouts.admin')

@section('title', 'Kelola Berita & Artikel – Beltway Office Park')
@section('breadcrumb', 'News Management')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar & Success Message -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 text-sm shadow-sm" id="success-alert">
        <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="font-semibold">{{ session('success') }}</div>
    </div>
    @endif

    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Dashboard Header Info -->
        <div>
            <h2 class="text-lg font-bold text-slate-800">Manajemen Berita & Insights</h2>
            <p class="text-xs text-slate-400 mt-0.5">Kelola artikel, pencapaian, dan berita yang tampil di landing page utama.</p>
        </div>

        <!-- Tambah Berita Button -->
        <a href="{{ route('admin.news.create') }}" 
           class="px-5 py-2.5 rounded-xl font-bold text-sm bg-[#1E3A8A] text-white hover:bg-slate-900 shadow-lg shadow-blue-900/10 hover:shadow-none transition-all duration-300 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Berita
        </a>
    </div>

    <!-- News List Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4 w-24">Gambar</th>
                        <th class="px-6 py-4">Informasi Berita</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Tanggal Publikasi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($news as $article)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <!-- Image Thumbnail -->
                            <td class="px-6 py-4">
                                <div class="w-16 h-12 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0">
                                    @if($article->image)
                                        @if(filter_var($article->image, FILTER_VALIDATE_URL))
                                            <img src="{{ $article->image }}" alt="Thumbnail" class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ asset('storage/' . $article->image) }}" alt="Thumbnail" class="w-full h-full object-cover">
                                        @endif
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">No Image</div>
                                    @endif
                                </div>
                            </td>

                            <!-- News Info details -->
                            <td class="px-6 py-4.5 max-w-md">
                                <div>
                                    <div class="font-bold text-slate-800 line-clamp-1" title="{{ $article->title }}">{{ $article->title }}</div>
                                    <div class="text-[11px] text-slate-400 mt-1 leading-normal line-clamp-2" title="{{ $article->excerpt }}">{{ $article->excerpt }}</div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="px-6 py-4.5">
                                <span class="text-xs font-bold px-2.5 py-1 rounded-lg {{ 
                                    $article->category === 'Achievement' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (
                                    $article->category === 'News' ? 'bg-blue-50 text-blue-700 border border-blue-200' : (
                                    $article->category === 'Technology' ? 'bg-purple-50 text-purple-700 border border-purple-200' : (
                                    $article->category === 'Facility' ? 'bg-amber-50 text-amber-700 border border-amber-200' : (
                                    $article->category === 'Community' ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'bg-slate-50 text-slate-700 border border-slate-200')))) 
                                }}">
                                    {{ $article->category }}
                                </span>
                            </td>

                            <!-- Published At Date -->
                            <td class="px-6 py-4.5 text-slate-500 font-semibold text-xs">
                                {{ $article->published_at->format('M d, Y') }}
                                <div class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $article->published_at->format('H:i') }} WIB</div>
                            </td>

                            <!-- Action Buttons -->
                            <td class="px-6 py-4.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Edit Link -->
                                    <a href="{{ route('admin.news.edit', $article->id) }}" 
                                       class="p-2 rounded-xl text-slate-500 hover:text-[#1E3A8A] hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-all"
                                       title="Edit Berita">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.news.destroy', $article->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-all cursor-pointer"
                                                title="Hapus Berita">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-700">Belum Ada Berita</h3>
                                        <p class="text-xs text-slate-400 mt-0.5">Mulai tambahkan artikel berita pertama Anda.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Automatically hide success alert after 3 seconds
    setTimeout(() => {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);
</script>
@endsection
