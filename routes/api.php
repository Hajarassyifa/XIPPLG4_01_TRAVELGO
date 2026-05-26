<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DestinasiController;
use App\Http\Controllers\TravelPackageController;
use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\TravelController;
use App\Http\Controllers\Api\ReviewController; // <-- SUDAH DIAKTIFKAN

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

// ─── Profile ───────────────────────────────────────────────
Route::get('/profile',           [ProfileController::class, 'show']);
Route::put('/profile',           [ProfileController::class, 'updateName']);
Route::post('/profile/photo',    [ProfileController::class, 'updatePhoto']);

// ─── Notifications ─────────────────────────────────────────
Route::get('/notifications',              [NotificationController::class, 'index']);
Route::put('/notifications/read-all',     [NotificationController::class, 'markAllRead']);
Route::put('/notifications/{id}/read',    [NotificationController::class, 'markRead']);

// use App\Http\Controllers\Api\ReviewController;

// ─── Publik (tanpa auth) ───────────────────────────────────
Route::get('/packages/{packageId}/reviews', [ReviewController::class, 'index']);

// ─── Perlu login (dalam grup auth:sanctum) ─────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/packages/{packageId}/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{id}',                  [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}',               [ReviewController::class, 'destroy']);
    Route::get('/reviews/my',                    [ReviewController::class, 'myReviews']);
});
