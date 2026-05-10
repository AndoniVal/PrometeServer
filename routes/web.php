<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TransaccionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MaterialController;

Route::get('/', [PageController::class, 'index']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Transacciones
    Route::get('/transacciones', [TransaccionController::class, 'transacciones'])->name('transacciones');

    // Economato
    Route::get('/economato', [TransaccionController::class, 'economato'])->name('economato');
    Route::post('/economato/comprar', [TransaccionController::class, 'comprar'])->name('economato.comprar');

    // Productos (admin)
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');

    // Inventario
    Route::get('/inventario', [MaterialController::class, 'inventario'])->name('inventario');

    // Mi cuenta
    Route::get('/mi-cuenta', [UserController::class, 'miCuenta'])->name('mi-cuenta');
    Route::post('/mi-cuenta/actualizar', [UserController::class, 'actualizarCuenta'])->name('mi-cuenta.actualizar');
    Route::post('/mi-cuenta/password', [UserController::class, 'cambiarPassword'])->name('mi-cuenta.password');
    Route::post('/mi-cuenta/avatar', [UserController::class, 'subirAvatar'])->name('mi-cuenta.avatar');
});