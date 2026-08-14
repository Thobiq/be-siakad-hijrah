<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::with('waliKelas');

        if ($request->has('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        $kelas = $query->get();

        return response()->json([
            'status' => true,
            'data' => $kelas
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tingkat' => 'required|in:KB,TK A,TK B',
            'nama_kelas' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|in:Ganjil,Genap',
            'guru_id' => 'nullable|exists:gurus,id'
        ]);

        $kelas = Kelas::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Kelas berhasil ditambahkan.',
            'data' => Kelas::with('waliKelas')->find($kelas->id)
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json(['status' => false, 'message' => 'Kelas tidak ditemukan.'], 404);
        }

        $request->validate([
            'tingkat' => 'required|in:KB,TK A,TK B',
            'nama_kelas' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|in:Ganjil,Genap',
            'guru_id' => 'nullable|exists:gurus,id'
        ]);

        $kelas->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Kelas berhasil diperbarui.',
            'data' => Kelas::with('waliKelas')->find($kelas->id)
        ]);
    }

    public function destroy($id)
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json(['status' => false, 'message' => 'Kelas tidak ditemukan.'], 404);
        }

        $kelas->delete();

        return response()->json([
            'status' => true,
            'message' => 'Kelas berhasil dihapus.'
        ]);
    }
}
