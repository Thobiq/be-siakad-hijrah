<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TujuanPembelajaran extends Model
{
    protected $guarded = ['id'];

    public function atpIndikators()
    {
        return $this->hasMany(AtpIndikator::class);
    }

    protected $fillable = [
        'capaian_pembelajaran_id', 
        'deskripsi'
    ];
}
