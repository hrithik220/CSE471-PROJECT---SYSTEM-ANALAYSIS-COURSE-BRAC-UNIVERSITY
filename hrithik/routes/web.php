<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\MapController;

Route::get('/', fn() => redirect()->route('map.index'));

Auth::routes();

// MODULE 1 — Resource Profile View & Edit (Hrithik)
Route::get('/resources/{resource}', [ResourceController::class, 'show'])->name('resources.show');

Route::middleware('auth')->group(function () {
    Route::get('/resources/{resource}/edit', [ResourceController::class, 'edit'])->name('resources.edit');
    Route::put('/resources/{resource}', [ResourceController::class, 'update'])->name('resources.update');

    // MODULE 2 — Google Maps Pickup Locator (Hrithik)
    Route::get('/map', [MapController::class, 'index'])->name('map.index');
    Route::get('/api/map-data', [MapController::class, 'getMapData'])->name('map.data');
    Route::post('/resources/{resource}/location', [MapController::class, 'updateLocation'])->name('resources.location');
});
