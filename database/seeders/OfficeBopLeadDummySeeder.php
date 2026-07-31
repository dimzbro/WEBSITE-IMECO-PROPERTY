<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OfficeBopLead;

class OfficeBopLeadDummySeeder extends Seeder
{
    public function run()
    {
        // Clear existing data first
        OfficeBopLead::truncate();

        $years = [2025, 2026, 2027];

        $romawiMap = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];

        $sampleLeads = [
            [
                'nama' => 'Budi Pratama',
                'email' => 'budi.pratama@nusantaradigital.co.id',
                'nama_perusahaan' => 'PT Nusantara Digital Jaya',
                'alamat' => 'Jl. Jend. Sudirman No. 45, Jakarta Selatan',
                'telpon_fax' => '0812-3456-7890',
                'kategori_diminati' => 'Penawaran Space: Building A; 2nd floor 130 sqm',
            ],
            [
                'nama' => 'Linda Wijaya',
                'email' => 'linda.wijaya@sinarenergi.com',
                'nama_perusahaan' => 'PT Sinar Global Energi',
                'alamat' => 'Gedung Wisma Millenium Lt. 3, Jakarta',
                'telpon_fax' => '0813-8899-1122',
                'kategori_diminati' => 'Penawaran Space: Building B; 3rd floor 200 sqm',
            ],
            [
                'nama' => 'Rizky Ramadan',
                'email' => 'rizky@creativestudio.id',
                'nama_perusahaan' => 'CV Creative Studio Indonesia',
                'alamat' => 'Jl. Rasuna Said Blok X5 No. 12, Kuningan',
                'telpon_fax' => '0857-1122-3344',
                'kategori_diminati' => 'Penawaran Space: Building C; 3Ath floor 250 sqm',
            ],
            [
                'nama' => 'Anita Rahayu',
                'email' => 'anita@translogistik.co.id',
                'nama_perusahaan' => 'PT Trans Logistik Nusantara',
                'alamat' => 'Jl. Gatot Subroto Kav. 18, Jakarta',
                'telpon_fax' => '0811-9988-7766',
                'kategori_diminati' => 'Penawaran Space: Building Annex; 2nd floor 490 sqm',
            ],
            [
                'nama' => 'Hendra Kusuma',
                'email' => 'hendra.k@mitrasejahtera.com',
                'nama_perusahaan' => 'PT Mitra Sejahtera Utama',
                'alamat' => 'Jl. TB Simatupang No. 88, Cilandak',
                'telpon_fax' => '0812-7766-5544',
                'kategori_diminati' => 'Penawaran Space: Building A; 8th floor 250 sqm',
            ],
            [
                'nama' => 'Maya Indah',
                'email' => 'maya.indah@solusitek.asia',
                'nama_perusahaan' => 'PT Solusi Teknologi Asia',
                'alamat' => 'Gedung Cyber 2 Lt. 10, Kuningan',
                'telpon_fax' => '0813-4455-6677',
                'kategori_diminati' => 'Penawaran Space: Building Annex 1; 2nd floor 125 sqm',
            ],
            [
                'nama' => 'Doni Setiawan',
                'email' => 'doni@berkahabadi.co.id',
                'nama_perusahaan' => 'PT Berkah Abadi Propertindo',
                'alamat' => 'Jl. MH Thamrin No. 9, Jakarta Pusat',
                'telpon_fax' => '0856-3322-1100',
                'kategori_diminati' => 'Penawaran Space: Building A; 3rd floor 90 sqm',
            ],
            [
                'nama' => 'Eko Prasetyo',
                'email' => 'eko.p@intiglobal.com',
                'nama_perusahaan' => 'PT Inti Global Media',
                'alamat' => 'Jl. K.H. Mas Mansyur No. 120, Tanah Abang',
                'telpon_fax' => '0878-9900-1122',
                'kategori_diminati' => 'Penawaran Space: Building B; 4th floor 150 sqm',
            ]
        ];

        $suratCount = 1;

        foreach ($years as $tahun) {
            foreach (range(1, 12) as $bulan) {
                // Generate 1 to 3 leads for each month
                $count = rand(1, 3);
                for ($i = 0; $i < $count; $i++) {
                    $template = $sampleLeads[array_rand($sampleLeads)];
                    $day = rand(1, 28);
                    $tanggalEntry = sprintf('%04d-%02d-%02d', $tahun, $bulan, $day);

                    $hasLoo = rand(0, 1) === 1;
                    $hasNomletDikirim = $hasLoo && rand(0, 1) === 1;
                    $hasNomletDisetujui = $hasNomletDikirim && rand(0, 1) === 1;
                    $hasDp  = $hasNomletDisetujui && rand(0, 1) === 1;
                    $hasSerah = $hasDp && rand(0, 1) === 1;
                    $hasFit = $hasSerah && rand(0, 1) === 1;

                    $looDate = $hasLoo ? date('Y-m-d', strtotime($tanggalEntry . ' +' . rand(3, 7) . ' days')) : null;
                    $nomorSuratLoo = $hasLoo ? sprintf('%03d/LOO/IMECO-BOP/%s/%d', $suratCount++, $romawiMap[$bulan], $tahun) : null;
                    $nomletDikirimDate = $hasNomletDikirim ? date('Y-m-d', strtotime($looDate . ' +' . rand(5, 10) . ' days')) : null;
                    $nomletDisetujuiDate = $hasNomletDisetujui ? date('Y-m-d', strtotime($nomletDikirimDate . ' +' . rand(3, 7) . ' days')) : null;
                    $dpDate = $hasDp ? date('Y-m-d', strtotime($nomletDisetujuiDate . ' +' . rand(4, 8) . ' days')) : null;
                    $serahDate = $hasSerah ? date('Y-m-d', strtotime($dpDate . ' +' . rand(7, 14) . ' days')) : null;
                    $fitDate = $hasFit ? date('Y-m-d', strtotime($serahDate . ' +' . rand(5, 12) . ' days')) : null;

                    OfficeBopLead::create([
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'tanggal_entry' => $tanggalEntry,
                        'nama' => $template['nama'],
                        'email' => $template['email'],
                        'nama_perusahaan' => $template['nama_perusahaan'],
                        'alamat' => $template['alamat'],
                        'telpon_fax' => $template['telpon_fax'],
                        'kategori_diminati' => $template['kategori_diminati'],
                        'kit_marketing' => $tanggalEntry,
                        'loo' => $looDate,
                        'nomor_surat_loo' => $nomorSuratLoo,
                        'nomlet_dikirim' => $nomletDikirimDate,
                        'nomlet_disetujui' => $nomletDisetujuiDate,
                        'dp' => $dpDate,
                        'serah_terima' => $serahDate,
                        'fitting_out' => $fitDate,
                    ]);
                }
            }
        }
    }
}
