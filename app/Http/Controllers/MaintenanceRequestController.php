<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;
use App\Models\Tenant;
use App\Models\Building;
use Carbon\Carbon;

class MaintenanceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Calculate dashboard statistics
        $totalRequests = MaintenanceRequest::count();
        $kritisRequests = MaintenanceRequest::where('priority', 'Kritis')->count();
        $prossesRequests = MaintenanceRequest::where('status', 'Dalam Proses')->count();
        $completedRequests = MaintenanceRequest::where('status', 'Selesai')->count();

        // 2. Query maintenance requests
        $query = MaintenanceRequest::with(['tenant.spaceAllocations.building', 'building']);

        // Filter: Search (Tenant, Building, Unit, Category, Title)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('tenant', function ($tQ) use ($search) {
                      $tQ->where('company_name', 'like', "%{$search}%")
                         ->orWhereHas('spaceAllocations', function ($saQ) use ($search) {
                             $saQ->where('unit_number', 'like', "%{$search}%")
                                 ->orWhereHas('building', function ($bQ) use ($search) {
                                     $bQ->where('name', 'like', "%{$search}%");
                                 });
                         });
                  });
            });
        }

        // Filter: Building
        if ($request->filled('building_id')) {
            $buildingId = $request->input('building_id');
            $query->whereHas('tenant.spaceAllocations', function ($q) use ($buildingId) {
                $q->where('building_id', $buildingId);
            });
        }

        // Filter: Category
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Filter: Priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        // Filter: Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Sorting: newest requests first
        $maintenanceRequests = $query->orderBy('requested_at', 'desc')
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(10)
                                    ->withQueryString();

        // 3. Fetch data for forms and filters
        $tenants = Tenant::with(['spaceAllocations.building'])->orderBy('company_name', 'asc')->get();
        $buildings = Building::orderBy('name', 'asc')->get();

        return view('admin.maintenance.index', compact(
            'maintenanceRequests',
            'tenants',
            'buildings',
            'totalRequests',
            'kritisRequests',
            'prossesRequests',
            'completedRequests'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'building_id' => 'required|exists:buildings,id',
            'unit' => 'required|string|max:255',
            'category' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|string|in:Rendah,Sedang,Tinggi,Kritis',
            'assigned_to' => 'required|string',
            'scheduled_at' => 'nullable|date',
        ]);

        $validated['requested_at'] = Carbon::today()->toDateString();
        $validated['status'] = 'Menunggu';

        MaintenanceRequest::create($validated);

        return redirect()->route('admin.maintenance.index')->with('success', 'Maintenance Request berhasil dibuat.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $maintenance = MaintenanceRequest::findOrFail($id);

        if ($maintenance->status === 'Selesai') {
            return redirect()->route('admin.maintenance.index')->with('error', 'Request pemeliharaan yang sudah selesai tidak dapat diedit.');
        }

        $validated = $request->validate([
            'status' => 'required|string|in:Menunggu,Dalam Proses,Selesai,Dibatalkan',
            'priority' => 'required|string|in:Rendah,Sedang,Tinggi,Kritis',
            'assigned_to' => 'required|string',
            'notes' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
        ]);

        if ($validated['status'] === 'Selesai' && $maintenance->status !== 'Selesai') {
            $validated['completed_at'] = Carbon::today()->toDateString();
        } elseif ($validated['status'] !== 'Selesai') {
            $validated['completed_at'] = null;
        }

        $maintenance->update($validated);

        return redirect()->route('admin.maintenance.index')->with('success', 'Maintenance Request berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $maintenance = MaintenanceRequest::findOrFail($id);
        $maintenance->delete();

        return redirect()->route('admin.maintenance.index')->with('success', 'Maintenance Request berhasil dihapus.');
    }
}
