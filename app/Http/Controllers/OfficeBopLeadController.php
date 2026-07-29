<?php

namespace App\Http\Controllers;

use App\Models\OfficeBopLead;
use Illuminate\Http\Request;

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

        $totalPeminat = $statsRecords->count();
        $totalLoo     = $statsRecords->filter(fn($item) => !empty(trim($item->loo ?? '')))->count();
        $totalLoi     = $statsRecords->filter(fn($item) => !empty(trim($item->loi ?? '')))->count();
        $totalDp      = $statsRecords->filter(fn($item) => !empty(trim($item->dp ?? '')))->count();
        $totalSerah   = $statsRecords->filter(fn($item) => !empty(trim($item->serah_terima ?? '')))->count();

        // ── 2. Chart Monthly Data (1 s.d. 12) ─────────────────────────────────
        $chartData = [
            'peminat' => array_fill(1, 12, 0),
            'loo'     => array_fill(1, 12, 0),
            'loi'     => array_fill(1, 12, 0),
            'dp'      => array_fill(1, 12, 0),
        ];

        foreach ($allYearRecords as $record) {
            $m = (int) $record->bulan;
            if ($m >= 1 && $m <= 12) {
                $chartData['peminat'][$m]++;
                if (!empty(trim($record->loo ?? ''))) {
                    $chartData['loo'][$m]++;
                }
                if (!empty(trim($record->loi ?? ''))) {
                    $chartData['loi'][$m]++;
                }
                if (!empty(trim($record->dp ?? ''))) {
                    $chartData['dp'][$m]++;
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
            'totalLoo',
            'totalLoi',
            'totalDp',
            'totalSerah',
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
            'nomor_surat_loo'   => 'nullable|string|max:255|required_with:loo',
            'loi'               => 'nullable|string|max:255',
            'dp'                => 'nullable|string|max:255',
            'serah_terima'      => 'nullable|string|max:255',
            'fitting_out'       => 'nullable|string|max:255',
        ], [
            'loo.required_with'             => 'Jika Nomor Surat LOO diisi, maka Tanggal LOO wajib diisi.',
            'nomor_surat_loo.required_with' => 'Jika Tanggal LOO diisi, maka Nomor Surat LOO wajib diisi.',
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
            'nomor_surat_loo'   => 'nullable|string|max:255|required_with:loo',
            'loi'               => 'nullable|string|max:255',
            'dp'                => 'nullable|string|max:255',
            'serah_terima'      => 'nullable|string|max:255',
            'fitting_out'       => 'nullable|string|max:255',
        ], [
            'loo.required_with'             => 'Jika Nomor Surat LOO diisi, maka Tanggal LOO wajib diisi.',
            'nomor_surat_loo.required_with' => 'Jika Tanggal LOO diisi, maka Nomor Surat LOO wajib diisi.',
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
            'field' => 'required|string|in:kit_marketing,loo,nomor_surat_loo,loi,dp,serah_terima,fitting_out',
            'value' => 'nullable|string|max:255',
        ]);

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
