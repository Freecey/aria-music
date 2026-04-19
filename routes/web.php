<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;

// Front routes
Route::get('/', [HomeController::class, 'index']);

// Health check
Route::get('/up', function () {
    return response()->json(['status' => 'ok']);
});
