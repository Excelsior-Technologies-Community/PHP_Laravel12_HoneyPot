<?php

use Illuminate\Support\Facades\Route; // Import Route facade
use App\Http\Controllers\ContactController; // Import ContactController
use Spatie\Honeypot\ProtectAgainstSpam; // Import Honeypot middleware

Route::get('/', function () {
    return view('welcome'); // Default welcome page route
});

Route::get('/contact', [ContactController::class, 'index']); 
// Displays the contact form page

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware(ProtectAgainstSpam::class); 
// Handles form submission and applies honeypot spam protection middleware
