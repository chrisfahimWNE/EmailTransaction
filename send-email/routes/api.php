<?php


use App\Http\Controllers\EmailWebhookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;

Route::post('/send-email', [EmailController::class, 'send']);
Route::post('/email/webhook', [EmailWebhookController::class, 'emailWebhook']);

