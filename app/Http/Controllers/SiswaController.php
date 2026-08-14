<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    // GET: Ambil data siswa berdasarkan tingkat (KB/TKA/TKB) dan/atau kelas_id
    public function index(Request $request)
    {
        $query = Siswa::with('kelas');
        
        if ($request->has('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        if ($request->has('kelas_id')) {
            if ($request->kelas_id === 'none') {
                $query->whereNull('kelas_id');
            } else {
                $query->where('kelas_id', $request->kelas_id);
            }
        }

        return response()->json([
            'status' => true,
            'data' => $query->orderBy('nama', 'asc')->get()
        ]);
    }

    // POST: Tambah siswa baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_induk' => 'required|string|unique:siswas,nomor_induk',
            'nisn' => 'nullable|string',
            'ttl' => 'nullable|string',
            'alamat' => 'nullable|string',
            'anak_ke' => 'nullable|string',
            'nama_ayah' => 'nullable|string',
            'nama_ibu' => 'nullable|string',
            'pekerjaan_ayah' => 'nullable|string',
            'pekerjaan_ibu' => 'nullable|string',
            'status' => 'required|string',
            'tingkat' => 'required|string',
            'tahun_ajaran' => 'required|string',
            'kelas_id' => 'nullable|exists:kelas,id'
        ]);

        $siswa = Siswa::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Siswa berhasil ditambahkan',
            'data' => Siswa::with('kelas')->find($siswa->id)
        ]);
    }

    // PUT: Update data siswa
    public function update(Request $request, $id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_induk' => 'required|string|unique:siswas,nomor_induk,' . $id, // Abaikan validasi unique untuk diri sendiri
            'nisn' => 'nullable|string',
            'ttl' => 'nullable|string',
            'alamat' => 'nullable|string',
            'anak_ke' => 'nullable|string',
            'nama_ayah' => 'nullable|string',
            'nama_ibu' => 'nullable|string',
            'pekerjaan_ayah' => 'nullable|string',
            'pekerjaan_ibu' => 'nullable|string',
            'status' => 'required|string',
            'tingkat' => 'required|string',
            'tahun_ajaran' => 'required|string',
            'kelas_id' => 'nullable|exists:kelas,id'
        ]);

        $siswa->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Data siswa berhasil diperbarui',
            'data' => Siswa::with('kelas')->find($siswa->id)
        ]);
    }

    // DELETE: Hapus data siswa
    public function destroy($id)
    {
        $siswa = Siswa::find($id);
        
        if ($siswa) {
            $siswa->delete();
            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
        }

        return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);
    }

    // POST: Aksi massal (bulk action)
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|string|in:pindah_kelas,naik_kelas,ubah_status',
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'exists:siswas,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'status' => 'nullable|string',
            'tingkat' => 'nullable|string'
        ]);

        $action = $validated['action'];
        $siswaIds = $validated['siswa_ids'];

        DB::beginTransaction();
        try {
            if ($action === 'pindah_kelas' || $action === 'naik_kelas') {
                $updateData = [];
                if (isset($validated['kelas_id'])) {
                    $updateData['kelas_id'] = $validated['kelas_id'];
                } else {
                    $updateData['kelas_id'] = null; // Bisa dikosongkan (tanpa kelas)
                }

                if (isset($validated['tingkat'])) {
                    $updateData['tingkat'] = $validated['tingkat'];
                }

                Siswa::whereIn('id', $siswaIds)->update($updateData);

            } elseif ($action === 'ubah_status') {
                if (isset($validated['status'])) {
                    Siswa::whereIn('id', $siswaIds)->update(['status' => $validated['status']]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Aksi massal berhasil dilakukan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
