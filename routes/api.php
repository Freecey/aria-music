<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AlbumController;
use App\Http\Controllers\Api\V1\TrackController;
use App\Http\Controllers\Api\V1\LinkController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\UpdateController;
use App\Http\Controllers\Api\V1\MediaController;
use App\Http\Controllers\Api\V1\SiteController;

// Public API routes
Route::prefix('v1')->group(function () {
    // Site info (public)
    Route::get('/site', [SiteController::class, 'index']);
    
    // Public resources
    Route::get('/albums', [AlbumController::class, 'index']);
    Route::get('/tracks', [TrackController::class, 'index']);
    Route::get('/links', [LinkController::class, 'index']);
    Route::get('/updates', [UpdateController::class, 'index']);
    
    // OpenAPI spec (public — for agents)
    Route::get('/docs', fn () => response()->json(
        json_decode(file_get_contents(base_path('docs/api-agent.json')), true)
    ));

    // Auth
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
});

// Protected API routes (require Bearer token)
Route::prefix('v1')->middleware(['auth:sanctum', 'log.api'])->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Albums write
    Route::post('/albums', [AlbumController::class, 'store']);
    Route::put('/albums/{id}', [AlbumController::class, 'update']);
    Route::delete('/albums/{id}', [AlbumController::class, 'destroy']);

    // Tracks write
    Route::post('/tracks', [TrackController::class, 'store']);
    Route::put('/tracks/{id}', [TrackController::class, 'update']);
    Route::delete('/tracks/{id}', [TrackController::class, 'destroy']);

    // Links write
    Route::post('/links', [LinkController::class, 'store']);
    Route::put('/links/{id}', [LinkController::class, 'update']);
    Route::delete('/links/{id}', [LinkController::class, 'destroy']);

    // Settings/Bio
    Route::patch('/bio', [SettingController::class, 'patchBio']);
    Route::patch('/settings', [SettingController::class, 'patchSettings']);

    // Updates write
    Route::post('/updates', [UpdateController::class, 'store']);
    Route::put('/updates/{id}', [UpdateController::class, 'update']);
    Route::delete('/updates/{id}', [UpdateController::class, 'destroy']);

    // Media
    Route::post('/media/upload', [MediaController::class, 'upload']);
    Route::delete('/media/{filename}', [MediaController::class, 'destroy']);
});
