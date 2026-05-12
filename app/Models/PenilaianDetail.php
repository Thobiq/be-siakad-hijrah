<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianDetail extends Model
{
    // Cukup gunakan fillable saja agar lebih eksplisit
    protected $fillable = [
        'penilaian_id', 
        'atp_indikator_id', 
        'tujuan_pembelajaran_id', // Relasi untuk ATP custom
        'deskripsi_custom',       // Teks untuk ATP custom
        'pertemuan', 
        'nilai_akhir'
    ];

    protected $casts = [
        'pertemuan' => 'array',
    ];

    /**
     * Relasi ke Tabel Penilaian (Induk)
     */
    public function penilaian(): BelongsTo
    {
        return $this->belongsTo(Penilaian::class);
    }

    /**
     * Relasi ke Master Data ATP (Jika menggunakan data yang sudah ada)
     */
    public function atpIndikator(): BelongsTo
    {
        return $this->belongsTo(AtpIndikator::class, 'atp_indikator_id');
    }

    /**
     * Relasi ke Master Data Tujuan Pembelajaran
     * Penting: Ini tetap dibutuhkan baik untuk ATP master maupun ATP custom
     */
    public function tujuanPembelajaran(): BelongsTo
    {
        return $this->belongsTo(TujuanPembelajaran::class, 'tujuan_pembelajaran_id');
    }
}