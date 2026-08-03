<?php

namespace App\Http\Controllers;

use App\Models\OfficeBopLead;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OfficeBopLeadController extends Controller
{
    public function index(Request $request)
    {
        $listBulan       = OfficeBopLead::listBulan();
        $listBulanRomawi = OfficeBopLead::listBulanRomawi();

        $currentYear = (int) date('Y');

        // Rentang tahun untuk Form Input & Modal Edit
        $formYears = range($currentYear + 4, 2020);

        // List tahun dari database
        $availableYears = OfficeBopLead::select('tahun')
            ->distinct()
            ->pluck('tahun')
            ->toArray();

        if (!in_array($currentYear, $availableYears)) {
            $availableYears[] = $currentYear;
        }
        if (!in_array(2024, $availableYears)) {
            $availableYears[] = 2024;
        }
        rsort($availableYears);

        // Filter Parameters
        $selectedYear  = $request->filled('tahun') ? (int) $request->tahun : $currentYear;
        $selectedMonth = $request->filled('bulan') ? (int) $request->bulan : null;
        $search        = $request->filled('search') ? trim($request->search) : null;
        $viewMode      = $request->input('view', 'matrix'); // 'matrix' or 'list'

        // Base Query
        $baseQuery = OfficeBopLead::where('tahun', $selectedYear);

        if ($search) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('telpon_fax', 'like', "%{$search}%")
                  ->orWhere('kategori_diminati', 'like', "%{$search}%")
                  ->orWhere('nomor_surat_loo', 'like', "%{$search}%");
            });
        }

        $allYearRecords = (clone $baseQuery)->orderBy('bulan')->orderBy('id')->get();

        // ── 1. Top Stat Summaries (Filtered by selectedMonth if active) ─────────
        $statsRecords = $selectedMonth 
            ? $allYearRecords->filter(fn($item) => $item->bulan == $selectedMonth)
            : $allYearRecords;

        $totalPeminat        = $statsRecords->count();
        $totalNomletDikirim  = $statsRecords->filter(fn($item) => !empty(trim($item->nomlet_dikirim ?? '')))->count();
        $totalNomletDisetujui = $statsRecords->filter(fn($item) => !empty(trim($item->nomlet_disetujui ?? '')))->count();
        $totalLoo            = $statsRecords->filter(fn($item) => !empty(trim($item->loo ?? '')))->count();
        $totalDp             = $statsRecords->filter(fn($item) => !empty(trim($item->dp ?? '')))->count();
        $totalSerah          = $statsRecords->filter(fn($item) => !empty(trim($item->serah_terima ?? '')))->count();
        $totalFitting        = $statsRecords->filter(fn($item) => !empty(trim($item->fitting_out ?? '')))->count();

        // ── Previous Year Stats for YoY Growth Calculation ───────────────────
        $prevYearBaseQuery = OfficeBopLead::where('tahun', $selectedYear - 1);
        if ($search) {
            $prevYearBaseQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('telpon_fax', 'like', "%{$search}%")
                  ->orWhere('kategori_diminati', 'like', "%{$search}%")
                  ->orWhere('nomor_surat_loo', 'like', "%{$search}%");
            });
        }
        $prevYearRecords = $prevYearBaseQuery->get();
        $prevStatsRecords = $selectedMonth 
            ? $prevYearRecords->filter(fn($item) => $item->bulan == $selectedMonth)
            : $prevYearRecords;

        $prevTotalPeminat        = $prevStatsRecords->count();
        $prevTotalNomletDikirim  = $prevStatsRecords->filter(fn($item) => !empty(trim($item->nomlet_dikirim ?? '')))->count();
        $prevTotalNomletDisetujui = $prevStatsRecords->filter(fn($item) => !empty(trim($item->nomlet_disetujui ?? '')))->count();
        $prevTotalLoo            = $prevStatsRecords->filter(fn($item) => !empty(trim($item->loo ?? '')))->count();
        $prevTotalDp             = $prevStatsRecords->filter(fn($item) => !empty(trim($item->dp ?? '')))->count();
        $prevTotalSerah          = $prevStatsRecords->filter(fn($item) => !empty(trim($item->serah_terima ?? '')))->count();
        $prevTotalFitting        = $prevStatsRecords->filter(fn($item) => !empty(trim($item->fitting_out ?? '')))->count();

        $calcGrowth = function ($curr, $prev) {
            if ($prev > 0) {
                $diff = (($curr - $prev) / $prev) * 100;
                $val = round($diff, 1);
                return [
                    'percent'   => abs($val),
                    'formatted' => ($val >= 0 ? '+' : '') . $val . '%',
                    'status'    => $val > 0 ? 'up' : ($val < 0 ? 'down' : 'same'),
                ];
            } elseif ($curr > 0) {
                return [
                    'percent'   => 100,
                    'formatted' => '+100%',
                    'status'    => 'up',
                ];
            } else {
                return [
                    'percent'   => 0,
                    'formatted' => '0%',
                    'status'    => 'same',
                ];
            }
        };

        $growthData = [
            'peminat'          => $calcGrowth($totalPeminat, $prevTotalPeminat),
            'nomlet_dikirim'   => $calcGrowth($totalNomletDikirim, $prevTotalNomletDikirim),
            'nomlet_disetujui' => $calcGrowth($totalNomletDisetujui, $prevTotalNomletDisetujui),
            'loo'              => $calcGrowth($totalLoo, $prevTotalLoo),
            'dp'               => $calcGrowth($totalDp, $prevTotalDp),
            'serah_terima'     => $calcGrowth($totalSerah, $prevTotalSerah),
            'fitting_out'      => $calcGrowth($totalFitting, $prevTotalFitting),
        ];

        // Progress percentage relative to Total Peminat
        $calcProgress = function ($curr, $total) {
            if ($total <= 0) return 0;
            return min(100, round(($curr / $total) * 100, 1));
        };

        $progressData = [
            'peminat'          => 100,
            'nomlet_dikirim'   => $calcProgress($totalNomletDikirim, $totalPeminat),
            'nomlet_disetujui' => $calcProgress($totalNomletDisetujui, $totalPeminat),
            'loo'              => $calcProgress($totalLoo, $totalPeminat),
            'dp'               => $calcProgress($totalDp, $totalPeminat),
            'serah_terima'     => $calcProgress($totalSerah, $totalPeminat),
            'fitting_out'      => $calcProgress($totalFitting, $totalPeminat),
        ];

        // ── 2. Chart Monthly Data (Agregasi berbasis Tanggal Follow Up) ───────
        $chartData = [
            'peminat'          => array_fill(1, 12, 0),
            'nomlet_dikirim'   => array_fill(1, 12, 0),
            'nomlet_disetujui' => array_fill(1, 12, 0),
            'loo'              => array_fill(1, 12, 0),
            'dp'               => array_fill(1, 12, 0),
            'serah_terima'     => array_fill(1, 12, 0),
            'fitting_out'      => array_fill(1, 12, 0),
        ];

        $parseYearMonth = function ($dateVal) {
            if (empty($dateVal) || trim($dateVal) === '-') return null;
            try {
                $c = \Carbon\Carbon::parse($dateVal);
                return [$c->year, $c->month];
            } catch (\Exception $e) {
                return null;
            }
        };

        $allLeads = OfficeBopLead::all();

        foreach ($allLeads as $record) {
            // 1. Peminat (Berdasarkan Tanggal Berminat / Form Entry)
            if ($record->tanggal_entry) {
                $ym = $parseYearMonth($record->tanggal_entry);
                if ($ym && $ym[0] == $selectedYear) {
                    $chartData['peminat'][$ym[1]]++;
                }
            } elseif ($record->tahun == $selectedYear && $record->bulan >= 1 && $record->bulan <= 12) {
                $chartData['peminat'][$record->bulan]++;
            }

            // 2. Nomlet Dikirim (Berdasarkan Tanggal Nomlet Dikirim)
            if (!empty(trim($record->nomlet_dikirim ?? ''))) {
                $ym = $parseYearMonth($record->nomlet_dikirim);
                if ($ym && $ym[0] == $selectedYear) {
                    $chartData['nomlet_dikirim'][$ym[1]]++;
                }
            }

            // 3. Nomlet Disetujui (Berdasarkan Tanggal Nomlet Disetujui)
            if (!empty(trim($record->nomlet_disetujui ?? ''))) {
                $ym = $parseYearMonth($record->nomlet_disetujui);
                if ($ym && $ym[0] == $selectedYear) {
                    $chartData['nomlet_disetujui'][$ym[1]]++;
                }
            }

            // 4. Kirim LOO (Berdasarkan Tanggal LOO)
            if (!empty(trim($record->loo ?? ''))) {
                $ym = $parseYearMonth($record->loo);
                if ($ym && $ym[0] == $selectedYear) {
                    $chartData['loo'][$ym[1]]++;
                }
            }

            // 5. DP (Berdasarkan Tanggal DP)
            if (!empty(trim($record->dp ?? ''))) {
                $ym = $parseYearMonth($record->dp);
                if ($ym && $ym[0] == $selectedYear) {
                    $chartData['dp'][$ym[1]]++;
                }
            }

            // 6. Serah Terima (Berdasarkan Tanggal Serah Terima)
            if (!empty(trim($record->serah_terima ?? ''))) {
                $ym = $parseYearMonth($record->serah_terima);
                if ($ym && $ym[0] == $selectedYear) {
                    $chartData['serah_terima'][$ym[1]]++;
                }
            }

            // 7. Fitting Out (Berdasarkan Tanggal Fitting Out)
            if (!empty(trim($record->fitting_out ?? ''))) {
                $ym = $parseYearMonth($record->fitting_out);
                if ($ym && $ym[0] == $selectedYear) {
                    $chartData['fitting_out'][$ym[1]]++;
                }
            }
        }

        // ── 3. Matrix Data Structure (Filtered by selectedMonth if specified) ───
        $matrixData = [];
        $monthsToInclude = $selectedMonth ? [$selectedMonth] : range(1, 12);

        foreach ($monthsToInclude as $m) {
            $matrixData[$m] = [
                'bulan_nama'   => $listBulan[$m],
                'bulan_romawi' => $listBulanRomawi[$m],
                'records'      => [],
            ];
        }

        foreach ($allYearRecords as $record) {
            $m = (int) $record->bulan;
            if (isset($matrixData[$m])) {
                $matrixData[$m]['records'][] = $record;
            }
        }

        // ── 4. Detailed List Data (Pagination if needed) ──────────────────────
        $listQuery = clone $baseQuery;
        if ($selectedMonth) {
            $listQuery->where('bulan', $selectedMonth);
        }
        $records = $listQuery->orderBy('bulan')->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.bop_leads.index', compact(
            'listBulan',
            'listBulanRomawi',
            'availableYears',
            'formYears',
            'selectedYear',
            'selectedMonth',
            'search',
            'viewMode',
            'totalPeminat',
            'totalNomletDikirim',
            'totalNomletDisetujui',
            'totalLoo',
            'totalDp',
            'totalSerah',
            'totalFitting',
            'growthData',
            'progressData',
            'chartData',
            'matrixData',
            'records'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_entry'     => 'required|date',
            'nama'              => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'nama_perusahaan'   => 'required|string|max:255',
            'alamat'            => 'required|string',
            'telpon_fax'        => 'required|string|max:255',
            'kategori_diminati' => 'required|string',
            'kit_marketing'     => 'nullable|string|max:255',
            'loo'               => 'nullable|string|max:255|required_with:nomor_surat_loo',
            'nomor_surat_loo'   => 'nullable|string|max:255|required_with:loo|unique:office_bop_leads,nomor_surat_loo',
            'nomlet_dikirim'    => 'nullable|string|max:255',
            'nomlet_disetujui'  => 'nullable|string|max:255',
            'dp'                => 'nullable|string|max:255',
            'serah_terima'      => 'nullable|string|max:255',
            'fitting_out'       => 'nullable|string|max:255',
        ], [
            'loo.required_with'             => 'Jika Nomor Surat LOO diisi, maka Tanggal LOO wajib diisi.',
            'nomor_surat_loo.required_with' => 'Jika Tanggal LOO diisi, maka Nomor Surat LOO wajib diisi.',
            'nomor_surat_loo.unique'        => 'Nomor Surat LOO sudah digunakan. Harap gunakan Nomor Surat LOO yang berbeda.',
        ]);

        $date = \Carbon\Carbon::parse($validated['tanggal_entry']);
        $validated['tahun'] = $date->year;
        $validated['bulan'] = $date->month;

        if (!empty($validated['telpon_fax'])) {
            $validated['telpon_fax'] = $this->formatPhoneFax($validated['telpon_fax']);
        }

        OfficeBopLead::create($validated);

        return redirect()->route('admin.bop-leads.index', [
            'tahun' => $validated['tahun'],
            'bulan' => $validated['bulan'],
        ])->with('success', 'Data peminat Office BOP berhasil ditambahkan.');
    }

    public function show($id)
    {
        $lead = OfficeBopLead::find($id);

        if (!$lead) {
            return redirect()->route('admin.bop-leads.index')->with('error', 'Data peminat tidak ditemukan.');
        }

        if (request()->wantsJson()) {
            return response()->json($lead);
        }

        return redirect()->route('admin.bop-leads.index', [
            'tahun' => $lead->tahun,
            'bulan' => $lead->bulan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $lead = OfficeBopLead::findOrFail($id);

        $validated = $request->validate([
            'tanggal_entry'     => 'required|date',
            'nama'              => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'nama_perusahaan'   => 'required|string|max:255',
            'alamat'            => 'required|string',
            'telpon_fax'        => 'required|string|max:255',
            'kategori_diminati' => 'required|string',
            'kit_marketing'     => 'nullable|string|max:255',
            'loo'               => 'nullable|string|max:255|required_with:nomor_surat_loo',
            'nomor_surat_loo'   => [
                'nullable',
                'string',
                'max:255',
                'required_with:loo',
                Rule::unique('office_bop_leads', 'nomor_surat_loo')->ignore($id),
            ],
            'nomlet_dikirim'    => 'nullable|string|max:255',
            'nomlet_disetujui'  => 'nullable|string|max:255',
            'dp'                => 'nullable|string|max:255',
            'serah_terima'      => 'nullable|string|max:255',
            'fitting_out'       => 'nullable|string|max:255',
        ], [
            'loo.required_with'             => 'Jika Nomor Surat LOO diisi, maka Tanggal LOO wajib diisi.',
            'nomor_surat_loo.required_with' => 'Jika Tanggal LOO diisi, maka Nomor Surat LOO wajib diisi.',
            'nomor_surat_loo.unique'        => 'Nomor Surat LOO sudah digunakan. Harap gunakan Nomor Surat LOO yang berbeda.',
        ]);

        $date = \Carbon\Carbon::parse($validated['tanggal_entry']);
        $validated['tahun'] = $date->year;
        $validated['bulan'] = $date->month;

        if (!empty($validated['telpon_fax'])) {
            $validated['telpon_fax'] = $this->formatPhoneFax($validated['telpon_fax']);
        }

        $lead->update($validated);

        return redirect()->back()->with('success', 'Data peminat Office BOP berhasil diperbarui.');
    }

    public function updateFollowUp(Request $request, $id)
    {
        $lead = OfficeBopLead::findOrFail($id);

        $validated = $request->validate([
            'field' => 'required|string|in:kit_marketing,loo,nomor_surat_loo,nomlet_dikirim,nomlet_disetujui,dp,serah_terima,fitting_out',
            'value' => 'nullable|string|max:255',
        ]);

        if ($validated['field'] === 'nomor_surat_loo' && !empty($validated['value'])) {
            $request->validate([
                'value' => Rule::unique('office_bop_leads', 'nomor_surat_loo')->ignore($lead->id),
            ], [
                'value.unique' => 'Nomor Surat LOO sudah digunakan. Harap gunakan Nomor Surat LOO yang berbeda.',
            ]);
        }

        $lead->update([
            $validated['field'] => $validated['value'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status Follow Up berhasil diperbarui.',
                'lead'    => $lead,
            ]);
        }

        return redirect()->back()->with('success', 'Status Follow Up berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lead = OfficeBopLead::findOrFail($id);
        $lead->delete();

        return redirect()->back()->with('success', 'Data peminat Office BOP berhasil dihapus.');
    }

    public function destroyAll(Request $request)
    {
        $query = OfficeBopLead::query();

        if ($request->filled('tahun')) {
            $query->where('tahun', (int) $request->tahun);
        }

        if ($request->filled('bulan')) {
            $query->where('bulan', (int) $request->bulan);
        }

        $deletedCount = $query->delete();

        return redirect()->back()->with('success', "Berhasil menghapus {$deletedCount} data peminat Office BOP.");
    }

    private function formatPhoneFax($val)
    {
        if (empty($val) || trim($val) === '-') return '-';

        $parts = explode('/', $val);
        $formattedParts = [];

        foreach ($parts as $part) {
            $trimmed = trim($part);

            if (str_contains($trimmed, '-')) {
                $formattedParts[] = $trimmed;
                continue;
            }

            $digitsOnly = preg_replace('/[^\d]/', '', $trimmed);

            if (strlen($digitsOnly) >= 8 && strlen($digitsOnly) <= 13) {
                if (strlen($digitsOnly) <= 10) {
                    $formatted = preg_replace('/^(\d{4})(\d{4})(\d{1,4})$/', '$1-$2-$3', $digitsOnly);
                } else {
                    $formatted = preg_replace('/^(\d{4})(\d{4})(\d{1,5})$/', '$1-$2-$3', $digitsOnly);
                }
                $formattedParts[] = $formatted;
            } else {
                $formattedParts[] = $trimmed;
            }
        }

        return implode(' / ', $formattedParts);
    }
}
