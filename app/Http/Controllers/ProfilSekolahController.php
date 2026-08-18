<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProfilSekolah;
use Illuminate\Support\Facades\Storage;

class ProfilSekolahController extends Controller
{
    public function index()
    {
        $profil = ProfilSekolah::first();
        if (!$profil) {
            $profil = ProfilSekolah::create([
                'nama_sekolah' => 'Al-Hijrah',
                'alamat' => 'Jl. Jawa II No. 22 Sumbersari-Jember',
                'nama_kepala_sekolah' => 'INGE MARRINDA P, S.Pd',
                'logo_path' => 'images/logo-alhijrah.png'
            ]);
        }
        return response()->json($profil);
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nama_kepala_sekolah' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $profil = ProfilSekolah::first();
        if (!$profil) {
            $profil = new ProfilSekolah();
        }

        $profil->nama_sekolah = $request->nama_sekolah;
        $profil->alamat = $request->alamat;
        $profil->nama_kepala_sekolah = $request->nama_kepala_sekolah;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Pindahkan langsung ke folder public/images untuk menghindari isu symlink di Windows (php artisan serve)
            $file->move(public_path('images'), $filename);
            $profil->logo_path = 'images/' . $filename;
        }

        $profil->save();

        return response()->json(['message' => 'Profil berhasil diperbarui', 'data' => $profil]);
    }
}
