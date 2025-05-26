<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ✅ Giriş yapmış kullanıcıların erişebileceği rotalar
Route::middleware('auth')->group(function () {

    // 🔹 Profil işlemleri
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🔹 Etkinlik işlemleri
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/list', [EventController::class, 'list'])->name('events.list');
    Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/test', function () {
        return view('events.test');
    });
    Route::put('/events/update/{id}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/delete/{id}', [EventController::class, 'destroy'])->name('events.destroy');
});

// ✅ Sadece admin kullanıcıların erişebileceği rotalar
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/admin', function () {
        return 'Admin paneline hoş geldiniz!';
    });

    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    Route::get('/admin/users/create', [AdminController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/admin/users/{user}/calendar', [EventController::class, 'adminCalendar'])
        ->name('admin.users.calendar');

    Route::get('/admin/events/{userId}', [EventController::class, 'listForUser'])
        ->name('admin.events.user');
});

require __DIR__ . '/auth.php';
