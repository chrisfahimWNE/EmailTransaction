<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;

Route::post('/send-email', [EmailController::class, 'send']);
Route::post('/send-confirm-email', [EmailController::class, 'sendConfirmEmail']);
Route::get('/email/confirmed', [EmailController::class, 'receiveConfirm']);

