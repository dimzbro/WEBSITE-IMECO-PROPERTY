<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OfficeBopLead;

class OfficeBopLeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data dari foto (Bulan April 2024)
        OfficeBopLead::create([
            'tahun' => 2024,
            'bulan' => 4, // April
            'tanggal_entry' => '2024-04-29',
            'nama' => 'Ririk Suriyana',
            'email' => 'ririksuriyana@gmail.com',
            'nama_perusahaan' => 'PT Surya Sejahtera Utama',
            'alamat' => 'Jl. Jend. Sudirman No. 45, Jakarta Selatan',
            'telpon_fax' => '0812-9876-5432 / (021) 5790-1234',
            'kategori_diminati' => 'Penawaran Space: Building A; 2nd floor 130 sqm, 3rd floor 90 sqm, 3Ath floor 416 sqm, 8th floor 250 sqm, Building C; 3Ath floor 250 sqm, Building Annex; 2nd floor 490 sqm, Building Annex 1; 2nd floor 125 sqm',
            'kit_marketing' => '29 April 2024',
            'loo' => '05 Mei 2024',
            'nomlet_dikirim' => '12 Mei 2024',
            'nomlet_disetujui' => '18 Mei 2024',
            'dp' => '20 Mei 2024',
            'serah_terima' => '01 Juni 2024',
            'fitting_out' => '15 Juni 2024',
        ]);

        // 2. Data Bulan Januari 2024
        OfficeBopLead::create([
            'tahun' => 2024,
            'bulan' => 1,
            'tanggal_entry' => '2024-01-15',
            'nama' => 'Budi Santoso',
            'email' => 'budi.santoso@energi-maju.co.id',
            'nama_perusahaan' => 'PT Energi Maju Nusantara',
            'alamat' => 'Gedung Wisma Millenium Lt. 8, Jakarta Pusat',
            'telpon_fax' => '0811-1234-5678',
            'kategori_diminati' => 'Penawaran Space: Building B; 4th floor 200 sqm',
            'kit_marketing' => '16 Januari 2024',
            'loo' => '22 Januari 2024',
            'nomlet_dikirim' => '28 Januari 2024',
            'nomlet_disetujui' => '02 Februari 2024',
            'dp' => '05 Februari 2024',
            'serah_terima' => '01 Maret 2024',
            'fitting_out' => '10 Maret 2024',
        ]);

        // 3. Data Bulan Februari 2024
        OfficeBopLead::create([
            'tahun' => 2024,
            'bulan' => 2,
            'tanggal_entry' => '2024-02-10',
            'nama' => 'Dewi Lestari',
            'email' => 'dewi@techindo.com',
            'nama_perusahaan' => 'CV Techindo Solusindo',
            'alamat' => 'Kawasan Industri TB Simatupang, Jakarta',
            'telpon_fax' => '0856-7890-1234',
            'kategori_diminati' => 'Penawaran Space: Building C; 2nd floor 150 sqm',
            'kit_marketing' => '12 Februari 2024',
            'loo' => '18 Februari 2024',
            'nomlet_dikirim' => '25 Februari 2024',
            'nomlet_disetujui' => null,
            'dp' => null,
            'serah_terima' => null,
            'fitting_out' => null,
        ]);

        // 4. Data Bulan Maret 2024
        OfficeBopLead::create([
            'tahun' => 2024,
            'bulan' => 3,
            'tanggal_entry' => '2024-03-05',
            'nama' => 'Hendra Wijaya',
            'email' => 'h.wijaya@globallogistics.id',
            'nama_perusahaan' => 'PT Global Logistics Indonesia',
            'alamat' => 'Jl. Gatot Subroto Kav. 12, Jakarta',
            'telpon_fax' => '0813-4567-8901 / (021) 520-9988',
            'kategori_diminati' => 'Penawaran Space: Building A; 5th floor 320 sqm',
            'kit_marketing' => '07 Maret 2024',
            'loo' => '15 Maret 2024',
            'nomlet_dikirim' => null,
            'nomlet_disetujui' => null,
            'dp' => null,
            'serah_terima' => null,
            'fitting_out' => null,
        ]);

        // 5. Data Tahun 2026 (Current Year) - Januari 2026
        OfficeBopLead::create([
            'tahun' => 2026,
            'bulan' => 1,
            'tanggal_entry' => '2026-01-10',
            'nama' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@bop-partners.com',
            'nama_perusahaan' => 'PT BOP Partners Indonesia',
            'alamat' => 'Jl. HR Rasuna Said Blok X-5, Jakarta',
            'telpon_fax' => '0812-1111-2222',
            'kategori_diminati' => 'Penawaran Space: Building A; 2nd floor 130 sqm & Building Annex 2nd floor 490 sqm',
            'kit_marketing' => '12 Januari 2026',
            'loo' => '20 Januari 2026',
            'nomlet_dikirim' => '25 Januari 2026',
            'nomlet_disetujui' => '30 Januari 2026',
            'dp' => '01 Februari 2026',
            'serah_terima' => null,
            'fitting_out' => null,
        ]);
    }
}
