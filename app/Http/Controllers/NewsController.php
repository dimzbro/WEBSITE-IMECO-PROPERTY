<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::orderBy('published_at', 'desc')->get();
        return view('admin.news.index', compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'excerpt'      => 'required|string|max:500',
            'content'      => 'nullable|string',
            'image_file'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_url'    => 'nullable|url|max:1000',
        ], [
            'title.required'        => 'Judul berita wajib diisi.',
            'category.required'     => 'Kategori berita wajib diisi.',
            'excerpt.required'      => 'Ringkasan berita wajib diisi.',
            'image_file.image'      => 'File harus berupa gambar.',
            'image_file.max'        => 'Ukuran gambar maksimal adalah 2MB.',
            'image_url.url'         => 'Format URL gambar tidak valid.',
        ]);

        $image = null;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('news', 'public');
            $image = $path;
        } elseif ($request->filled('image_url')) {
            $image = $request->input('image_url');
        }

        News::create([
            'title'        => $validated['title'],
            'category'     => $validated['category'],
            'excerpt'      => $validated['excerpt'],
            'content'      => $validated['content'],
            'published_at' => now(),
            'image'        => $image,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'excerpt'      => 'required|string|max:500',
            'content'      => 'nullable|string',
            'image_file'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_url'    => 'nullable|url|max:1000',
        ], [
            'title.required'        => 'Judul berita wajib diisi.',
            'category.required'     => 'Kategori berita wajib diisi.',
            'excerpt.required'      => 'Ringkasan berita wajib diisi.',
            'image_file.image'      => 'File harus berupa gambar.',
            'image_file.max'        => 'Ukuran gambar maksimal adalah 2MB.',
            'image_url.url'         => 'Format URL gambar tidak valid.',
        ]);

        $image = $news->image;

        if ($request->hasFile('image_file')) {
            // Delete old file if it exists locally
            if ($news->image && !filter_var($news->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($news->image);
            }
            $path = $request->file('image_file')->store('news', 'public');
            $image = $path;
        } elseif ($request->filled('image_url')) {
            // Delete old file if it exists locally
            if ($news->image && !filter_var($news->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($news->image);
            }
            $image = $request->input('image_url');
        }

        $news->update([
            'title'        => $validated['title'],
            'category'     => $validated['category'],
            'excerpt'      => $validated['excerpt'],
            'content'      => $validated['content'],
            'image'        => $image,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        // Delete local image file if it exists
        if ($news->image && !filter_var($news->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }
}
