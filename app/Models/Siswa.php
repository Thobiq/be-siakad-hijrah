<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nomor_induk',
        'nisn',
        'ttl',
        'alamat',
        'anak_ke',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'status',
        'tingkat',
        'tahun_ajaran',
        'kelas_id',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function rapors()
    {
        return $this->hasMany(Rapor::class);
    }

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }
}
