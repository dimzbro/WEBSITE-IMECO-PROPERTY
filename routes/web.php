<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [HomeController::class, 'contact'])->name('contact');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

use App\Http\Controllers\AdminController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\OfficeSpaceController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Lk3ReportController;
use App\Http\Controllers\RekapitulasiRequestController;
use Illuminate\Support\Facades\Auth;

Route::post('/login', function (Illuminate\Http\Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        session(['admin_logged_in' => true]);
        return redirect()->route('admin')->with('success', 'Selamat datang kembali, Admin!');
    }

    return back()->withInput()->withErrors(['login_error' => 'Username atau password salah.']);
});

Route::middleware(['admin.auth'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin');
    Route::resource('tenants', TenantController::class)->names('admin.tenants');
    Route::get('buildings', [BuildingController::class, 'index'])->name('admin.buildings.index');
    Route::post('buildings/allocate', [BuildingController::class, 'allocate'])->name('admin.buildings.allocate');
    Route::post('buildings/release/{allocation}', [BuildingController::class, 'release'])->name('admin.buildings.release');
    Route::resource('news', NewsController::class)->names('admin.news');
    Route::resource('maintenance', MaintenanceRequestController::class)->names('admin.maintenance');
    Route::resource('office-spaces', OfficeSpaceController::class)->names('admin.office_spaces');
    Route::resource('gallery', GalleryController::class)->names('admin.gallery');
    Route::get('calendar', [\App\Http\Controllers\CalendarController::class, 'index'])->name('admin.calendar.index');

    // ── LK3 Reports ──────────────────────────────────────────────────────────
    Route::get('lk3', [Lk3ReportController::class, 'index'])->name('admin.lk3.index');
    Route::post('lk3/import', [Lk3ReportController::class, 'import'])->name('admin.lk3.import');
    Route::delete('lk3/clear-all', [Lk3ReportController::class, 'destroyAll'])->name('admin.lk3.destroyAll');
    Route::delete('lk3/{id}', [Lk3ReportController::class, 'destroy'])->name('admin.lk3.destroy');

    // ── Rekapitulasi Request ──────────────────────────────────────────────────
    Route::get('rekapitulasi', [RekapitulasiRequestController::class, 'index'])->name('admin.rekapitulasi.index');
    Route::post('rekapitulasi/import', [RekapitulasiRequestController::class, 'import'])->name('admin.rekapitulasi.import');
    Route::delete('rekapitulasi/clear-all', [RekapitulasiRequestController::class, 'destroyAll'])->name('admin.rekapitulasi.destroyAll');
    Route::delete('rekapitulasi/{id}', [RekapitulasiRequestController::class, 'destroy'])->name('admin.rekapitulasi.destroy');
});

Route::post('/logout', function (Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    session()->forget('admin_logged_in');
    return redirect()->route('home');
})->name('logout');
