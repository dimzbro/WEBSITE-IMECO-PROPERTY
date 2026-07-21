<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Tenant;
use App\Models\SpaceAllocation;
use App\Models\FurnitureRental;
use App\Models\Lk3Report;
use App\Models\RekapitulasiRequest;
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

        // 4. Analytics: Realtime Tenant Growth Chart (dynamically calculated past 6 months)
        $monthsShortMap = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $growthLabels = [];
        $growthData   = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $endOfMonth = $monthDate->copy()->endOfMonth();
            
            $growthLabels[] = $monthsShortMap[$monthDate->month] ?? $monthDate->format('M');

            // Count occupied space allocations active on or before $endOfMonth
            $activeAllocCount = SpaceAllocation::whereNotNull('tenant_id')
                ->where(function($q) use ($endOfMonth) {
                    $q->whereNull('lease_start')
                      ->orWhere('lease_start', '<=', $endOfMonth->format('Y-m-d'));
                })
                ->where(function($q) use ($endOfMonth) {
                    $q->whereNull('lease_end')
                      ->orWhere('lease_end', '>=', $endOfMonth->startOfMonth()->format('Y-m-d'));
                })
                ->count();

            // Fallback to cumulative tenants count if allocations don't have lease dates
            if ($activeAllocCount == 0) {
                $activeAllocCount = Tenant::where('created_at', '<=', $endOfMonth)->count();
            }

            $growthData[] = $activeAllocCount;
        }

        $monthlyUtilization = [
            'labels' => $growthLabels,
            'growth' => $growthData,
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

        // 5. Dynamic Realtime Recent Activities Feed
        $timelineEvents = [];

        // a) Tenant Registrations
        $recentTenants = Tenant::orderBy('created_at', 'desc')->take(5)->get();
        foreach ($recentTenants as $tenant) {
            $timelineEvents[] = [
                'title' => 'Tenant Baru',
                'desc' => 'Tenant "' . $tenant->company_name . '" ditambahkan',
                'time' => $tenant->created_at ? $tenant->created_at->diffForHumans() : 'Baru saja',
                'type' => 'tenant',
                'badge' => 'Tenant',
                'color' => 'bg-emerald-500',
                'timestamp' => $tenant->created_at ? $tenant->created_at->timestamp : 0
            ];
        }

        // b) Space Allocations / Updates
        $recentAllocations = SpaceAllocation::with(['tenant', 'building'])
            ->whereNotNull('tenant_id')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
        foreach ($recentAllocations as $alloc) {
            $timelineEvents[] = [
                'title' => 'Alokasi Unit Sewa',
                'desc' => ($alloc->tenant->company_name ?? 'Tenant') . ' dialokasikan di ' . ($alloc->building->name ?? '') . ($alloc->floor_number ? ' Lt. ' . $alloc->floor_number : '') . ' ' . $alloc->unit_number,
                'time' => $alloc->updated_at ? $alloc->updated_at->diffForHumans() : 'Baru saja',
                'type' => 'allocation',
                'badge' => 'Alokasi',
                'color' => 'bg-blue-500',
                'timestamp' => $alloc->updated_at ? $alloc->updated_at->timestamp : 0
            ];
        }

        // c) LK3 Reports
        $recentLk3 = Lk3Report::orderBy('created_at', 'desc')->take(5)->get();
        foreach ($recentLk3 as $lk3) {
            $ts = $lk3->created_at ? $lk3->created_at->timestamp : ($lk3->tanggal ? Carbon::parse($lk3->tanggal)->timestamp : 0);
            $timelineEvents[] = [
                'title' => 'Laporan LK3 Masuk',
                'desc' => 'Dept ' . ($lk3->dari_dept ?: 'Umum') . ' melapor "' . $lk3->jenis_pekerjaan . '"',
                'time' => $lk3->created_at ? $lk3->created_at->diffForHumans() : ($lk3->tanggal ? Carbon::parse($lk3->tanggal)->diffForHumans() : 'Baru saja'),
                'type' => 'lk3',
                'badge' => 'LK3',
                'color' => 'bg-indigo-500',
                'timestamp' => $ts
            ];
        }

        // d) Rekapitulasi Request
        $recentRek = RekapitulasiRequest::orderBy('created_at', 'desc')->take(5)->get();
        foreach ($recentRek as $rek) {
            $ts = $rek->created_at ? $rek->created_at->timestamp : ($rek->date ? Carbon::parse($rek->date)->timestamp : 0);
            $timelineEvents[] = [
                'title' => 'Rekap Request Masuk',
                'desc' => 'Request pekerjaan "' . $rek->jenis_pekerjaan . '"',
                'time' => $rek->created_at ? $rek->created_at->diffForHumans() : ($rek->date ? Carbon::parse($rek->date)->diffForHumans() : 'Baru saja'),
                'type' => 'rekapitulasi',
                'badge' => 'Rekap',
                'color' => 'bg-purple-500',
                'timestamp' => $ts
            ];
        }

        // e) Expiring Contracts
        foreach ($expiringContractsList as $alloc) {
            $timelineEvents[] = [
                'title' => 'Kontrak Segera Habis',
                'desc' => 'Sewa ' . ($alloc->tenant->company_name ?? 'Tenant') . ' di ' . ($alloc->building->name ?? '') . ' Lt. ' . ($alloc->floor_number ?? '—') . ' ' . ($alloc->unit_number ?? ''),
                'time' => $alloc->lease_end ? Carbon::parse($alloc->lease_end)->translatedFormat('d M Y') : 'Segera',
                'type' => 'lease',
                'badge' => 'Perlu Tindakan',
                'color' => 'bg-amber-500',
                'timestamp' => $alloc->lease_end ? Carbon::parse($alloc->lease_end)->timestamp : 0
            ];
        }

        // Sort all events by timestamp descending
        usort($timelineEvents, function ($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        // Take top 7 activities
        $timelineEvents = array_slice($timelineEvents, 0, 7);

        // 6. LK3 & Rekapitulasi Dashboard Charts (Mengikuti data bulan terbaru) ────
        $monthsMap = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // LK3 Charts - Data bulan terbaru
        $latestLk3Date = Lk3Report::max('tanggal');
        $lk3MonthLabel = null;
        $lk3ByJenisPekerjaan = collect();
        $lk3ByDept = collect();
        $lk3TotalRecords = Lk3Report::count();

        if ($latestLk3Date) {
            $parsedDate = Carbon::parse($latestLk3Date);
            $lk3Month   = $parsedDate->month;
            $lk3Year    = $parsedDate->year;
            $lk3MonthLabel = ($monthsMap[$lk3Month] ?? '') . ' ' . $lk3Year;

            $lk3ByJenisPekerjaan = Lk3Report::select('jenis_pekerjaan', DB::raw('count(*) as total'))
                ->whereNotNull('jenis_pekerjaan')
                ->where('jenis_pekerjaan', '!=', '')
                ->whereMonth('tanggal', $lk3Month)
                ->whereYear('tanggal', $lk3Year)
                ->groupBy('jenis_pekerjaan')
                ->orderByDesc('total')
                ->get();

            $lk3ByDept = Lk3Report::select('dari_dept', DB::raw('count(*) as total'))
                ->whereNotNull('dari_dept')
                ->where('dari_dept', '!=', '')
                ->whereMonth('tanggal', $lk3Month)
                ->whereYear('tanggal', $lk3Year)
                ->groupBy('dari_dept')
                ->orderByDesc('total')
                ->get();
        }

        // Rekapitulasi Request Charts - Data bulan terbaru
        $latestRekDate = RekapitulasiRequest::max('date');
        $rekMonthLabel = null;
        $rekByJenisPekerjaan = collect();
        $rekTotalRecords = RekapitulasiRequest::count();

        if ($latestRekDate) {
            $parsedDate = Carbon::parse($latestRekDate);
            $rekMonth   = $parsedDate->month;
            $rekYear    = $parsedDate->year;
            $rekMonthLabel = ($monthsMap[$rekMonth] ?? '') . ' ' . $rekYear;

            $rekByJenisPekerjaan = RekapitulasiRequest::select('jenis_pekerjaan', DB::raw('count(*) as total'))
                ->whereNotNull('jenis_pekerjaan')
                ->where('jenis_pekerjaan', '!=', '')
                ->whereMonth('date', $rekMonth)
                ->whereYear('date', $rekYear)
                ->groupBy('jenis_pekerjaan')
                ->orderByDesc('total')
                ->get();
        }

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
            'monthlyUtilization',
            'contractStatusDistribution',
            'activeTenantsList',
            'expiringContractsList',
            'timelineEvents',
            'lk3ByJenisPekerjaan',
            'lk3ByDept',
            'lk3TotalRecords',
            'lk3MonthLabel',
            'rekByJenisPekerjaan',
            'rekTotalRecords',
            'rekMonthLabel'
        ));
    }
}
