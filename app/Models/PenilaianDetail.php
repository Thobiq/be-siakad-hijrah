<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianDetail extends Model
{
    protected $guarded = ['id'];

    // Ini kunci rahasianya! Laravel akan otomatis handle JSON <-> Array
    protected $casts = [
        'pertemuan' => 'array',
    ];
}