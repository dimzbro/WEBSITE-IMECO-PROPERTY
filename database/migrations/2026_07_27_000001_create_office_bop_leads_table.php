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
        Schema::create('office_bop_leads', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->integer('bulan'); // 1 to 12
            $table->date('tanggal_entry')->nullable();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('telpon_fax')->nullable();
            $table->text('kategori_diminati')->nullable();
            $table->string('kit_marketing')->nullable();
            $table->string('loo')->nullable();
            $table->string('nomlet_dikirim')->nullable();
            $table->string('nomlet_disetujui')->nullable();
            $table->string('dp')->nullable();
            $table->string('serah_terima')->nullable();
            $table->string('fitting_out')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_bop_leads');
    }
};
