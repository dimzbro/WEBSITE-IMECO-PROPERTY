<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeBopLead extends Model
{
    use HasFactory;

    protected $table = 'office_bop_leads';

    protected $fillable = [
        'tahun',
        'bulan',
        'tanggal_entry',
        'nama',
        'email',
        'nama_perusahaan',
        'alamat',
        'telpon_fax',
        'kategori_diminati',
        'kit_marketing',
        'loo',
        'nomor_surat_loo',
        'nomlet_dikirim',
        'nomlet_disetujui',
        'dp',
        'serah_terima',
        'fitting_out',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'bulan' => 'integer',
        'tanggal_entry' => 'date',
    ];

    public static function listBulan(): array
    {
        return [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
    }

    public static function listBulanRomawi(): array
    {
        return [
            1  => 'I JANUARI',
            2  => 'II FEBRUARI',
            3  => 'III MARET',
            4  => 'IV APRIL',
            5  => 'V MEI',
            6  => 'VI JUNI',
            7  => 'VII JULI',
            8  => 'VIII AGUSTUS',
            9  => 'IX SEPTEMBER',
            10 => 'X OKTOBER',
            11 => 'XI NOVEMBER',
            12 => 'XII DESEMBER',
        ];
    }

    public function getNamaBulanAttribute(): string
    {
        return self::listBulan()[$this->bulan] ?? '-';
    }

    public function getNamaBulanRomawiAttribute(): string
    {
        return self::listBulanRomawi()[$this->bulan] ?? '-';
    }
}
