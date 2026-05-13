<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DestinasiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('travel-packages', \App\Http\Controllers\TravelPackageController::class);

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);

Route::get('/destinasi', [DestinasiController::class, 'index']);