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
        $activeBuildingId = $request->input('building_id', $buildings->first()->id ?? null);
        
        $activeBuilding = Building::find($activeBuildingId);
        
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

        $space = SpaceAllocation::find($validated['space_allocation_id']);
        
        $space->update([
            'tenant_id' => $validated['tenant_id'],
            'lease_start' => $validated['lease_start'],
            'lease_end' => $validated['lease_end'],
            'rent_price' => $validated['rent_price'],
            'area_size' => $validated['area_size'],
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'],
        ]);

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
        
        $space->update([
            'tenant_id' => null,
            'status' => 'Kosong',
            'lease_start' => null,
            'lease_end' => null,
            'payment_status' => null,
        ]);

        return redirect()->back()->with('success', 'Unit ' . $unitNumber . ' berhasil dikosongkan.');
    }
}
