@extends('layouts.admin')

@section('title', 'Tambah Berita Baru – Beltway Office Park')
@section('breadcrumb', 'Tambah Berita')

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
        <h2 class="text-xl font-extrabold text-slate-800 mt-2">Buat Berita Baru</h2>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Masukkan judul berita yang menarik..."
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                        </div>

                        <!-- Ringkasan (Excerpt) -->
                        <div>
                            <label for="excerpt" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ringkasan (Excerpt) *</label>
                            <textarea id="excerpt" name="excerpt" required rows="3" maxlength="500" placeholder="Ringkasan singkat maksimal 500 karakter untuk ditampilkan pada kartu berita di halaman utama..."
                                      class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all"></textarea>
                        </div>

                        <!-- Konten Lengkap -->
                        <div>
                            <label for="content" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Konten Lengkap Berita (Opsional)</label>
                            <textarea id="content" name="content" rows="10" placeholder="Tulis isi lengkap berita di sini..."
                                      class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Upload Gambar -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-base font-extrabold text-slate-800 border-b border-slate-100 pb-3">
                        Media & Gambar
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Pilihan Gambar: Upload vs URL -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Upload File -->
                            <div>
                                <label for="image_file" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Upload File Gambar</label>
                                <input type="file" id="image_file" name="image_file" accept="image/*"
                                       class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#1E3A8A] file:cursor-pointer hover:file:bg-blue-100 transition-all border border-slate-200 rounded-xl p-1 bg-slate-50/50">
                                <span class="text-[10px] text-slate-400 mt-1.5 block">Format: JPG, JPEG, PNG, WEBP. Maks: 2MB.</span>
                            </div>

                            <!-- Link URL Gambar -->
                            <div>
                                <label for="image_url" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Atau URL Gambar Eksternal</label>
                                <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://images.unsplash.com/photo-..."
                                       class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                                <span class="text-[10px] text-slate-400 mt-1.5 block">Tempel link gambar jika ingin menggunakan gambar eksternal (Unsplash, dll).</span>
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
                                <option value="News" {{ old('category') === 'News' ? 'selected' : '' }}>News</option>
                                <option value="Achievement" {{ old('category') === 'Achievement' ? 'selected' : '' }}>Achievement</option>
                                <option value="Technology" {{ old('category') === 'Technology' ? 'selected' : '' }}>Technology</option>
                                <option value="Facility" {{ old('category') === 'Facility' ? 'selected' : '' }}>Facility</option>
                                <option value="Community" {{ old('category') === 'Community' ? 'selected' : '' }}>Community</option>
                                <option value="Tips" {{ old('category') === 'Tips' ? 'selected' : '' }}>Tips</option>
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
                        Simpan Berita
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
