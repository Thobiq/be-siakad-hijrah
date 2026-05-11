<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\ElemenCapaian;
use App\Models\CapaianPembelajaran;
use App\Models\TujuanPembelajaran;
use App\Models\AtpIndikator;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin321',
            'password' => Hash::make('admin321'),
            'role' => 'guru',
        ]);
        $user = User::create([
            'name' => 'Bu Hijrah',
            'username' => 'buhijrah',
            'password' => Hash::make('password123'),
            'role' => 'guru'
        ]);

        // 2. Masukkan ke Tabel Guru
        Guru::create([
            'user_id' => $user->id,
            'nama' => 'Bu Hijrah',
            'nomor_induk' => 'G-001'
        ]);

        // 3. Masukkan Data Siswa
        Siswa::create([
            'nama' => 'Mukhamad Alyasyi Thobiq',
            'nomor_induk' => '1234567890',
            'tingkat' => 'KB',
            'status' => 'Aktif'
        ]);

        Siswa::create([
            'nama' => 'Ahmad Thobiq',
            'nomor_induk' => '1234567891',
            'tingkat' => 'TK A',
            'status' => 'Aktif'
        ]);
        Siswa::create([
            'nama' => 'Ahmad Alyasyi',
            'nomor_induk' => '1234567892',
            'tingkat' => 'TK B',
            'status' => 'Aktif'
        ]);

        $masterData = [
            'Nilai Agama dan Budi Pekerti' => [
                [
                    'deskripsi' => '1. Murid percaya kepada Tuhan Yang Maha Esa sebagai pencipta dirinya, makhluk lain dan alam, serta mulai mengenal dan mempraktikkan ajaran pokok sesuai dengan agama dan kepercayaannya',
                    'tujuan' => [
                        [
                            'deskripsi' => 'TP1. Murid mengenal nama Tuhan dan dapat menjelaskan sumber-sumber praktek agamanya secara sederhana',
                            'atp' => [
                                'Murid mengenal Allah melalui ciptaan-Nya',
                                'Murid mengenal Allah lewat Asmaul Husna',
                                'Murid mengetahui hari besar agama Islam',
                                'Murid mengenal agama yang ada di Indonesia',
                                'Murid mengenal tempat ibadah yang ada di Indonesia',
                                'Murid mengenal dan mempraktekkan kalimat toyyibah',
                                'Murid dapat membedakan ciptaan Allah dan buatan manusia',
                            ]
                        ],
                        [
                            'deskripsi' => 'TP2. Murid berpartisipasi dalam kegiatan ibadah sesuai agama dan kepercayaannya',
                            'atp' => [
                                'Murid dapat menghafal doa-doa harian',
                                'Murid dapat menirukan gerakan sholat dan bacaannya',
                                'Murid dapat mengenal waktu sholat fardhu',
                                'Murid dapat mengenal waktu sholat sunnah',
                                'Murid dapat terbiasa berinfaq',
                                'Murid dapat mengenal puasa Ramadhan',
                                'Murid dapat mengenal ritual haji dan berqurban',
                            ]
                        ]
                    ]
                ],
                [
                    'deskripsi' => '2. Murid menghargai diri sendiri dan memiliki rasa syukur terhadap Tuhan YME sehingga dapat berpartisipasi aktif dalam menjaga kebersihan, kesehatan, dan keselamatan dirinya',
                    'tujuan' => [
                        [
                            'deskripsi' => 'TP1. Murid dapat menyebutkan karakteristik diri yang bersih',
                            'atp' => [
                                'Murid dapat mengetahui akibat tidak mencuci tangan, tidak potong kuku, tidak gosok gigi, tidak mandi, tidak cuci rambut, tidak berpakaian bersih dan menahan BAK/BAB',
                                'Murid dapat menyebutkan ciri-ciri tubuh yang bersih',
                            ]
                        ],
                        [
                            'deskripsi' => 'TP2. Murid melakukan kegiatan bina diri',
                            'atp' => [
                                'Murid dapat mandiri BAK/BAB',
                                'Murid dapat mencuci tangan',
                                'Murid dapat menggosok gigi mandiri',
                                'Murid dapat mandi sendiri',
                                'Murid dapat menjaga kebersihan kuku',
                            ]
                        ]
                    ]
                ]
            ],
            'Jati Diri' => [
                [
                    'deskripsi' => '1. Murid mengenali identitas dirinya yang terbentuk oleh karakteristik fisik dan gender, minat, kebutuhan, agama, dan sosial budaya',
                    'tujuan' => [
                        [
                            'deskripsi' => 'TP1. Murid dapat mengenal identitas dirinya',
                            'atp' => [
                                'Murid dapat mengenal nama lengkap dan panggilannya sendiri',
                                'Murid dapat mengetahui tempat, tanggal, bulan dan tahun kelahirannya',
                            ]
                        ],
                        [
                            'deskripsi' => 'TP2. Murid dapat mengetahui minat dan kebutuhan yang diperlukan',
                            'atp' => [
                                'Murid dapat mengenali ciri-ciri tubuhnya',
                                'Murid dapat mengenal jenis kelaminnya',
                                'Murid dapat membedakan laki-laki dan perempuan',
                                'Murid dapat mengetahui hobi/kesukaannya',
                                'Murid dapat mengetahui apa yang diperlukan untuk memenuhi kebutuhan hidupnya',
                            ]
                        ]
                    ]
                ]
            ],
            'Literasi, Matematika, Sains, Teknologi Rekayasa, Seni' => [
                [
                    'deskripsi' => '1. Murid mengenal dan memahami berbagai informasi, mengomunikasikan perasaan dan pikiran secara lisan, menulis atau menggunaan berbagai media serta membangun percakapan, menunjukkan minat, dan partisipasi dalam kegiatan pramembaca',
                    'tujuan' => [
                        [
                            'deskripsi' => 'TP1. Murid mengenali dan memahami berbagai informasi yang ada disekitarnya',
                            'atp' => [
                                'Murid dapat melakukan 2-3 perintah sederhana',
                                'Anak dapat menirukan kembali 2-4 kata sederhana membentuk kalimat',
                                'Murid dapat menerima pesan sederhana dan menyampaikan pesan',
                                'Murid dapat menceritakan isi buku cerita walaupun tidak sama tulisan dengan yang diungkapkan',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // 3. Looping Pengeksekusi Data (Auto Insert Database)
        foreach ($masterData as $namaElemen => $cps) {
            // Buat Elemen
            $elemen = ElemenCapaian::create(['nama_elemen' => $namaElemen]);

            foreach ($cps as $cpData) {
                // Buat CP terkait Elemen
                $cp = CapaianPembelajaran::create([
                    'elemen_capaian_id' => $elemen->id,
                    'deskripsi' => $cpData['deskripsi']
                ]);

                foreach ($cpData['tujuan'] as $tpData) {
                    // Buat TP terkait CP
                    $tp = TujuanPembelajaran::create([
                        'capaian_pembelajaran_id' => $cp->id,
                        'deskripsi' => $tpData['deskripsi']
                    ]);

                    foreach ($tpData['atp'] as $atpTeks) {
                        // Buat ATP terkait TP
                        AtpIndikator::create([
                            'tujuan_pembelajaran_id' => $tp->id,
                            'deskripsi' => $atpTeks
                        ]);
                    }
                }
            }
        }

        // 4. Masukkan Data Elemen Penilaian (Sesuai UI)
        // $elemen1 = ElemenCapaian::create(['nama_elemen' => 'Nilai Agama dan Budi Pekerti']);
        // $elemen2 = ElemenCapaian::create(['nama_elemen' => 'Nilai Jati Diri']);
        // $elemen3 = ElemenCapaian::create(['nama_elemen' => 'Literasi, Matematika, Sains, Teknologi Rekayasa, Seni']);

        // // 5. Masukkan Data Hierarki (CP -> TP -> ATP) untuk Elemen 1
        // $cp1 = CapaianPembelajaran::create([
        //     'elemen_capaian_id' => $elemen1->id,
        //     'deskripsi' => '1. Murid percaya kepada Tuhan Yang Maha Esa sebagai pencipta dirinya, makhluk lain dan alam, serta mulai mengenal dan mempraktikkan ajaran pokok sesuai dengan agama dan kepercayaannya'
        // ]);

        // $tp1 = TujuanPembelajaran::create([
        //     'capaian_pembelajaran_id' => $cp1->id,
        //     'deskripsi' => 'TP1. Murid mengenal nama Tuhan dan dapat menjelaskan sumber-sumber praktek agamanya secara sederhana'
        // ]);

        // AtpIndikator::create([
        //     'tujuan_pembelajaran_id' => $tp1->id,
        //     'deskripsi' => 'Murid mengenal Allah melalui ciptaan-Nya'
        // ]);

        // AtpIndikator::create([
        //     'tujuan_pembelajaran_id' => $tp1->id,
        //     'deskripsi' => 'Mampu menyebutkan rukun iman secara berurutan'
        // ]);
    }
}
