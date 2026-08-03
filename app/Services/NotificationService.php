<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\CalendarEvent;
use App\Models\SpaceAllocation;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Check upcoming calendar events and tenant space allocations, generating due reminder notifications.
     */
    public static function checkAndGenerateCalendarNotifications(): void
    {
        $now = Carbon::now();

        // 1. Process Calendar Events
        $events = CalendarEvent::all();
        foreach ($events as $event) {
            if (!$event->event_date) continue;

            $eventTime = Carbon::parse($event->event_date);
            $reminderTimeSetting = $event->reminder_time ?? 'same_time';

            // Determine when the reminder should trigger
            $triggerTime = match ($reminderTimeSetting) {
                '15_min_before'  => $eventTime->copy()->subMinutes(15),
                '30_min_before'  => $eventTime->copy()->subMinutes(30),
                '1_hour_before'  => $eventTime->copy()->subHours(1),
                '1_day_before'   => $eventTime->copy()->subDay(),
                '2_days_before'  => $eventTime->copy()->subDays(2),
                default          => $eventTime->copy(), // same_time
            };

            // Unique key to prevent duplicates for this event reminder
            $sourceId = "calendar_event_{$event->id}_{$reminderTimeSetting}";

            // If current time is past or equal to trigger time, create notification if not already created
            if ($now->greaterThanOrEqualTo($triggerTime)) {
                $exists = Notification::where('source_id', $sourceId)->exists();
                if (!$exists) {
                    $description = self::buildReminderDescription($event->title, $eventTime, $reminderTimeSetting, $event->location);
                    Notification::create([
                        'title'       => 'Pengingat Agenda: ' . $event->title,
                        'description' => $description,
                        'type'        => 'calendar',
                        'source_id'   => $sourceId,
                        'action_url'  => route('admin.calendar.index', [
                            'month' => $eventTime->month,
                            'year'  => $eventTime->year,
                            'event_id' => $event->id
                        ]),
                        'is_read'     => false,
                        'reminder_at' => $triggerTime,
                    ]);
                }
            }
        }

        // 2. Process Tenant Lease Reminders based on remaining days rules
        self::checkAndGenerateTenantLeaseReminders();
    }

    /**
     * Hitung sisa masa kontrak tenant dan hasilkan notifikasi otomatis berdasarkan 4 kategori status.
     * Returns total new notifications created.
     */
    public static function checkAndGenerateTenantLeaseReminders(): int
    {
        $today = Carbon::now()->startOfDay();
        $createdCount = 0;

        // Ambil semua alokasi unit yang memiliki tenant dan tanggal berakhir sewa (lease_end)
        $allocations = SpaceAllocation::with(['tenant', 'building'])
            ->whereNotNull('tenant_id')
            ->whereNotNull('lease_end')
            ->get();

        foreach ($allocations as $alloc) {
            $companyName = $alloc->tenant->company_name ?? 'Tenant';
            $leaseEnd = Carbon::parse($alloc->lease_end)->startOfDay();
            $formattedDate = $leaseEnd->translatedFormat('d F Y');
            
            // Hitung sisa hari (remaining days)
            // positive = sisa hari di masa depan, 0 = berakhir hari ini, negative = sudah terlewati/berakhir
            $daysRemaining = (int) $today->diffInDays($leaseEnd, false);

            if ($daysRemaining > 180) {
                // 🟢 Kategori 1: Kontrak Aktif (>180 Hari)
                // - Sisa masa kontrak lebih dari 180 hari.
                // - Tidak perlu mengirim notifikasi harian.
                // - Tampil sebagai status pada dashboard dan halaman tenant.
                if ($alloc->status !== 'Kontrak Aktif') {
                    $alloc->update(['status' => 'Kontrak Aktif']);
                }
            } elseif ($daysRemaining >= 31 && $daysRemaining <= 180) {
                // 🟡 Kategori 2: Kontrak Mendekati Berakhir (31–180 Hari)
                // - Sisa masa kontrak antara 31 sampai 180 hari.
                // - Buat notifikasi pengingat secara otomatis setiap 30 hari sekali atau saat status pertama kali berubah ke kategori ini.
                if ($alloc->status !== 'Kontrak Mendekati Berakhir') {
                    $alloc->update(['status' => 'Kontrak Mendekati Berakhir']);
                }

                // Hitung langkah interval 30 hari (misal: 180-151 hari = step 0; 150-121 hari = step 1; 120-91 = step 2; dst)
                $intervalStep = (int) floor((180 - $daysRemaining) / 30);
                $sourceId = "lease_reminder_30d_{$alloc->id}_step_{$intervalStep}";

                if (!Notification::where('source_id', $sourceId)->exists()) {
                    Notification::create([
                        'title'       => "Kontrak Mendekati Berakhir: {$companyName}",
                        'description' => "Kontrak tenant {$companyName} akan berakhir dalam {$daysRemaining} hari pada {$formattedDate}. Silakan mulai proses negosiasi perpanjangan kontrak.",
                        'type'        => 'calendar',
                        'source_id'   => $sourceId,
                        'action_url'  => route('admin.tenants.index'),
                        'is_read'     => false,
                        'reminder_at' => Carbon::now(),
                    ]);
                    $createdCount++;
                }
            } elseif ($daysRemaining >= 0 && $daysRemaining <= 30) {
                // 🔴 Kategori 3: Hampir Berakhir (0–30 Hari)
                // - Sisa masa kontrak antara 0 sampai 30 hari.
                // - Kirim In-App Notification setiap hari hingga kontrak berakhir.
                if ($alloc->status !== 'Hampir Berakhir') {
                    $alloc->update(['status' => 'Hampir Berakhir']);
                }

                $sourceId = "lease_reminder_daily_{$alloc->id}_" . $today->format('Y-m-d');

                if (!Notification::where('source_id', $sourceId)->exists()) {
                    Notification::create([
                        'title'       => "Kontrak Hampir Berakhir: {$companyName}",
                        'description' => "Kontrak tenant {$companyName} akan berakhir dalam {$daysRemaining} hari pada {$formattedDate}. Segera lakukan tindak lanjut perpanjangan atau proses administrasi.",
                        'type'        => 'calendar',
                        'source_id'   => $sourceId,
                        'action_url'  => route('admin.tenants.index'),
                        'is_read'     => false,
                        'reminder_at' => Carbon::now(),
                    ]);
                    $createdCount++;
                }
            } else {
                // ⚫ Kategori 4: Kontrak Berakhir (< 0 Hari / Terlewati)
                // - Jika tanggal kontrak telah terlewati.
                // - Buat satu notifikasi saat berakhir.
                if ($alloc->status !== 'Kontrak Habis') {
                    $alloc->update(['status' => 'Kontrak Habis']);
                }

                $sourceId = "lease_expired_final_{$alloc->id}";

                if (!Notification::where('source_id', $sourceId)->exists()) {
                    Notification::create([
                        'title'       => "Kontrak Berakhir: {$companyName}",
                        'description' => "Kontrak tenant {$companyName} telah berakhir pada {$formattedDate}.",
                        'type'        => 'calendar',
                        'source_id'   => $sourceId,
                        'action_url'  => route('admin.tenants.index'),
                        'is_read'     => false,
                        'reminder_at' => Carbon::now(),
                    ]);
                    $createdCount++;
                }
            }
        }

        return $createdCount;
    }

    /**
     * Create immediate notification when a new CalendarEvent is added or modified.
     */
    public static function createEventCreatedNotification(CalendarEvent $event, bool $isUpdate = false): void
    {
        $eventTime = Carbon::parse($event->event_date);
        $action = $isUpdate ? 'diperbarui' : 'dibuat';
        $titleText = $isUpdate ? "Agenda Diperbarui: {$event->title}" : "Agenda Baru: {$event->title}";
        
        $sourceId = "calendar_event_{$event->id}_created_" . time();

        Notification::create([
            'title'       => $titleText,
            'description' => "Agenda '{$event->title}' telah {$action} untuk tanggal {$eventTime->translatedFormat('d F Y H:i')} WIB di {$event->location}.",
            'type'        => 'calendar',
            'source_id'   => $sourceId,
            'action_url'  => route('admin.calendar.index', [
                'month' => $eventTime->month,
                'year'  => $eventTime->year,
                'event_id' => $event->id
            ]),
            'is_read'     => false,
            'reminder_at' => Carbon::now(),
        ]);
    }

    /**
     * Helper to format descriptive Indonesia text based on reminder timing.
     */
    private static function buildReminderDescription(string $title, Carbon $eventTime, string $reminderType, ?string $location): string
    {
        $timeString = $eventTime->format('H:i') . ' WIB';
        $dateString = $eventTime->translatedFormat('d F Y');
        $locText = $location ? " di {$location}" : "";

        return match ($reminderType) {
            '15_min_before' => "Agenda '{$title}'{$locText} akan dimulai dalam 15 menit (pukul {$timeString}).",
            '30_min_before' => "Agenda '{$title}'{$locText} akan dimulai dalam 30 menit (pukul {$timeString}).",
            '1_hour_before' => "Agenda '{$title}'{$locText} akan dimulai dalam 1 jam (pukul {$timeString}).",
            '1_day_before'  => "Besok terdapat agenda '{$title}'{$locText} pada pukul {$timeString}.",
            '2_days_before' => "2 hari lagi terdapat agenda '{$title}'{$locText} pada tanggal {$dateString} pukul {$timeString}.",
            default         => "Agenda '{$title}'{$locText} dimulai sekarang (pukul {$timeString}).",
        };
    }
}
