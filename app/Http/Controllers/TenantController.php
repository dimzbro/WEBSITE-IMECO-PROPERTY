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
            'contract_status' => 'nullable|string|in:Terisi,Hampir Berakhir,Berakhir,Kosong',
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
            $space->update([
                'tenant_id' => $tenant->id,
                'area_size' => $validated['area_size'] ?? $space->area_size,
                'rent_price' => $validated['rent_price'] ?? $space->rent_price,
                'lease_start' => $validated['lease_start'],
                'lease_end' => $validated['lease_end'],
                'status' => $validated['contract_status'] ?? 'Terisi',
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
        
        // Simulate logs/receipts/history for a rich layout representation
        $logs = [
            [
                'date' => Carbon::now()->subDays(2)->format('d M Y'),
                'title' => 'Tagihan sewa bulan ini lunas terbayar',
                'status' => 'Selesai',
                'badge' => 'success',
                'pic' => 'Sistem Finansial'
            ],
            [
                'date' => Carbon::now()->subDays(15)->format('d M Y'),
                'title' => 'Inspeksi berkala kelistrikan unit & AC',
                'status' => 'Selesai',
                'badge' => 'success',
                'pic' => 'Tim Maintenance'
            ],
            [
                'date' => Carbon::now()->subMonths(3)->format('d M Y'),
                'title' => 'Pembaruan detail PIC kontak darurat',
                'status' => 'Selesai',
                'badge' => 'success',
                'pic' => 'Admin BOP'
            ],
            [
                'date' => Carbon::parse($tenant->created_at)->format('d M Y'),
                'title' => 'Registrasi Tenant Baru & Alokasi Ruang',
                'status' => 'Selesai',
                'badge' => 'success',
                'pic' => 'Admin BOP'
            ]
        ];

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
            'contract_status' => 'nullable|string|in:Terisi,Hampir Berakhir,Berakhir,Kosong',
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

        // Release all current space allocations first to avoid duplicates
        $currentAllocations = SpaceAllocation::where('tenant_id', $tenant->id)->get();
        foreach ($currentAllocations as $alloc) {
            $alloc->update([
                'tenant_id' => null,
                'status' => 'Kosong',
                'lease_start' => null,
                'lease_end' => null,
                'payment_status' => null,
            ]);
        }

        // Apply new allocation if selected
        if (!empty($validated['space_allocation_id'])) {
            $space = SpaceAllocation::find($validated['space_allocation_id']);
            $space->update([
                'tenant_id' => $tenant->id,
                'area_size' => $validated['area_size'] ?? $space->area_size,
                'rent_price' => $validated['rent_price'] ?? $space->rent_price,
                'lease_start' => $validated['lease_start'],
                'lease_end' => $validated['lease_end'],
                'status' => $validated['contract_status'] ?? 'Terisi',
                'payment_status' => $validated['payment_status'] ?? 'Menunggu',
            ]);
        }

        return redirect()->route('admin.tenants.show', $tenant->id)->with('success', 'Detail Tenant ' . $tenant->company_name . ' berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        // 1. Free up all space allocations occupied by this tenant
        SpaceAllocation::where('tenant_id', $tenant->id)->update([
            'tenant_id' => null,
            'status' => 'Kosong',
            'lease_start' => null,
            'lease_end' => null,
            'payment_status' => null,
        ]);

        // 3. Delete tenant
        $companyName = $tenant->company_name;
        $tenant->delete();

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant ' . $companyName . ' dan alokasi unitnya berhasil dihapus dari sistem.');
    }
}
