<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('office_bop_leads', function (Blueprint $table) {
            if (Schema::hasColumn('office_bop_leads', 'loi')) {
                $table->dropColumn('loi');
            }
            if (!Schema::hasColumn('office_bop_leads', 'nomlet_dikirim')) {
                $table->string('nomlet_dikirim')->nullable()->after('nomor_surat_loo');
            }
            if (!Schema::hasColumn('office_bop_leads', 'nomlet_disetujui')) {
                $table->string('nomlet_disetujui')->nullable()->after('nomlet_dikirim');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('office_bop_leads', function (Blueprint $table) {
            if (Schema::hasColumn('office_bop_leads', 'nomlet_dikirim')) {
                $table->dropColumn('nomlet_dikirim');
            }
            if (Schema::hasColumn('office_bop_leads', 'nomlet_disetujui')) {
                $table->dropColumn('nomlet_disetujui');
            }
            if (!Schema::hasColumn('office_bop_leads', 'loi')) {
                $table->string('loi')->nullable()->after('nomor_surat_loo');
            }
        });
    }
};
