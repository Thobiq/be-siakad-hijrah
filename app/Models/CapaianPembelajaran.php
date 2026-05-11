<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapaianPembelajaran extends Model
{
    protected $guarded = ['id'];

    public function tujuanPembelajarans()
    {
        return $this->hasMany(TujuanPembelajaran::class);
    }
}
