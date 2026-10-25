<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\JalanController;
use App\Http\Controllers\Admin\CctvController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Api\CctvSensorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

Route::middleware('auth')->group(function () {
    // Rute Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Rute Manajemen Jalan
    Route::get('/admin/jalan', [JalanController::class, 'index'])->name('admin.jalan.index');
    Route::get('/admin/jalan/create', [JalanController::class, 'create'])->name('admin.jalan.create');
    Route::post('/admin/jalan', [JalanController::class, 'store'])->name('admin.jalan.store');
    Route::get('/admin/jalan/{jalan}/edit', [JalanController::class, 'edit'])->name('admin.jalan.edit');
    Route::put('/admin/jalan/{jalan}', [JalanController::class, 'update'])->name('admin.jalan.update');
    Route::delete('/admin/jalan/{jalan}', [JalanController::class, 'destroy'])->name('admin.jalan.destroy');

    // Rute Manajemen CCTV & IoT
    Route::get('/admin/cctv', [CctvController::class, 'index'])->name('admin.cctv.index');
    Route::get('/admin/cctv/create', [CctvController::class, 'create'])->name('admin.cctv.create');
    Route::post('/admin/cctv', [CctvController::class, 'store'])->name('admin.cctv.store');
    Route::get('/admin/cctv/{cctv}/edit', [CctvController::class, 'edit'])->name('admin.cctv.edit');
    Route::put('/admin/cctv/{cctv}', [CctvController::class, 'update'])->name('admin.cctv.update');
    Route::delete('/admin/cctv/{cctv}', [CctvController::class, 'destroy'])->name('admin.cctv.destroy');

    // Rute Logout
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    // Rute Halaman Utama Warga (Landing Page)
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Rute Daftar CCTV (Dashboard ATCS) <--- INI YANG BARU
    Route::get('/cctv', [HomeController::class, 'cctv'])->name('cctv');

    // Rute Peta Pintar Warga
    Route::get('/rute-cerdas', [HomeController::class, 'rute'])->name('rute');

    // Rute Detail Analitik CCTV
    Route::get('/cctv/detail/{id}', [HomeController::class, 'showDetail']);

    // Rute Halaman Tentang Kami
    Route::get('/tentang-kami', [HomeController::class, 'tentang'])->name('tentang');
});