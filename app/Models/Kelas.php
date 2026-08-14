<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'tingkat',
        'nama_kelas',
        'tahun_ajaran',
        'semester',
        'guru_id'
    ];

    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
