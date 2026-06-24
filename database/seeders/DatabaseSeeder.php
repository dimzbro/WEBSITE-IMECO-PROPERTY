<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure no duplicate email
        User::where('email', 'admin@beltwayofficepark')->delete();

        User::create([
            'name' => 'Admin Beltway',
            'email' => 'admin@beltwayofficepark',
            'password' => \Illuminate\Support\Facades\Hash::make('bopadmin'),
        ]);
    }
}
