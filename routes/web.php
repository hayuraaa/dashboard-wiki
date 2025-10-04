<?php

use App\Http\Controllers\AdminProjectsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\KomunitasController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
|
| Routes yang memerlukan authentication untuk mengakses admin panel
|
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard (Root Route)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Language Switch
    Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // User Management
    Route::resource('users', UserController::class);

    // Role Management
    Route::resource('roles', RoleController::class);

    // Permission Management
    Route::resource('permissions', PermissionController::class)->only(['index', 'create', 'store', 'destroy']);

    // Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->middleware('role:super-admin')
        ->name('activity-logs.index');
    Route::get('/activity-logs/{activity}', [ActivityLogController::class, 'show'])
        ->middleware('role:super-admin')
        ->name('activity-logs.show');

    // Data Master Routes
    Route::get('/data-master', function () {
        return Inertia::render('DataMaster/Index');
    })->middleware('permission:view data master')->name('data-master.index');

    // Komunitas Management
    Route::get('/list-komunitas', [KomunitasController::class, 'index'])->name('komunitas.index');
    Route::get('/list-komunitas/create', [KomunitasController::class, 'create'])->name('komunitas.create');
    Route::post('/list-komunitas', [KomunitasController::class, 'store'])->name('komunitas.store');
    Route::get('/list-komunitas/{komunita}', [KomunitasController::class, 'show'])->name('komunitas.show');
    Route::get('/list-komunitas/{komunita}/edit', [KomunitasController::class, 'edit'])->name('komunitas.edit');
    Route::put('/list-komunitas/{komunita}', [KomunitasController::class, 'update'])->name('komunitas.update');
    Route::delete('/list-komunitas/{komunita}', [KomunitasController::class, 'destroy'])->name('komunitas.destroy');

});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';