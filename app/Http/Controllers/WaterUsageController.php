<?php

namespace App\Http\Controllers;

use App\Models\WaterUsage;
use Illuminate\Http\Request;

class WaterUsageController extends Controller
{
    public function index(Request $request)
    {
        $listGedung = WaterUsage::listGedung();
        $listBulan  = WaterUsage::listBulan();

        $currentYear = (int) date('Y');

        // Rentang tahun untuk Form Input & Modal Edit
        $formYears = range($currentYear + 4, 2020);

        // Get list of distinct years in database for dropdown filter
        $availableYears = WaterUsage::whereIn('gedung', $listGedung)
            ->select('tahun')
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

        // ── 1. Matrix Data (Grid view per Gedung & Bulan) ──────────────────────
        $matrixData = [];
        $buildingTotals = [];
        foreach ($listGedung as $g) {
            $matrixData[$g] = array_fill(1, 12, null);
            $buildingTotals[$g] = 0;
        }

        $monthlyTotals = array_fill(1, 12, 0);

        $selectedYearRecords = WaterUsage::whereIn('gedung', $listGedung)->where('tahun', $selectedYear)->get();
        $prevYearRecords = WaterUsage::whereIn('gedung', $listGedung)->where('tahun', $previousYear)->get()->keyBy(function ($item) {
            return $item->gedung . '_' . $item->bulan;
        });

        foreach ($selectedYearRecords as $item) {
            $key = $item->gedung . '_' . $item->bulan;
            $prev = $prevYearRecords->get($key);

            $selisih = null;
            $persentase = null;
            $status = null;
            if ($prev && $prev->debet_air > 0) {
                $selisih = $item->debet_air - $prev->debet_air;
                $persentase = ($selisih / $prev->debet_air) * 100;
                $status = $selisih > 0 ? 'Naik' : ($selisih < 0 ? 'Turun' : 'Tetap');
            }

            if (isset($matrixData[$item->gedung])) {
                $matrixData[$item->gedung][$item->bulan] = [
                    'id'             => $item->id,
                    'gedung'         => $item->gedung,
                    'nomor_id'       => $item->nomor_id,
                    'tahun'          => $item->tahun,
                    'bulan'          => $item->bulan,
                    'nama_bulan'     => $item->nama_bulan,
                    'debet_air'      => $item->debet_air,
                    'prev_debet_air' => $prev ? $prev->debet_air : null,
                    'selisih'        => $selisih,
                    'persentase'     => $persentase,
                    'status'         => $status,
                ];

                $buildingTotals[$item->gedung] += $item->debet_air;
                $monthlyTotals[$item->bulan] += $item->debet_air;
            }
        }

        $grandTotal = array_sum($buildingTotals);

        // ── 2. Detailed List View Data Table Query ─────────────────────────────
        $query = WaterUsage::whereIn('gedung', $listGedung);

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
                  ->orWhere('nomor_id', 'like', "%{$search}%")
                  ->orWhere('debet_air', 'like', "%{$search}%")
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

            if ($prev && $prev->debet_air > 0) {
                $selisih = $item->debet_air - $prev->debet_air;
                $persentase = ($selisih / $prev->debet_air) * 100;
                $status = $selisih > 0 ? 'Naik' : ($selisih < 0 ? 'Turun' : 'Tetap');

                $item->prev_debet_air = $prev->debet_air;
                $item->selisih = $selisih;
                $item->persentase = $persentase;
                $item->status = $status;
            } else {
                $item->prev_debet_air = null;
                $item->selisih = null;
                $item->persentase = null;
                $item->status = null;
            }

            return $item;
        });

        // ── 3. Summary Statistics Cards ─────────────────────────────────────────
        $statsCurrentQuery = WaterUsage::whereIn('gedung', $listGedung)
            ->where('tahun', $selectedYear)
            ->when($selectedGedung, function ($q) use ($selectedGedung) {
                $q->where('gedung', $selectedGedung);
            });

        $totalDebetAirSelectedYear = (float) (clone $statsCurrentQuery)->sum('debet_air');
        $recordedMonthsCount       = (clone $statsCurrentQuery)->distinct()->count('bulan');
        $avgDebetAirPerMonth       = $recordedMonthsCount > 0 ? ($totalDebetAirSelectedYear / $recordedMonthsCount) : 0;

        // Aggregation per bulan untuk kartu Tertinggi dan Terendah
        $monthlyAggregates = WaterUsage::whereIn('gedung', $listGedung)
            ->where('tahun', $selectedYear)
            ->when($selectedGedung, function ($q) use ($selectedGedung) {
                $q->where('gedung', $selectedGedung);
            })
            ->selectRaw('bulan, SUM(debet_air) as total_debet_air')
            ->groupBy('bulan')
            ->get();

        $highestMonth = null;
        $lowestMonth  = null;

        if ($monthlyAggregates->count() > 0) {
            $highestItem = $monthlyAggregates->sortByDesc('total_debet_air')->first();
            $lowestItem  = $monthlyAggregates->sortBy('total_debet_air')->first();

            $highestMonth = (object) [
                'bulan'       => $highestItem->bulan,
                'nama_bulan'  => $listBulan[$highestItem->bulan] ?? '',
                'total_debet' => (float) $highestItem->total_debet_air,
            ];

            $lowestMonth = (object) [
                'bulan'       => $lowestItem->bulan,
                'nama_bulan'  => $listBulan[$lowestItem->bulan] ?? '',
                'total_debet' => (float) $lowestItem->total_debet_air,
            ];
        }

        $statsPrevQuery = WaterUsage::whereIn('gedung', $listGedung)
            ->where('tahun', $previousYear)
            ->when($selectedGedung, function ($q) use ($selectedGedung) {
                $q->where('gedung', $selectedGedung);
            });

        $totalDebetAirPrevYear = (float) $statsPrevQuery->sum('debet_air');
        $overallPercentageChange = null;
        if ($totalDebetAirPrevYear > 0) {
            $overallPercentageChange = (($totalDebetAirSelectedYear - $totalDebetAirPrevYear) / $totalDebetAirPrevYear) * 100;
        }

        // ── 4. Chart Datasets ──────────────────────────────────────────────────
        $chartDataSelectedYear = array_fill(1, 12, 0);
        $chartDataPrevYear     = array_fill(1, 12, 0);

        $selectedYearMonthly = WaterUsage::whereIn('gedung', $listGedung)
            ->where('tahun', $selectedYear)
            ->when($selectedGedung, function ($q) use ($selectedGedung) {
                $q->where('gedung', $selectedGedung);
            })
            ->selectRaw('bulan, SUM(debet_air) as total_debet_air')
            ->groupBy('bulan')
            ->pluck('total_debet_air', 'bulan');

        foreach ($selectedYearMonthly as $b => $val) {
            $chartDataSelectedYear[(int)$b] = round((float)$val, 2);
        }

        $prevYearMonthly = WaterUsage::whereIn('gedung', $listGedung)
            ->where('tahun', $previousYear)
            ->when($selectedGedung, function ($q) use ($selectedGedung) {
                $q->where('gedung', $selectedGedung);
            })
            ->selectRaw('bulan, SUM(debet_air) as total_debet_air')
            ->groupBy('bulan')
            ->pluck('total_debet_air', 'bulan');

        foreach ($prevYearMonthly as $b => $val) {
            $chartDataPrevYear[(int)$b] = round((float)$val, 2);
        }

        $nomorIdMapping = WaterUsage::getNomorIdMapping();

        return view('admin.water.index', compact(
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
            'totalDebetAirSelectedYear',
            'avgDebetAirPerMonth',
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
            'gedung'    => 'required|string',
            'tahun'     => 'required|integer|min:2000|max:2100',
            'bulan'     => 'required|integer|min:1|max:12',
            'debet_air' => 'required|numeric|gt:0',
        ], [
            'gedung.required'    => 'Field Gedung wajib dipilih.',
            'tahun.required'     => 'Field Tahun wajib diisi.',
            'bulan.required'     => 'Field Bulan wajib dipilih.',
            'debet_air.required' => 'Field Debet Air wajib diisi.',
            'debet_air.numeric'  => 'Debet Air harus berupa angka.',
            'debet_air.gt'       => 'Debet Air harus berupa angka positif (lebih dari 0).',
        ]);

        if (!in_array($validated['gedung'], WaterUsage::listGedung())) {
            return back()->withInput()->withErrors(['gedung' => 'Gedung tidak valid.']);
        }

        // Set nomor_id otomatis dari mapping gedung
        $validated['nomor_id'] = WaterUsage::getNomorIdMapping()[$validated['gedung']] ?? null;

        $existing = WaterUsage::where('gedung', $validated['gedung'])
            ->where('tahun', $validated['tahun'])
            ->where('bulan', $validated['bulan'])
            ->first();

        if ($existing) {
            $namaBulan = WaterUsage::listBulan()[$validated['bulan']] ?? '';
            return back()->withInput()->with('duplicate_error', [
                'message' => "Data penggunaan air bersih untuk {$validated['gedung']} pada bulan {$namaBulan} {$validated['tahun']} sudah tersimpan di database.",
                'edit_id' => $existing->id,
                'existing_data' => $existing
            ]);
        }

        WaterUsage::create($validated);

        return redirect()->route('admin.water.index', [
            'tahun' => $validated['tahun'],
            'gedung' => $validated['gedung']
        ])->with('success', 'Data penggunaan air bersih berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $usage = WaterUsage::findOrFail($id);

        $validated = $request->validate([
            'gedung'    => 'required|string',
            'tahun'     => 'required|integer|min:2000|max:2100',
            'bulan'     => 'required|integer|min:1|max:12',
            'debet_air' => 'required|numeric|gt:0',
        ], [
            'gedung.required'    => 'Field Gedung wajib dipilih.',
            'tahun.required'     => 'Field Tahun wajib diisi.',
            'bulan.required'     => 'Field Bulan wajib dipilih.',
            'debet_air.required' => 'Field Debet Air wajib diisi.',
            'debet_air.numeric'  => 'Debet Air harus berupa angka.',
            'debet_air.gt'       => 'Debet Air harus berupa angka positif.',
        ]);

        if (!in_array($validated['gedung'], WaterUsage::listGedung())) {
            return back()->withInput()->withErrors(['gedung' => 'Gedung tidak valid.']);
        }

        // Set nomor_id otomatis dari mapping gedung
        $validated['nomor_id'] = WaterUsage::getNomorIdMapping()[$validated['gedung']] ?? null;

        $existing = WaterUsage::where('gedung', $validated['gedung'])
            ->where('tahun', $validated['tahun'])
            ->where('bulan', $validated['bulan'])
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            $namaBulan = WaterUsage::listBulan()[$validated['bulan']] ?? '';
            return back()->withInput()->with('error', "Kombinasi {$validated['gedung']} - {$namaBulan} {$validated['tahun']} sudah digunakan pada data lain.");
        }

        $usage->update($validated);

        return redirect()->back()->with('success', 'Data penggunaan air bersih berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $usage = WaterUsage::findOrFail($id);
        $usage->delete();

        return redirect()->back()->with('success', 'Data penggunaan air bersih berhasil dihapus.');
    }

    public function destroyAll(Request $request)
    {
        $query = WaterUsage::query();

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('gedung')) {
            $query->where('gedung', $request->gedung);
        }

        $deletedCount = $query->delete();

        return redirect()->back()->with('success', "Berhasil menghapus {$deletedCount} data penggunaan air bersih.");
    }
}
