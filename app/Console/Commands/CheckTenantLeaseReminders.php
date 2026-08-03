<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

class CheckTenantLeaseReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:check-lease-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hitung sisa masa kontrak tenant dan buat notifikasi pengingat otomatis berdasarkan status kontrak';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan sisa masa kontrak tenant...');
        
        $count = NotificationService::checkAndGenerateTenantLeaseReminders();

        $this->info("Pengecekan selesai. Total notifikasi baru dibuat: {$count}");
        
        return Command::SUCCESS;
    }
}
