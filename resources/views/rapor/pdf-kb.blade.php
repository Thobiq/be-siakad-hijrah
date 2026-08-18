<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perkembangan Anak Didik - {{ $siswa->nama }}</title>
    <style>
        /* Pengaturan Kertas (A4) dan Margin Utama */
        @page {
            margin: 0cm 0cm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            /* Margin disesuaikan agar teks tidak menabrak bingkai background */
            margin-top: 4cm;
            margin-left: 3cm;
            margin-right: 3cm;
            margin-bottom: 3cm;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
        }

        /* --- BACKGROUND PLACEHOLDER --- */
        .bg-template {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1000; /* Diletakkan paling belakang */
            /* object-fit: cover; // DomPDF tidak terlalu support object-fit, gunakan ukuran pas A4 */
        }

        /* --- HEADER --- */
        header {
            position: fixed;
            top: 1.5cm;
            left: 3cm;
            right: 3cm;
            height: 2cm;
        }
        .logo-container { float: left; width: 2cm; }
        .logo { width: 1.5cm; height: 1.5cm; }
        .header-text { float: left; margin-left: 0.5cm; padding-top: 0.1cm; }
        .header-text h3 { margin: 0; font-size: 12pt; font-weight: bold; }
        .header-text p { margin: 0; font-size: 10pt; }
        
        .clear { clear: both; }

        /* --- TYPOGRAPHY & BOXES --- */
        .teks-arab {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }
        .judul-halaman {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 15px;
        }
        .section-title {
            font-weight: bold;
            font-size: 12pt;
            text-align: center;
            text-transform: uppercase;
            margin-top: 15px;
            margin-bottom: 15px;
            color: #000;
        }
        .content-box {
            text-align: justify;
            line-height: 1.6;
            margin-bottom: 15px;
            /* Dihilangkan bordernya agar lebih mirip PDF aslinya yang clean */
        }
        .catatan-box {
            border: 1px solid #000;
            padding: 10px;
            min-height: 80px;
            margin-top: 10px;
        }

        /* --- TABLES --- */
        table {
            width: 100%;
            border-spacing: 0;
            font-size: 11pt;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #000;
            padding: 6px;
        }
        .table-bordered th {
            background-color: #f3f4f6;
            text-align: center;
        }
        .text-center { text-align: center; }

        /* --- PHOTOS --- */
        .photo-col {
            width: 33.33%;
            padding: 10px;
            text-align: center;
            vertical-align: top;
        }
        .photo-img {
            max-width: 100%;
            max-height: 5.5cm;
            border: 2px solid #000;
        }

        /* --- TANDA TANGAN --- */
        .ttd-table {
            width: 100%;
            margin-top: 30px;
            text-align: center;
        }
        .ttd-table td {
            width: 33.33%;
            padding-top: 50px; /* Space untuk tanda tangan */
        }

        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <!-- BACKGROUND IMAGE PLACEHOLDER (Ganti dengan file background A4 berbingkai) -->
    <!-- Asumsi kamu menyimpan template background kosong di public/images/bg-rapor.jpg -->
    <!-- Gunakan ekstensi .jpg untuk background agar ukuran file PDF tidak membengkak -->
    @if(file_exists(public_path('images/bg-rapor.jpg')))
        <img src="{{ public_path('images/bg-rapor.jpg') }}" class="bg-template" alt="Background Template">
    @endif

    <!-- HEADER (Akan muncul di setiap halaman jika diletakkan di luar div/main) -->
    <header>
        <div class="logo-container">
            @php
                $logoUrl = isset($profil->logo_path) && $profil->logo_path ? public_path($profil->logo_path) : public_path('images/logo-alhijrah.png');
            @endphp
            @if(file_exists($logoUrl))
                <img src="{{ $logoUrl }}" class="logo" alt="Logo">
            @endif
        </div>
        <div class="header-text">
            <h3>KB {{ strtoupper($profil->nama_sekolah ?? 'AL-HIJRAH') }}</h3>
            <p>{{ $profil->alamat ?? 'Jl. Jawa II No. 22 Sumbersari-Jember' }}</p>
            <p>Laporan Perkembangan Anak Didik</p>
        </div>
        <div class="clear"></div>
    </header>

    <main>
        
        <!-- HALAMAN 1: PEMBIASAAN AGAMA -->
        <div class="teks-arab">بسم الله الرحمن الرحيم</div>
        <div class="section-title">PEMBIASAAN AGAMA</div>
        
        <table class="table-bordered">
            <tr>
                <th style="width: 5%; text-align: center;">No.</th>
                <th style="width: 50%; text-align: center;">Materi</th>
                <th style="width: 15%; text-align: center; vertical-align: middle;">
                    <div style="width: 20px; height: 20px; border-radius: 50%; background-color: red; border: 1px solid black; display: inline-block; margin: 0 auto; line-height: 20px; font-size: 14px;">&#9785;</div>
                </th>
                <th style="width: 15%; text-align: center; vertical-align: middle;">
                    <div style="width: 20px; height: 20px; border-radius: 50%; background-color: yellow; border: 1px solid black; display: inline-block; margin: 0 auto; line-height: 20px; font-size: 14px;">&#128528;</div>
                </th>
                <th style="width: 15%; text-align: center; vertical-align: middle;">
                    <div style="width: 20px; height: 20px; border-radius: 50%; background-color: #0ea5e9; border: 1px solid black; display: inline-block; margin: 0 auto; line-height: 20px; font-size: 14px;">&#9786;</div>
                </th>
            </tr>
            <!-- Contoh list materi sesuai PDF aslinya -->
            @php
                $materiAgama = [
                    'Kalimat Basmalah', 'Kalimat istighfar', 'Kalimat salam', 
                    'Do’a sebelum makan dan sesudah makan', 'Do’a sebelum tidur',
                    'Do’a sebelum pulang', 'Do’a naik kendaraan', 'Surat Al-Fatihah',
                    'Surat Al-Ikhlas', 'Surat An-Naas', 'Mengenal huruf hijaiyah alif sampai Ha’'
                ];
            @endphp
            
            @foreach($materiAgama as $index => $materi)
            <tr>
                <td class="text-center">{{ $index + 1 }}.</td>
                <td style="padding-left: 10px;">{{ $materi }}</td>
                <td class="text-center"></td> {{-- Isi dengan logika checkmark misal: $rapor->nilai_agama[$index] == 'Belum' ? '√' : '' --}}
                <td class="text-center"></td>
                <td class="text-center">√</td> <!-- Hardcoded untuk contoh layout -->
            </tr>
            @endforeach
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold; padding: 20px 10px; vertical-align: middle;">
                    Catatan<br>Bu Guru
                </td>
                <td colspan="3" style="text-align: justify; padding: 10px; vertical-align: top;">
                    {!! nl2br(e($rapor->catatan_pembiasaan_agama ?? 'Belum ada catatan.')) !!}
                </td>
            </tr>
        </table>

        <div style="margin-top: 30px; margin-left: 10px; font-weight: bold; font-size: 12pt;">Keterangan :</div>
        <table class="table-bordered" style="width: 60%; margin: 20px auto; border: 2px solid #000;">
            <tr>
                <th style="width: 33.33%; background-color: #fff;">Belum hafal</th>
                <th style="width: 33.33%; background-color: #fff;">Kurang lancar</th>
                <th style="width: 33.33%; background-color: #fff;">Lancar</th>
            </tr>
            <tr>
                <td style="text-align: center; padding: 10px;">
                    <div style="width: 30px; height: 30px; border-radius: 50%; background-color: red; border: 1px solid black; display: inline-block; line-height: 30px; font-size: 18px;">&#9785;</div>
                </td>
                <td style="text-align: center; padding: 10px;">
                    <div style="width: 30px; height: 30px; border-radius: 50%; background-color: yellow; border: 1px solid black; display: inline-block; line-height: 30px; font-size: 18px;">&#128528;</div>
                </td>
                <td style="text-align: center; padding: 10px;">
                    <div style="width: 30px; height: 30px; border-radius: 50%; background-color: #0ea5e9; border: 1px solid black; display: inline-block; line-height: 30px; font-size: 18px;">&#9786;</div>
                </td>
            </tr>
        </table>

        <div class="page-break"></div>

        <!-- HALAMAN 2: NARASI AGAMA -->
        <div class="teks-arab">بسم الله الرحمن الرحيم</div>
        
        <div style="border: 2px solid #000; margin-bottom: 20px;">
            <div style="background-color: #0275d8; color: white; padding: 10px; font-size: 12pt; text-align: center; font-weight: bold; border-bottom: 2px solid #000; page-break-after: avoid;">
                Nilai Agama dan Budi Pekerti
            </div>
            <div style="padding: 20px; text-align: justify; text-indent: 40px; line-height: 1.6;">
                {!! nl2br(e($rapor->narasi_agama)) !!}
            </div>
        </div>

        <!-- FOTO KEGIATAN AGAMA -->
        @if(is_array($rapor->foto_agama) && count($rapor->foto_agama) > 0)
        <table class="table-bordered" style="width: 100%; border: 2px solid #000; page-break-inside: avoid;">
            <tr>
                <th colspan="3" style="background-color: #0275d8; color: white; padding: 10px; font-size: 12pt; text-align: center;">
                    Foto Kegiatan
                </th>
            </tr>
            <tr>
                @for($i = 0; $i < 3; $i++)
                    <td class="photo-col">
                        @if(isset($rapor->foto_agama[$i]))
                            @php $fotoPath = public_path(str_replace('/storage/', 'storage/', $rapor->foto_agama[$i])); @endphp
                            @if(file_exists($fotoPath) && is_file($fotoPath))
                                <img src="{{ $fotoPath }}" class="photo-img">
                            @endif
                        @endif
                    </td>
                @endfor
            </tr>
        </table>
        @endif

        <div class="page-break"></div>

        <!-- HALAMAN 3: JATI DIRI -->
        <div style="border: 2px solid #000; margin-bottom: 20px;">
            <div style="background-color: #0275d8; color: white; padding: 10px; font-size: 12pt; text-align: center; font-weight: bold; border-bottom: 2px solid #000; page-break-after: avoid;">
                Nilai Jati Diri
            </div>
            <div style="padding: 20px; text-align: justify; text-indent: 40px; line-height: 1.6;">
                {!! nl2br(e($rapor->narasi_jati_diri)) !!}
            </div>
        </div>

        @if(is_array($rapor->foto_jati_diri) && count($rapor->foto_jati_diri) > 0)
        <table class="table-bordered" style="width: 100%; border: 2px solid #000; page-break-inside: avoid;">
            <tr>
                <th colspan="3" style="background-color: #0275d8; color: white; padding: 10px; font-size: 12pt; text-align: center;">
                    Foto Kegiatan
                </th>
            </tr>
            <tr>
                @for($i = 0; $i < 3; $i++)
                    <td class="photo-col">
                        @if(isset($rapor->foto_jati_diri[$i]))
                            @php $fotoPath = public_path(str_replace('/storage/', 'storage/', $rapor->foto_jati_diri[$i])); @endphp
                            @if(file_exists($fotoPath) && is_file($fotoPath))
                                <img src="{{ $fotoPath }}" class="photo-img">
                            @endif
                        @endif
                    </td>
                @endfor
            </tr>
        </table>
        @endif

        <div class="page-break"></div>

        <!-- HALAMAN 4: LITERASI STEAM -->
        <div style="border: 2px solid #000; margin-bottom: 20px;">
            <div style="background-color: #0275d8; color: white; padding: 10px; font-size: 12pt; text-align: center; font-weight: bold; border-bottom: 2px solid #000; page-break-after: avoid;">
                Nilai Literasi STEAM
            </div>
            <div style="padding: 20px; text-align: justify; text-indent: 40px; line-height: 1.6;">
                {!! nl2br(e($rapor->narasi_literasi)) !!}
            </div>
        </div>

        @if(is_array($rapor->foto_literasi) && count($rapor->foto_literasi) > 0)
        <table class="table-bordered" style="width: 100%; border: 2px solid #000; page-break-inside: avoid;">
            <tr>
                <th colspan="3" style="background-color: #0275d8; color: white; padding: 10px; font-size: 12pt; text-align: center;">
                    Foto Kegiatan
                </th>
            </tr>
            <tr>
                @for($i = 0; $i < 3; $i++)
                    <td class="photo-col">
                        @if(isset($rapor->foto_literasi[$i]))
                            @php $fotoPath = public_path(str_replace('/storage/', 'storage/', $rapor->foto_literasi[$i])); @endphp
                            @if(file_exists($fotoPath) && is_file($fotoPath))
                                <img src="{{ $fotoPath }}" class="photo-img">
                            @endif
                        @endif
                    </td>
                @endfor
            </tr>
        </table>
        @endif

        <div class="page-break"></div>

        <!-- HALAMAN 5: ABSENSI & REFLEKSI -->
        <div class="section-title">Ketidakhadiran</div>
        <table class="table-bordered" style="width: 50%;">
            <tr>
                <td style="width: 50%;">Sakit</td>
                <td class="text-center">{{ $rapor->sakit ?? '-' }}</td>
            </tr>
            <tr>
                <td>Ijin</td>
                <td class="text-center">{{ $rapor->ijin ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tanpa Keterangan</td>
                <td class="text-center">{{ $rapor->alpha ?? '-' }}</td>
            </tr>
        </table>

        <div class="section-title" style="margin-top: 20px;">Refleksi Guru</div>
        <div class="content-box">
            {!! nl2br(e($rapor->refleksi_guru)) !!}
        </div>

        <div class="section-title" style="margin-top: 20px;">Refleksi Orang Tua</div>
        <div class="catatan-box" style="min-height: 80px; border-style: dotted;">
            <!-- Area kosong untuk diisi tulisan tangan -->
        </div>

        <div class="page-break"></div>

        <!-- HALAMAN 6: FISIK & TANDA TANGAN -->
        <div class="judul-halaman">Bismillahirrahmannirrahim</div>
        <div class="section-title text-center">Catatan Perkembangan Fisikku</div>
        
        <table class="table-bordered" style="margin-top: 20px;">
            <tr>
                <th style="width: 33.33%;">Berat Badan (BB)</th>
                <th style="width: 33.33%;">Tinggi Badan (TB)</th>
                <th style="width: 33.33%;">Lingkar Kepala (LK)</th>
            </tr>
            <tr>
                <td class="text-center">{{ $rapor->berat_badan ?? '-' }} kg</td>
                <td class="text-center">{{ $rapor->tinggi_badan ?? '-' }} cm</td>
                <td class="text-center">{{ $rapor->lingkar_kepala ?? '-' }} cm</td>
            </tr>
        </table>

        <!-- Kolom Tanda Tangan Menggunakan Tabel -->
        <table class="ttd-table">
            <tr>
                <td>
                    Mengetahui,<br>
                    Kepala KB {{ strtoupper($profil->nama_sekolah ?? 'AL-HIJRAH') }}
                    <br><br><br><br>
                    <strong>{{ $profil->nama_kepala_sekolah ?? 'INGE MARRINDA P, S.Pd' }}</strong>
                </td>
                <td>
                    <!-- Kosong di tengah -->
                </td>
                <td>
                    Guru Kelas,
                    <br><br><br><br><br>
                    <strong>{{ $guruKelas ?? 'PUTRI SETIYANINGSIH' }}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top: 40px;">
                    Orang Tua / Wali Murid
                    <br><br><br><br>
                    <strong>..........................................</strong>
                </td>
            </tr>
        </table>

    </main>
</body>
</html>