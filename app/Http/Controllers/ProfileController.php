<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman Pengaturan Akun / Profil Pengguna.
     */
    public function index()
    {
        $user = Auth::user() ?? User::where('email', 'admin@beltway.co.id')->first() ?? User::first();
        return view('admin.profile.index', compact('user'));
    }

    /**
     * Proses ubah password dengan validasi ketat & pembuatan notifikasi in-app.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user() ?? User::where('email', 'admin@beltway.co.id')->first() ?? User::first();

        // 1. Rule & Pesan Validasi
        $rules = [
            'current_password'          => ['required'],
            'new_password'              => [
                'required',
                'min:8',
                'regex:/[A-Z]/', // minimal 1 huruf besar
                'regex:/[a-z]/', // minimal 1 huruf kecil
                'regex:/[0-9]/', // minimal 1 angka
                'different:current_password',
            ],
            'new_password_confirmation' => ['required', 'same:new_password'],
        ];

        $messages = [
            'current_password.required'          => 'Password saat ini wajib diisi.',
            'new_password.required'              => 'Password baru wajib diisi.',
            'new_password.min'                   => 'Password baru minimal 8 karakter.',
            'new_password.regex'                 => 'Password baru harus mengandung minimal 1 huruf besar, 1 huruf kecil, dan 1 angka.',
            'new_password.different'             => 'Password baru tidak boleh sama dengan password saat ini.',
            'new_password_confirmation.required' => 'Konfirmasi password baru wajib diisi.',
            'new_password_confirmation.same'     => 'Konfirmasi password harus sama dengan password baru.',
        ];

        $request->validate($rules, $messages);

        // 2. Verifikasi Password Saat Ini dengan Hashing Laravel
        if (!$user || !Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.'
            ]);
        }

        // 3. Simpan Password Baru Menggunakan Hashing Laravel
        $user->password = Hash::make($request->new_password);
        $user->save();

        // 4. Buat In-App Notification Otomatis pada Tabel Notifications
        $now = Carbon::now();
        $formattedDateTime = $now->translatedFormat('d F Y') . ' pukul ' . $now->format('H:i');

        Notification::create([
            'title'       => 'Password Berhasil Diubah',
            'description' => "Password akun Anda telah berhasil diperbarui pada tanggal {$formattedDateTime} WIB.",
            'type'        => 'account',
            'source_id'   => 'password_change_' . $user->id . '_' . time(),
            'action_url'  => route('admin.profile.index'),
            'is_read'     => false,
            'reminder_at' => $now,
        ]);

        // 5. Kembali ke Halaman Pengaturan Akun dengan Pesan Sukses
        return redirect()->route('admin.profile.index')
            ->with('success', 'Password berhasil diperbarui.');
    }
}
