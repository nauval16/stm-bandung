<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CctvSensorController;
use App\Http\Controllers\Api\TrafficLogController;
use App\Models\TrafficLog;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rute untuk menerima data dari IoT/AI
Route::post('/cctv-sensor/update', [CctvSensorController::class, 'updateStatus']);

// Rute untuk memberi data status ke Frontend Web (Fetch API JavaScript)
Route::get('/cctv-sensor/status/{id}', [CctvSensorController::class, 'getStatus']);

// Status terkini seluruh CCTV berkoordinat untuk pembaruan peta rute.
Route::get('/cctv-sensor/map-status', [CctvSensorController::class, 'getMapStatus']);

// Rute untuk memberikan daftar CCTV ke skrip Python secara otomatis
Route::get('/cctv-sensor/list', [CctvSensorController::class, 'getListCctv']);

// API Baru untuk menerima data log spesifik dari Python YOLO
Route::post('/cctv-sensor/traffic-log', [TrafficLogController::class, 'storeLog']);