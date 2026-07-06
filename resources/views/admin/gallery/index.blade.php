@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Gallery Management</h1>
            <p class="text-sm text-slate-500 mt-1">Unggah, kelola, dan atur visual galeri foto yang ditampilkan di halaman utama.</p>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Upload Form -->
        <div class="lg:col-span-1 bg-white rounded-2xl border border-slate-100 shadow-sm p-6 self-start">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Tambah Foto Baru
            </h3>

            @if($errors->any())
                <div class="mb-4 p-4 rounded-xl bg-rose-50 text-rose-800 text-xs space-y-1 font-bold">
                    @foreach($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Title -->
                <div>
                    <label for="title" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Foto (Opsional)</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Grand Lobby"
                           class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                </div>

                <!-- Span Size (Layout Grid) -->
                <div>
                    <label for="span" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ukuran Grid Layout *</label>
                    <select name="span" id="span" required
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none bg-white transition-all">
                        <option value="normal" {{ old('span') == 'normal' ? 'selected' : '' }}>Normal (Persegi 1x1)</option>
                        <option value="large" {{ old('span') == 'large' ? 'selected' : '' }}>Lebar (Landscape 2x1)</option>
                    </select>
                    <span class="text-[10px] text-slate-400 mt-1 block">Tentukan proporsi rasio foto di halaman landing page.</span>
                </div>

                <!-- Upload File Gambar -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Upload File Gambar</label>
                    <input type="file" id="image_file" name="image_file" accept="image/*"
                           class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#1E3A8A] file:cursor-pointer hover:file:bg-blue-100 transition-all border border-slate-200 rounded-xl p-1 bg-slate-50/50">
                    <span class="text-[10px] text-slate-400 mt-1 block">Format: JPG, JPEG, PNG, WEBP, GIF, SVG. Maks: 5MB.</span>
                </div>

                <!-- Atau URL Gambar -->
                <div>
                    <label for="image_url" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Atau URL Gambar Eksternal</label>
                    <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://images.unsplash.com/..."
                           class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                    <span class="text-[10px] text-slate-400 mt-1 block">Tempel link jika tidak ingin mengupload file lokal.</span>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full py-3 rounded-xl font-bold text-sm bg-[#1E3A8A] text-white hover:bg-slate-900 shadow-md transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer mt-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Simpan Foto Galeri
                </button>
            </form>
        </div>

        <!-- Right: Gallery Grid -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                    </svg>
                    Koleksi Foto Galeri
                </h3>

                @if($galleryItems->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-slate-50 text-slate-350 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-400">Belum ada foto galeri.</p>
                        <p class="text-xs text-slate-450 mt-1">Unggah foto baru menggunakan form di sebelah kiri.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($galleryItems as $item)
                            <div class="group relative bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden hover:shadow-md transition-all duration-300">
                                
                                <!-- Image wrapper -->
                                <div class="w-full h-40 bg-slate-100 overflow-hidden flex items-center justify-center relative p-3">
                                    <img src="{{ $item->image }}" alt="{{ $item->title ?? 'Photo' }}" class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                </div>

                                <!-- Details -->
                                <div class="p-3 flex items-center justify-between gap-2">
                                    <div class="min-w-0">
                                        <div class="font-bold text-xs text-slate-800 truncate" title="{{ $item->title ?? 'Tidak Ada Judul' }}">
                                            {{ $item->title ?? 'Tidak Ada Judul' }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">
                                            {{ $item->created_at ? $item->created_at->format('d M Y') : '—' }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        @if($item->span === 'large')
                                            <span class="px-2 py-0.5 rounded-lg text-[9px] font-extrabold bg-blue-50 text-[#1E3A8A] border border-blue-100">Lebar</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-lg text-[9px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200">Normal</span>
                                        @endif

                                        <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto galeri ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 shadow-sm transition-all cursor-pointer"
                                                    title="Hapus Foto">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($galleryItems->hasPages())
                        <div class="mt-6 pt-4 border-t border-slate-100">
                            {{ $galleryItems->links() }}
                        </div>
                    @endif
                @endif

            </div>
        </div>

    </div>
</div>
@endsection
