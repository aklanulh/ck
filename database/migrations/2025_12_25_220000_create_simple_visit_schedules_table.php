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
        Schema::create('visit_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('judul_kunjungan');
            $table->text('lokasi_kunjungan')->nullable();
            $table->text('deskripsi_kunjungan')->nullable();
            $table->date('tanggal_kunjungan');
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'tanggal_kunjungan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_schedules');
    }
};
