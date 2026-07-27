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
        Schema::create('electricity_usages', function (Blueprint $table) {
            $table->id();
            $table->string('gedung');
            $table->integer('tahun');
            $table->integer('bulan'); // 1 = Januari, 12 = Desember
            $table->decimal('kwh', 12, 2);
            $table->timestamps();

            // Unique combination constraint Gedung + Tahun + Bulan
            $table->unique(['gedung', 'tahun', 'bulan'], 'unique_gedung_tahun_bulan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electricity_usages');
    }
};
