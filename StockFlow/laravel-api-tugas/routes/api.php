<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BatchStokController;
use App\Http\Controllers\MutasiStokController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LampiranController;
use App\Http\Controllers\RiwayatAktivitasController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\DashboardController;

// Public Route
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (Satu Pintu autentikasi Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // Auth & User Profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Dashboard & Summary View
    Route::get('/dashboard/summary', [DashboardController::class, 'summary']);

    // Master Barang
    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/barang', [BarangController::class, 'store']);
    Route::get('/barang/{id}', [BarangController::class, 'show']);
    Route::put('/barang/{id}', [BarangController::class, 'update']);
    Route::delete('/barang/{id}', [BarangController::class, 'destroy']);

    // Batch Stok & Smart Expiry
    Route::post('/batch', [BatchStokController::class, 'store']);
    Route::get('/batch/perlu-ditangani', [BatchStokController::class, 'perluDitangani']);
    Route::post('/batch/{id}/tangani', [BatchStokController::class, 'tanganiKedaluwarsa']);

    // Mutasi Stok (FEFO)
    Route::post('/mutasi/keluar-fefo', [MutasiStokController::class, 'keluarFEFO']);

    // Upload Lampiran Foto
    Route::post('/lampiran', [LampiranController::class, 'store']);

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index']);
    Route::post('/notifikasi/{id}/tandai-tampil', [NotifikasiController::class, 'tandaiTampil']);

    // Audit Log Aktivitas
    Route::get('/riwayat-aktivitas', [RiwayatAktivitasController::class, 'index']);

    // Pengaturan & Kelola Staf (Khusus Pemilik)
    Route::get('/pengaturan', [PengaturanController::class, 'index']);
    Route::put('/pengaturan', [PengaturanController::class, 'update']);
    Route::get('/users/staf', [UserController::class, 'index']);
    Route::post('/users/staf', [UserController::class, 'store']);
    Route::patch('/users/staf/{id}/toggle-aktif', [UserController::class, 'toggleAktif']);
});
?>