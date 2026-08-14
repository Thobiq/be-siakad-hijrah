<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perkembangan Anak Didik - {{ $siswa->nama }}</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin-top: 3cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
        }
        header {
            position: fixed;
            top: 1cm;
            left: 2cm;
            right: 2cm;
            height: 2cm;
        }
        .logo-container {
            float: left;
            width: 2cm;
        }
        .logo {
            width: 1.5cm;
            height: 1.5cm;
        }
        .header-text {
            float: left;
            margin-left: 0.5cm;
            padding-top: 0.1cm;
        }
        .header-text h3 {
            margin: 0;
            font-size: 12pt;
            font-weight: normal;
        }
        .header-text p {
            margin: 0;
            font-size: 11pt;
        }
        hr {
            margin-top: 5px;
            border: 1px solid #000;
        }
        
        .table-data-diri {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11pt;
        }
        .table-data-diri th {
            background-color: #d1d5db;
            text-align: center;
            border: 1px solid #000;
            padding: 5px;
            font-weight: bold;
        }
        .table-data-diri td {
            border: 1px solid #000;
            padding: 5px;
        }
        
        .section-header {
            text-align: center;
            font-weight: bold;
            color: white;
            padding: 5px;
            margin-top: 20px;
            border: 1px solid #000;
        }
        
        .bg-agama { background-color: #2da76b; }
        .bg-jati-diri { background-color: #dc2626; }
        .bg-literasi { background-color: #1e3a8a; }
        .bg-kokurikuler { background-color: #eab308; color: #000; }
        .bg-refleksi-guru { background-color: #84cc16; color: #000; }
        .bg-refleksi-ortu { background-color: #f59e0b; color: #000; }
        
        .content-box {
            border: 1px solid #000;
            padding: 10px;
            text-align: justify;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .section-header {
            text-align: center;
            font-weight: bold;
            color: white;
            padding: 5px;
            border: 1px solid #000;
            border-bottom: none;
            margin-top: 20px;
        }
        
        .photo-container {
            width: 100%;
            border-collapse: collapse;
            margin-top: -1px; /* collapse border */
        }
        .photo-header {
            border: 1px solid #000;
            padding: 5px;
        }
        .photo-row {
            width: 100%;
        }
        .photo-col {
            width: 33.33%;
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            height: 4.5cm; /* fixed height for photo cell */
            vertical-align: middle;
        }
        .photo-img {
            max-width: 100%;
            max-height: 4cm;
            object-fit: contain;
        }

        .dotted-lines {
            margin-top: 10px;
            line-height: 1.8;
        }
        .dotted-line {
            border-bottom: 1px dashed #000;
            margin-bottom: 20px;
            width: 100%;
            height: 10px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        /* Footer for NIS/Kode at bottom right */
        .footer-code {
            position: fixed;
            bottom: 1cm;
            right: 2cm;
            color: #ef4444;
            font-weight: bold;
            font-size: 10pt;
        }
    </style>
</head>
<body>

    <header>
        <div class="logo-container">
            <!-- Asumsi ada logo di public/images/logo-alhijrah.png -->
            <img src="{{ public_path('images/logo-alhijrah.png') }}" class="logo" alt="Logo">
        </div>
        <div class="header-text">
            <h3>TK AL-HIJRAH</h3>
            <p>Jl. Jawa II No. 22 Sumbersari-Jember</p>
            <p>Laporan Perkembangan Anak Didik</p>
        </div>
        <div style="clear: both;"></div>
        <hr>
    </header>

    <div class="footer-code">
        <!-- Kode di kanan bawah, misalnya NISN + tahun -->
        {{ date('Y') }}{{ $siswa->nomor_induk }}{{ date('Y') }}
    </div>

    <!-- MAIN CONTENT -->
    <main>
        <!-- DATA DIRI -->
        <table class="table-data-diri">
            <thead>
                <tr>
                    <th colspan="4">Laporan Perkembangan Anak Didik</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 20%;">Nama sekolah</td>
                    <td style="width: 30%;">TK Al-Hijrah</td>
                    <td style="width: 20%;">Kelas</td>
                    <td style="width: 30%;">{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Nama siswa</td>
                    <td>{{ $siswa->nama }}</td>
                    <td>Fase</td>
                    <td>Fondasi</td>
                </tr>
                <tr>
                    <td>NIS</td>
                    <td>{{ $siswa->nomor_induk }}</td>
                    <td>Tinggi Badan</td>
                    <td>{{ $rapor->tinggi_badan ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Semester / TA</td>
                    <td>{{ $rapor->semester == 'Ganjil' ? '1' : '2' }} / {{ $rapor->tahun_ajaran }}</td>
                    <td>Berat Badan</td>
                    <td>{{ $rapor->berat_badan ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Guru Kelas</td>
                    <td>{{ $guruKelas ?? 'Annabella Widyadhana, S.Pd' }}</td>
                    <td>Lingkar Kepala</td>
                    <td>{{ $rapor->lingkar_kepala ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- NILAI AGAMA -->
        <div class="section-header bg-agama">Nilai Agama dan Budi Pekerti</div>
        <div class="content-box">
            {!! nl2br(e($rapor->narasi_agama)) !!}
        </div>
        @if(is_array($rapor->foto_agama) && count($rapor->foto_agama) > 0)
        <table class="photo-container">
            <tr>
                <td colspan="3" class="photo-header">Foto Kegiatan Anak</td>
            </tr>
            <tr>
                @for($i = 0; $i < 3; $i++)
                    <td class="photo-col">
                        @if(isset($rapor->foto_agama[$i]))
                            <img src="{{ public_path(str_replace('/storage/', 'storage/', $rapor->foto_agama[$i])) }}" class="photo-img">
                        @endif
                    </td>
                @endfor
            </tr>
        </table>
        @endif
        
        <div class="page-break"></div>

        <!-- JATI DIRI -->
        <div class="section-header bg-jati-diri">Jati Diri</div>
        <div class="content-box">
            {!! nl2br(e($rapor->narasi_jati_diri)) !!}
        </div>
        @if(is_array($rapor->foto_jati_diri) && count($rapor->foto_jati_diri) > 0)
        <table class="photo-container">
            <tr>
                <td colspan="3" class="photo-header">Foto Kegiatan Anak</td>
            </tr>
            <tr>
                @for($i = 0; $i < 3; $i++)
                    <td class="photo-col">
                        @if(isset($rapor->foto_jati_diri[$i]))
                            <img src="{{ public_path(str_replace('/storage/', 'storage/', $rapor->foto_jati_diri[$i])) }}" class="photo-img">
                        @endif
                    </td>
                @endfor
            </tr>
        </table>
        @endif

        <div class="page-break"></div>

        <!-- LITERASI & STEAM -->
        <div class="section-header bg-literasi">Dasar-dasar Literasi, Matematika, Sains, Teknologi, Rekayasa, dan Seni</div>
        <div class="content-box">
            {!! nl2br(e($rapor->narasi_literasi)) !!}
        </div>
        @if(is_array($rapor->foto_literasi) && count($rapor->foto_literasi) > 0)
        <table class="photo-container">
            <tr>
                <td colspan="3" class="photo-header">Foto Kegiatan Anak</td>
            </tr>
            <tr>
                @for($i = 0; $i < 3; $i++)
                    <td class="photo-col">
                        @if(isset($rapor->foto_literasi[$i]))
                            <img src="{{ public_path(str_replace('/storage/', 'storage/', $rapor->foto_literasi[$i])) }}" class="photo-img">
                        @endif
                    </td>
                @endfor
            </tr>
        </table>
        @endif

        <div class="page-break"></div>

        <!-- P5 (KOKURIKULER) -->
        @if($rapor->narasi_kokurikuler || (is_array($rapor->foto_kokurikuler) && count($rapor->foto_kokurikuler) > 0))
        <div class="section-header bg-kokurikuler">Projek Penguatan Profil Pelajar Pancasila</div>
        <div class="content-box">
            {!! nl2br(e($rapor->narasi_kokurikuler)) !!}
        </div>
        @if(is_array($rapor->foto_kokurikuler) && count($rapor->foto_kokurikuler) > 0)
        <table class="photo-container">
            <tr>
                <td colspan="3" class="photo-header">Foto Kegiatan Anak</td>
            </tr>
            <tr>
                @for($i = 0; $i < 3; $i++)
                    <td class="photo-col">
                        @if(isset($rapor->foto_kokurikuler[$i]))
                            <img src="{{ public_path(str_replace('/storage/', 'storage/', $rapor->foto_kokurikuler[$i])) }}" class="photo-img">
                        @endif
                    </td>
                @endfor
            </tr>
        </table>
        @endif
        @endif

        <div class="page-break"></div>

        <!-- REFLEKSI GURU -->
        <div class="section-header bg-refleksi-guru">Refleksi Guru</div>
        <div class="content-box">
            {!! nl2br(e($rapor->refleksi_guru)) !!}
        </div>
        
        <!-- REFLEKSI ORTU -->
        <div class="section-header bg-refleksi-ortu">Refleksi Orang Tua / Wali</div>
        <div class="content-box" style="height: 100px;">
            <!-- Kotak kosong untuk diisi tulisan tangan orang tua -->
        </div>

    </main>
</body>
</html>
