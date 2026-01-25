<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Redirect awal ke login
Route::get('/', function () { return redirect('/login'); });

// === AUTHENTICATION ===
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// === DASHBOARD UTAMA ===
Route::get('/dashboard', [DashboardController::class, 'index']);

// === MANAJEMEN KELAS (RUANGAN) ===
Route::post('/kelas', [DashboardController::class, 'storeKelas']);       // Tambah Kelas
Route::delete('/kelas/{id}', [DashboardController::class, 'deleteKelas']); // Hapus Kelas
// (Edit Kelas tidak ada karena backend tidak support)

// === MANAJEMEN JADWAL (INI YANG TADI KURANG) ===
Route::post('/jadwal', [DashboardController::class, 'storeJadwal']);           // Tambah Jadwal
Route::put('/jadwal/{id}', [DashboardController::class, 'updateJadwal']);      // <--- INI YG BIKIN ERROR (Edit Jadwal)
Route::delete('/jadwal/{id}', [DashboardController::class, 'deleteJadwal']);   // Hapus Jadwal
Route::put('/jadwal/{id}/reset', [DashboardController::class, 'resetStatus']); // Reset Status

// === BOOKING (DOSEN) ===
Route::post('/booking', [DashboardController::class, 'booking']);