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
            ],
            [
                'name' => 'Annex',
                'total_floors' => 2,
                'total_capacity_units' => 2,
            ],
            [
                'name' => 'Annex 1',
                'total_floors' => 2,
                'total_capacity_units' => 2,
            ],
            [
                'name' => 'Workshop',
                'total_floors' => 1,
                'total_capacity_units' => 1,
            ],
            [
                'name' => 'Canteen',
                'total_floors' => 2,
                'total_capacity_units' => 3,
            ],
            [
                'name' => 'Open Yard',
                'total_floors' => 1,
                'total_capacity_units' => 1,
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
            ],
            [
                'company_name' => 'PT dimas berkarya',
                'npwp' => '08.999.888.7-999.000',
                'pic_name' => 'Dimas Berlian',
                'phone' => '0812-3456-7890',
                'email' => 'moeslimgaming@gmail.com',
                'address' => 'Jl. Sukarno Hatta No. 8, Bandung',
                'emergency_contact' => '0812-3456-7899',
                'business_sector' => 'Korporat',
            ],
            [
                'company_name' => 'PT IMECO INTER SARANA',
                'npwp' => '08.123.456.7-908.000',
                'pic_name' => 'Dimas Berlian',
                'phone' => '021-5551111',
                'email' => 'moeslimgaming@gmail.com',
                'address' => 'Jl. TB Simatupang No. 1, Jakarta Selatan',
                'emergency_contact' => '021-5551112',
                'business_sector' => 'Migas & Industri',
            ],
            [
                'company_name' => 'PT INTISARI KREASI UTAMA',
                'npwp' => '09.123.456.7-909.000',
                'pic_name' => 'Budi Santoso',
                'phone' => '021-5552222',
                'email' => 'budi@intisari.co.id',
                'address' => 'Jl. TB Simatupang No. 2, Jakarta Selatan',
                'emergency_contact' => '021-5552223',
                'business_sector' => 'Manufaktur',
            ],
            [
                'company_name' => 'PT NOV OILFIELD SERVICES',
                'npwp' => '10.123.456.7-910.000',
                'pic_name' => 'Sari Dewi',
                'phone' => '021-5553333',
                'email' => 'sari@nov.com',
                'address' => 'Jl. TB Simatupang No. 3, Jakarta Selatan',
                'emergency_contact' => '021-5553334',
                'business_sector' => 'Migas',
            ],
            [
                'company_name' => 'PT KARTIKA BINA MEDIKATAMA (KLINIK MEDIKA PLAZA)',
                'npwp' => '11.123.456.7-911.000',
                'pic_name' => 'Rini Wulandari',
                'phone' => '021-5554444',
                'email' => 'rini@medikaplaz.co.id',
                'address' => 'Jl. TB Simatupang No. 4, Jakarta Selatan',
                'emergency_contact' => '021-5554445',
                'business_sector' => 'Kesehatan',
            ],
            [
                'company_name' => 'PT CONTROL SYSTEMS ARENA PARA NUSA',
                'npwp' => '12.123.456.7-912.000',
                'pic_name' => 'Ahmad Fauzi',
                'phone' => '021-5555555',
                'email' => 'ahmad@controlsys.co.id',
                'address' => 'Jl. TB Simatupang No. 5, Jakarta Selatan',
                'emergency_contact' => '021-5555556',
                'business_sector' => 'Instrumentasi',
            ]
        ];

        $tenantModels = [];
        foreach ($tenants as $t) {
            Tenant::where('company_name', $t['company_name'])->delete();
            $tenantModels[$t['company_name']] = Tenant::create($t);
        }

        // 3. Generate Space Allocations based on configurations
        $buildingConfigs = [
            'Gedung A' => [
                'floors' => [1, 2, 3, 4, 5, 6, 7, 8],
                'units_per_floor' => [
                    1 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    2 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    3 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    4 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    5 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    6 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    7 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    8 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                ]
            ],
            'Gedung B' => [
                'floors' => [1, 2, 3, 4, 5, 6, 7, 8],
                'units_per_floor' => [
                    1 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    2 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    3 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    4 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    5 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    6 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    7 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    8 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                ]
            ],
            'Gedung C' => [
                'floors' => [1, 2, 3, 4, 5, 6, 7, 8],
                'units_per_floor' => [
                    1 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    2 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    3 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    4 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    5 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    6 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    7 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                    8 => ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'],
                ]
            ],
            'Annex' => [
                'floors' => [1, 2],
                'units_per_floor' => [
                    1 => ['Seluruh Lantai 1'],
                    2 => ['Seluruh Lantai 2'],
                ]
            ],
            'Annex 1' => [
                'floors' => [1, 2],
                'units_per_floor' => [
                    1 => ['Seluruh Lantai 1'],
                    2 => ['Seluruh Lantai 2'],
                ]
            ],
            'Workshop' => [
                'floors' => [1],
                'units_per_floor' => [
                    1 => ['Seluruh Area Workshop'],
                ]
            ],
            'Canteen' => [
                'floors' => [1, 2],
                'units_per_floor' => [
                    1 => ['Indoor Lantai 1', 'Outdoor'],
                    2 => ['Indoor Lantai 2'],
                ]
            ],
            'Open Yard' => [
                'floors' => [1],
                'units_per_floor' => [
                    1 => ['Area Tanah (Open Yard)'],
                ]
            ]
        ];

        $occupiedUnits = [
            'Gedung A' => [
                '3-Zona 1' => [
                    'tenant' => 'PT Astra Internasional',
                    'size' => 450,
                    'rent' => 45000000,
                    'start' => '2022-03-15',
                    'end' => '2025-03-15',
                    'status' => 'Terisi',
                    'payment' => 'Lunas'
                ],
                '7-Zona 1' => [
                    'tenant' => 'PT Bank Nusantara',
                    'size' => 680,
                    'rent' => 68000000,
                    'start' => '2023-01-18',
                    'end' => '2026-01-18',
                    'status' => 'Terisi',
                    'payment' => 'Lunas'
                ],
                '6-Zona 1' => [
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
                '5-Zona 1' => [
                    'tenant' => 'CV Teknologi Maju',
                    'size' => 128,
                    'rent' => 12000000,
                    'start' => '2023-08-20',
                    'end' => '2024-08-20',
                    'status' => 'Hampir Berakhir',
                    'payment' => 'Menunggu'
                ],
                '4-Zona 2' => [
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
                '2-Zona 1' => [
                    'tenant' => 'PT Garuda Logistik',
                    'size' => 320,
                    'rent' => 32000000,
                    'start' => '2023-06-01',
                    'end' => '2024-06-01',
                    'status' => 'Berakhir',
                    'payment' => 'Tertunggak'
                ],
                '6-Zona 1' => [
                    'tenant' => 'PT Mandiri Properti',
                    'size' => 560,
                    'rent' => 56000000,
                    'start' => '2023-05-20',
                    'end' => '2026-05-20',
                    'status' => 'Terisi',
                    'payment' => 'Lunas'
                ]
            ],
            'Annex' => [
                '1-Seluruh Lantai 1' => [
                    'tenant' => 'PT Astra Internasional',
                    'size' => 350,
                    'rent' => 35000000,
                    'start' => '2023-02-10',
                    'end' => '2026-02-10',
                    'status' => 'Terisi',
                    'payment' => 'Lunas'
                ],
                '2-Seluruh Lantai 2' => [
                    'tenant' => 'PT Bank Nusantara',
                    'size' => 350,
                    'rent' => 35000000,
                    'start' => '2023-05-15',
                    'end' => '2026-05-15',
                    'status' => 'Terisi',
                    'payment' => 'Lunas'
                ]
            ],
            'Annex 1' => [
                '1-Seluruh Lantai 1' => [
                    'tenant' => 'CV Teknologi Maju',
                    'size' => 300,
                    'rent' => 28000000,
                    'start' => '2024-01-10',
                    'end' => '2026-01-10',
                    'status' => 'Terisi',
                    'payment' => 'Lunas'
                ]
            ],
            'Workshop' => [
                '1-Seluruh Area Workshop' => [
                    'tenant' => 'PT Garuda Logistik',
                    'size' => 800,
                    'rent' => 75000000,
                    'start' => '2023-08-01',
                    'end' => '2025-08-01',
                    'status' => 'Terisi',
                    'payment' => 'Lunas'
                ]
            ],
            'Canteen' => [
                '1-Indoor Lantai 1' => [
                    'tenant' => 'PT Medika Farma',
                    'size' => 120,
                    'rent' => 12000000,
                    'start' => '2024-03-01',
                    'end' => '2026-03-01',
                    'status' => 'Terisi',
                    'payment' => 'Lunas'
                ],
                '1-Outdoor' => [
                    'tenant' => 'PT Solusi Digital',
                    'size' => 80,
                    'rent' => 8000000,
                    'start' => '2024-04-01',
                    'end' => '2026-04-01',
                    'status' => 'Terisi',
                    'payment' => 'Lunas'
                ],
                '2-Indoor Lantai 2' => [
                    'tenant' => 'PT Mandiri Properti',
                    'size' => 100,
                    'rent' => 10000000,
                    'start' => '2024-02-15',
                    'end' => '2026-02-15',
                    'status' => 'Terisi',
                    'payment' => 'Lunas'
                ]
            ],
            'Open Yard' => [
                '1-Area Tanah (Open Yard)' => [
                    'tenant' => 'PT Garuda Logistik',
                    'size' => 1200,
                    'rent' => 40000000,
                    'start' => '2023-01-01',
                    'end' => '2027-01-01',
                    'status' => 'Terisi',
                    'payment' => 'Lunas'
                ]
            ]
        ];

        foreach ($buildingModels as $bName => $bModel) {
            // Delete existing allocations for building
            SpaceAllocation::where('building_id', $bModel->id)->delete();

            $config = $buildingConfigs[$bName];
            foreach ($config['floors'] as $floor) {
                $unitNames = $config['units_per_floor'][$floor];
                foreach ($unitNames as $uName) {
                    $key = $floor . '-' . $uName;

                    if ($bName === 'Open Yard' && $uName === 'Area Tanah (Open Yard)') {
                        // Seed the 5 real tenants for Open Yard to match total area 1059.72 sqm
                        $openYardTenants = [
                            [
                                'tenant' => 'PT IMECO INTER SARANA',
                                'size' => 880.64,
                                'rent' => 30000000,
                                'start' => '2023-01-01',
                                'end' => '2028-01-01',
                                'status' => 'Terisi',
                                'payment' => 'Lunas',
                            ],
                            [
                                'tenant' => 'PT INTISARI KREASI UTAMA',
                                'size' => 40.00,
                                'rent' => 1500000,
                                'start' => '2024-01-01',
                                'end' => '2026-01-01',
                                'status' => 'Terisi',
                                'payment' => 'Lunas',
                            ],
                            [
                                'tenant' => 'PT NOV OILFIELD SERVICES',
                                'size' => 40.00,
                                'rent' => 1500000,
                                'start' => '2024-01-01',
                                'end' => '2026-01-01',
                                'status' => 'Terisi',
                                'payment' => 'Lunas',
                            ],
                            [
                                'tenant' => 'PT KARTIKA BINA MEDIKATAMA (KLINIK MEDIKA PLAZA)',
                                'size' => 40.00,
                                'rent' => 1500000,
                                'start' => '2024-04-14',
                                'end' => '2029-04-14',
                                'status' => 'Terisi',
                                'payment' => 'Lunas',
                            ],
                            [
                                'tenant' => 'PT CONTROL SYSTEMS ARENA PARA NUSA',
                                'size' => 59.08,
                                'rent' => 2000000,
                                'start' => '2023-12-31',
                                'end' => '2030-12-31',
                                'status' => 'Terisi',
                                'payment' => 'Lunas',
                            ],
                        ];

                        foreach ($openYardTenants as $oyt) {
                            $tenant = $tenantModels[$oyt['tenant']];
                            SpaceAllocation::create([
                                'building_id' => $bModel->id,
                                'floor_number' => $floor,
                                'unit_number' => $uName,
                                'tenant_id' => $tenant->id,
                                'area_size' => $oyt['size'],
                                'rent_price' => $oyt['rent'],
                                'status' => $oyt['status'],
                                'lease_start' => $oyt['start'],
                                'lease_end' => $oyt['end'],
                                'payment_status' => $oyt['payment'],
                            ]);
                        }
                    } else {
                        $allocationData = [
                            'building_id' => $bModel->id,
                            'floor_number' => $floor,
                            'unit_number' => $uName,
                            'area_size' => rand(100, 200), // Default random size for vacant units
                            'rent_price' => rand(100, 200) * 100000, // Default rent price
                            'status' => 'Kosong',
                            'tenant_id' => null,
                            'lease_start' => null,
                            'lease_end' => null,
                            'payment_status' => null,
                        ];

                        if (isset($occupiedUnits[$bName][$key])) {
                            $occ = $occupiedUnits[$bName][$key];
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
            }
        }
    }
}
