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
        // 1. Suntikkan kolom-kolom baru yang belum ada sebelumnya
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('nisn')->nullable()->after('nomor_induk');
            $table->string('ttl')->nullable()->after('nisn');
            $table->text('alamat')->nullable()->after('ttl');
            $table->string('nama_ibu')->nullable()->after('alamat');
            $table->string('tahun_ajaran')->after('tingkat');
        });

        // 2. Modifikasi ENUM status agar mendukung 'Tidak Aktif' dari frontend
        // Menggunakan DB::statement karena memodifikasi ENUM bawaan lama lebih aman dengan native SQL
        DB::statement("ALTER TABLE siswas MODIFY COLUMN status ENUM('Aktif', 'Lulus', 'Pindah', 'Tidak Aktif') DEFAULT 'Aktif'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Kembalikan ENUM status ke struktur semula jika di-rollback
        DB::statement("ALTER TABLE siswas MODIFY COLUMN status ENUM('Aktif', 'Lulus', 'Pindah') DEFAULT 'Aktif'");

        // 2. Hapus kolom-kolom yang tadi disuntikkan
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn(['nisn', 'ttl', 'alamat', 'nama_ibu', 'tahun_ajaran']);
        });
    }
};