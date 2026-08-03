<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'source_id',
        'action_url',
        'is_read',
        'read_at',
        'reminder_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'reminder_at' => 'datetime',
    ];

    protected $appends = [
        'relative_time',
        'type_icon',
        'type_badge_color',
    ];

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function getRelativeTimeAttribute(): string
    {
        if (!$this->created_at) {
            return '-';
        }

        Carbon::setLocale('id');
        $now = Carbon::now();
        $createdAt = Carbon::parse($this->created_at);

        $diffInSeconds = $now->diffInSeconds($createdAt);
        if ($diffInSeconds < 60) {
            return 'Baru saja';
        }

        $diffInMinutes = $now->diffInMinutes($createdAt);
        if ($diffInMinutes < 60) {
            return $diffInMinutes . ' menit yang lalu';
        }

        $diffInHours = $now->diffInHours($createdAt);
        if ($diffInHours < 24) {
            return $diffInHours . ' jam yang lalu';
        }

        if ($createdAt->isYesterday()) {
            return 'Kemarin ' . $createdAt->format('H:i');
        }

        if ($createdAt->isCurrentYear()) {
            return $createdAt->translatedFormat('d M H:i');
        }

        return $createdAt->translatedFormat('d M Y H:i');
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'calendar' => 'lucide:calendar',
            'bop_lead' => 'lucide:building-2',
            'electricity' => 'lucide:zap',
            'water' => 'lucide:droplets',
            'lk3' => 'lucide:shield-alert',
            default => 'lucide:bell',
        };
    }

    public function getTypeBadgeColorAttribute(): string
    {
        return match ($this->type) {
            'calendar' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'bop_lead' => 'bg-blue-50 text-[#1E3A8A] border-blue-200',
            'electricity' => 'bg-amber-50 text-amber-700 border-amber-200',
            'water' => 'bg-sky-50 text-sky-700 border-sky-200',
            'lk3' => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}
