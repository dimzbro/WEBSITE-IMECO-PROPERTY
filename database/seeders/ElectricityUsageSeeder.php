<?php

namespace Database\Seeders;

use App\Models\ElectricityUsage;
use Illuminate\Database\Seeder;

class ElectricityUsageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sampleData = [
            // Gedung A 2025 & 2026 (Prompt example)
            ['gedung' => 'Gedung A', 'tahun' => 2025, 'bulan' => 1, 'kwh' => 82165.00],
            ['gedung' => 'Gedung A', 'tahun' => 2026, 'bulan' => 1, 'kwh' => 90500.00],

            ['gedung' => 'Gedung A', 'tahun' => 2025, 'bulan' => 2, 'kwh' => 85000.00],
            ['gedung' => 'Gedung A', 'tahun' => 2026, 'bulan' => 2, 'kwh' => 87200.00],

            ['gedung' => 'Gedung A', 'tahun' => 2025, 'bulan' => 3, 'kwh' => 88200.00],
            ['gedung' => 'Gedung A', 'tahun' => 2026, 'bulan' => 3, 'kwh' => 86000.00],

            ['gedung' => 'Gedung A', 'tahun' => 2025, 'bulan' => 4, 'kwh' => 83400.00],
            ['gedung' => 'Gedung A', 'tahun' => 2026, 'bulan' => 4, 'kwh' => 89100.00],

            ['gedung' => 'Gedung A', 'tahun' => 2025, 'bulan' => 5, 'kwh' => 86500.00],
            ['gedung' => 'Gedung A', 'tahun' => 2026, 'bulan' => 5, 'kwh' => 88000.00],

            ['gedung' => 'Gedung A', 'tahun' => 2025, 'bulan' => 6, 'kwh' => 89000.00],
            ['gedung' => 'Gedung A', 'tahun' => 2026, 'bulan' => 6, 'kwh' => 91500.00],

            // Gedung B
            ['gedung' => 'Gedung B', 'tahun' => 2025, 'bulan' => 1, 'kwh' => 65400.00],
            ['gedung' => 'Gedung B', 'tahun' => 2026, 'bulan' => 1, 'kwh' => 64000.00],

            ['gedung' => 'Gedung B', 'tahun' => 2025, 'bulan' => 2, 'kwh' => 67000.00],
            ['gedung' => 'Gedung B', 'tahun' => 2026, 'bulan' => 2, 'kwh' => 68500.00],

            // Gedung C
            ['gedung' => 'Gedung C', 'tahun' => 2025, 'bulan' => 1, 'kwh' => 45000.00],
            ['gedung' => 'Gedung C', 'tahun' => 2026, 'bulan' => 1, 'kwh' => 48200.00],

            // Gedung Annex Baru
            ['gedung' => 'Gedung Annex Baru', 'tahun' => 2025, 'bulan' => 1, 'kwh' => 32100.00],
            ['gedung' => 'Gedung Annex Baru', 'tahun' => 2026, 'bulan' => 1, 'kwh' => 31500.00],

            // Kantin
            ['gedung' => 'Kantin', 'tahun' => 2025, 'bulan' => 1, 'kwh' => 12500.00],
            ['gedung' => 'Kantin', 'tahun' => 2026, 'bulan' => 1, 'kwh' => 13800.00],
        ];

        foreach ($sampleData as $data) {
            ElectricityUsage::updateOrCreate(
                [
                    'gedung' => $data['gedung'],
                    'tahun'  => $data['tahun'],
                    'bulan'  => $data['bulan'],
                ],
                [
                    'kwh'    => $data['kwh'],
                ]
            );
        }
    }
}
