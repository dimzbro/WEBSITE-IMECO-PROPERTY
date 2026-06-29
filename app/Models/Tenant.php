<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'npwp',
        'pic_name',
        'phone',
        'email',
        'address',
        'emergency_contact',
        'business_sector',
    ];

    public function spaceAllocations()
    {
        return $this->hasMany(SpaceAllocation::class);
    }
}
