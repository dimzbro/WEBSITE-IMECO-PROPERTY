<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Building;
use App\Models\SpaceAllocation;
use Carbon\Carbon;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $buildingFilter = $request->input('building_id');

        $query = Tenant::with(['spaceAllocations.building']);

        // Filter by Search text
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'LIKE', "%{$search}%")
                  ->orWhere('pic_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('business_sector', 'LIKE', "%{$search}%")
                  ->orWhereHas('spaceAllocations', function($sub) use ($search) {
                      $sub->where('unit_number', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by Contract Status (stored on space allocations)
        if ($statusFilter) {
            $query->whereHas('spaceAllocations', function($q) use ($statusFilter) {
                $q->where('status', $statusFilter);
            });
        }

        // Filter by Building
        if ($buildingFilter) {
            $query->whereHas('spaceAllocations', function($q) use ($buildingFilter) {
                $q->where('building_id', $buildingFilter);
            });
        }

        $tenants = $query->latest()->paginate(10)->withQueryString();
        $buildings = Building::all();

        return view('admin.tenants.index', compact('tenants', 'buildings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $buildings = Building::all();
        
        // Fetch all vacant units for allocation dropdown
        $vacantUnits = SpaceAllocation::where('status', 'Kosong')
            ->orderBy('unit_number', 'asc')
            ->get();

        return view('admin.tenants.create', compact('buildings', 'vacantUnits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Profile fields
            'company_name' => 'required|string|max:255',
            'npwp' => 'required|string|max:100',
            'pic_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'emergency_contact' => 'required|string|max:255',
            'business_sector' => 'nullable|string|max:100',

            // Allocation fields
            'space_allocation_id' => 'nullable|exists:space_allocations,id',
            'area_size' => 'nullable|integer|min:1',
            'rent_price' => 'nullable|integer|min:0',
            'lease_start' => 'nullable|date',
            'lease_end' => 'nullable|date|after_or_equal:lease_start',
            'contract_status' => 'nullable|string',
            'payment_status' => 'nullable|string|in:Lunas,Menunggu,Tertunggak',
        ]);

        // Create the Tenant
        $tenant = Tenant::create([
            'company_name' => $validated['company_name'],
            'npwp' => $validated['npwp'],
            'pic_name' => $validated['pic_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'emergency_contact' => $validated['emergency_contact'],
            'business_sector' => $validated['business_sector'] ?? 'Umum',
        ]);

        // Allocate Space
        if (!empty($validated['space_allocation_id'])) {
            $space = SpaceAllocation::find($validated['space_allocation_id']);
            
            // Calculate status dynamically based on lease dates
            $status = 'Kosong';
            if (!empty($validated['lease_end'])) {
                $today = \Carbon\Carbon::today();
                $end = \Carbon\Carbon::parse($validated['lease_end']);
                if ($end->isBefore($today)) {
                    $status = 'Kontrak Habis';
                } else {
                    $diff = $today->diffInDays($end, false);
                    if ($diff <= 30) {
                        $status = 'Hampir Berakhir';
                    } elseif ($diff <= 180) {
                        $status = 'Kontrak Mendekati Berakhir';
                    } else {
                        $status = 'Kontrak Aktif';
                    }
                }
            }

            $space->update([
                'tenant_id' => $tenant->id,
                'area_size' => $validated['area_size'] ?? $space->area_size,
                'rent_price' => $validated['rent_price'] ?? $space->rent_price,
                'lease_start' => $validated['lease_start'],
                'lease_end' => $validated['lease_end'],
                'status' => $status,
                'payment_status' => $validated['payment_status'] ?? 'Menunggu',
            ]);
        }

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant ' . $tenant->company_name . ' berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        $tenant->load(['spaceAllocations.building']);
        
        $logs = [];
        

        
        // 3. Registration log entry
        $logs[] = [
            'date' => $tenant->created_at ? $tenant->created_at->format('d M Y, H:i') : '—',
            'timestamp' => $tenant->created_at ? $tenant->created_at->timestamp : 0,
            'title' => 'Registrasi Tenant Baru & Alokasi Ruang',
            'status' => 'Selesai',
            'badge' => 'success',
            'pic' => 'Admin BOP'
        ];
        
        // Sort logs by timestamp descending
        usort($logs, function ($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        return view('admin.tenants.show', compact('tenant', 'logs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        $tenant->load(['spaceAllocations']);
        $buildings = Building::all();
        
        // Fetch current allocation if any
        $currentAllocation = $tenant->spaceAllocations->first();
        
        // Fetch all vacant units plus the current tenant's unit for allocation selection
        $vacantUnits = SpaceAllocation::where('status', 'Kosong')
            ->orWhere('tenant_id', $tenant->id)
            ->orderBy('unit_number', 'asc')
            ->get();

        return view('admin.tenants.edit', compact('tenant', 'buildings', 'vacantUnits', 'currentAllocation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            // Profile fields
            'company_name' => 'required|string|max:255',
            'npwp' => 'required|string|max:100',
            'pic_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'emergency_contact' => 'required|string|max:255',
            'business_sector' => 'nullable|string|max:100',

            // Allocation fields
            'space_allocation_id' => 'nullable|exists:space_allocations,id',
            'area_size' => 'nullable|integer|min:1',
            'rent_price' => 'nullable|integer|min:0',
            'lease_start' => 'nullable|date',
            'lease_end' => 'nullable|date|after_or_equal:lease_start',
            'contract_status' => 'nullable|string',
            'payment_status' => 'nullable|string|in:Lunas,Menunggu,Tertunggak',
        ]);

        // Update profile
        $tenant->update([
            'company_name' => $validated['company_name'],
            'npwp' => $validated['npwp'],
            'pic_name' => $validated['pic_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'emergency_contact' => $validated['emergency_contact'],
            'business_sector' => $validated['business_sector'] ?? 'Umum',
        ]);

        // Release all current space allocations first to avoid duplicates (except the one being applied/updated)
        $currentAllocations = SpaceAllocation::where('tenant_id', $tenant->id)->get();
        foreach ($currentAllocations as $alloc) {
            if ($alloc->id == ($validated['space_allocation_id'] ?? null)) {
                continue; // Skip releasing this allocation because it's being updated/re-applied
            }
            
            $otherAllocationsCount = SpaceAllocation::where('building_id', $alloc->building_id)
                ->where('floor_number', $alloc->floor_number)
                ->where('unit_number', $alloc->unit_number)
                ->where('id', '!=', $alloc->id)
                ->count();
            
            if ($otherAllocationsCount > 0) {
                $alloc->delete();
            } else {
                $alloc->update([
                    'tenant_id' => null,
                    'status' => 'Kosong',
                    'lease_start' => null,
                    'lease_end' => null,
                    'payment_status' => null,
                ]);
            }
        }

        // Apply new allocation if selected
        if (!empty($validated['space_allocation_id'])) {
            $space = SpaceAllocation::find($validated['space_allocation_id']);
            if ($space) {
                // Calculate status dynamically based on lease dates
                $status = 'Kosong';
                if (!empty($validated['lease_end'])) {
                    $today = \Carbon\Carbon::today();
                    $end = \Carbon\Carbon::parse($validated['lease_end']);
                    if ($end->isBefore($today)) {
                        $status = 'Kontrak Habis';
                    } else {
                        $diff = $today->diffInDays($end, false);
                        if ($diff <= 30) {
                            $status = 'Hampir Berakhir';
                        } elseif ($diff <= 180) {
                            $status = 'Kontrak Mendekati Berakhir';
                        } else {
                            $status = 'Kontrak Aktif';
                        }
                    }
                }

                $space->update([
                    'tenant_id' => $tenant->id,
                    'area_size' => $validated['area_size'] ?? $space->area_size,
                    'rent_price' => $validated['rent_price'] ?? $space->rent_price,
                    'lease_start' => $validated['lease_start'],
                    'lease_end' => $validated['lease_end'],
                    'status' => $status,
                    'payment_status' => $validated['payment_status'] ?? 'Menunggu',
                ]);
            }
        }

        return redirect()->route('admin.tenants.show', $tenant->id)->with('success', 'Detail Tenant ' . $tenant->company_name . ' berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        // 1. Free up all space allocations occupied by this tenant
        $currentAllocations = SpaceAllocation::where('tenant_id', $tenant->id)->get();
        foreach ($currentAllocations as $alloc) {
            $otherAllocationsCount = SpaceAllocation::where('building_id', $alloc->building_id)
                ->where('floor_number', $alloc->floor_number)
                ->where('unit_number', $alloc->unit_number)
                ->where('id', '!=', $alloc->id)
                ->count();
            
            if ($otherAllocationsCount > 0) {
                $alloc->delete();
            } else {
                $alloc->update([
                    'tenant_id' => null,
                    'status' => 'Kosong',
                    'lease_start' => null,
                    'lease_end' => null,
                    'payment_status' => null,
                ]);
            }
        }

        // 3. Delete tenant
        $companyName = $tenant->company_name;
        $tenant->delete();

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant ' . $companyName . ' dan alokasi unitnya berhasil dihapus dari sistem.');
    }
}
