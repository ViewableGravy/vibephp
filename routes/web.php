<?php

use App\Http\Controllers\VibeController;
use Illuminate\Support\Facades\Route;

// Cheap utility endpoints must never invoke the model.
Route::get('/health', fn () => response('ok')->header('Cache-Control', 'no-store'));
Route::get('/robots.txt', fn () => response("User-agent: *\nDisallow: /\n", 200)
    ->header('Content-Type', 'text/plain; charset=UTF-8'));
Route::get('/favicon.ico', fn () => response('', 204));

// VibePHP: only real scripts and the explicit /posts/{id} demo route reach the model.
// Keep the catch-all last.
Route::any('/{path?}', VibeController::class)
    ->where('path', '.*');
