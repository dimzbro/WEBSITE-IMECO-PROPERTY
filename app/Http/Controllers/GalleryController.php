<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleryItems = Gallery::orderBy('created_at', 'desc')->paginate(12);
        return view('admin.gallery.index', compact('galleryItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'span' => 'required|string|in:normal,large',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120', // supports all formats up to 5MB
            'image_url' => 'nullable|url|max:2048',
        ], [
            'span.required' => 'Ukuran layout wajib dipilih.',
            'span.in' => 'Ukuran layout tidak valid.',
            'image_file.image' => 'File harus berupa gambar.',
            'image_file.mimes' => 'Format gambar harus jpeg, png, jpg, gif, svg, atau webp.',
            'image_file.max' => 'Ukuran gambar maksimal adalah 5MB.',
            'image_url.url' => 'Format URL gambar tidak valid.',
        ]);

        $image = null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('gallery', 'public');
            $image = asset('storage/' . $path);
        } elseif ($request->filled('image_url')) {
            $image = $request->input('image_url');
        } else {
            return redirect()->back()
                ->withErrors(['image_file' => 'Wajib mengunggah file gambar atau memasukkan URL gambar eksternal.'])
                ->withInput();
        }

        Gallery::create([
            'title' => $validated['title'],
            'span' => $validated['span'],
            'image' => $image,
        ]);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Foto galeri berhasil ditambahkan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        // If local storage asset, delete it
        if (str_contains($gallery->image, asset('storage/'))) {
            $relativePath = str_replace(asset('storage/'), '', $gallery->image);
            Storage::disk('public')->delete($relativePath);
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Foto galeri berhasil dihapus!');
    }
}
