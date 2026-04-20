<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AlbumController;
use App\Http\Controllers\Admin\TrackController;
use App\Http\Controllers\Admin\LinkController;
use App\Http\Controllers\Admin\UpdateController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ApiController;
use Illuminate\Support\Facades\Auth;

// Front routes
Route::get('/', [HomeController::class, 'index']);

// Health check
Route::get('/up', fn () => response()->json(['status' => 'ok']));

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });

    // Protected
    Route::middleware('auth')->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Albums
        Route::get('/albums', [AlbumController::class, 'index'])->name('albums.index');
        Route::get('/albums/create', [AlbumController::class, 'create'])->name('albums.create');
        Route::post('/albums', [AlbumController::class, 'store'])->name('albums.store');
        Route::get('/albums/{id}/edit', [AlbumController::class, 'edit'])->name('albums.edit');
        Route::put('/albums/{id}', [AlbumController::class, 'update'])->name('albums.update');
        Route::delete('/albums/{id}', [AlbumController::class, 'destroy'])->name('albums.destroy');

        // Tracks
        Route::get('/tracks', [TrackController::class, 'index'])->name('tracks.index');
        Route::get('/tracks/create', [TrackController::class, 'create'])->name('tracks.create');
        Route::post('/tracks', [TrackController::class, 'store'])->name('tracks.store');
        Route::get('/tracks/{id}/edit', [TrackController::class, 'edit'])->name('tracks.edit');
        Route::put('/tracks/{id}', [TrackController::class, 'update'])->name('tracks.update');
        Route::delete('/tracks/{id}', [TrackController::class, 'destroy'])->name('tracks.destroy');

        // Links
        Route::get('/links', [LinkController::class, 'index'])->name('links.index');
        Route::get('/links/create', [LinkController::class, 'create'])->name('links.create');
        Route::post('/links', [LinkController::class, 'store'])->name('links.store');
        Route::get('/links/{id}/edit', [LinkController::class, 'edit'])->name('links.edit');
        Route::put('/links/{id}', [LinkController::class, 'update'])->name('links.update');
        Route::delete('/links/{id}', [LinkController::class, 'destroy'])->name('links.destroy');

        // Updates
        Route::get('/updates', [UpdateController::class, 'index'])->name('updates.index');
        Route::get('/updates/create', [UpdateController::class, 'create'])->name('updates.create');
        Route::post('/updates', [UpdateController::class, 'store'])->name('updates.store');
        Route::get('/updates/{id}/edit', [UpdateController::class, 'edit'])->name('updates.edit');
        Route::put('/updates/{id}', [UpdateController::class, 'update'])->name('updates.update');
        Route::delete('/updates/{id}', [UpdateController::class, 'destroy'])->name('updates.destroy');

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

        // Media
        Route::get('/media', [MediaController::class, 'index'])->name('media');
        Route::delete('/media/{filename}', [MediaController::class, 'destroy'])->name('media.destroy');

        // Logs
        Route::get('/logs', [LogController::class, 'index'])->name('logs');

        // Users (admin only)
        Route::middleware('role:admin')->group(function () {
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        });

        // AJAX API
        Route::post('/api/toggle', [ApiController::class, 'toggle']);
        Route::post('/api/reorder', [ApiController::class, 'reorder']);
    });
});
