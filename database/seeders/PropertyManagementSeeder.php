<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;
use App\Models\Tenant;
use App\Models\SpaceAllocation;
use Carbon\Carbon;

class PropertyManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Buildings
        $buildings = [
            [
                'name' => 'Gedung A',
                'total_floors' => 8,
                'total_capacity_units' => 32,
            ],
            [
                'name' => 'Gedung B',
                'total_floors' => 8,
                'total_capacity_units' => 32,
            ],
            [
                'name' => 'Gedung C',
                'total_floors' => 8,
                'total_capacity_units' => 32,
            ]
        ];

        $buildingModels = [];
        foreach ($buildings as $b) {
            Building::where('name', $b['name'])->delete();
            $buildingModels[$b['name']] = Building::create($b);
        }

        // 2. Seed Tenants
        $tenants = [
            [
                'company_name' => 'PT Astra Internasional',
                'npwp' => '01.234.567.8-901.000',
                'pic_name' => 'Budi Santoso',
                'phone' => '021-5551234',
                'email' => 'budi@astra.co.id',
                'address' => 'Jl. Jend. Sudirman Kav. 1, Jakarta Pusat 10220',
                'emergency_contact' => '021-5559999 (Security)',
                'business_sector' => 'Korporat',
            ],
            [
                'company_name' => 'CV Teknologi Maju',
                'npwp' => '02.345.678.9-902.000',
                'pic_name' => 'Sari Dewi',
                'phone' => '021-5552345',
                'email' => 'sari@tekno.id',
                'address' => 'Jl. Gatot Subroto No. 45, Jakarta Selatan 12710',
                'emergency_contact' => '021-5558888',
                'business_sector' => 'UKM Teknologi',
            ],
            [
                'company_name' => 'PT Bank Nusantara',
                'npwp' => '03.456.789.0-903.000',
                'pic_name' => 'Ahmad Fauzi',
                'phone' => '021-5553456',
                'email' => 'ahmad@banknusantara.id',
                'address' => 'Jl. MH Thamrin No. 8, Jakarta Pusat 10310',
                'emergency_contact' => '021-5557777',
                'business_sector' => 'Perbankan',
            ],
            [
                'company_name' => 'PT Garuda Logistik',
                'npwp' => '04.567.890.1-904.000',
                'pic_name' => 'Rini Wulandari',
                'phone' => '021-5554567',
                'email' => 'rini@garuda.co.id',
                'address' => 'Jl. Yos Sudarso Kav. 12, Jakarta Utara 14350',
                'emergency_contact' => '021-5556666',
                'business_sector' => 'Logistik',
            ],
            [
                'company_name' => 'PT Medika Farma',
                'npwp' => '05.678.901.2-905.000',
                'pic_name' => 'Dian Kusuma',
                'phone' => '021-5555678',
                'email' => 'dian@medika.id',
                'address' => 'Jl. Rasuna Said Block X-5, Jakarta Selatan 12950',
                'emergency_contact' => '021-5555555',
                'business_sector' => 'Farmasi',
            ],
            [
                'company_name' => 'PT Solusi Digital',
                'npwp' => '06.789.012.3-906.000',
                'pic_name' => 'Eko Prasetyo',
                'phone' => '021-5556789',
                'email' => 'eko@solusidigital.id',
                'address' => 'Jl. Kemang Raya No. 10, Jakarta Selatan 12730',
                'emergency_contact' => '021-5554444',
                'business_sector' => 'Teknologi',
            ],
            [
                'company_name' => 'PT Mandiri Properti',
                'npwp' => '07.890.123.4-907.000',
                'pic_name' => 'Putri Rahayu',
                'phone' => '021-5557890',
                'email' => 'putri@mandiri.id',
                'address' => 'Jl. Asia Afrika No. 19, Bandung 40111',
                'emergency_contact' => '021-5553333',
                'business_sector' => 'Properti',
            ]
        ];

        $tenantModels = [];
        foreach ($tenants as $t) {
            Tenant::where('company_name', $t['company_name'])->delete();
            $tenantModels[$t['company_name']] = Tenant::create($t);
        }

        // 3. Generate Space Allocations
        // We will pre-populate all 32 units for each building.
        // Units layout: floor number 1 to 8, unit index 1 to 4.
        // e.g. Floor 3, unit index 1 -> Zona 1
        $occupiedUnits = [
            'Gedung A' => [
                301 => [
                    'tenant' => 'PT Astra Internasional',
                    'size' => 450,
                    'rent' => 45000000,
                    'start' => '2022-03-15',
                    'end' => '2025-03-15',
                    'status' => 'Terisi',
                    'payment' => 'Lunas'
                ],
                701 => [
                    'tenant' => 'PT Bank Nusantara',
                    'size' => 680,
                    'rent' => 68000000,
                    'start' => '2023-01-18',
                    'end' => '2026-01-18',
                    'status' => 'Terisi',
                    'payment' => 'Lunas'
                ],
                601 => [
                    'tenant' => 'PT Solusi Digital',
                    'size' => 180,
                    'rent' => 18000000,
                    'start' => '2023-09-15',
                    'end' => '2024-09-15',
                    'status' => 'Hampir Berakhir',
                    'payment' => 'Menunggu'
                ]
            ],
            'Gedung B' => [
                501 => [
                    'tenant' => 'CV Teknologi Maju',
                    'size' => 128,
                    'rent' => 12000000,
                    'start' => '2023-08-20',
                    'end' => '2024-08-20',
                    'status' => 'Hampir Berakhir',
                    'payment' => 'Menunggu'
                ],
                402 => [
                    'tenant' => 'PT Medika Farma',
                    'size' => 240,
                    'rent' => 24000000,
                    'start' => '2023-11-30',
                    'end' => '2025-11-30',
                    'status' => 'Terisi',
                    'payment' => 'Lunas'
                ]
            ],
            'Gedung C' => [
                201 => [
                    'tenant' => 'PT Garuda Logistik',
                    'size' => 320,
                    'rent' => 32000000,
                    'start' => '2023-06-01',
                    'end' => '2024-06-01',
                    'status' => 'Berakhir',
                    'payment' => 'Tertunggak'
                ],
                601 => [
                    'tenant' => 'PT Mandiri Properti',
                    'size' => 560,
                    'rent' => 56000000,
                    'start' => '2023-05-20',
                    'end' => '2026-05-20',
                    'status' => 'Terisi',
                    'payment' => 'Lunas'
                ]
            ]
        ];

        foreach ($buildingModels as $bName => $bModel) {
            // Delete existing allocations for building
            SpaceAllocation::where('building_id', $bModel->id)->delete();

            for ($floor = 1; $floor <= 8; $floor++) {
                for ($u = 1; $u <= 4; $u++) {
                    $unitNumInt = ($floor * 100) + $u; // e.g. 301
                    $unitNumStr = 'Zona ' . $u;

                    $allocationData = [
                        'building_id' => $bModel->id,
                        'floor_number' => $floor,
                        'unit_number' => $unitNumStr,
                        'area_size' => rand(100, 200), // Default random size for vacant units
                        'rent_price' => rand(100, 200) * 100000, // Default rent price
                        'status' => 'Kosong',
                        'tenant_id' => null,
                        'lease_start' => null,
                        'lease_end' => null,
                        'payment_status' => null,
                    ];

                    if (isset($occupiedUnits[$bName][$unitNumInt])) {
                        $occ = $occupiedUnits[$bName][$unitNumInt];
                        $tenant = $tenantModels[$occ['tenant']];

                        $allocationData['tenant_id'] = $tenant->id;
                        $allocationData['area_size'] = $occ['size'];
                        $allocationData['rent_price'] = $occ['rent'];
                        $allocationData['status'] = $occ['status'];
                        $allocationData['lease_start'] = $occ['start'];
                        $allocationData['lease_end'] = $occ['end'];
                        $allocationData['payment_status'] = $occ['payment'];
                    }

                    SpaceAllocation::create($allocationData);
                }
            }
        }    }
}
