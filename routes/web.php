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
use App\Http\Controllers\MateriDatatekController;
use App\Http\Controllers\KomunitasController;
use App\Http\Controllers\BannerModalController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SliderCategoryController;
use App\Http\Controllers\ContactSettingController;
use App\Http\Controllers\ContactSubmissionAdminController;
use App\Http\Controllers\PublicContactController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


// Public Contact Form Page
Route::get('/contact-forms', [PublicContactController::class, 'index'])->name('contact.forms');
Route::post('/contact-forms/submit', [PublicContactController::class, 'store'])->name('contact.submit');

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
    

    Route::get('/sliders', [SliderController::class, 'index'])->name('sliders.index');
    Route::get('/sliders/create', [SliderController::class, 'create'])->name('sliders.create');
    Route::post('/sliders', [SliderController::class, 'store'])->name('sliders.store');
    Route::get('/sliders/{slider}/edit', [SliderController::class, 'edit'])->name('sliders.edit');
    Route::put('/sliders/{slider}', [SliderController::class, 'update'])->name('sliders.update');
    Route::delete('/sliders/{slider}', [SliderController::class, 'destroy'])->name('sliders.destroy');

    // Data Master Routes
    Route::get('/data-master', function () {
        return Inertia::render('DataMaster/Index');
    })->name('data-master.index');
    Route::resource('data-master/slider-categories', SliderCategoryController::class)->except(['show']);

    // Komunitas Management
    Route::get('/list-komunitas', [KomunitasController::class, 'index'])->name('komunitas.index');
    Route::get('/list-komunitas/create', [KomunitasController::class, 'create'])->name('komunitas.create');
    Route::post('/list-komunitas', [KomunitasController::class, 'store'])->name('komunitas.store');
    Route::get('/list-komunitas/{komunita}', [KomunitasController::class, 'show'])->name('komunitas.show');
    Route::get('/list-komunitas/{komunita}/edit', [KomunitasController::class, 'edit'])->name('komunitas.edit');
    Route::put('/list-komunitas/{komunita}', [KomunitasController::class, 'update'])->name('komunitas.update');
    Route::delete('/list-komunitas/{komunita}', [KomunitasController::class, 'destroy'])->name('komunitas.destroy');

    // Materi Datatek Management
    Route::get('/materi-datatek', [MateriDatatekController::class, 'index'])->name('materi-datatek.index');
    Route::get('/materi-datatek/create', [MateriDatatekController::class, 'create'])->name('materi-datatek.create');
    Route::post('/materi-datatek', [MateriDatatekController::class, 'store'])->name('materi-datatek.store');
    Route::get('/materi-datatek/{materiDatatek}', [MateriDatatekController::class, 'show'])->name('materi-datatek.show');
    Route::get('/materi-datatek/{materiDatatek}/edit', [MateriDatatekController::class, 'edit'])->name('materi-datatek.edit');
    Route::put('/materi-datatek/{materiDatatek}', [MateriDatatekController::class, 'update'])->name('materi-datatek.update');
    Route::delete('/materi-datatek/{materiDatatek}', [MateriDatatekController::class, 'destroy'])->name('materi-datatek.destroy');

    // Banner Modal Management
    Route::get('/banner-modals', [BannerModalController::class, 'index'])->name('banner-modals.index');
    Route::get('/banner-modals/create', [BannerModalController::class, 'create'])->name('banner-modals.create');
    Route::post('/banner-modals', [BannerModalController::class, 'store'])->name('banner-modals.store');
    Route::get('/banner-modals/{bannerModal}', [BannerModalController::class, 'show'])->name('banner-modals.show');
    Route::get('/banner-modals/{bannerModal}/edit', [BannerModalController::class, 'edit'])->name('banner-modals.edit');
    Route::put('/banner-modals/{bannerModal}', [BannerModalController::class, 'update'])->name('banner-modals.update');
    Route::delete('/banner-modals/{bannerModal}', [BannerModalController::class, 'destroy'])->name('banner-modals.destroy');

    // Contact Settings Management
    Route::get('/contact-settings', [ContactSettingController::class, 'index'])->name('contact-settings.index');
    Route::get('/contact-settings/{contactSetting}/edit', [ContactSettingController::class, 'edit'])->name('contact-settings.edit');
    Route::put('/contact-settings/{contactSetting}', [ContactSettingController::class, 'update'])->name('contact-settings.update');

    // Contact Submissions Management
    Route::get('/contact-submissions', [ContactSubmissionAdminController::class, 'index'])->name('contact-submissions.index');
    Route::get('/contact-submissions/{contactSubmission}', [ContactSubmissionAdminController::class, 'show'])->name('contact-submissions.show');
    Route::patch('/contact-submissions/{contactSubmission}/status', [ContactSubmissionAdminController::class, 'updateStatus'])->name('contact-submissions.update-status');
    Route::delete('/contact-submissions/{contactSubmission}', [ContactSubmissionAdminController::class, 'destroy'])->name('contact-submissions.destroy');

    
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';