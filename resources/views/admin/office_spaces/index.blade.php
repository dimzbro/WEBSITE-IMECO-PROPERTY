@extends('layouts.admin')

@section('title', 'Kelola Available Spaces – Beltway Office Park')
@section('breadcrumb', 'Available Spaces')

@section('content')
<div class="space-y-6">

    <!-- Top Info Bar -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Manajemen Available Spaces</h2>
        <p class="text-xs text-slate-400 mt-0.5">Kelola daftar unit kantor kosong (showcase pemasaran) yang ditampilkan di landing page utama.</p>
    </div>

    <!-- Main Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Panel: Tambah Space Form (1 Col) -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4 sticky top-6">
                <h3 class="text-base font-extrabold text-slate-800 border-b border-slate-100 pb-3">
                    Tambah Available Space
                </h3>

                @if ($errors->any())
                    <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-xl">
                        <ul class="list-disc list-inside text-xs text-rose-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.office_spaces.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Tower -->
                    <div>
                        <label for="tower" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Tower *</label>
                        <select id="tower" name="tower" required
                                class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 bg-white outline-none transition-all">
                            <option value="" disabled selected>Pilih Tower...</option>
                            <option value="Tower A" {{ old('tower') === 'Tower A' ? 'selected' : '' }}>Tower A</option>
                            <option value="Tower B" {{ old('tower') === 'Tower B' ? 'selected' : '' }}>Tower B</option>
                            <option value="Tower C" {{ old('tower') === 'Tower C' ? 'selected' : '' }}>Tower C</option>
                        </select>
                    </div>

                    <!-- Lantai -->
                    <div>
                        <label for="floor" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Lantai *</label>
                        <select id="floor" name="floor" required
                                class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 bg-white outline-none transition-all">
                            <option value="" disabled selected>Pilih Lantai...</option>
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ old('floor') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Ukuran (Sqm) -->
                    <div>
                        <label for="sqm" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Luas Ruangan *</label>
                        <input type="text" id="sqm" name="sqm" value="{{ old('sqm') }}" required placeholder="Contoh: 250"
                               class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                        <span class="text-[10px] text-slate-400 mt-1 block">Dimensi "sqm" akan ditambahkan otomatis.</span>
                    </div>

                    <!-- Harga -->
                    <div>
                        <label for="price" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Harga Sewa *</label>
                        <input type="text" id="price" name="price" value="{{ old('price') }}" required placeholder="Contoh: IDR 185,000"
                               class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                        <span class="text-[10px] text-slate-400 mt-1 block">Sufiks "/sqm/mo" akan ditambahkan otomatis.</span>
                    </div>

                    <!-- Upload Gambar -->
                    <div>
                        <label for="image_file" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Upload File Gambar</label>
                        <input type="file" id="image_file" name="image_file" accept="image/*"
                               class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#1E3A8A] file:cursor-pointer hover:file:bg-blue-100 transition-all border border-slate-200 rounded-xl p-1 bg-slate-50/50">
                        <span class="text-[10px] text-slate-400 mt-1 block">Format: JPG, JPEG, PNG, WEBP. Maks: 2MB.</span>
                    </div>

                    <!-- Atau URL Gambar -->
                    <div>
                        <label for="image_url" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Atau URL Gambar Eksternal</label>
                        <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://images.unsplash.com/..."
                               class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/10 outline-none transition-all">
                        <span class="text-[10px] text-slate-400 mt-1 block">Tempel link gambar jika tidak ingin mengupload.</span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full py-3 rounded-xl font-bold text-sm bg-[#1E3A8A] text-white hover:bg-slate-900 shadow-md transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer mt-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Simpan Unit Space
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Panel: Grid & List Spaces (2 Cols) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-6 py-4 w-24">Gambar</th>
                                <th class="px-6 py-4">Lokasi & Gedung</th>
                                <th class="px-6 py-4">Ukuran / Harga</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($officeSpaces as $space)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    
                                    <!-- Image Thumbnail -->
                                    <td class="px-6 py-4">
                                        <div class="w-16 h-12 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0 flex items-center justify-center">
                                            @if($space->image)
                                                <img src="{{ $space->image }}" alt="Thumbnail" class="max-w-full max-h-full object-contain">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">No Image</div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Tower & Floor -->
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="font-bold text-slate-800">{{ $space->tower }}</div>
                                            <div class="text-[11px] text-slate-400 mt-0.5">{{ $space->floor }}</div>
                                        </div>
                                    </td>

                                    <!-- Size & Price -->
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="text-xs font-bold text-[#1E3A8A]">{{ $space->sqm }}</div>
                                            <div class="text-[11px] text-slate-500 mt-0.5">{{ $space->price }}</div>
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ 
                                            $space->status === 'Available' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (
                                            $space->status === 'Limited' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200')
                                        }}">
                                            {{ $space->status }}
                                        </span>
                                    </td>

                                    <!-- Action -->
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.office_spaces.destroy', $space->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus space {{ $space->tower }} – {{ $space->floor }} ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-all cursor-pointer"
                                                    title="Hapus Space">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-bold text-slate-700">Belum Ada Available Space</h3>
                                                <p class="text-xs text-slate-400 mt-0.5">Mulai dengan menambahkan unit space kosong pertama Anda.</p>
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
    </div>
</div>
@endsection
