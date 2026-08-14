<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    public function generateNarasi(Request $request)
    {
        $request->validate([
            'nama_siswa' => 'required|string',
            'kategori'   => 'required|string',
            'poin_guru'  => 'required|string'
        ]);

        $nama = $request->nama_siswa;
        $kategori = $request->kategori;
        $poin = $request->poin_guru;

        // 1. Merakit Prompt Instruksi yang ketat untuk Gemini
        $prompt = "Anda adalah seorang guru TK/PAUD yang sangat profesional, penuh kasih sayang, dan terbiasa menulis narasi rapor dengan sangat detail, mendalam, dan menggunakan bahasa Indonesia baku yang mengalir.
        
        Tugas Anda adalah mengembangkan catatan singkat dari guru menjadi narasi rapor yang panjang dan komprehensif untuk elemen '{$kategori}'.
        
        Data Siswa:
        - Nama Anak: {$nama}
        - Catatan Singkat Guru: {$poin}

        Aturan Penulisan Wajib:
        1. Buat narasi yang panjang, WAJIB terdiri dari 3 hingga 4 paragraf.
        2. Gunakan sapaan 'Ananda' untuk menyebut anak tersebut.
        3. Kembangkan catatan singkat guru dengan menambahkan konteks pembelajaran PAUD yang relevan (misalnya: jika guru mencatat 'hafal doa', kembangkan menjadi cerita tentang pembiasaan adab sehari-hari di kelas).
        4. Ikuti struktur paragraf berikut:
           - Paragraf 1: Pembukaan yang mengapresiasi perkembangan anak secara umum pada semester ini sesuai elemen tersebut (misal: pengenalan ciptaan Tuhan, sikap awal yang positif, dll).
           - Paragraf 2 & 3: Penjabaran mendetail dari 'Catatan Singkat Guru'. Rangkai poin-poin tersebut dengan kalimat deskriptif mengenai kemajuan, kemandirian, atau cara Ananda berinteraksi dengan teman/guru.
           - Paragraf 4: Kesimpulan, rencana pembiasaan untuk semester depan, doa/harapan guru, dan ucapan terima kasih kepada orang tua.
        5. Jangan membuat aktivitas fiktif yang melenceng jauh dari catatan guru, cukup kembangkan konteksnya saja.
        6. JANGAN memberikan kalimat basa-basi di awal/akhir seperti 'Berikut adalah narasinya'. Langsung berikan teks narasinya saja.";

        try {
            // 2. Mengirim Request ke Google Gemini API
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . env('GEMINI_API_KEY'), [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            $result = $response->json();

            // 3. Menangkap dan mengembalikan teks balasan
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $generatedText = $result['candidates'][0]['content']['parts'][0]['text'];
                
                return response()->json([
                    'status' => true,
                    'data'   => trim($generatedText)
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Gagal mendapatkan respons dari AI.'. json_encode($result)
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }
}