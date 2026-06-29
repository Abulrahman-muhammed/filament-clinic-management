<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\SlotController;
use App\Http\Controllers\Api\V1\BookAppointmentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('services', [ServiceController::class, 'index']);
    Route::get('/available-slots', [SlotController::class, 'index']);
    Route::post('/book-appointment', [BookAppointmentController::class, 'store']);
});