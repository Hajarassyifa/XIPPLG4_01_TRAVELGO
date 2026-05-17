<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DestinasiController;
use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\TravelController;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// User (protected)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Travel Packages
Route::apiResource('travel-packages', \App\Http\Controllers\TravelPackageController::class);

// Destinasi (MySQL)
Route::get('/destinasi',      [DestinasiController::class, 'index']);
Route::get('/destinasi/{id}', [DestinasiController::class, 'show']);

// Booking (Firebase)
Route::get('/booking',         [TravelController::class, 'indexBooking']);
Route::post('/booking',        [TravelController::class, 'storeBooking']);
Route::put('/booking/{id}',    [TravelController::class, 'updateBooking']);
Route::delete('/booking/{id}', [TravelController::class, 'destroyBooking']);

// Artikel (Firebase)
Route::get('/artikel',         [ArtikelController::class, 'index']);
Route::get('/artikel/{id}',    [ArtikelController::class, 'show']);
Route::post('/artikel',        [ArtikelController::class, 'store']);
Route::put('/artikel/{id}',    [ArtikelController::class, 'update']);
Route::delete('/artikel/{id}', [ArtikelController::class, 'destroy']);

// Transaksi (Firebase)
Route::get('/transaksi',         [TransaksiController::class, 'index']);
Route::get('/transaksi/{id}',    [TransaksiController::class, 'show']);
Route::post('/transaksi',        [TransaksiController::class, 'store']);
Route::put('/transaksi/{id}',    [TransaksiController::class, 'update']);
Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy']);