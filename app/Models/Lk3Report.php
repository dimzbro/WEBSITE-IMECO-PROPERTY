<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lk3Report extends Model
{
    use HasFactory;

    protected $table = 'lk3_reports';

    protected $fillable = [
        'no',
        'tanggal',
        'nomor_laporan',
        'nama_pelapor',
        'dari_dept',
        'gedung',
        'lantai',
        'laporan',
        'kegiatan',
        'jenis_pekerjaan',
        'departemen_terkait',
        'di_kerjakan',
        'jam',
        'tanggal_selesai',
        'status',
    ];

    protected $casts = [
        'tanggal'         => 'date',
        'tanggal_selesai' => 'date',
    ];
}
