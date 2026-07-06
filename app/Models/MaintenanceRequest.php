<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'building_id',
        'unit',
        'category',
        'title',
        'description',
        'priority',
        'status',
        'assigned_to',
        'notes',
        'requested_at',
        'completed_at',
        'scheduled_at',
    ];

    protected $casts = [
        'requested_at' => 'date',
        'completed_at' => 'date',
        'scheduled_at' => 'date',
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
