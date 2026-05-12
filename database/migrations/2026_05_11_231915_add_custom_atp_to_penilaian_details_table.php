<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('penilaian_details', function (Blueprint $table) {
        // Buat atp_indikator_id jadi boleh kosong
        $table->unsignedBigInteger('atp_indikator_id')->nullable()->change();
        
        // Tambahkan kolom baru
        $table->unsignedBigInteger('tujuan_pembelajaran_id')->nullable()->after('atp_indikator_id');
        $table->string('deskripsi_custom')->nullable()->after('tujuan_pembelajaran_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_details', function (Blueprint $table) {
            //
        });
    }
};
