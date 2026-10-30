<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('penilaian:update-pertemuan-20', function () {
    $this->info('Memulai sinkronisasi data pertemuan...');
    $details = \App\Models\PenilaianDetail::all();
    $updatedCount = 0;

    foreach ($details as $detail) {
        $pertemuan = $detail->pertemuan;
        if (is_array($pertemuan) && count($pertemuan) < 20) {
            while (count($pertemuan) < 20) {
                $pertemuan[] = '';
            }
            $detail->pertemuan = $pertemuan;
            $detail->save();
            $updatedCount++;
        }
    }

    $this->info("Selesai! Sebanyak {$updatedCount} baris data berhasil diperbarui ke 20 pertemuan.");
})->purpose('Sinkronisasi array pertemuan penilaian_details menjadi 20 kolom');

Schedule::command('backup:clean')
->dailyAt('01:00')
->timezone('Asia/Jakarta');
Schedule::command('backup:run --only-db')
->dailyAt('01:30')
->timezone('Asia/Jakarta');