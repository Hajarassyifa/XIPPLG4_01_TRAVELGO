<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DestinasiController;
use App\Http\Controllers\TravelPackageController;

use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\TravelController;

// ======================
// AUTH
// ======================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ======================
// USER
// ======================
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ======================
// TRAVEL PACKAGE
// ======================
Route::apiResource('travel-packages', TravelPackageController::class);

// ======================
// DESTINASI
// ======================
Route::get('/destinasi', [DestinasiController::class, 'index']);
Route::get('/destinasi/{id}', [DestinasiController::class, 'show']);

// ======================
// BOOKING
// ======================
Route::get('/booking', [TravelController::class, 'index']);
Route::get('/booking/{id}', [TravelController::class, 'show']);
Route::post('/booking', [TravelController::class, 'store']);
Route::put('/booking/{id}', [TravelController::class, 'update']);
Route::delete('/booking/{id}', [TravelController::class, 'destroy']);

// ======================
// ARTIKEL
// ======================
Route::get('/artikel', [ArtikelController::class, 'index']);
Route::get('/artikel/{id}', [ArtikelController::class, 'show']);
Route::post('/artikel', [ArtikelController::class, 'store']);
Route::put('/artikel/{id}', [ArtikelController::class, 'update']);
Route::delete('/artikel/{id}', [ArtikelController::class, 'destroy']);

// ======================
// TRANSAKSI
// ======================
Route::get('/transaksi', [TransaksiController::class, 'index']);
Route::get('/transaksi/{id}', [TransaksiController::class, 'show']);
Route::post('/transaksi', [TransaksiController::class, 'store']);
Route::put('/transaksi/{id}', [TransaksiController::class, 'update']);
Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy']);