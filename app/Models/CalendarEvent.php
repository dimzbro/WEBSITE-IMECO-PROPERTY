<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $fillable = [
        'title',
        'category',
        'event_date',
        'location',
        'reminder_time',
        'notes',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    public function getCategoryColorAttribute(): string
    {
        return match ($this->category) {
            'Meeting' => 'bg-indigo-500 text-white',
            'Inspeksi' => 'bg-emerald-500 text-white',
            'Maintenance' => 'bg-amber-500 text-white',
            'Acara' => 'bg-purple-500 text-white',
            default => 'bg-slate-600 text-white',
        };
    }
}
