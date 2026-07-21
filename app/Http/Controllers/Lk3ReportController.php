<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lk3Report;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class Lk3ReportController extends Controller
{
    /**
     * Display a listing of LK3 reports with search, filter, pagination, and chart data.
     */
    public function index(Request $request)
    {
        $query = Lk3Report::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nomor_laporan', 'like', "%{$search}%")
                  ->orWhere('nama_pelapor', 'like', "%{$search}%")
                  ->orWhere('laporan', 'like', "%{$search}%")
                  ->orWhere('kegiatan', 'like', "%{$search}%")
                  ->orWhere('dari_dept', 'like', "%{$search}%")
                  ->orWhere('jenis_pekerjaan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis_pekerjaan')) {
            $query->where('jenis_pekerjaan', $request->input('jenis_pekerjaan'));
        }
        if ($request->filled('dari_dept')) {
            $query->where('dari_dept', $request->input('dari_dept'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('year')) {
            $query->whereYear('tanggal', $request->input('year'));
        }
        if ($request->filled('month')) {
            $query->whereMonth('tanggal', $request->input('month'));
        }

        // 1. Clone the clean query (with only filters applied) for counts and charts!
        $totalRecords = (clone $query)->count();
        $doneCount    = (clone $query)->where('status', 'Done')->count();

        $chartJenisPekerjaan = (clone $query)->select('jenis_pekerjaan', DB::raw('count(*) as total'))
            ->whereNotNull('jenis_pekerjaan')->where('jenis_pekerjaan', '!=', '')
            ->groupBy('jenis_pekerjaan')->orderByDesc('total')->get();

        $chartDariDept = (clone $query)->select('dari_dept', DB::raw('count(*) as total'))
            ->whereNotNull('dari_dept')->where('dari_dept', '!=', '')
            ->groupBy('dari_dept')->orderByDesc('total')->get();

        // 2. Apply order and pagination to the main query
        $reports = $query->orderBy('tanggal', 'desc')
                         ->orderBy('id', 'desc')
                         ->paginate(15)
                         ->withQueryString();

        $filterJenisPekerjaan = Lk3Report::select('jenis_pekerjaan')
            ->whereNotNull('jenis_pekerjaan')->where('jenis_pekerjaan', '!=', '')
            ->distinct()->orderBy('jenis_pekerjaan')->pluck('jenis_pekerjaan');

        $filterDariDept = Lk3Report::select('dari_dept')
            ->whereNotNull('dari_dept')->where('dari_dept', '!=', '')
            ->distinct()->orderBy('dari_dept')->pluck('dari_dept');

        $filterStatus = Lk3Report::select('status')
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
        $oldestDate = Lk3Report::min('tanggal');
        $oldestYear = $oldestDate ? Carbon::parse($oldestDate)->year : 2026;
        
        $minYear     = min(2026, $oldestYear);
        $currentYear = Carbon::now()->year;
        $maxYear     = max($currentYear, $oldestYear);
        
        $filterYears = range($maxYear, $minYear);

        return view('admin.lk3.index', compact(
            'reports', 'totalRecords', 'doneCount',
            'chartJenisPekerjaan', 'chartDariDept',
            'filterJenisPekerjaan', 'filterDariDept', 'filterStatus', 'filterMonths', 'filterYears'
        ));
    }

    /**
     * Import Excel (.xlsx) or CSV file into lk3_reports table.
     *
     * Kolom yang diharapkan (dari file Excel):
     * No | Tanggal | Nomor Laporan | Nama Pelapor | Dari Dept | Laporan |
     * Kegiatan | Jenis Pekerjaan | Departemen Terkait | Di kerjakan | Jam |
     * Tanggal Selesai | Status
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
            // ── Baca file: Excel atau CSV ────────────────────────────────────
            if (in_array($extension, ['xlsx', 'xls', 'ods'])) {
                $rows = $this->readExcelFile($path);
            } else {
                $rows = $this->readCsvFile($path);
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.lk3.index')
                ->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }

        if (empty($rows)) {
            return redirect()->route('admin.lk3.index')
                ->with('error', 'File kosong atau tidak dapat dibaca.');
        }

        // ── Ambil header dari baris pertama ──────────────────────────────────
        $rawHeaders = array_shift($rows);
        $headers    = $this->normalizeHeaders($rawHeaders);

        // ── Mapping kolom: nama DB => alias yang mungkin muncul di file ──────
        // Disesuaikan PERSIS dengan kolom Excel LK3 di screenshot
        $columnMap = [
            'no'                 => ['no', 'no_', '#', 'nomor', 'num'],
            'tanggal'            => ['tanggal', 'tgl', 'date', 'tanggal_laporan'],
            'nomor_laporan'      => ['nomor_laporan', 'nomor laporan', 'no_laporan', 'no laporan', 'no._laporan', 'number'],
            'nama_pelapor'       => ['nama_pelapor', 'nama pelapor', 'pelapor', 'reporter', 'nama'],
            'dari_dept'          => ['dari_dept', 'dari dept', 'dari', 'dept', 'departemen', 'from_dept', 'dept_pelapor'],
            'laporan'            => ['laporan', 'isi_laporan', 'deskripsi', 'keterangan', 'report'],
            'kegiatan'           => ['kegiatan', 'keterangan_kegiatan', 'tindakan', 'activity', 'handling'],
            'jenis_pekerjaan'    => ['jenis_pekerjaan', 'jenis pekerjaan', 'jenis', 'kategori', 'type', 'work_type'],
            'departemen_terkait' => ['departemen_terkait', 'departemen terkait', 'dept_terkait', 'dept terkait'],
            'di_kerjakan'        => ['di_kerjakan', 'di kerjakan', 'dikerjakan', 'petugas', 'teknisi', 'worker'],
            'jam'                => ['jam', 'time', 'waktu', 'durasi', 'hours'],
            'tanggal_selesai'    => ['tanggal_selesai', 'tanggal selesai', 'tgl_selesai', 'selesai', 'done_date', 'completion'],
            'status'             => ['status', 'kondisi', 'state'],
        ];

        $indexMap = $this->buildIndexMap($headers, $columnMap);

        if (empty($indexMap)) {
            $headerDebug = implode(' | ', array_slice($headers, 0, 15));
            return redirect()->route('admin.lk3.index')
                ->with('error', "Kolom tidak ditemukan. Header terdeteksi: [{$headerDebug}]. Pastikan file sesuai format LK3.");
        }

        // ── Parse setiap baris data ───────────────────────────────────────────
        $records     = [];
        $skippedRows = 0;
        $now         = now()->toDateTimeString();

        foreach ($rows as $row) {
            // Skip baris kosong
            if (empty(array_filter($row, fn($v) => trim((string)$v) !== ''))) {
                $skippedRows++;
                continue;
            }

            $data = [];
            foreach ($indexMap as $dbCol => $colIndex) {
                $val = isset($row[$colIndex]) ? $row[$colIndex] : null;

                // PhpSpreadsheet mengembalikan nilai numerik untuk tanggal Excel
                if (in_array($dbCol, ['tanggal', 'tanggal_selesai'])) {
                    $val = $this->parseDate($val);
                } elseif ($dbCol === 'no') {
                    $val = is_numeric($val) ? (int) $val : null;
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
            return redirect()->route('admin.lk3.index')
                ->with('error', 'Tidak ada baris data valid yang ditemukan dalam file.');
        }

        // ── Insert ke database ───────────────────────────────────────────────
        try {
            DB::transaction(function () use ($records) {
                foreach (array_chunk($records, 500) as $chunk) {
                    DB::table('lk3_reports')->insert($chunk);
                }
            });
        } catch (\Exception $e) {
            return redirect()->route('admin.lk3.index')
                ->with('error', 'Gagal menyimpan ke database: ' . $e->getMessage());
        }

        $count   = count($records);
        $message = "Berhasil mengimpor {$count} data LK3.";
        if ($skippedRows > 0) {
            $message .= " ({$skippedRows} baris kosong dilewati)";
        }

        return redirect()->route('admin.lk3.index')->with('success', $message);
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

            // Trim trailing null cells
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

        // Hapus BOM
        $rawContent = ltrim($rawContent, "\xEF\xBB\xBF");

        // Konversi encoding
        if (!mb_check_encoding($rawContent, 'UTF-8')) {
            $rawContent = mb_convert_encoding($rawContent, 'UTF-8', 'Windows-1252');
        }

        // Normalisasi line ending
        $rawContent = str_replace(["\r\n", "\r"], "\n", $rawContent);

        // Auto-detect delimiter
        $firstLine  = strtok($rawContent, "\n");
        $delimiter  = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

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
            $h = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h); // hapus non-printable & BOM
            $h = trim($h);
            $h = strtolower($h);
            $h = str_replace([' ', '-', '.', '/', '\\', '(', ')'], '_', $h);
            $h = preg_replace('/_+/', '_', $h); // collapse multiple underscores
            $h = trim($h, '_');
            return $h;
        }, $rawHeaders);
    }

    // ── Helper: Build index map dari headers & column map ────────────────────
    private function buildIndexMap(array $headers, array $columnMap): array
    {
        $indexMap = [];
        foreach ($columnMap as $dbCol => $aliases) {
            foreach ($aliases as $alias) {
                $normalizedAlias = strtolower(trim(str_replace([' ', '-', '.', '/'], '_', $alias)));
                $normalizedAlias = preg_replace('/_+/', '_', $normalizedAlias);
                $normalizedAlias = trim($normalizedAlias, '_');
                $pos = array_search($normalizedAlias, $headers);
                if ($pos !== false) {
                    $indexMap[$dbCol] = $pos;
                    break;
                }
            }
        }
        return $indexMap;
    }

    // ── Helper: Parse berbagai format tanggal ────────────────────────────────
    private function parseDate($val): ?string
    {
        if ($val === null || $val === '') return null;

        // PhpSpreadsheet: nilai numerik Excel serial date
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
            // DD/MM/YYYY
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $val)) {
                return Carbon::createFromFormat('d/m/Y', $val)->toDateString();
            }
            // DD-MM-YYYY
            if (preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $val)) {
                return Carbon::createFromFormat('d-m-Y', $val)->toDateString();
            }
            // YYYY-MM-DD
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) {
                return $val;
            }
            return Carbon::parse($val)->toDateString();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Delete a single LK3 report record.
     */
    public function destroy($id)
    {
        Lk3Report::findOrFail($id)->delete();
        return redirect()->route('admin.lk3.index')
            ->with('success', 'Data LK3 berhasil dihapus.');
    }

    /**
     * Delete LK3 report records (by month if specified in filter, or all).
     */
    public function destroyAll(Request $request)
    {
        if ($request->filled('month')) {
            $month = $request->input('month');
            $query = Lk3Report::whereMonth('tanggal', $month);

            if ($request->filled('year')) {
                $year = $request->input('year');
                $query->whereYear('tanggal', $year);
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

            return redirect()->route('admin.lk3.index')
                ->with('success', "Berhasil menghapus {$count} data LK3 bulan {$label}.");
        }

        Lk3Report::truncate();
        return redirect()->route('admin.lk3.index')
            ->with('success', 'Semua data LK3 berhasil dihapus.');
    }
}
