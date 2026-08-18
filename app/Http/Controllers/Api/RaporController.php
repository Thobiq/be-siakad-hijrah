<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->except(['foto_agama', 'foto_jati_diri', 'foto_literasi', 'foto_kokurikuler']);
            
            // Parse JSON jika dikirim sebagai string via FormData
            if (isset($data['pembiasaan_agama']) && is_string($data['pembiasaan_agama'])) {
                $data['pembiasaan_agama'] = json_decode($data['pembiasaan_agama'], true);
            }

            // Proses Upload Foto (multiple files)
            $fileFields = ['foto_agama', 'foto_jati_diri', 'foto_literasi', 'foto_kokurikuler'];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $paths = [];
                    foreach ($request->file($field) as $file) {
                        if ($file) {
                            $path = $file->store('rapor-fotos', 'public');
                            $paths[] = '/storage/' . $path;
                        }
                    }
                    $data[$field] = $paths;
                }
            }

            // Simpan atau Perbarui jika sudah ada
            $rapor = \App\Models\Rapor::updateOrCreate(
                [
                    'siswa_id' => $request->siswa_id,
                    'semester' => $request->semester,
                    'tahun_ajaran' => $request->tahun_ajaran,
                ],
                $data
            );

            return response()->json([
                'status' => true,
                'message' => 'E-Rapor berhasil disimpan!',
                'data' => $rapor
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan rapor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $rapor = \App\Models\Rapor::find($id);

        if (!$rapor) {
            return response()->json(['status' => false, 'message' => 'Data Rapor tidak ditemukan'], 404);
        }

        // Jika ingin menghapus foto dari storage juga bisa ditambahkan di sini, tapi untuk saat ini hapus data saja
        $rapor->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data Rapor berhasil dihapus!'
        ]);
    }

    public function downloadPdf($id)
    {
        // Tingkatkan memory limit dan execution time untuk DomPDF jika terdapat banyak foto beresolusi tinggi
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '300');

        $rapor = \App\Models\Rapor::with('siswa.kelas')->find($id);

        if (!$rapor) {
            return response()->json(['status' => false, 'message' => 'Data Rapor tidak ditemukan'], 404);
        }

        $siswa = $rapor->siswa;
        
        $profil = \App\Models\ProfilSekolah::first();
        if (!$profil) {
            $profil = (object)[
                'nama_sekolah' => 'Al-Hijrah',
                'alamat' => 'Jl. Jawa II No. 22 Sumbersari-Jember',
                'nama_kepala_sekolah' => 'INGE MARRINDA P, S.Pd',
                'logo_path' => 'images/logo-alhijrah.png'
            ];
        }

        // Pass data to view
        $data = [
            'rapor' => $rapor,
            'siswa' => $siswa,
            'profil' => $profil,
        ];

        // Pilih view berdasarkan tingkat
        $viewTemplate = (strtoupper($siswa->tingkat ?? '') === 'KB') ? 'rapor.pdf-kb' : 'rapor.pdf-tk';

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($viewTemplate, $data);
        
        // Set paper to A4 Portrait
        $pdf->setPaper('a4', 'portrait');

        $fileName = 'Rapor_' . ($siswa->tingkat ?? 'TK') . '_' . str_replace(' ', '_', $siswa->nama) . '.pdf';
        return $pdf->download($fileName);
    }
}
