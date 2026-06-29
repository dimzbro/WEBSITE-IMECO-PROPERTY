@extends('layouts.admin')

@section('title', 'Edit Berita – Beltway Office Park')
@section('breadcrumb', 'Edit Berita')

@section('content')
<div class="space-y-6">

    <!-- Header navigation -->
    <div>
        <a href="{{ route('admin.news.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Batal & Kembali ke Daftar
        </a>
        <h2 class="text-xl font-extrabold text-slate-800 mt-2">Edit Berita</h2>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
            
            <!-- Left Panel: Form Input (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Section 1: Informasi Utama -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-base font-extrabold text-slate-800 border-b border-slate-100 pb-3">
                        Konten Berita
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Judul Berita -->
                        <div>
                            <label for="title" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Berita *</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $news->title) }}" required placeholder="Masukkan judul berita..."
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                        </div>

                        <!-- Ringkasan (Excerpt) -->
                        <div>
                            <label for="excerpt" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ringkasan (Excerpt) *</label>
                            <textarea id="excerpt" name="excerpt" required rows="3" maxlength="500" placeholder="Ringkasan singkat..."
                                      class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">{{ old('excerpt', $news->excerpt) }}</textarea>
                        </div>

                        <!-- Konten Lengkap -->
                        <div>
                            <label for="content" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Konten Lengkap Berita (Opsional)</label>
                            <textarea id="content" name="content" rows="10" placeholder="Tulis isi lengkap berita..."
                                      class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">{{ old('content', $news->content) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Media & Gambar -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-base font-extrabold text-slate-800 border-b border-slate-100 pb-3">
                        Media & Gambar
                    </h3>

                    @if($news->image)
                    <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="w-24 h-16 rounded-lg overflow-hidden border border-slate-300 bg-white flex-shrink-0">
                            @if(filter_var($news->image, FILTER_VALIDATE_URL))
                                <img src="{{ $news->image }}" alt="Current Image" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('storage/' . $news->image) }}" alt="Current Image" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-700">Gambar Saat Ini</div>
                            <div class="text-[10px] text-slate-400 mt-1 break-all">{{ $news->image }}</div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Upload File -->
                            <div>
                                <label for="image_file" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ganti Gambar (Upload File)</label>
                                <input type="file" id="image_file" name="image_file" accept="image/*"
                                       class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#1E3A8A] file:cursor-pointer hover:file:bg-blue-100 transition-all border border-slate-200 rounded-xl p-1 bg-slate-50/50">
                                <span class="text-[10px] text-slate-400 mt-1.5 block">Format: JPG, JPEG, PNG, WEBP. Maks: 2MB.</span>
                            </div>

                            <!-- Link URL Gambar -->
                            <div>
                                <label for="image_url" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Atau Ganti Gambar via URL</label>
                                <input type="url" id="image_url" name="image_url" value="{{ old('image_url', filter_var($news->image, FILTER_VALIDATE_URL) ? $news->image : '') }}" placeholder="https://images.unsplash.com/photo-..."
                                       class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                                <span class="text-[10px] text-slate-400 mt-1.5 block">Isi jika ingin beralih menggunakan URL gambar eksternal.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Metadata & Action (1 Col) -->
            <div class="space-y-6">
                <!-- Pengaturan Publikasi -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-base font-extrabold text-slate-800 border-b border-slate-100 pb-3">
                        Metadata Publikasi
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Kategori -->
                        <div>
                            <label for="category" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori *</label>
                            <select id="category" name="category" required
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] bg-white outline-none">
                                <option value="News" {{ old('category', $news->category) === 'News' ? 'selected' : '' }}>News</option>
                                <option value="Achievement" {{ old('category', $news->category) === 'Achievement' ? 'selected' : '' }}>Achievement</option>
                                <option value="Technology" {{ old('category', $news->category) === 'Technology' ? 'selected' : '' }}>Technology</option>
                                <option value="Facility" {{ old('category', $news->category) === 'Facility' ? 'selected' : '' }}>Facility</option>
                                <option value="Community" {{ old('category', $news->category) === 'Community' ? 'selected' : '' }}>Community</option>
                                <option value="Tips" {{ old('category', $news->category) === 'Tips' ? 'selected' : '' }}>Tips</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Action Button Card -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                    <button type="submit" 
                            class="w-full py-3.5 rounded-xl font-bold text-sm bg-[#1E3A8A] text-white hover:bg-slate-900 shadow-lg shadow-blue-900/10 hover:shadow-none transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        Perbarui Berita
                    </button>
                    
                    <a href="{{ route('admin.news.index') }}" 
                       class="block w-full py-3 text-center rounded-xl font-bold text-xs bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
