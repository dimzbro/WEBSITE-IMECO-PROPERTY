<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Tenant;
use App\Models\SpaceAllocation;
use App\Models\FurnitureRental;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Core Metrics
        $totalUnits = SpaceAllocation::count();
        $occupiedUnitsCount = SpaceAllocation::whereIn('status', ['Terisi', 'Hampir Berakhir', 'Berakhir'])->count();
        $occupancyRate = $totalUnits > 0 ? round(($occupiedUnitsCount / $totalUnits) * 100, 1) : 0;

        $activeTenantsCount = Tenant::has('spaceAllocations')->count();

        // Contracts approaching end (status = Hampir Berakhir or ending within 90 days)
        $approachingEndCount = SpaceAllocation::where('status', 'Hampir Berakhir')
            ->orWhere(function ($query) {
                $query->whereIn('status', ['Terisi', 'Hampir Berakhir'])
                      ->whereNotNull('lease_end')
                      ->where('lease_end', '<=', Carbon::now()->addDays(90));
            })->count();

        // Payment metrics
        $paymentDueCount = SpaceAllocation::where('payment_status', 'Menunggu')->count();
        $overdueCount = SpaceAllocation::where('payment_status', 'Tertunggak')->count();
        $overdueAmount = SpaceAllocation::where('payment_status', 'Tertunggak')->sum('rent_price');

        // Total active monthly revenue
        $monthlyRevenue = SpaceAllocation::whereIn('status', ['Terisi', 'Hampir Berakhir'])->sum('rent_price');

        // 2. Building Occupancy Progress Bars
        $buildings = Building::with('spaceAllocations')->get();
        $buildingStats = [];
        foreach ($buildings as $building) {
            $bTotalUnits = $building->spaceAllocations->count();
            $bOccupiedUnits = $building->spaceAllocations->whereIn('status', ['Terisi', 'Hampir Berakhir', 'Berakhir'])->count();
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

        // 3. Upcoming Tasks ("Tugas Mendatang")
        // Dynamically pull upcoming renewals or overdue collections
        $upcomingTasks = [];
        
        // Add renewals
        $expiringLeases = SpaceAllocation::with('tenant')
            ->whereIn('status', ['Hampir Berakhir', 'Berakhir'])
            ->whereNotNull('lease_end')
            ->orderBy('lease_end', 'asc')
            ->take(3)
            ->get();

        foreach ($expiringLeases as $lease) {
            if ($lease->tenant) {
                $upcomingTasks[] = [
                    'title' => 'Renewal Kontrak ' . $lease->tenant->company_name,
                    'date' => Carbon::parse($lease->lease_end)->translatedFormat('d M Y'),
                    'type' => 'warning',
                    'icon' => 'file-text'
                ];
            }
        }

        // Add billings
        $unpaidLeases = SpaceAllocation::with('tenant')
            ->whereIn('payment_status', ['Menunggu', 'Tertunggak'])
            ->take(2)
            ->get();

        foreach ($unpaidLeases as $lease) {
            if ($lease->tenant) {
                $upcomingTasks[] = [
                    'title' => 'Penagihan ' . $lease->tenant->company_name,
                    'date' => Carbon::parse($lease->lease_end ?? Carbon::now())->translatedFormat('d M Y'),
                    'type' => $lease->payment_status === 'Tertunggak' ? 'danger' : 'info',
                    'icon' => 'credit-card'
                ];
            }
        }

        // Add some static operations checks to round out the widget nicely
        $upcomingTasks[] = [
            'title' => 'Inspeksi rutin Gedung B lantai 3-5',
            'date' => Carbon::now()->addDays(5)->translatedFormat('d M Y'),
            'type' => 'info',
            'icon' => 'check-square'
        ];
        $upcomingTasks[] = [
            'title' => 'Follow-up keluhan AC PT Astra',
            'date' => Carbon::now()->addDays(2)->translatedFormat('d M Y'),
            'type' => 'warning',
            'icon' => 'phone'
        ];

        // Sort upcoming tasks by date (closest first)
        usort($upcomingTasks, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        // Slice to max 5 items
        $upcomingTasks = array_slice($upcomingTasks, 0, 5);

        // 4. Analytics: Space utilization & growth (mocked trends based on seeded contracts)
        $monthlyUtilization = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
            'growth' => [42, 45, 46, 49, 52, 54, $occupiedUnitsCount],
            'revenue_gedung_a' => [320, 320, 320, 348, 348, 348, 348], // in Juta Rupiah
            'revenue_gedung_b' => [200, 200, 210, 228, 228, 228, 228],
            'revenue_gedung_c' => [240, 240, 240, 264, 264, 264, 264]
        ];

        // Distribution of contracts by status (Pie chart)
        $contractStatusDistribution = [
            'aktif' => SpaceAllocation::where('status', 'Terisi')->count(),
            'hampir_berakhir' => SpaceAllocation::where('status', 'Hampir Berakhir')->count(),
            'berakhir' => SpaceAllocation::where('status', 'Berakhir')->count(),
            'kosong' => SpaceAllocation::where('status', 'Kosong')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalUnits',
            'occupiedUnitsCount',
            'occupancyRate',
            'activeTenantsCount',
            'approachingEndCount',
            'paymentDueCount',
            'overdueCount',
            'overdueAmount',
            'monthlyRevenue',
            'buildingStats',
            'upcomingTasks',
            'monthlyUtilization',
            'contractStatusDistribution'
        ));
    }
}
