<?php

namespace App\Http\Controllers;

use App\Models\ElectricityUsage;
use Illuminate\Http\Request;

class ElectricityUsageController extends Controller
{
    public function index(Request $request)
    {
        $listGedung = ElectricityUsage::listGedung();
        $listBulan  = ElectricityUsage::listBulan();

        $currentYear = (int) date('Y');

        // Rentang tahun untuk Form Input & Modal Edit (misal 2020 - 2030)
        $formYears = range($currentYear + 4, 2020);

        // Get list of distinct years in database for dropdown filter
        $availableYears = ElectricityUsage::select('tahun')
            ->distinct()
            ->pluck('tahun')
            ->toArray();

        if (!in_array($currentYear, $availableYears)) {
            $availableYears[] = $currentYear;
        }
        if (!in_array($currentYear - 1, $availableYears)) {
            $availableYears[] = $currentYear - 1;
        }
        rsort($availableYears);

        // Filter Parameters
        $selectedYear   = $request->filled('tahun') ? (int) $request->tahun : $currentYear;
        $selectedMonth  = $request->filled('bulan') ? (int) $request->bulan : null;
        $selectedGedung = $request->filled('gedung') ? $request->gedung : null;
        $search         = $request->filled('search') ? trim($request->search) : null;
        $viewMode       = $request->input('view', 'matrix'); // 'matrix' or 'list'

        $previousYear = $selectedYear - 1;
        $shortYear    = substr((string)$selectedYear, -2);

        // ── 1. Matrix Data (Grid view per Gedung & Bulan as requested) ─────────────
        $matrixData = [];
        $buildingTotals = [];
        foreach ($listGedung as $g) {
            $matrixData[$g] = array_fill(1, 12, null);
            $buildingTotals[$g] = 0;
        }

        $monthlyTotals = array_fill(1, 12, 0);

        $selectedYearRecords = ElectricityUsage::where('tahun', $selectedYear)->get();
        $prevYearRecords = ElectricityUsage::where('tahun', $previousYear)->get()->keyBy(function ($item) {
            return $item->gedung . '_' . $item->bulan;
        });

        foreach ($selectedYearRecords as $item) {
            $key = $item->gedung . '_' . $item->bulan;
            $prev = $prevYearRecords->get($key);

            $selisih = null;
            $persentase = null;
            $status = null;
            if ($prev && $prev->kwh > 0) {
                $selisih = $item->kwh - $prev->kwh;
                $persentase = ($selisih / $prev->kwh) * 100;
                $status = $selisih > 0 ? 'Naik' : ($selisih < 0 ? 'Turun' : 'Tetap');
            }

            if (isset($matrixData[$item->gedung])) {
                $matrixData[$item->gedung][$item->bulan] = [
                    'id'         => $item->id,
                    'gedung'     => $item->gedung,
                    'tahun'      => $item->tahun,
                    'bulan'      => $item->bulan,
                    'nama_bulan' => $item->nama_bulan,
                    'kwh'        => $item->kwh,
                    'prev_kwh'   => $prev ? $prev->kwh : null,
                    'selisih'    => $selisih,
                    'persentase' => $persentase,
                    'status'     => $status,
                ];

                $buildingTotals[$item->gedung] += $item->kwh;
                $monthlyTotals[$item->bulan] += $item->kwh;
            }
        }

        $grandTotal = array_sum($buildingTotals);

        // ── 2. Detailed List View Data Table Query ─────────────────────────────
        $query = ElectricityUsage::query();

        if ($selectedYear) {
            $query->where('tahun', $selectedYear);
        }

        if ($selectedMonth) {
            $query->where('bulan', $selectedMonth);
        }

        if ($selectedGedung) {
            $query->where('gedung', $selectedGedung);
        }

        if ($search) {
            $query->where(function ($q) use ($search, $listBulan) {
                $q->where('gedung', 'like', "%{$search}%")
                  ->orWhere('kwh', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%");

                foreach ($listBulan as $num => $name) {
                    if (stripos($name, $search) !== false) {
                        $q->orWhere('bulan', $num);
                    }
                }
            });
        }

        $records = $query->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->orderBy('gedung', 'asc')
            ->paginate(15)
            ->withQueryString();

        // Map comparison for paginated list
        $records->getCollection()->transform(function ($item) use ($prevYearRecords) {
            $key = $item->gedung . '_' . $item->bulan;
            $prev = $prevYearRecords->get($key);

            if ($prev && $prev->kwh > 0) {
                $selisih = $item->kwh - $prev->kwh;
                $persentase = ($selisih / $prev->kwh) * 100;
                $status = $selisih > 0 ? 'Naik' : ($selisih < 0 ? 'Turun' : 'Tetap');

                $item->prev_kwh = $prev->kwh;
                $item->selisih = $selisih;
                $item->persentase = $persentase;
                $item->status = $status;
            } else {
                $item->prev_kwh = null;
                $item->selisih = null;
                $item->persentase = null;
                $item->status = null;
            }

            return $item;
        });

        // ── 3. Summary Statistics Cards ─────────────────────────────────────────
        $statsCurrentQuery = ElectricityUsage::where('tahun', $selectedYear)
            ->when($selectedGedung, function ($q) use ($selectedGedung) {
                $q->where('gedung', $selectedGedung);
            });

        $totalKwhSelectedYear = (float) (clone $statsCurrentQuery)->sum('kwh');
        $recordedMonthsCount  = (clone $statsCurrentQuery)->distinct()->count('bulan');
        $avgKwhPerMonth       = $recordedMonthsCount > 0 ? ($totalKwhSelectedYear / $recordedMonthsCount) : 0;

        // Aggregation per bulan untuk kartu Tertinggi dan Terendah
        $monthlyAggregates = ElectricityUsage::where('tahun', $selectedYear)
            ->when($selectedGedung, function ($q) use ($selectedGedung) {
                $q->where('gedung', $selectedGedung);
            })
            ->selectRaw('bulan, SUM(kwh) as total_kwh')
            ->groupBy('bulan')
            ->get();

        $highestMonth = null;
        $lowestMonth  = null;

        if ($monthlyAggregates->count() > 0) {
            $highestItem = $monthlyAggregates->sortByDesc('total_kwh')->first();
            $lowestItem  = $monthlyAggregates->sortBy('total_kwh')->first();

            $highestMonth = (object) [
                'bulan'      => $highestItem->bulan,
                'nama_bulan' => $listBulan[$highestItem->bulan] ?? '',
                'total_kwh'  => (float) $highestItem->total_kwh,
            ];

            $lowestMonth = (object) [
                'bulan'      => $lowestItem->bulan,
                'nama_bulan' => $listBulan[$lowestItem->bulan] ?? '',
                'total_kwh'  => (float) $lowestItem->total_kwh,
            ];
        }

        $statsPrevQuery = ElectricityUsage::where('tahun', $previousYear)
            ->when($selectedGedung, function ($q) use ($selectedGedung) {
                $q->where('gedung', $selectedGedung);
            });

        $totalKwhPrevYear = (float) $statsPrevQuery->sum('kwh');
        $overallPercentageChange = null;
        if ($totalKwhPrevYear > 0) {
            $overallPercentageChange = (($totalKwhSelectedYear - $totalKwhPrevYear) / $totalKwhPrevYear) * 100;
        }

        // ── 4. Chart Datasets ──────────────────────────────────────────────────
        $chartDataSelectedYear = array_fill(1, 12, 0);
        $chartDataPrevYear     = array_fill(1, 12, 0);

        $selectedYearMonthly = ElectricityUsage::where('tahun', $selectedYear)
            ->when($selectedGedung, function ($q) use ($selectedGedung) {
                $q->where('gedung', $selectedGedung);
            })
            ->selectRaw('bulan, SUM(kwh) as total_kwh')
            ->groupBy('bulan')
            ->pluck('total_kwh', 'bulan');

        foreach ($selectedYearMonthly as $b => $val) {
            $chartDataSelectedYear[(int)$b] = round((float)$val, 2);
        }

        $prevYearMonthly = ElectricityUsage::where('tahun', $previousYear)
            ->when($selectedGedung, function ($q) use ($selectedGedung) {
                $q->where('gedung', $selectedGedung);
            })
            ->selectRaw('bulan, SUM(kwh) as total_kwh')
            ->groupBy('bulan')
            ->pluck('total_kwh', 'bulan');

        foreach ($prevYearMonthly as $b => $val) {
            $chartDataPrevYear[(int)$b] = round((float)$val, 2);
        }

        $nomorIdMapping = ElectricityUsage::getNomorIdMapping();

        return view('admin.electricity.index', compact(
            'listGedung',
            'listBulan',
            'availableYears',
            'selectedYear',
            'selectedMonth',
            'selectedGedung',
            'search',
            'viewMode',
            'previousYear',
            'shortYear',
            'matrixData',
            'monthlyTotals',
            'buildingTotals',
            'grandTotal',
            'records',
            'totalKwhSelectedYear',
            'avgKwhPerMonth',
            'highestMonth',
            'lowestMonth',
            'overallPercentageChange',
            'chartDataSelectedYear',
            'chartDataPrevYear',
            'nomorIdMapping',
            'formYears'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gedung' => 'required|string',
            'tahun'  => 'required|integer|min:2000|max:2100',
            'bulan'  => 'required|integer|min:1|max:12',
            'kwh'    => 'required|numeric|gt:0',
        ], [
            'gedung.required' => 'Field Gedung wajib dipilih.',
            'tahun.required'  => 'Field Tahun wajib diisi.',
            'bulan.required'  => 'Field Bulan wajib dipilih.',
            'kwh.required'    => 'Field Penggunaan Daya (KWH) wajib diisi.',
            'kwh.numeric'     => 'Penggunaan Daya (KWH) harus berupa angka.',
            'kwh.gt'          => 'Penggunaan Daya (KWH) harus berupa angka positif (lebih dari 0).',
        ]);

        if (!in_array($validated['gedung'], ElectricityUsage::listGedung())) {
            return back()->withInput()->withErrors(['gedung' => 'Gedung tidak valid.']);
        }

        // Set nomor_id otomatis dari mapping gedung
        $validated['nomor_id'] = ElectricityUsage::getNomorIdMapping()[$validated['gedung']] ?? null;

        $existing = ElectricityUsage::where('gedung', $validated['gedung'])
            ->where('tahun', $validated['tahun'])
            ->where('bulan', $validated['bulan'])
            ->first();

        if ($existing) {
            $namaBulan = ElectricityUsage::listBulan()[$validated['bulan']] ?? '';
            return back()->withInput()->with('duplicate_error', [
                'message' => "Data penggunaan daya listrik untuk {$validated['gedung']} pada bulan {$namaBulan} {$validated['tahun']} sudah tersimpan di database.",
                'edit_id' => $existing->id,
                'existing_data' => $existing
            ]);
        }

        ElectricityUsage::create($validated);

        $redirectParams = [
            'tahun' => $validated['tahun'],
        ];
        if ($request->filled('view')) {
            $redirectParams['view'] = $request->input('view');
        }

        return redirect()->route('admin.electricity.index', $redirectParams)
            ->with('success', 'Data penggunaan daya listrik berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $usage = ElectricityUsage::findOrFail($id);

        $validated = $request->validate([
            'gedung' => 'required|string',
            'tahun'  => 'required|integer|min:2000|max:2100',
            'bulan'  => 'required|integer|min:1|max:12',
            'kwh'    => 'required|numeric|gt:0',
        ], [
            'gedung.required' => 'Field Gedung wajib dipilih.',
            'tahun.required'  => 'Field Tahun wajib diisi.',
            'bulan.required'  => 'Field Bulan wajib dipilih.',
            'kwh.required'    => 'Field Penggunaan Daya (KWH) wajib diisi.',
            'kwh.numeric'     => 'Penggunaan Daya (KWH) harus berupa angka.',
            'kwh.gt'          => 'Penggunaan Daya (KWH) harus berupa angka positif.',
        ]);

        if (!in_array($validated['gedung'], ElectricityUsage::listGedung())) {
            return back()->withInput()->withErrors(['gedung' => 'Gedung tidak valid.']);
        }

        // Set nomor_id otomatis dari mapping gedung
        $validated['nomor_id'] = ElectricityUsage::getNomorIdMapping()[$validated['gedung']] ?? null;

        $existing = ElectricityUsage::where('gedung', $validated['gedung'])
            ->where('tahun', $validated['tahun'])
            ->where('bulan', $validated['bulan'])
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            $namaBulan = ElectricityUsage::listBulan()[$validated['bulan']] ?? '';
            return back()->withInput()->with('error', "Kombinasi {$validated['gedung']} - {$namaBulan} {$validated['tahun']} sudah digunakan pada data lain.");
        }

        $usage->update($validated);

        return redirect()->back()->with('success', 'Data penggunaan daya listrik berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $usage = ElectricityUsage::findOrFail($id);
        $usage->delete();

        return redirect()->back()->with('success', 'Data penggunaan daya listrik berhasil dihapus.');
    }

    public function destroyAll(Request $request)
    {
        $query = ElectricityUsage::query();

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('gedung')) {
            $query->where('gedung', $request->gedung);
        }

        $deletedCount = $query->delete();

        return redirect()->back()->with('success', "Berhasil menghapus {$deletedCount} data penggunaan daya listrik.");
    }
}
