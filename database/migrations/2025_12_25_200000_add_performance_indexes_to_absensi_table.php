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
        Schema::table('absensi', function (Blueprint $table) {
            // Add composite index for user_id and tanggal queries (most common)
            $table->index(['user_id', 'tanggal'], 'idx_user_tanggal');

            // Add index for user_id only queries
            $table->index('user_id', 'idx_user_id');

            // Add index for tanggal only queries (for reports)
            $table->index('tanggal', 'idx_tanggal');

            // Add index for status queries
            $table->index('status', 'idx_status');

            // Add index for created_at queries
            $table->index('created_at', 'idx_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropIndex('idx_user_tanggal');
            $table->dropIndex('idx_user_id');
            $table->dropIndex('idx_tanggal');
            $table->dropIndex('idx_status');
            $table->dropIndex('idx_created_at');
        });
    }
};
