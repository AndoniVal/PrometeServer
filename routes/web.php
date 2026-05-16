<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TransaccionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\FinanzasController;

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
    Route::post('/stock/añadir', [ProductoController::class, 'añadirStock'])->name('stock.añadir');
    Route::post('/stock/eliminar', [ProductoController::class, 'eliminarStock'])->name('stock.eliminar');

    // Inventario
    Route::get('/inventario', [MaterialController::class, 'inventario'])->name('inventario');

    // Mi cuenta
    Route::get('/mi-cuenta', [UserController::class, 'miCuenta'])->name('mi-cuenta');
    Route::post('/mi-cuenta/actualizar', [UserController::class, 'actualizarCuenta'])->name('mi-cuenta.actualizar');
    Route::post('/mi-cuenta/password', [UserController::class, 'cambiarPassword'])->name('mi-cuenta.password');
    Route::post('/mi-cuenta/avatar', [UserController::class, 'subirAvatar'])->name('mi-cuenta.avatar');

    // Carrito
    Route::get('/carrito', [CarritoController::class, 'ver'])->name('carrito');
    Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::delete('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
    Route::post('/carrito/confirmar', [CarritoController::class, 'confirmar'])->name('carrito.confirmar');

    // Finanzas
    Route::get('/finanzas', [FinanzasController::class, 'index'])->name('finanzas');
    Route::post('/finanzas/saldo', [FinanzasController::class, 'gestionarSaldo'])->name('finanzas.saldo');

    Route::get('/inventario', [MaterialController::class, 'inventarioWeb'])->name('inventario');
    Route::post('/inventario/prestar', [MaterialController::class, 'solicitarPrestamo'])->name('inventario.prestar');
    Route::post('/inventario/devolver', [MaterialController::class, 'devolverMaterial'])->name('inventario.devolver');
    Route::post('/materiales', [MaterialController::class, 'storeMaterial'])->name('materiales.store');
    Route::delete('/materiales/{id}', [MaterialController::class, 'destroyMaterial'])->name('materiales.destroy');

    Route::post('/materiales/estado', [MaterialController::class, 'cambiarEstado'])->name('materiales.estado');

    Route::post('/inventario/aprobar', [MaterialController::class, 'aprobarPrestamo'])->name('inventario.aprobar');
    Route::post('/inventario/rechazar', [MaterialController::class, 'rechazarPrestamo'])->name('inventario.rechazar');

    Route::get('/inventario/movimientos', [MaterialController::class, 'movimientos'])->name('inventario.movimientos');
    
});