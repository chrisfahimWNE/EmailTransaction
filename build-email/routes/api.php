<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuildEmailController;

Route::post('/build-email', [BuildEmailController::class, 'buildEmail']);
