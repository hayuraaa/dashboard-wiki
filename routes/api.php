<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactFormApiController;
use App\Http\Controllers\Api\KomunitasApiController;
use App\Http\Controllers\Api\MateriApiController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::post('/contact-forms/submit', [ContactFormApiController::class, 'submit']);

// Komunitas API Routes
Route::prefix('komunitas')->group(function () {
    // Public routes (tidak perlu authentication)
    Route::get('/', [KomunitasApiController::class, 'index']);
    Route::get('/jenis', [KomunitasApiController::class, 'getJenisKomunitas']);
    Route::get('/statistics', [KomunitasApiController::class, 'getStatistics']);
    Route::get('/{id}', [KomunitasApiController::class, 'show']);
    
    // Protected routes (perlu authentication)
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/', [KomunitasApiController::class, 'store']);
        Route::put('/{id}', [KomunitasApiController::class, 'update']);
        Route::patch('/{id}', [KomunitasApiController::class, 'update']);
        Route::delete('/{id}', [KomunitasApiController::class, 'destroy']);
    });
});

// Materi API Routes
Route::prefix('materi')->group(function () {
    // Public routes (tidak perlu authentication)
    Route::get('/', [MateriApiController::class, 'index']);
    Route::get('/kategori', [MateriApiController::class, 'getKategori']);
    Route::get('/jenis-media', [MateriApiController::class, 'getJenisMedia']);
    Route::get('/statistics', [MateriApiController::class, 'getStatistics']);
    Route::get('/{id}', [MateriApiController::class, 'show']);
    
    // Protected routes (perlu authentication)
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/', [MateriApiController::class, 'store']);
        Route::put('/{id}', [MateriApiController::class, 'update']);
        Route::patch('/{id}', [MateriApiController::class, 'update']);
        Route::delete('/{id}', [MateriApiController::class, 'destroy']);
    });
});
