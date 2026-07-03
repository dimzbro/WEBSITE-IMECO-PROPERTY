<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Tenant;
use App\Models\SpaceAllocation;

class BuildingController extends Controller
{
    /**
     * Display building overview and floor map layout.
     */
    public function index(Request $request)
    {
        $buildings = Building::all();
        
        // Active building ID (default to first building)
        $activeBuildingId = $request->input('building_id');
        
        $activeBuilding = Building::find($activeBuildingId);
        if (!$activeBuilding && $buildings->isNotEmpty()) {
            $activeBuilding = $buildings->first();
        }
        
        $units = [];
        if ($activeBuilding) {
            // Group allocations by floor number descending (L8 at the top, down to L1)
            $units = SpaceAllocation::with('tenant')
                ->where('building_id', $activeBuilding->id)
                ->orderBy('floor_number', 'desc')
                ->orderBy('unit_number', 'asc')
                ->get()
                ->groupBy('floor_number');
        }

        // Fetch tenants list for quick allocation dropdown
        $tenants = Tenant::orderBy('company_name', 'asc')->get();

        return view('admin.buildings.index', compact('buildings', 'activeBuilding', 'units', 'tenants'));
    }

    /**
     * Allocate a vacant space unit to an existing tenant.
     */
    public function allocate(Request $request)
    {
        $validated = $request->validate([
            'space_allocation_id' => 'required|exists:space_allocations,id',
            'tenant_id' => 'required|exists:tenants,id',
            'lease_start' => 'required|date',
            'lease_end' => 'required|date|after_or_equal:lease_start',
            'rent_price' => 'required|integer|min:0',
            'area_size' => 'required|integer|min:1',
            'status' => 'required|string|in:Terisi,Hampir Berakhir,Berakhir',
            'payment_status' => 'required|string|in:Lunas,Menunggu,Tertunggak',
        ]);

        $space = SpaceAllocation::with('building')->findOrFail($validated['space_allocation_id']);
        
        $buildingName = $space->building->name;
        $unitName = $space->unit_number;

        // Fetch currently active allocations in this specific unit/floor/building
        $activeAllocations = SpaceAllocation::where('building_id', $space->building_id)
            ->where('floor_number', $space->floor_number)
            ->where('unit_number', $space->unit_number)
            ->where('status', '!=', 'Kosong');

        if ($buildingName === 'Open Yard') {
            // Open Yard limit: total occupied space <= 1059.72 sqm
            $currentOccupiedArea = $activeAllocations->sum('area_size');
            if (($currentOccupiedArea + $validated['area_size']) > 1059.72) {
                return redirect()->back()->withErrors([
                    'area_size' => 'Gagal mengalokasikan. Sisa area Open Yard yang tersedia hanya ' . (1059.72 - $currentOccupiedArea) . ' m².'
                ]);
            }
        } else {
            // Count capacity constraints
            $maxCapacity = 1;
            if (in_array($buildingName, ['Gedung A', 'Gedung B', 'Gedung C', 'Annex', 'Annex 1', 'Workshop'])) {
                $maxCapacity = 3;
            } elseif ($buildingName === 'Canteen') {
                if ($unitName === 'Indoor Lantai 1') {
                    $maxCapacity = 8;
                } elseif ($unitName === 'Outdoor') {
                    $maxCapacity = 4;
                } elseif ($unitName === 'Indoor Lantai 2') {
                    $maxCapacity = 3;
                }
            }

            // Only check limit if adding a NEW occupant (the selected space is already occupied and we want to clone it)
            if ($space->status !== 'Kosong') {
                $currentOccupantsCount = $activeAllocations->count();
                if ($currentOccupantsCount >= $maxCapacity) {
                    return redirect()->back()->withErrors([
                        'tenant_id' => 'Kapasitas maksimal unit ini (' . $maxCapacity . ' tenant) sudah terpenuhi.'
                    ]);
                }
            }
        }
        
        if ($space->status !== 'Kosong') {
            // Space already occupied (multi-tenant allocation), create a new allocation row
            $space = SpaceAllocation::create([
                'building_id' => $space->building_id,
                'floor_number' => $space->floor_number,
                'unit_number' => $space->unit_number,
                'tenant_id' => $validated['tenant_id'],
                'lease_start' => $validated['lease_start'],
                'lease_end' => $validated['lease_end'],
                'rent_price' => $validated['rent_price'],
                'area_size' => $validated['area_size'],
                'status' => $validated['status'],
                'payment_status' => $validated['payment_status'],
            ]);
        } else {
            // Re-allocate the vacant allocation row
            $space->update([
                'tenant_id' => $validated['tenant_id'],
                'lease_start' => $validated['lease_start'],
                'lease_end' => $validated['lease_end'],
                'rent_price' => $validated['rent_price'],
                'area_size' => $validated['area_size'],
                'status' => $validated['status'],
                'payment_status' => $validated['payment_status'],
            ]);
        }

        $tenant = Tenant::find($validated['tenant_id']);

        return redirect()->back()->with('success', 'Unit ' . $space->unit_number . ' berhasil dialokasikan ke ' . $tenant->company_name);
    }

    /**
     * Release a tenant from a unit (set it back to Kosong).
     */
    public function release($id)
    {
        $space = SpaceAllocation::findOrFail($id);
        $unitNumber = $space->unit_number;
        
        // Check if there are other allocations for the same unit/floor/building
        $otherAllocationsCount = SpaceAllocation::where('building_id', $space->building_id)
            ->where('floor_number', $space->floor_number)
            ->where('unit_number', $space->unit_number)
            ->where('id', '!=', $space->id)
            ->count();

        if ($otherAllocationsCount > 0) {
            // If there are multiple allocations in this zone, delete this row to clean up
            $space->delete();
        } else {
            // Otherwise, revert it to vacant
            $space->update([
                'tenant_id' => null,
                'status' => 'Kosong',
                'lease_start' => null,
                'lease_end' => null,
                'payment_status' => null,
            ]);
        }

        return redirect()->back()->with('success', 'Unit ' . $unitNumber . ' berhasil dikosongkan.');
    }
}
