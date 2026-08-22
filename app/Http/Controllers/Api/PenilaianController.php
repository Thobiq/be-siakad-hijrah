<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ElemenCapaian;
use Illuminate\Support\Facades\DB;
use App\Models\Penilaian;
use App\Models\Siswa;

class PenilaianController extends Controller
{

    public function index(Request $request)
    {
        // Tangkap parameter 'tingkat' dari URL SvelteKit (contoh: ?tingkat=KB)
        $tingkat = $request->query('tingkat');

        // Tarik data penilaian dan siswa beserta kelasnya dan rapor
        $query = \App\Models\Penilaian::with(['kelas', 'siswa.kelas', 'siswa.rapors'])->orderBy('created_at', 'desc');

        // Jika ada parameter tingkat, filter datanya!
        if ($tingkat) {
            $query->where(function ($q) use ($tingkat) {
                // Jika punya relasi kelas (data baru), filter dari tingkat kelas historis
                $q->whereHas('kelas', function ($q2) use ($tingkat) {
                    $q2->where('tingkat', $tingkat);
                })
                // Jika belum punya relasi kelas (data lama), filter dari tingkat siswa saat ini
                ->orWhere(function ($q2) use ($tingkat) {
                    $q2->whereNull('kelas_id')
                       ->whereHas('siswa', function ($q3) use ($tingkat) {
                           $q3->where('tingkat', $tingkat);
                       });
                });
            });
        }

        $penilaians = $query->get();

        $formattedData = $penilaians->map(function ($p) {
            $rapor_id = null;
            if ($p->siswa && $p->siswa->rapors) {
                $rapor = $p->siswa->rapors->where('semester', $p->semester)->where('tahun_ajaran', $p->tahun_ajaran)->first();
                if ($rapor) {
                    $rapor_id = $rapor->id;
                }
            }

            return [
                'id' => $p->id,
                'siswa_id' => $p->siswa_id,
                'nama' => $p->siswa->nama ?? 'Siswa Tidak Ditemukan',
                'noInduk' => $p->siswa->nomor_induk ?? '-',
                'kelas' => $p->kelas->nama_kelas ?? ($p->siswa->kelas->nama_kelas ?? '-'),
                'tahun_ajaran' => $p->tahun_ajaran ?? '-',
                'semester' => $p->semester ?? 'Ganjil',
                'status' => $p->status,
                'rapor_id' => $rapor_id
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
            'tingkat' => 'required|string', // 👈 Tambahkan validasi tingkat
            'semester' => 'required|string'
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
            'kelas_id' => $siswa->kelas_id,
            'elemen_capaian_id' => 1, 
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester,
            'status' => 'Draft'
        ]);

        return response()->json(['status' => true, 'message' => 'Berhasil', 'data' => $penilaian]);
    }

    public function show($id)
    {
        // Ambil penilaian beserta siswa, kelas, dan rapor
        $penilaian = \App\Models\Penilaian::with(['kelas', 'siswa.kelas', 'siswa.rapors'])->find($id);

        if (!$penilaian) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        // Ambil semua daftar Elemen Penilaian untuk dijadikan tombol
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

        // 1. CARI TAHU TP ID & ATP ID MILIK ELEMEN INI
        // (Pastikan atpIndikators juga di-load agar kita bisa mengambil ID data lama)
        $elemen = \App\Models\ElemenCapaian::with('capaianPembelajarans.tujuanPembelajarans.atpIndikators')->find($elemenId);
        
        $tpIdsInThisElemen = [];
        $atpIdsInThisElemen = [];
        
        if ($elemen) {
            foreach ($elemen->capaianPembelajarans as $cp) {
                foreach ($cp->tujuanPembelajarans as $tp) {
                    $tpIdsInThisElemen[] = $tp->id; // Ambil TP ID untuk data baru
                    
                    foreach ($tp->atpIndikators as $atp) {
                        $atpIdsInThisElemen[] = $atp->id; // Ambil ATP ID untuk data lama
                    }
                }
            }
        }

        // 2. FITUR HAPUS (SYNC): Hapus data Lama dan Baru sekaligus!
        if (count($tpIdsInThisElemen) > 0 || count($atpIdsInThisElemen) > 0) {
            \App\Models\PenilaianDetail::where('penilaian_id', $id)
                ->where(function($query) use ($tpIdsInThisElemen, $atpIdsInThisElemen) {
                    $query->whereIn('tujuan_pembelajaran_id', $tpIdsInThisElemen)
                          ->orWhereIn('atp_indikator_id', $atpIdsInThisElemen);
                })
                ->delete();
        }

        // 3. INSERT ULANG DATA BARU (Master & Custom)
        foreach ($details as $item) {
            \App\Models\PenilaianDetail::create([
                'penilaian_id' => $id,
                'tujuan_pembelajaran_id' => $item['tp_id'] ?? null,
                'atp_indikator_id' => $item['atp_id'] ?? null,
                'deskripsi_custom' => $item['deskripsi_custom'] ?? null,
                'pertemuan' => $item['pertemuan'], 
                'nilai_akhir' => $item['nilai_akhir']
            ]);
        }

        return response()->json(['status' => true, 'message' => 'Penilaian berhasil diperbarui!']);
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi input dari frontend
        $request->validate([
            'nama'         => 'required|string|max:255',
            'no_induk'     => 'required|string|max:100',
            'tahun_ajaran' => 'required|string',
            'tingkat'      => 'required|string',
            'semester'     => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // 2. Cari data penilaian berdasarkan ID
            $penilaian = Penilaian::with('siswa')->find($id);

            if (!$penilaian) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data penilaian tidak ditemukan.'
                ], 404);
            }

            // 3. Update data Siswa (karena nama dan no_induk menempel pada siswa)
            if ($penilaian->siswa) {
                $penilaian->siswa->update([
                    'nama'        => $request->nama,
                    'nomor_induk' => $request->no_induk, // Sesuaikan dengan nama kolom di database-mu
                ]);
            }

            // 4. Update data Penilaian-nya sendiri
            $penilaian->update([
                'tahun_ajaran' => $request->tahun_ajaran,
                'semester'     => $request->semester,
                'tingkat'      => $request->tingkat,
            ]);

            DB::commit();

            // 5. Kembalikan response sukses ke SvelteKit
            return response()->json([
                'status'  => true,
                'message' => 'Data berhasil diperbarui!',
                'data'    => $penilaian
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
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

    public function getDashboardStats()
    {
        // Menghitung jumlah penilaian yang statusnya 'Selesai' berdasarkan tingkat siswanya
        $kb = \App\Models\Penilaian::where('status', 'Selesai')
            ->whereHas('siswa', function ($query) { 
                $query->where('tingkat', 'KB'); 
            })->count();

        $tka = \App\Models\Penilaian::where('status', 'Selesai')
            ->whereHas('siswa', function ($query) { 
                $query->where('tingkat', 'TK A'); // Sesuaikan string 'TKA' dengan yang ada di databasemu
            })->count();

        $tkb = \App\Models\Penilaian::where('status', 'Selesai')
            ->whereHas('siswa', function ($query) { 
                $query->where('tingkat', 'TK B'); // Sesuaikan string 'TKB' dengan yang ada di databasemu
            })->count();

        return response()->json([
            'status' => true,
            'data' => [
                'kb' => $kb,
                'tka' => $tka,
                'tkb' => $tkb
            ]
        ]);
    }
}