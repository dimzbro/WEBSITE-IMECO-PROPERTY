<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing admin if any
        User::where('email', 'admin@beltway.co.id')->delete();

        User::create([
            'name' => 'Admin Kawasan',
            'email' => 'admin@beltway.co.id',
            'password' => Hash::make('admin1234'),
        ]);
    }
}
