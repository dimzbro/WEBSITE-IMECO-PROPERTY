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
        Schema::create('lk3_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('no')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('nomor_laporan')->nullable();
            $table->string('nama_pelapor')->nullable();
            $table->string('dari_dept')->nullable();     // Engineering, Landscape, Security, Parkir, ISS Cleaner, GA
            $table->string('gedung')->nullable();
            $table->string('lantai')->nullable();
            $table->text('laporan')->nullable();
            $table->text('kegiatan')->nullable();
            $table->string('jenis_pekerjaan')->nullable(); // Elektrikal, Plumbing, AC, Sipil, dll
            $table->string('departemen_terkait')->nullable();
            $table->string('di_kerjakan')->nullable();
            $table->string('jam')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lk3_reports');
    }
};
