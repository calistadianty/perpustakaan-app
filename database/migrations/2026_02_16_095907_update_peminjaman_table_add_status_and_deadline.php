<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new column first
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->date('tanggal_batas_kembali')->nullable()->after('tanggal_pinjam');
        });

        // Modify enum using raw SQL for better compatibility or simply re-define if driver supports
        // Since Laravel's enum modification can be tricky with some drivers, 
        // we'll try to change column definition.
        // NOTE: For SQLite/MySQL/Postgres, standardized way often varies.
        // Here we assume MySQL/MariaDB or similar which allows modifying column.
        
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('pending', 'disetujui', 'ditolak', 'dipinjam', 'dikembalikan') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert enum
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('dipinjam', 'dikembalikan') DEFAULT 'dipinjam'");

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('tanggal_batas_kembali');
        });
    }
};
