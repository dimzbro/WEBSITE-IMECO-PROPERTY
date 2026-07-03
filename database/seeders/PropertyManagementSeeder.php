<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;
use App\Models\Tenant;
use App\Models\SpaceAllocation;

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

        // 2. Define Building Configurations (Floors and Units)
        $buildingConfigs = [
            'Gedung A' => [
                'floors' => [1, 2, 3, 4, 5, 6, 7, 8],
                'units_per_floor' => array_fill_keys([1, 2, 3, 4, 5, 6, 7, 8], ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'])
            ],
            'Gedung B' => [
                'floors' => [1, 2, 3, 4, 5, 6, 7, 8],
                'units_per_floor' => array_fill_keys([1, 2, 3, 4, 5, 6, 7, 8], ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'])
            ],
            'Gedung C' => [
                'floors' => [1, 2, 3, 4, 5, 6, 7, 8],
                'units_per_floor' => array_fill_keys([1, 2, 3, 4, 5, 6, 7, 8], ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'])
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

        // Truncate previous Space Allocations and Tenants
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        SpaceAllocation::truncate();
        Tenant::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        foreach ($buildingModels as $bName => $bModel) {
            $config = $buildingConfigs[$bName];
            foreach ($config['floors'] as $floor) {
                $unitNames = $config['units_per_floor'][$floor];
                foreach ($unitNames as $uName) {
                    $areaSize = 150; // default area size
                    $rentPrice = 15000000; // default rent price

                    if ($bName === 'Open Yard') {
                        $areaSize = 1059.72; // total space limit
                        $rentPrice = 40000000;
                    } elseif ($bName === 'Workshop') {
                        $areaSize = 800;
                        $rentPrice = 75000000;
                    } elseif ($bName === 'Annex' || $bName === 'Annex 1') {
                        $areaSize = 350;
                        $rentPrice = 30000000;
                    } elseif ($bName === 'Canteen') {
                        if ($uName === 'Indoor Lantai 1') {
                            $areaSize = 120;
                            $rentPrice = 12000000;
                        } elseif ($uName === 'Outdoor') {
                            $areaSize = 80;
                            $rentPrice = 8000000;
                        } elseif ($uName === 'Indoor Lantai 2') {
                            $areaSize = 100;
                            $rentPrice = 10000000;
                        }
                    }

                    SpaceAllocation::create([
                        'building_id' => $bModel->id,
                        'floor_number' => $floor,
                        'unit_number' => $uName,
                        'area_size' => $areaSize,
                        'rent_price' => $rentPrice,
                        'status' => 'Kosong',
                        'tenant_id' => null,
                        'lease_start' => null,
                        'lease_end' => null,
                        'payment_status' => null,
                    ]);
                }
            }
        }
    }
}
