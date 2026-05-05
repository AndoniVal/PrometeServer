<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TransaccionController;
use App\Http\Controllers\UserController;



Route::get('/', [PageController::class, 'index']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/transacciones', [TransaccionController::class, 'transacciones'])->name('transacciones');
    Route::get('/mi-cuenta', [UserController::class, 'miCuenta'])->name('mi-cuenta');
    Route::post('/mi-cuenta/actualizar', [UserController::class, 'actualizarCuenta'])->name('mi-cuenta.actualizar');
    Route::post('/mi-cuenta/password', [UserController::class, 'cambiarPassword'])->name('mi-cuenta.password');
});

