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
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('anak_ke')->nullable()->after('alamat');
            $table->string('nama_ayah')->nullable()->after('nama_ibu');
            $table->string('pekerjaan_ayah')->nullable()->after('nama_ayah');
            $table->string('pekerjaan_ibu')->nullable()->after('pekerjaan_ayah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn(['anak_ke', 'nama_ayah', 'pekerjaan_ayah', 'pekerjaan_ibu']);
        });
    }
};
