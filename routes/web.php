<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscribeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'check.user.limit'])->group(function () {
    Route::get('/subscribe', [SubscribeController::class, 'index']);
   
});
Route::get('/purchase', [SubscribeController::class, 'purchase']);
// مسیر خاص با سه میدل‌ور
Route::get('/chat', [MessageController::class, 'index'])
    ->middleware(['auth', 'update.last.activity', 'check.user.limit'])
    ->name('chat');

Broadcast::routes(['middleware' => ['web', 'auth']]);
