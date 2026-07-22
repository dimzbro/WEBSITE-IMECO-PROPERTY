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
            'image_file' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'tower.required' => 'Nama Tower wajib diisi.',
            'floor.required' => 'Lantai wajib diisi.',
            'sqm.required' => 'Ukuran ruangan (Sqm) wajib diisi.',
            'price.required' => 'Harga sewa wajib diisi.',
            'image_file.required' => 'Upload file gambar wajib diisi.',
            'image_file.image' => 'File harus berupa gambar.',
            'image_file.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'image_file.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        // Format sqm (e.g. 250 -> "250 sqm", 1200 -> "1.200 sqm")
        if (is_numeric($validated['sqm'])) {
            $validated['sqm'] = number_format(intval($validated['sqm']), 0, ',', '.') . ' sqm';
        } else {
            if (!Str::endsWith(Str::lower($validated['sqm']), 'sqm')) {
                $validated['sqm'] .= ' sqm';
            }
        }

        // Format price (strip any existing suffix starting with '/' to prevent duplication)
        $basePrice = preg_replace('#/.*$#', '', trim($validated['price']));
        if (is_numeric($basePrice)) {
            $validated['price'] = 'IDR ' . number_format(intval($basePrice), 0, ',', '.') . '/sqm/month';
        } else {
            $validated['price'] = $basePrice . '/sqm/month';
        }

        // Set status defaults to Available
        $validated['status'] = 'Available';

        // Auto-generate filter based on tower (e.g. "Tower A" -> "tower-a")
        $validated['filter'] = Str::slug($validated['tower']);

        // Handle image upload
        $path = $request->file('image_file')->store('office-spaces', 'public');
        $validated['image'] = asset('storage/' . $path);

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
