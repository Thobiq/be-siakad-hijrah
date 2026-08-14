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
        Schema::create('rapors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->string('tingkat')->nullable(); // KB, TK A, TK B
            $table->string('semester')->nullable(); // Ganjil, Genap
            $table->string('tahun_ajaran')->nullable(); // 2025/2026
            
            // Data Diri Khusus Rapor
            $table->string('tinggi_badan')->nullable();
            $table->string('berat_badan')->nullable();
            $table->string('lingkar_kepala')->nullable();
            
            // KB Specific
            $table->json('pembiasaan_agama')->nullable(); // [{"materi": "Doa", "capaian": "Lancar"}]
            $table->text('catatan_bu_guru')->nullable();
            
            // Narasi (Umum)
            $table->text('narasi_agama')->nullable();
            $table->text('narasi_jati_diri')->nullable();
            $table->text('narasi_literasi')->nullable();
            $table->text('narasi_kokurikuler')->nullable(); // TK Specific
            
            // Foto Kegiatan (Simpan path sebagai JSON array)
            $table->json('foto_agama')->nullable();
            $table->json('foto_jati_diri')->nullable();
            $table->json('foto_literasi')->nullable();
            $table->json('foto_kokurikuler')->nullable();
            
            // Absensi
            $table->integer('sakit')->default(0);
            $table->integer('ijin')->default(0);
            $table->integer('tanpa_keterangan')->default(0);
            
            // Refleksi Guru
            $table->text('refleksi_guru')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapors');
    }
};
