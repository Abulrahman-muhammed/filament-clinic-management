<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\BookingController;
use App\Http\Controllers\Front\AiController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/booking',        [BookingController::class, 'index'])->name('user.booking.index');
Route::get('/booking/slots',  [BookingController::class, 'slots'])->name('user.booking.slots');
Route::post('/booking',       [BookingController::class, 'store'])->name('user.booking.store');
Route::post('/booking/patient', [BookingController::class, 'patientLookup'])
    ->name('user.booking.patient.lookup');

Route::get('/assistant',        [AiController::class, 'index'])->name('user.assistant.index');
Route::post('/assistant/chat',  [AiController::class, 'chat'])->name('user.assistant.chat');
use Illuminate\Support\Facades\Http;

Route::get('/gemini-test', function () {
// dd(config('services.gemini.api_key'));
    $response = Http::get(
        'https://generativelanguage.googleapis.com/v1beta/models',
        [
            'key' => config('services.gemini.api_key')
        ]
    );

    dd(
        $response->status(),
        $response->json()
    );
});