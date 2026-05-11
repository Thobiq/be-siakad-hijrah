<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ElemenCapaian;
use App\Models\Siswa;

class PenilaianController extends Controller
{

    public function index(Request $request)
    {
        // Tangkap parameter 'tingkat' dari URL SvelteKit (contoh: ?tingkat=KB)
        $tingkat = $request->query('tingkat');

        // Tarik data penilaian dan siswa
        $query = \App\Models\Penilaian::with('siswa')->orderBy('created_at', 'desc');

        // Jika ada parameter tingkat, filter datanya!
        if ($tingkat) {
            $query->whereHas('siswa', function ($q) use ($tingkat) {
                $q->where('tingkat', $tingkat);
            });
        }

        $penilaians = $query->get();

        $formattedData = $penilaians->map(function ($p) {
            return [
                'id' => $p->id,
                'nama' => $p->siswa->nama ?? 'Siswa Tidak Ditemukan',
                'noInduk' => $p->siswa->nomor_induk ?? '-',
                'status' => $p->status
            ];
        });

        return response()->json(['status' => true, 'data' => $formattedData]);
    }

    public function getMasterData()
    {
        // 1. Ambil data siswa yang statusnya Aktif
        $siswa = Siswa::where('status', 'Aktif')->get();

        // 2. Ambil seluruh hierarki Elemen -> CP -> TP -> ATP
        // Penulisan titik (.) menandakan kita mengambil relasi bersarang
        $elemen = ElemenCapaian::with([
            'capaianPembelajarans.tujuanPembelajarans.atpIndikators'
        ])->get();

        // 3. Kembalikan sebagai JSON
        return response()->json([
            'status' => true,
            'message' => 'Berhasil mengambil master data penilaian',
            'data' => [
                'siswa' => $siswa,
                'elemen' => $elemen
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'no_induk' => 'required|string',
            'tahun_ajaran' => 'required|string',
            'tingkat' => 'required|string' // 👈 Tambahkan validasi tingkat
        ]);

        $siswa = \App\Models\Siswa::firstOrCreate(
            ['nomor_induk' => $request->no_induk],
            [
                'nama' => $request->nama, 
                'tingkat' => $request->tingkat, // 👈 Simpan tingkatnya
                'status' => 'Aktif'
            ]
        );

        $guru = \App\Models\Guru::where('user_id', auth()->id())->first();

        $penilaian = \App\Models\Penilaian::create([
            'siswa_id' => $siswa->id,
            'guru_id' => $guru->id,
            'elemen_capaian_id' => 1, 
            'tahun_ajaran' => $request->tahun_ajaran,
            'status' => 'Draft'
        ]);

        return response()->json(['status' => true, 'message' => 'Berhasil', 'data' => $penilaian]);
    }

    public function show($id)
    {
        // 1. Cari data penilaian berdasarkan ID beserta data siswanya
        $penilaian = \App\Models\Penilaian::with('siswa')->find($id);

        if (!$penilaian) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        // 2. Ambil semua daftar Elemen Penilaian untuk dijadikan tombol
        $elemen = \App\Models\ElemenCapaian::all();

        return response()->json([
            'status' => true,
            'data' => [
                'penilaian' => $penilaian,
                'elemen' => $elemen
            ]
        ]);
    }

    public function getMatriksData($id, $elemenId)
    {
        // 1. Ambil data Penilaian beserta data Siswanya
        $penilaian = \App\Models\Penilaian::with('siswa')->find($id);

        if (!$penilaian) {
            return response()->json(['status' => false, 'message' => 'Data penilaian tidak ditemukan'], 404);
        }

        // 2. Ambil Master Data Elemen beserta anak-cucunya (CP, TP, ATP)
        $elemenData = \App\Models\ElemenCapaian::with([
            'capaianPembelajarans.tujuanPembelajarans.atpIndikators'
        ])->find($elemenId);

        $savedDetails = \App\Models\PenilaianDetail::where('penilaian_id', $id)->get();

        if (!$elemenData) {
            return response()->json(['status' => false, 'message' => 'Data elemen tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'siswa' => [
                    'nama' => $penilaian->siswa->nama,
                    'nomor_induk' => $penilaian->siswa->nomor_induk
                ],
                'elemen' => $elemenData,
                'saved_details' => $savedDetails,
            ]
        ]);
    }

    public function saveMatriksData(Request $request, $id, $elemenId)
    {
        $details = $request->details;

        if (!$details) {
            $details = [];
        }

        $atpIdsFromFrontend = collect($details)->pluck('atp_id')->filter()->toArray();

        // 1. CARI TAHU ATP MILIK ELEMEN INI (Menggunakan metode Top-to-Bottom yang aman)
        $elemen = \App\Models\ElemenCapaian::with('capaianPembelajarans.tujuanPembelajarans.atpIndikators')->find($elemenId);
        
        $atpIdsInThisElemen = [];
        if ($elemen) {
            foreach ($elemen->capaianPembelajarans as $cp) {
                foreach ($cp->tujuanPembelajarans as $tp) {
                    foreach ($tp->atpIndikators as $atp) {
                        $atpIdsInThisElemen[] = $atp->id;
                    }
                }
            }
        }

        // 2. FITUR HAPUS: Hapus data yang tidak ada di layar, TAPI hanya untuk elemen ini
        \App\Models\PenilaianDetail::where('penilaian_id', $id)
            ->whereIn('atp_indikator_id', $atpIdsInThisElemen)
            ->whereNotIn('atp_indikator_id', $atpIdsFromFrontend)
            ->delete();

        // 3. FITUR UPDATE & CREATE: Simpan nilai yang dikirim
        foreach ($details as $item) {
            \App\Models\PenilaianDetail::updateOrCreate(
                [
                    'penilaian_id' => $id,
                    'atp_indikator_id' => $item['atp_id']
                ],
                [
                    'pertemuan' => $item['pertemuan'], 
                    'nilai_akhir' => $item['nilai_akhir']
                ]
            );
        }

        \App\Models\Penilaian::where('id', $id)->update(['status' => 'Selesai']);

        return response()->json(['status' => true, 'message' => 'Penilaian berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        $penilaian = \App\Models\Penilaian::find($id);

        if (!$penilaian) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        // Hapus detail penilaiannya (riwayat nilainya) terlebih dahulu agar tidak ada data yatim piatu
        \App\Models\PenilaianDetail::where('penilaian_id', $id)->delete();
        
        // Hapus data utama penilaiannya
        $penilaian->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data penilaian berhasil dihapus!'
        ]);
    }

    public function updateStatus($id)
    {
        $penilaian = \App\Models\Penilaian::find($id);

        if (!$penilaian) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan!']);
        }

        $penilaian->status = 'Selesai';
        $penilaian->save();

        return response()->json([
            'status' => true, 
            'message' => 'Status penilaian berhasil diubah menjadi Selesai!'
        ]);
    }

    public function updateToDraft($id)
    {
        $penilaian = \App\Models\Penilaian::find($id);

        if (!$penilaian) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan!']);
        }

        $penilaian->status = 'Draft'; // Ubah kembali ke Draft
        $penilaian->save();

        return response()->json([
            'status' => true, 
            'message' => 'Status berhasil dikembalikan ke Draft!'
        ]);
    }
}