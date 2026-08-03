<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $dummyNotifications = [
            [
                'title'       => 'Pengingat Agenda: Meeting Tenant PT ABC Indonesia',
                'description' => 'Meeting pembahasan perpanjangan sewa di Ruang Rapat BOP Building A akan dimulai dalam 30 menit (pukul 11:00 WIB).',
                'type'        => 'calendar',
                'source_id'   => 'dummy_seed_1',
                'action_url'  => route('admin.calendar.index'),
                'is_read'     => false,
                'read_at'     => null,
                'reminder_at' => $now->copy()->subMinutes(2),
                'created_at'  => $now->copy()->subMinutes(2),
                'updated_at'  => $now->copy()->subMinutes(2),
            ],
            [
                'title'       => 'Peminat BOP Baru: PT Sampoerna Agro Tbk',
                'description' => 'Peminat baru mengajukan minat sewa ruang kantor Gedung B Lt. 3. Status saat ini: Dikirim Nomlet.',
                'type'        => 'bop_lead',
                'source_id'   => 'dummy_seed_2',
                'action_url'  => route('admin.bop-leads.index'),
                'is_read'     => false,
                'read_at'     => null,
                'reminder_at' => $now->copy()->subMinutes(15),
                'created_at'  => $now->copy()->subMinutes(15),
                'updated_at'  => $now->copy()->subMinutes(15),
            ],
            [
                'title'       => 'Pengingat Laporan: Penggunaan Daya Listrik',
                'description' => 'Data rekapitulasi penggunaan daya listrik Gedung C bulan Juni 2026 telah diperbarui dengan kenaikan 4,2%.',
                'type'        => 'electricity',
                'source_id'   => 'dummy_seed_3',
                'action_url'  => route('admin.electricity.index'),
                'is_read'     => false,
                'read_at'     => null,
                'reminder_at' => $now->copy()->subMinutes(45),
                'created_at'  => $now->copy()->subMinutes(45),
                'updated_at'  => $now->copy()->subMinutes(45),
            ],
            [
                'title'       => 'Pencatatan Debet Air Bersih Ditambahkan',
                'description' => 'Pencatatan debet air bersih untuk Gedung A bulan Juni 2026 sebesar 1.450,50 m³ telah berhasil disimpan.',
                'type'        => 'water',
                'source_id'   => 'dummy_seed_4',
                'action_url'  => route('admin.water.index'),
                'is_read'     => false,
                'read_at'     => null,
                'reminder_at' => $now->copy()->subHours(2),
                'created_at'  => $now->copy()->subHours(2),
                'updated_at'  => $now->copy()->subHours(2),
            ],
            [
                'title'       => 'Laporan LK3 Safety Inspection Terbaru',
                'description' => 'Inspeksi K3 & Keselamatan Kerja bulanan area Gedung Utama telah selesai dilaksanakan dengan predikat Sangat Baik (98%).',
                'type'        => 'lk3',
                'source_id'   => 'dummy_seed_5',
                'action_url'  => route('admin.lk3.index'),
                'is_read'     => false,
                'read_at'     => null,
                'reminder_at' => $now->copy()->subHours(5),
                'created_at'  => $now->copy()->subHours(5),
                'updated_at'  => $now->copy()->subHours(5),
            ],
            [
                'title'       => 'Peminat BOP: Surat LOO Dikirim',
                'description' => 'Surat Letter of Offer (LOO) untuk PT Unilever Indonesia Tbk di Gedung A Lt. 5 telah resmi dikirim.',
                'type'        => 'bop_lead',
                'source_id'   => 'dummy_seed_8',
                'action_url'  => route('admin.bop-leads.index'),
                'is_read'     => false,
                'read_at'     => null,
                'reminder_at' => $now->copy()->subHours(8),
                'created_at'  => $now->copy()->subHours(8),
                'updated_at'  => $now->copy()->subHours(8),
            ],
            [
                'title'       => 'Lease Berakhir: PT Pertamina Bina Medika',
                'description' => 'Masa sewa PT Pertamina Bina Medika di Gedung B Lt. 5 akan berakhir dalam 7 hari lagi.',
                'type'        => 'calendar',
                'source_id'   => 'dummy_seed_6',
                'action_url'  => route('admin.calendar.index'),
                'is_read'     => true,
                'read_at'     => $now->copy()->subHours(12),
                'reminder_at' => $now->copy()->subDays(2),
                'created_at'  => $now->copy()->subDays(2),
                'updated_at'  => $now->copy()->subHours(12),
            ],
            [
                'title'       => 'Nomlet Disetujui: PT Bank Central Asia Tbk',
                'description' => 'Dokumen Nomlet penawaran sewa ruang kantor Gedung A Lt. 8 telah resmi disetujui oleh calon tenant.',
                'type'        => 'bop_lead',
                'source_id'   => 'dummy_seed_7',
                'action_url'  => route('admin.bop-leads.index'),
                'is_read'     => true,
                'read_at'     => $now->copy()->subDays(1),
                'reminder_at' => $now->copy()->subDays(3),
                'created_at'  => $now->copy()->subDays(3),
                'updated_at'  => $now->copy()->subDays(1),
            ],
        ];

        foreach ($dummyNotifications as $item) {
            Notification::updateOrCreate(
                ['source_id' => $item['source_id']],
                $item
            );
        }
    }
}
