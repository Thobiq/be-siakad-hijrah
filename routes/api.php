<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PenilaianController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/penilaian/master-data', [PenilaianController::class, 'getMasterData']);

    Route::post('/penilaian', [PenilaianController::class, 'store']);

    // API untuk mengambil daftar penilaian di halaman depan KB
    Route::get('/penilaian', [PenilaianController::class, 'index']);

    Route::post('/penilaian', [PenilaianController::class, 'store']);
    // Tambahkan baris ini untuk fungsi Detail
    Route::get('/penilaian/{id}', [PenilaianController::class, 'show']);

    Route::get('/penilaian/{id}', [PenilaianController::class, 'show']);
    Route::get('/penilaian/matriks/{id}/{elemenId}', [PenilaianController::class, 'getMatriksData']);

    Route::get('/penilaian/matriks/{id}/{elemenId}', [PenilaianController::class, 'getMatriksData']);
    Route::post('/penilaian/matriks/{id}/{elemenId}', [PenilaianController::class, 'saveMatriksData']);

    Route::delete('/penilaian/{id}', [PenilaianController::class, 'destroy']);

    Route::put('/penilaian/{id}/status', [PenilaianController::class, 'updateStatus']);

    Route::put('/penilaian/{id}/status-draft', [PenilaianController::class, 'updateToDraft']);

    // --- ROUTE UNTUK ELEMEN PENILAIAN (INDUK) ---
    Route::apiResource('elemen-capaian', \App\Http\Controllers\Api\ElemenCapaianController::class);

    // --- ROUTE UNTUK HIERARKI KURIKULUM (ANAK-CUCU) ---
    // Capaian Pembelajaran (CP)
    Route::post('/cp', [\App\Http\Controllers\Api\KurikulumController::class, 'storeCp']);
    Route::put('/cp/{id}', [\App\Http\Controllers\Api\KurikulumController::class, 'updateCp']);
    Route::delete('/cp/{id}', [\App\Http\Controllers\Api\KurikulumController::class, 'destroyCp']);

    // Tujuan Pembelajaran (TP)
    Route::post('/tp', [\App\Http\Controllers\Api\KurikulumController::class, 'storeTp']);
    Route::put('/tp/{id}', [\App\Http\Controllers\Api\KurikulumController::class, 'updateTp']);
    Route::delete('/tp/{id}', [\App\Http\Controllers\Api\KurikulumController::class, 'destroyTp']);

    // ATP / Indikator
    Route::post('/atp', [\App\Http\Controllers\Api\KurikulumController::class, 'storeAtp']);
    Route::put('/atp/{id}', [\App\Http\Controllers\Api\KurikulumController::class, 'updateAtp']);
    Route::delete('/atp/{id}', [\App\Http\Controllers\Api\KurikulumController::class, 'destroyAtp']);

    Route::get('/dashboard/stats', [\App\Http\Controllers\Api\PenilaianController::class, 'getDashboardStats']);
});
