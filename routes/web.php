<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\SpamDashboardController;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::get('/', function () {
    return view('welcome');
});


// Contact Form

Route::get('/contact', [ContactController::class, 'index'])
    ->name('contact.form');

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware([
        'blocked.ip',
        'minimum.time',
        ProtectAgainstSpam::class,
    ])
    ->name('contact.store');


// Honeypot Security Dashboard

Route::get('/spam-dashboard', [SpamDashboardController::class, 'index'])
    ->name('spam.dashboard');


// Block IP

Route::post('/spam-dashboard/block-ip', [
    SpamDashboardController::class,
    'blockIp'
])->name('spam.block-ip');


// Unblock IP

Route::delete('/spam-dashboard/unblock-ip/{blockedIp}', [
    SpamDashboardController::class,
    'unblockIp'
])->name('spam.unblock-ip');