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
        Schema::create('marketing_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->string('lokasi'); // Main location from first location
            $table->integer('total_locations')->default(1); // Total number of locations
            $table->json('locations_data'); // Store detailed locations data
            $table->string('status')->default('draft'); // draft, submitted
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'tanggal']);
            $table->index(['status', 'tanggal']);
            $table->index(['tanggal']); // For date range queries
        });

        // Create separate table for marketing locations for better admin review
        Schema::create('marketing_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_report_id')->constrained()->onDelete('cascade');
            $table->string('lokasi');
            $table->string('nama_kontak');
            $table->string('nomor_kontak');
            $table->text('laporan');
            $table->json('photos'); // Store photos data
            $table->timestamps();

            $table->index(['marketing_report_id']);
            $table->index(['lokasi']);
            $table->index(['nama_kontak']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_locations');
        Schema::dropIfExists('marketing_reports');
    }
};
