<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;

class FinanzasController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalIngresos = Transaccion::sum('total');
        $ingresosMes = Transaccion::whereMonth('fecha', now()->month)->sum('total');
        $totalTransacciones = Transaccion::count();
        $usuariosConDeuda = 0; // placeholder para sistema de fiar

        $gastosPorUsuario = User::selectRaw('users.id, users.nombre,
                COUNT(transacciones.id) as total_compras,
                SUM(transacciones.total) as total_gastado')
            ->leftJoin('transacciones', 'users.id', '=', 'transacciones.id_us')
            ->groupBy('users.id', 'users.nombre')
            ->having('total_compras', '>', 0)
            ->orderByDesc('total_gastado')
            ->get();

        $controlStock = Producto::selectRaw('productos.id, productos.nombre,
                productos.stock,
                SUM(transacciones.cantidad) as total_salidas')
            ->leftJoin('transacciones', 'productos.id', '=', 'transacciones.id_prod')
            ->groupBy('productos.id', 'productos.nombre', 'productos.stock')
            ->orderBy('productos.stock')
            ->get();

        return view('finanzas', compact(
            'user', 'totalIngresos', 'ingresosMes',
            'totalTransacciones', 'usuariosConDeuda',
            'gastosPorUsuario', 'controlStock'
        ));
    }
}