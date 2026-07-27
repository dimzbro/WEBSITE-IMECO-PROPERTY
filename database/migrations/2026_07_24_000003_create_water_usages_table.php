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
        Schema::create('water_usages', function (Blueprint $table) {
            $table->id();
            $table->string('gedung');
            $table->string('nomor_id', 50)->nullable();
            $table->integer('tahun');
            $table->integer('bulan');
            $table->decimal('debet_air', 12, 2);
            $table->timestamps();

            $table->unique(['gedung', 'tahun', 'bulan'], 'unique_water_gedung_tahun_bulan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_usages');
    }
};
