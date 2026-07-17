<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapitulasiRequest extends Model
{
    use HasFactory;

    protected $table = 'rekapitulasi_requests';

    protected $fillable = [
        'no_cr',
        'tenant',
        'name',
        'date',
        'request_description',
        'request_handling',
        'jenis_pekerjaan',
        'name_handling',
        'time',
        'status',
        'cost',
    ];

    protected $casts = [
        'date' => 'date',
        'cost' => 'decimal:2',
    ];
}
