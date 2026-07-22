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
     * Get the price attribute formatted with /sqm/month.
     */
    public function getPriceAttribute($value)
    {
        if (empty($value)) return $value;

        // Strip any existing suffix starting with '/' to prevent duplication
        $base = preg_replace('#/.*$#', '', trim($value));
        return $base . '/sqm/month';
    }
}
