<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactFormApiController;

Route::post('/contact-forms/submit', [ContactFormApiController::class, 'submit']);