<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('category');
            $table->string('title');
            $table->text('description');
            $table->string('priority'); // Rendah, Sedang, Tinggi, Kritis
            $table->string('status')->default('Menunggu'); // Menunggu, Dalam Proses, Selesai, Dibatalkan
            $table->string('assigned_to');
            $table->text('notes')->nullable();
            $table->date('requested_at');
            $table->date('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};
