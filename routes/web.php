<?php

use App\Http\Controllers\PostCourtController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistNewCourtController;
use App\Http\Controllers\PostAttendanceController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'approved'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'approved'])->group(function () {
    Route::resource('post-court', PostCourtController::class);
    Route::resource('post-attendance', PostAttendanceController::class)
        ->only(['edit', 'update', 'destroy']);

    Route::get('regist/regist-new-court', [RegistNewCourtController::class, 'create'])->name('regist-new-court.create');
    Route::post('regist', [RegistNewCourtController::class, 'store'])->name('regist-new-court.store');

    Route::get('photos/album/{album}', [PhotoController::class, 'index'])->name('photos.index');
    Route::post('photos/album/{album}', [PhotoController::class, 'store'])->name('photos.store');
    Route::delete('photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');

    Route::resource('gallery', GalleryController::class);

    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

require __DIR__.'/auth.php';
