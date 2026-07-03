<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaintenanceRequest;
use App\Models\Tenant;
use Carbon\Carbon;

class MaintenanceRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing maintenance requests
        MaintenanceRequest::truncate();

        // Retrieve tenants
        $astra = Tenant::where('company_name', 'PT Astra Internasional')->first();
        $tekno = Tenant::where('company_name', 'CV Teknologi Maju')->first();
        $bank = Tenant::where('company_name', 'PT Bank Nusantara')->first();
        $medika = Tenant::where('company_name', 'PT Medika Farma')->first();
        $solusi = Tenant::where('company_name', 'PT Solusi Digital')->first();

        // Create dummy requests
        if ($astra) {
            MaintenanceRequest::create([
                'tenant_id' => $astra->id,
                'category' => 'AC / HVAC',
                'title' => 'AC ruang meeting tidak dingin, perlu service menyeluruh',
                'description' => 'AC di ruang meeting Lantai 3 zona 1 mengeluarkan udara panas dan mengeluarkan bunyi bising. Harap segera dilakukan perbaikan atau pencucian filter.',
                'priority' => 'Tinggi',
                'status' => 'Dalam Proses',
                'assigned_to' => 'Tim Mekanikal',
                'requested_at' => '2024-07-10',
            ]);
        }

        if ($tekno) {
            MaintenanceRequest::create([
                'tenant_id' => $tekno->id,
                'category' => 'Listrik',
                'title' => 'Korsleting di panel listrik area server room',
                'description' => 'Terdapat bau hangus di dekat panel MCB server room. Lampu indikator status berkedip tidak stabil. Perlu teknisi elektrikal segera.',
                'priority' => 'Kritis',
                'status' => 'Menunggu',
                'assigned_to' => 'Tim Elektrikal',
                'requested_at' => '2024-07-12',
            ]);
        }

        if ($bank) {
            MaintenanceRequest::create([
                'tenant_id' => $bank->id,
                'category' => 'Plumbing',
                'title' => 'Kebocoran pipa di toilet lantai 7',
                'description' => 'Terdapat rembesan air dari bawah wastafel toilet pria lantai 7 yang mengakibatkan genangan air di lantai.',
                'priority' => 'Sedang',
                'status' => 'Selesai',
                'assigned_to' => 'Tim Sipil',
                'notes' => 'Selesai diperbaiki oleh tim sipil dengan mengganti seal pipa yang bocor.',
                'requested_at' => '2024-07-08',
                'completed_at' => '2024-07-09',
            ]);
        }

        if ($medika) {
            MaintenanceRequest::create([
                'tenant_id' => $medika->id,
                'category' => 'Lift',
                'title' => 'Lift bergetar tidak normal dan lambat',
                'description' => 'Lift penumpang sisi kanan mengalami getaran hebat saat bergerak naik antara lantai 2 dan 4. Pintu lift juga lambat menutup.',
                'priority' => 'Tinggi',
                'status' => 'Dalam Proses',
                'assigned_to' => 'Vendor Schindler',
                'requested_at' => '2024-07-11',
            ]);
        }

        if ($solusi) {
            MaintenanceRequest::create([
                'tenant_id' => $solusi->id,
                'category' => 'CCTV',
                'title' => 'CCTV mati di koridor barat lantai 6',
                'description' => 'Kamera CCTV pengawas koridor barat lantai 6 kehilangan sinyal gambar sejak kemarin sore. Indikator power mati.',
                'priority' => 'Sedang',
                'status' => 'Menunggu',
                'assigned_to' => 'Tim IT / Security',
                'requested_at' => '2024-07-12',
            ]);
        }
    }
}
