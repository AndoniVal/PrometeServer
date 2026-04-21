<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\TransaccionController;

// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('materiales', MaterialController::class);
    Route::apiResource('productos', ProductoController::class);
    Route::apiResource('prestamos', PrestamoController::class);
    Route::apiResource('transacciones', TransaccionController::class);

    Route::get('materiales/usuario/{id_us}', [MaterialController::class, 'porUsuario']);
    Route::get('prestamos/usuario/{id_us}', [PrestamoController::class, 'porUsuario']);
    Route::get('prestamos/material/{id_mat}', [PrestamoController::class, 'porMaterial']);
    Route::get('productos/sin-stock', [ProductoController::class, 'sinStock']);
    Route::put('productos/{id}/stock', [ProductoController::class, 'actualizarStock']);
    Route::get('transacciones/usuario/{id_us}', [TransaccionController::class, 'porUsuario']);
    Route::get('transacciones/producto/{id_prod}', [TransaccionController::class, 'porProducto']);
});