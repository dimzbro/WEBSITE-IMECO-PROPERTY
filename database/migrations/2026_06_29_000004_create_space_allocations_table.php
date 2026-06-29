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
        Schema::create('space_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('set null');
            $table->foreignId('building_id')->constrained('buildings')->onDelete('cascade');
            $table->integer('floor_number');
            $table->string('unit_number'); // e.g. A-301, B-501
            $table->integer('area_size'); // in sqm
            $table->bigInteger('rent_price'); // monthly rent in IDR
            $table->date('lease_start')->nullable();
            $table->date('lease_end')->nullable();
            $table->string('status')->default('Kosong'); // Terisi, Hampir Berakhir, Berakhir, Kosong
            $table->string('payment_status')->nullable(); // Lunas, Menunggu, Tertunggak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('space_allocations');
    }
};
