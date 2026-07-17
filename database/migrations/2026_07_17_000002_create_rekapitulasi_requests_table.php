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
        Schema::create('rekapitulasi_requests', function (Blueprint $table) {
            $table->id();
            $table->string('no_cr')->nullable();           // contoh: CR/2025/VI/117
            $table->string('tenant')->nullable();
            $table->string('name')->nullable();            // nama PIC
            $table->date('date')->nullable();
            $table->text('request_description')->nullable();
            $table->text('request_handling')->nullable();
            $table->string('jenis_pekerjaan')->nullable(); // Sipil, Elektrikal, dll
            $table->string('name_handling')->nullable();
            $table->string('time')->nullable();
            $table->string('status')->nullable();          // Done, dll
            $table->decimal('cost', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekapitulasi_requests');
    }
};
