<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CapaianPembelajaran;
use App\Models\TujuanPembelajaran;
use App\Models\AtpIndikator;

class KurikulumController extends Controller
{
    // ==================================================
    // CAPAIAN PEMBELAJARAN (CP)
    // ==================================================
    public function storeCp(Request $request)
    {
        $request->validate([
            'elemen_capaian_id' => 'required|exists:elemen_capaians,id',
            'deskripsi' => 'required|string'
        ]);

        $cp = CapaianPembelajaran::create($request->only(['elemen_capaian_id', 'deskripsi']));
        return response()->json(['status' => true, 'message' => 'CP Berhasil ditambahkan!', 'data' => $cp]);
    }

    public function updateCp(Request $request, $id)
    {
        $cp = CapaianPembelajaran::findOrFail($id);
        $cp->update(['deskripsi' => $request->deskripsi]);
        return response()->json(['status' => true, 'message' => 'CP Berhasil diperbarui!']);
    }

    public function destroyCp($id)
    {
        CapaianPembelajaran::destroy($id);
        return response()->json(['status' => true, 'message' => 'CP Berhasil dihapus!']);
    }


    // ==================================================
    // TUJUAN PEMBELAJARAN (TP)
    // ==================================================
    public function storeTp(Request $request)
    {
        $request->validate([
            'capaian_pembelajaran_id' => 'required|exists:capaian_pembelajarans,id',
            'deskripsi' => 'required|string'
        ]);

        $tp = TujuanPembelajaran::create($request->only(['capaian_pembelajaran_id', 'deskripsi']));
        return response()->json(['status' => true, 'message' => 'TP Berhasil ditambahkan!', 'data' => $tp]);
    }

    public function updateTp(Request $request, $id)
    {
        $tp = TujuanPembelajaran::findOrFail($id);
        $tp->update(['deskripsi' => $request->deskripsi]);
        return response()->json(['status' => true, 'message' => 'TP Berhasil diperbarui!']);
    }

    public function destroyTp($id)
    {
        TujuanPembelajaran::destroy($id);
        return response()->json(['status' => true, 'message' => 'TP Berhasil dihapus!']);
    }


    // ==================================================
    // ATP / INDIKATOR
    // ==================================================
    public function storeAtp(Request $request)
    {
        $request->validate([
            'tujuan_pembelajaran_id' => 'required|exists:tujuan_pembelajarans,id',
            'deskripsi' => 'required|string'
        ]);

        $atp = AtpIndikator::create($request->only(['tujuan_pembelajaran_id', 'deskripsi']));
        return response()->json(['status' => true, 'message' => 'ATP/Indikator Berhasil ditambahkan!', 'data' => $atp]);
    }

    public function updateAtp(Request $request, $id)
    {
        $atp = AtpIndikator::findOrFail($id);
        $atp->update(['deskripsi' => $request->deskripsi]);
        return response()->json(['status' => true, 'message' => 'ATP/Indikator Berhasil diperbarui!']);
    }

    public function destroyAtp($id)
    {
        AtpIndikator::destroy($id);
        return response()->json(['status' => true, 'message' => 'ATP/Indikator Berhasil dihapus!']);
    }
}