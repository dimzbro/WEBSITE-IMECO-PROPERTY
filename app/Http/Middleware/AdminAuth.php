<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('admin_logged_in') && !Auth::check()) {
            return redirect()->route('login')->withErrors(['login_error' => 'Akses ditolak. Silakan login terlebih dahulu.']);
        }

        // Real-time lease status synchronization
        $today = \Carbon\Carbon::today()->toDateString();
        $thirtyDaysFromNow = \Carbon\Carbon::today()->addDays(30)->toDateString();
        $oneHundredEightyDaysFromNow = \Carbon\Carbon::today()->addDays(180)->toDateString();

        // 1. Any active/allocated space where lease_end < today becomes 'Kontrak Habis'
        \App\Models\SpaceAllocation::whereNotNull('tenant_id')
            ->whereNotNull('lease_end')
            ->where('lease_end', '<', $today)
            ->where('status', '!=', 'Kontrak Habis')
            ->update(['status' => 'Kontrak Habis']);

        // 2. Any active/allocated space where today <= lease_end <= 30 days from now becomes 'Hampir Berakhir'
        \App\Models\SpaceAllocation::whereNotNull('tenant_id')
            ->whereNotNull('lease_end')
            ->where('lease_end', '>=', $today)
            ->where('lease_end', '<=', $thirtyDaysFromNow)
            ->where('status', '!=', 'Hampir Berakhir')
            ->update(['status' => 'Hampir Berakhir']);

        // 3. Any active/allocated space where today + 31 <= lease_end <= 180 days from now becomes 'Kontrak Mendekati Berakhir'
        \App\Models\SpaceAllocation::whereNotNull('tenant_id')
            ->whereNotNull('lease_end')
            ->where('lease_end', '>', $thirtyDaysFromNow)
            ->where('lease_end', '<=', $oneHundredEightyDaysFromNow)
            ->where('status', '!=', 'Kontrak Mendekati Berakhir')
            ->update(['status' => 'Kontrak Mendekati Berakhir']);

        // 4. Any active/allocated space where lease_end > 180 days from now becomes 'Kontrak Aktif'
        \App\Models\SpaceAllocation::whereNotNull('tenant_id')
            ->whereNotNull('lease_end')
            ->where('lease_end', '>', $oneHundredEightyDaysFromNow)
            ->where('status', '!=', 'Kontrak Aktif')
            ->update(['status' => 'Kontrak Aktif']);
            
        // 5. Any space without a tenant should be 'Kosong'
        \App\Models\SpaceAllocation::whereNull('tenant_id')
            ->where('status', '!=', 'Kosong')
            ->update([
                'status' => 'Kosong',
                'lease_start' => null,
                'lease_end' => null,
                'payment_status' => null
            ]);

        return $next($request);
    }
}
