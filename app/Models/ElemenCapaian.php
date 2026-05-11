<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElemenCapaian extends Model
{
    protected $guarded = ['id'];

    public function capaianPembelajarans()
    {
        return $this->hasMany(CapaianPembelajaran::class);
    }
}
