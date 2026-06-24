<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [HomeController::class, 'contact'])->name('contact');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

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

Route::get('/admin', function () {
    if (!session('admin_logged_in')) {
        return redirect()->route('login');
    }
    return view('admin.dashboard');
})->name('admin');

Route::post('/logout', function (Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    session()->forget('admin_logged_in');
    return redirect()->route('home');
})->name('logout');
