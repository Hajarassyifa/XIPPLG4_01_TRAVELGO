<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('travel-packages', \App\Http\Controllers\TravelPackageController::class);
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
