<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtpIndikator extends Model
{
    protected $fillable = [
        'tujuan_pembelajaran_id', 
        'deskripsi'
    ];
}
