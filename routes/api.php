<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('travel-packages', \App\Http\Controllers\TravelPackageController::class);
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
=======
use App\Http\Controllers\DestinasiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/destinasi', [DestinasiController::class, 'index']);
>>>>>>> 4d501fe714dd859ef96d96dd86de68b6dff1d8fb
