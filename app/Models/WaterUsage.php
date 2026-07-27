<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterUsage extends Model
{
    use HasFactory;

    protected $table = 'water_usages';

    protected $fillable = [
        'gedung',
        'nomor_id',
        'tahun',
        'bulan',
        'debet_air',
    ];

    protected $casts = [
        'tahun'     => 'integer',
        'bulan'     => 'integer',
        'debet_air' => 'float',
    ];

    public static function listGedung(): array
    {
        return [
            'Gedung A',
            'Gedung B',
            'Gedung C',
            'Gedung Annex Baru',
            'Gedung Annex Lama',
            'KWH Meter PJU Gedung B',
            'Kantin',
            'KWH Meter PJU Annex Lama',
            'KWH Meter Kontener',
        ];
    }

    public static function getNomorIdMapping(): array
    {
        return [
            'Gedung A'                 => '543103461270',
            'Gedung B'                 => '548400528238',
            'Gedung C'                 => '543800111438',
            'Gedung Annex Baru'        => '543800027370',
            'Gedung Annex Lama'        => '543800153055',
            'KWH Meter PJU Gedung B'   => '543800028244',
            'Kantin'                   => '547101731789',
            'KWH Meter PJU Annex Lama' => '547400024082',
            'KWH Meter Kontener'       => '543800027504',
        ];
    }

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

    public function getNomorIdAttribute($value): string
    {
        if (!empty($value)) {
            return $value;
        }
        return self::getNomorIdMapping()[$this->gedung] ?? '-';
    }

    public function getNamaBulanAttribute(): string
    {
        return self::listBulan()[$this->bulan] ?? '-';
    }

    public function getDebetAirFormattedAttribute(): string
    {
        return number_format($this->debet_air, 2, ',', '.');
    }
}
