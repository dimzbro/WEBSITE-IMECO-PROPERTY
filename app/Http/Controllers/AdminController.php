<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Tenant;
use App\Models\SpaceAllocation;
use App\Models\FurnitureRental;
use App\Models\MaintenanceRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Core Metrics
        $totalUnits = SpaceAllocation::count();
        $occupiedUnitsCount = SpaceAllocation::whereIn('status', ['Kontrak Aktif', 'Kontrak Mendekati Berakhir', 'Hampir Berakhir', 'Kontrak Habis'])->count();
        $occupancyRate = $totalUnits > 0 ? round(($occupiedUnitsCount / $totalUnits) * 100, 1) : 0;

        $activeTenantsList = Tenant::with(['spaceAllocations' => function($q) {
                $q->where('status', 'Kontrak Aktif')->with('building');
            }])
            ->whereHas('spaceAllocations', function ($q) {
                $q->where('status', 'Kontrak Aktif');
            })
            ->get();

        $activeTenantsCount = $activeTenantsList->count();

        // Contracts approaching end (status = Hampir Berakhir or ending within 90 days)
        $approachingEndCount = SpaceAllocation::where('status', 'Hampir Berakhir')
            ->orWhere(function ($query) {
                $query->whereIn('status', ['Kontrak Aktif', 'Kontrak Mendekati Berakhir', 'Hampir Berakhir'])
                      ->whereNotNull('lease_end')
                      ->where('lease_end', '<=', Carbon::now()->addDays(90));
            })->count();

        // Payment metrics
        $paymentDueCount = SpaceAllocation::where('payment_status', 'Menunggu')->count();
        $overdueCount = SpaceAllocation::where('payment_status', 'Tertunggak')->count();
        $overdueAmount = SpaceAllocation::where('payment_status', 'Tertunggak')->sum('rent_price');
        $totalTenants = Tenant::count();
        $duePaymentRatio = $totalTenants > 0 ? ($paymentDueCount / $totalTenants) * 100 : 0;

        // Total active monthly revenue
        $monthlyRevenue = SpaceAllocation::whereIn('status', ['Kontrak Aktif', 'Kontrak Mendekati Berakhir', 'Hampir Berakhir'])->sum('rent_price');

        // 2. Building Occupancy Progress Bars
        $buildings = Building::with('spaceAllocations')->get();
        $buildingStats = [];
        foreach ($buildings as $building) {
            $bTotalUnits = $building->spaceAllocations->count();
            $bOccupiedUnits = $building->spaceAllocations->whereIn('status', ['Kontrak Aktif', 'Kontrak Mendekati Berakhir', 'Hampir Berakhir', 'Kontrak Habis'])->count();
            $bOccupancyRate = $bTotalUnits > 0 ? round(($bOccupiedUnits / $bTotalUnits) * 100, 1) : 0;
            $bVacantUnits = $bTotalUnits - $bOccupiedUnits;

            $buildingStats[] = [
                'id' => $building->id,
                'name' => $building->name,
                'total_units' => $bTotalUnits,
                'occupied_units' => $bOccupiedUnits,
                'occupancy_rate' => $bOccupancyRate,
                'vacant_units' => $bVacantUnits,
            ];
        }

        // 3. Maintenance Requests ("Daftar Maintenance")
        $maintenanceRequests = MaintenanceRequest::with('tenant')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 4. Analytics: Space utilization & growth (dynamic trends based on seeded contracts)
        $colors = [
            'Gedung A' => '#1E3A8A', // Navy
            'Gedung B' => '#D4AF37', // Gold
            'Gedung C' => '#64748B', // Slate
            'Annex' => '#0D9488',    // Teal
            'Annex 1' => '#0284C7',  // Sky Blue
            'Workshop' => '#4F46E5', // Indigo
            'Canteen' => '#F97316',  // Orange
            'Open Yard' => '#16A34A' // Green
        ];

        $datasets = [];
        foreach ($buildings as $building) {
            $currentRevenue = $building->spaceAllocations->whereIn('status', ['Kontrak Aktif', 'Kontrak Mendekati Berakhir', 'Hampir Berakhir'])->sum('rent_price') / 1000000;
            // Generate a mock trend ending in current revenue
            $trend = [
                round($currentRevenue * 0.85),
                round($currentRevenue * 0.88),
                round($currentRevenue * 0.90),
                round($currentRevenue * 0.93),
                round($currentRevenue * 0.95),
                round($currentRevenue * 0.98),
                round($currentRevenue)
            ];
            
            $color = $colors[$building->name] ?? ('#' . substr(md5($building->name), 0, 6));

            $datasets[] = [
                'label' => $building->name,
                'data' => $trend,
                'backgroundColor' => $color,
                'borderRadius' => 4
            ];
        }

        $monthlyUtilization = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
            'growth' => [42, 45, 46, 49, 52, 54, $occupiedUnitsCount],
            'datasets' => $datasets
        ];

        // Distribution of contracts by status (Pie chart)
        $contractStatusDistribution = [
            'aktif' => SpaceAllocation::where('status', 'Kontrak Aktif')->count(),
            'mendekati_berakhir' => SpaceAllocation::where('status', 'Kontrak Mendekati Berakhir')->count(),
            'hampir_berakhir' => SpaceAllocation::where('status', 'Hampir Berakhir')->count(),
            'berakhir' => SpaceAllocation::where('status', 'Kontrak Habis')->count(),
            'kosong' => SpaceAllocation::where('status', 'Kosong')->count(),
        ];


        $expiringContractsList = SpaceAllocation::with(['tenant', 'building'])
            ->where(function ($query) {
                $query->where('status', 'Hampir Berakhir')
                      ->orWhere(function ($sub) {
                          $sub->whereIn('status', ['Kontrak Aktif', 'Kontrak Mendekati Berakhir', 'Hampir Berakhir'])
                                ->whereNotNull('lease_end')
                                ->where('lease_end', '<=', Carbon::now()->addDays(90));
                      });
            })
            ->orderBy('lease_end', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'totalUnits',
            'occupiedUnitsCount',
            'occupancyRate',
            'activeTenantsCount',
            'approachingEndCount',
            'paymentDueCount',
            'overdueCount',
            'overdueAmount',
            'duePaymentRatio',
            'monthlyRevenue',
            'buildingStats',
            'maintenanceRequests',
            'monthlyUtilization',
            'contractStatusDistribution',
            'activeTenantsList',
            'expiringContractsList'
        ));
    }
}
