<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaintenanceRequest;

class MaintenanceRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clean existing records first (no dummy maintenance requests)
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        MaintenanceRequest::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }
}
