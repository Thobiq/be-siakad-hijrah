<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rapor extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'pembiasaan_agama' => 'array',
        'foto_agama' => 'array',
        'foto_jati_diri' => 'array',
        'foto_literasi' => 'array',
        'foto_kokurikuler' => 'array',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
