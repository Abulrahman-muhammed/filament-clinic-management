<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\BookingController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/booking',        [BookingController::class, 'index'])->name('user.booking.index');
Route::get('/booking/slots',  [BookingController::class, 'slots'])->name('user.booking.slots');
Route::post('/booking',       [BookingController::class, 'store'])->name('user.booking.store');



