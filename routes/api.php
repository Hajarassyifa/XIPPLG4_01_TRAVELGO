<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// HAPUS TravelPackageController kalau emang filenya nggak ada
use App\Http\Controllers\Api\TravelController; 
use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\TransaksiController;

// =========================================================
// Auth (Users)
// POST /api/register  → daftar user baru
// POST /api/login     → masuk dengan email & password
// =========================================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// =========================================================
// User (protected - butuh token)
// =========================================================
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// =========================================================
// Travel Packages (CRUD otomatis)
// GET    /api/travel-packages
// POST   /api/travel-packages
// GET    /api/travel-packages/{id}
// PUT    /api/travel-packages/{id}
// DELETE /api/travel-packages/{id}
// =========================================================
Route::apiResource('travel-packages', TravelPackageController::class);

// =========================================================
// Destinasi
// GET /api/destinasi        → semua destinasi
// GET /api/destinasi/{id}   → satu destinasi by ID
// =========================================================
Route::get('/destinasi',      [TravelController::class, 'index']);
Route::get('/destinasi/{id}', [TravelController::class, 'show']);

// =========================================================
// Booking
// GET    /api/booking              → semua booking (filter ?id_user=xxx)
// POST   /api/booking              → buat booking baru
// PUT    /api/booking/{id}         → update booking by ID
// DELETE /api/booking/{id}         → hapus booking by ID
// =========================================================
Route::get('/booking',         [TravelController::class, 'indexBooking']);
Route::post('/booking',        [TravelController::class, 'storeBooking']);
Route::put('/booking/{id}',    [TravelController::class, 'updateBooking']);
Route::delete('/booking/{id}', [TravelController::class, 'destroyBooking']);

// =========================================================
// Artikel
// GET    /api/artikel        → semua artikel
// GET    /api/artikel/{id}   → satu artikel by ID
// POST   /api/artikel        → buat artikel baru
// PUT    /api/artikel/{id}   → update artikel
// DELETE /api/artikel/{id}   → hapus artikel
// =========================================================
Route::get('/artikel',         [ArtikelController::class, 'index']);
Route::get('/artikel/{id}',    [ArtikelController::class, 'show']);
Route::post('/artikel',        [ArtikelController::class, 'store']);
Route::put('/artikel/{id}',    [ArtikelController::class, 'update']);
Route::delete('/artikel/{id}', [ArtikelController::class, 'destroy']);

// =========================================================
// Transaksi
// GET    /api/transaksi              → semua transaksi (filter ?id_booking=xxx)
// GET    /api/transaksi/{id}         → satu transaksi by ID
// POST   /api/transaksi              → buat transaksi baru
// PUT    /api/transaksi/{id}         → update status transaksi
// DELETE /api/transaksi/{id}         → hapus transaksi
// =========================================================
Route::get('/transaksi',         [TransaksiController::class, 'index']);
Route::get('/transaksi/{id}',    [TransaksiController::class, 'show']);
Route::post('/transaksi',        [TransaksiController::class, 'store']);
Route::put('/transaksi/{id}',    [TransaksiController::class, 'update']);
Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy']);