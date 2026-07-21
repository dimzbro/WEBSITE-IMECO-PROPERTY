<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RekapitulasiRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class RekapitulasiRequestController extends Controller
{
    /**
     * Display a listing of Rekapitulasi Requests.
     */
    public function index(Request $request)
    {
        $query = RekapitulasiRequest::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('no_cr', 'like', "%{$search}%")
                  ->orWhere('tenant', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('request_description', 'like', "%{$search}%")
                  ->orWhere('request_handling', 'like', "%{$search}%")
                  ->orWhere('jenis_pekerjaan', 'like', "%{$search}%")
                  ->orWhere('name_handling', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis_pekerjaan')) {
            $query->where('jenis_pekerjaan', $request->input('jenis_pekerjaan'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('tenant')) {
            $query->where('tenant', 'like', '%' . $request->input('tenant') . '%');
        }
        if ($request->filled('year')) {
            $query->whereYear('date', $request->input('year'));
        }
        if ($request->filled('month')) {
            $query->whereMonth('date', $request->input('month'));
        }

        // 1. Clone clean query for counts
        $totalRecords = (clone $query)->count();
        $doneCount    = (clone $query)->where('status', 'Done')->count();

        $chartJenisPekerjaan = (clone $query)->select('jenis_pekerjaan', DB::raw('count(*) as total'))
            ->whereNotNull('jenis_pekerjaan')->where('jenis_pekerjaan', '!=', '')
            ->groupBy('jenis_pekerjaan')->orderByDesc('total')->get();

        // 2. Apply order and pagination to main query
        $requests = $query->orderBy('date', 'desc')
                          ->orderBy('id', 'desc')
                          ->paginate(15)
                          ->withQueryString();

        $filterJenisPekerjaan = RekapitulasiRequest::select('jenis_pekerjaan')
            ->whereNotNull('jenis_pekerjaan')->where('jenis_pekerjaan', '!=', '')
            ->distinct()->orderBy('jenis_pekerjaan')->pluck('jenis_pekerjaan');

        $filterStatus = RekapitulasiRequest::select('status')
            ->whereNotNull('status')->where('status', '!=', '')
            ->distinct()->orderBy('status')->pluck('status');

        // Static months in Indonesian
        $filterMonths = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        // Dynamic years based on DB inputs, guaranteed to scale from 2026 up to the current system year
        $oldestDate = RekapitulasiRequest::min('date');
        $oldestYear = $oldestDate ? Carbon::parse($oldestDate)->year : 2026;
        
        $minYear     = min(2026, $oldestYear);
        $currentYear = Carbon::now()->year;
        $maxYear     = max($currentYear, $oldestYear);
        
        $filterYears = range($maxYear, $minYear);

        return view('admin.rekapitulasi.index', compact(
            'requests', 'totalRecords', 'doneCount', 'chartJenisPekerjaan',
            'filterJenisPekerjaan', 'filterStatus', 'filterMonths', 'filterYears'
        ));
    }

    /**
     * Import Excel (.xlsx) or CSV file into rekapitulasi_requests table.
     *
     * Kolom yang diharapkan (dari file Excel):
     * No CR | Tenant | Name | Date | Request description | Request handling |
     * Jenis Pekerjaan | Name Handling | Time | Status | Cost
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|max:10240',
        ], [
            'csv_file.required' => 'File wajib dipilih.',
            'csv_file.max'      => 'Ukuran file maksimal 10MB.',
        ]);

        $file      = $request->file('csv_file');
        $extension = strtolower($file->getClientOriginalExtension());
        $path      = $file->getRealPath();

        try {
            if (in_array($extension, ['xlsx', 'xls', 'ods'])) {
                $rows = $this->readExcelFile($path);
            } else {
                $rows = $this->readCsvFile($path);
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.rekapitulasi.index')
                ->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }

        if (empty($rows)) {
            return redirect()->route('admin.rekapitulasi.index')
                ->with('error', 'File kosong atau tidak dapat dibaca.');
        }

        // ── Header row ───────────────────────────────────────────────────────
        $rawHeaders = array_shift($rows);
        $headers    = $this->normalizeHeaders($rawHeaders);

        // ── Column mapping sesuai file Rekapitulasi di screenshot ────────────
        $columnMap = [
            'no_cr'               => ['no_cr', 'no cr', 'nocr', 'cr', 'nomor_cr', 'no__cr', 'number_cr'],
            'tenant'              => ['tenant', 'pt', 'perusahaan', 'company', 'nama_tenant'],
            'name'                => ['name', 'nama', 'pic', 'nama_pic', 'mr'],
            'date'                => ['date', 'tanggal', 'tgl', 'tanggal_request'],
            'request_description' => ['request_description', 'request description', 'deskripsi', 'keluhan', 'description', 'permintaan', 'laporan', 'request_desc'],
            'request_handling'    => ['request_handling', 'request handling', 'penanganan', 'handling', 'tindakan', 'solusi'],
            'jenis_pekerjaan'     => ['jenis_pekerjaan', 'jenis pekerjaan', 'jenis', 'kategori', 'type'],
            'name_handling'       => ['name_handling', 'name handling', 'namehandling', 'teknisi', 'petugas', 'handler', 'nama_teknisi', 'dikerjakan', 'di_kerjakan'],
            'time'                => ['time', 'waktu', 'jam', 'durasi', 'hours'],
            'status'              => ['status', 'kondisi', 'state'],
            'cost'                => ['cost', 'biaya', 'harga', 'total_biaya', 'total biaya', 'total', 'nilai'],
        ];

        $indexMap = $this->buildIndexMap($headers, $columnMap);

        if (empty($indexMap)) {
            $headerDebug = implode(' | ', array_slice($headers, 0, 15));
            return redirect()->route('admin.rekapitulasi.index')
                ->with('error', "Kolom tidak ditemukan. Header terdeteksi: [{$headerDebug}]. Pastikan file sesuai format Rekapitulasi Request.");
        }

        // ── Parse baris data ─────────────────────────────────────────────────
        $records     = [];
        $skippedRows = 0;
        $now         = now()->toDateTimeString();

        foreach ($rows as $row) {
            if (empty(array_filter($row, fn($v) => trim((string)$v) !== ''))) {
                $skippedRows++;
                continue;
            }

            $data = [];
            foreach ($indexMap as $dbCol => $colIndex) {
                $val = isset($row[$colIndex]) ? $row[$colIndex] : null;

                if ($dbCol === 'date') {
                    $val = $this->parseDate($val);
                } elseif ($dbCol === 'cost') {
                    $val = $this->parseCost($val);
                } else {
                    $val = $val !== null ? trim((string) $val) : null;
                    $val = $val === '' ? null : $val;
                }

                $data[$dbCol] = $val;
            }

            if (empty(array_filter($data, fn($v) => $v !== null))) {
                $skippedRows++;
                continue;
            }

            $data['created_at'] = $now;
            $data['updated_at'] = $now;
            $records[] = $data;
        }

        if (empty($records)) {
            return redirect()->route('admin.rekapitulasi.index')
                ->with('error', 'Tidak ada baris data valid yang ditemukan dalam file.');
        }

        // ── Insert ke database ───────────────────────────────────────────────
        try {
            DB::transaction(function () use ($records) {
                foreach (array_chunk($records, 500) as $chunk) {
                    DB::table('rekapitulasi_requests')->insert($chunk);
                }
            });
        } catch (\Exception $e) {
            return redirect()->route('admin.rekapitulasi.index')
                ->with('error', 'Gagal menyimpan ke database: ' . $e->getMessage());
        }

        $count   = count($records);
        $message = "Berhasil mengimpor {$count} data Rekapitulasi Request.";
        if ($skippedRows > 0) {
            $message .= " ({$skippedRows} baris kosong dilewati)";
        }

        return redirect()->route('admin.rekapitulasi.index')->with('success', $message);
    }

    // ── Helper: Baca file Excel ───────────────────────────────────────────────
    private function readExcelFile(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = [];

        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            while (!empty($rowData) && end($rowData) === null) {
                array_pop($rowData);
            }

            $rows[] = $rowData;
        }

        return $rows;
    }

    // ── Helper: Baca file CSV ─────────────────────────────────────────────────
    private function readCsvFile(string $path): array
    {
        $rawContent = file_get_contents($path);
        $rawContent = ltrim($rawContent, "\xEF\xBB\xBF");

        if (!mb_check_encoding($rawContent, 'UTF-8')) {
            $rawContent = mb_convert_encoding($rawContent, 'UTF-8', 'Windows-1252');
        }

        $rawContent = str_replace(["\r\n", "\r"], "\n", $rawContent);

        $firstLine = strtok($rawContent, "\n");
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        $lines = array_filter(explode("\n", $rawContent), fn($l) => trim($l) !== '');
        $rows  = [];
        foreach (array_values($lines) as $line) {
            $rows[] = str_getcsv($line, $delimiter);
        }

        return $rows;
    }

    // ── Helper: Normalize headers ─────────────────────────────────────────────
    private function normalizeHeaders(array $rawHeaders): array
    {
        return array_map(function ($h) {
            if ($h === null) return '';
            $h = (string) $h;
            $h = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h);
            $h = trim($h);
            $h = strtolower($h);
            $h = str_replace([' ', '-', '.', '/', '\\', '(', ')'], '_', $h);
            $h = preg_replace('/_+/', '_', $h);
            $h = trim($h, '_');
            return $h;
        }, $rawHeaders);
    }

    // ── Helper: Build index map ───────────────────────────────────────────────
    private function buildIndexMap(array $headers, array $columnMap): array
    {
        $indexMap = [];
        foreach ($columnMap as $dbCol => $aliases) {
            foreach ($aliases as $alias) {
                $norm = strtolower(trim(str_replace([' ', '-', '.', '/'], '_', $alias)));
                $norm = preg_replace('/_+/', '_', $norm);
                $norm = trim($norm, '_');
                $pos  = array_search($norm, $headers);
                if ($pos !== false) {
                    $indexMap[$dbCol] = $pos;
                    break;
                }
            }
        }
        return $indexMap;
    }

    // ── Helper: Parse tanggal ─────────────────────────────────────────────────
    private function parseDate($val): ?string
    {
        if ($val === null || $val === '') return null;

        // Excel serial date number
        if (is_numeric($val) && $val > 1000) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $val)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        $val = trim((string) $val);
        if ($val === '') return null;

        try {
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $val)) {
                return Carbon::createFromFormat('d/m/Y', $val)->toDateString();
            }
            if (preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $val)) {
                return Carbon::createFromFormat('d-m-Y', $val)->toDateString();
            }
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) {
                return $val;
            }
            // Format like "1-Jul-2026" or "Jul 1, 2026"
            return Carbon::parse($val)->toDateString();
        } catch (\Exception $e) {
            return null;
        }
    }

    // ── Helper: Parse biaya (Rp format Indonesia) ────────────────────────────
    private function parseCost($val): ?float
    {
        if ($val === null || $val === '') return null;

        // Jika sudah numeric (dari Excel)
        if (is_numeric($val)) return (float) $val;

        $val     = trim((string) $val);
        $cleaned = preg_replace('/[^0-9,.]/', '', $val);

        if ($cleaned === '') return null;

        $dots   = substr_count($cleaned, '.');
        $commas = substr_count($cleaned, ',');

        if ($commas === 0 && $dots > 1) {
            // 1.350.000 → ribuan pakai titik
            $cleaned = str_replace('.', '', $cleaned);
        } elseif ($commas === 1 && $dots > 0) {
            // 1.350,50 → ribuan titik, desimal koma
            $cleaned = str_replace('.', '', $cleaned);
            $cleaned = str_replace(',', '.', $cleaned);
        } elseif ($commas === 0 && $dots === 1) {
            $parts = explode('.', $cleaned);
            if (strlen($parts[1]) === 3) {
                // 350.000 → ribuan
                $cleaned = str_replace('.', '', $cleaned);
            }
        } elseif ($commas > 0 && $dots === 0) {
            $cleaned = str_replace(',', '', $cleaned);
        }

        return is_numeric($cleaned) ? (float) $cleaned : null;
    }

    /**
     * Delete a single record.
     */
    public function destroy($id)
    {
        RekapitulasiRequest::findOrFail($id)->delete();
        return redirect()->route('admin.rekapitulasi.index')
            ->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Delete Rekapitulasi Request records (by month if specified in filter, or all).
     */
    public function destroyAll(Request $request)
    {
        if ($request->filled('month')) {
            $month = $request->input('month');
            $query = RekapitulasiRequest::whereMonth('date', $month);

            if ($request->filled('year')) {
                $year = $request->input('year');
                $query->whereYear('date', $year);
            }

            $count = $query->delete();

            $monthNames = [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $label = $monthNames[$month] ?? $month;
            if ($request->filled('year')) {
                $label .= ' ' . $request->input('year');
            }

            return redirect()->route('admin.rekapitulasi.index')
                ->with('success', "Berhasil menghapus {$count} data Rekapitulasi Request bulan {$label}.");
        }

        RekapitulasiRequest::truncate();
        return redirect()->route('admin.rekapitulasi.index')
            ->with('success', 'Semua data Rekapitulasi Request berhasil dihapus.');
    }
}
