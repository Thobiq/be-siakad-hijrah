<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfilSekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ProfilSekolah::create([
            'nama_sekolah' => 'Al-Hijrah',
            'alamat' => 'Jl. Jawa II No. 22 Sumbersari-Jember',
            'nama_kepala_sekolah' => 'INGE MARRINDA P, S.Pd',
            'logo_path' => 'images/logo-alhijrah.png'
        ]);
    }
}
