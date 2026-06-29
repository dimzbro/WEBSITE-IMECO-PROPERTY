<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'building_id',
        'floor_number',
        'unit_number',
        'area_size',
        'rent_price',
        'lease_start',
        'lease_end',
        'status', // Terisi, Hampir Berakhir, Berakhir, Kosong
        'payment_status', // Lunas, Menunggu, Tertunggak
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
