<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ResourceReportController;
use App\Http\Controllers\StatisticsController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
    Route::get('/resources/create', [ResourceController::class, 'create'])->name('resources.create');
    Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');
    Route::get('/resources/{resource}', [ResourceController::class, 'show'])->name('resources.show');
    Route::get('/resources/{resource}/edit', [ResourceController::class, 'edit'])->name('resources.edit');
    Route::put('/resources/{resource}', [ResourceController::class, 'update'])->name('resources.update');
    Route::delete('/resources/{resource}', [ResourceController::class, 'destroy'])->name('resources.destroy');
    Route::post('/resources/{resource}/review', [ResourceController::class, 'storeReview'])->name('resources.review.store');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::put('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
    Route::put('/transactions/{transaction}/return', [TransactionController::class, 'markReturned'])->name('transactions.return');
    Route::put('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');

    Route::get('/map', [MapController::class, 'index'])->name('map.index');
    Route::get('/api/map/resources', [MapController::class, 'getResourceLocations'])->name('map.resources');

    Route::prefix('reminders')->name('reminders.')->group(function () {
        Route::get('/', [ReminderController::class, 'index'])->name('index');
        Route::get('/notifications', [ReminderController::class, 'notifications'])->name('notifications');
        Route::post('/read/{id}', [ReminderController::class, 'markRead'])->name('read');
        Route::post('/read-all', [ReminderController::class, 'markAllRead'])->name('read-all');
    });

    Route::get('/my-statistics', [StatisticsController::class, 'index'])->name('statistics.index');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/create', [ResourceReportController::class, 'create'])->name('create');
        Route::post('/', [ResourceReportController::class, 'store'])->name('store');
        Route::get('/my-reports', [ResourceReportController::class, 'myReports'])->name('my');
        Route::get('/{report}', [ResourceReportController::class, 'show'])->name('show');
    });

    Route::prefix('leaderboard')->name('leaderboard.')->group(function () {
        Route::get('/', [LeaderboardController::class, 'index'])->name('index');
        Route::get('/my-badges', [LeaderboardController::class, 'myBadges'])->name('badges');
    });

    Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{report}', [AdminReportController::class, 'show'])->name('reports.show');
        Route::put('/reports/{report}', [AdminReportController::class, 'update'])->name('reports.update');
    });
});