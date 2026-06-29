<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'total_floors',
        'total_capacity_units',
    ];

    public function spaceAllocations()
    {
        return $this->hasMany(SpaceAllocation::class);
    }
}
