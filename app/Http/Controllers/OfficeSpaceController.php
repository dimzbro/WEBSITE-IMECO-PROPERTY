<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\OfficeSpace;
use Illuminate\Support\Str;

class OfficeSpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $officeSpaces = OfficeSpace::orderBy('created_at', 'desc')->get();
        $buildings = \App\Models\Building::with('spaceAllocations')->get();
        return view('admin.office_spaces.index', compact('officeSpaces', 'buildings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tower' => 'required|string|max:255',
            'floor' => 'required|string|max:255',
            'sqm' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url' => 'nullable|url|max:2048',
        ], [
            'tower.required' => 'Nama Tower wajib diisi.',
            'floor.required' => 'Lantai wajib diisi.',
            'sqm.required' => 'Ukuran ruangan (Sqm) wajib diisi.',
            'price.required' => 'Harga sewa wajib diisi.',
            'image_file.image' => 'File harus berupa gambar.',
            'image_file.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'image_file.max' => 'Ukuran gambar maksimal adalah 2MB.',
            'image_url.url' => 'Format URL gambar tidak valid.',
        ]);

        // Format sqm (e.g. 250 -> "250 sqm", 1200 -> "1.200 sqm")
        if (is_numeric($validated['sqm'])) {
            $validated['sqm'] = number_format(intval($validated['sqm']), 0, ',', '.') . ' sqm';
        } else {
            if (!Str::endsWith(Str::lower($validated['sqm']), 'sqm')) {
                $validated['sqm'] .= ' sqm';
            }
        }

        // Format price (if pure numeric e.g. 200000 -> "IDR 200.000/month", otherwise keep text and append suffix)
        if (is_numeric($validated['price'])) {
            $validated['price'] = 'IDR ' . number_format(intval($validated['price']), 0, ',', '.') . '/month';
        } else {
            if (!Str::endsWith($validated['price'], '/month')) {
                $validated['price'] .= '/month';
            }
        }

        // Set status defaults to Available
        $validated['status'] = 'Available';

        // Auto-generate filter based on tower (e.g. "Tower A" -> "tower-a")
        $validated['filter'] = Str::slug($validated['tower']);

        // Handle image upload vs external URL vs default
        $image = null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('office-spaces', 'public');
            $image = asset('storage/' . $path);
        } elseif ($request->filled('image_url')) {
            $image = $request->input('image_url');
        } else {
            $image = 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=700&h=460&fit=crop&auto=format';
        }
        $validated['image'] = $image;

        OfficeSpace::create($validated);

        return redirect()->route('admin.office_spaces.index')
            ->with('success', 'Available Space berhasil ditambahkan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfficeSpace $officeSpace)
    {
        $spaceDetails = $officeSpace->tower . ' – ' . $officeSpace->floor;
        $officeSpace->delete();

        return redirect()->route('admin.office_spaces.index')
            ->with('success', 'Available Space ' . $spaceDetails . ' berhasil dihapus!');
    }
}
