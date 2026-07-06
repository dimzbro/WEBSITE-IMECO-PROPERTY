<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeSpace extends Model
{
    protected $fillable = [
        'tower',
        'floor',
        'sqm',
        'price',
        'status',
        'image',
        'filter',
    ];

    /**
     * Get the price attribute formatted with /month instead of /sqm/mo.
     */
    public function getPriceAttribute($value)
    {
        $value = str_replace('/sqm/mo', '/month', $value);
        return str_replace('/sqm', '/month', $value);
    }
}
