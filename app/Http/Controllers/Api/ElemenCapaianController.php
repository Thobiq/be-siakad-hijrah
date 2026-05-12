<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ElemenCapaian;

class ElemenCapaianController extends Controller
{
    // Mengambil semua daftar elemen
    public function index()
    {
        $elemen = ElemenCapaian::orderBy('created_at', 'asc')->get();
        return response()->json(['status' => true, 'data' => $elemen]);
    }

    // Mengambil 1 elemen beserta SELURUH anak cucunya (CP -> TP -> ATP)
    public function show($id)
    {
        $elemen = ElemenCapaian::with(['capaianPembelajarans.tujuanPembelajarans.atpIndikators'])->find($id);
        
        if (!$elemen) {
            return response()->json(['status' => false, 'message' => 'Elemen tidak ditemukan'], 404);
        }

        return response()->json(['status' => true, 'data' => $elemen]);
    }

    // Menyimpan elemen baru
    public function store(Request $request)
    {
        $request->validate(['nama_elemen' => 'required|string']);
        
        $elemen = ElemenCapaian::create(['nama_elemen' => $request->nama_elemen]);
        return response()->json(['status' => true, 'message' => 'Elemen berhasil ditambahkan!', 'data' => $elemen]);
    }

    // Mengupdate nama elemen
    public function update(Request $request, $id)
    {
        $request->validate(['nama_elemen' => 'required|string']);
        
        $elemen = ElemenCapaian::find($id);
        if(!$elemen) return response()->json(['status' => false, 'message' => 'Elemen tidak ditemukan'], 404);

        $elemen->update(['nama_elemen' => $request->nama_elemen]);
        return response()->json(['status' => true, 'message' => 'Elemen berhasil diperbarui!']);
    }

    // Menghapus elemen (dan semua yang ada di dalamnya)
    public function destroy($id)
    {
        $elemen = ElemenCapaian::find($id);
        if(!$elemen) return response()->json(['status' => false, 'message' => 'Elemen tidak ditemukan'], 404);

        // Fitur hapus ini sebaiknya didukung dengan ON DELETE CASCADE di database 
        // agar anak cucunya otomatis terhapus, atau hapus lewat Eloquent Model Events.
        $elemen->delete();
        
        return response()->json(['status' => true, 'message' => 'Elemen berhasil dihapus!']);
    }
}