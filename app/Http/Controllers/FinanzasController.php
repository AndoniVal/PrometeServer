<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use App\Models\Producto;
use App\Models\User;
use App\Models\MovimientoSaldo;
use Illuminate\Http\Request;

class FinanzasController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalIngresos = Transaccion::sum('total');
        $ingresosMes = Transaccion::whereMonth('fecha', now()->month)->sum('total');
        $totalTransacciones = Transaccion::count();

        $gastosPorUsuario = User::selectRaw('users.id, users.nombre, users.saldo,
                COUNT(transacciones.id) as total_compras,
                SUM(transacciones.total) as total_gastado')
            ->leftJoin('transacciones', 'users.id', '=', 'transacciones.id_us')
            ->groupBy('users.id', 'users.nombre', 'users.saldo')
            ->orderByDesc('total_gastado')
            ->get();

        $usuariosConDeuda = $gastosPorUsuario->where('saldo', '<', 0)->count();

        $controlStock = Producto::selectRaw('productos.id, productos.nombre,
                productos.stock,
                SUM(transacciones.cantidad) as total_salidas')
            ->leftJoin('transacciones', 'productos.id', '=', 'transacciones.id_prod')
            ->groupBy('productos.id', 'productos.nombre', 'productos.stock')
            ->orderBy('productos.stock')
            ->get();

        $usuarios = User::orderBy('nombre')->get();

        $movimientosSaldo = MovimientoSaldo::with('usuario')
            ->orderBy('fecha', 'desc')
            ->take(20)
            ->get();

        return view('finanzas', compact(
            'user',
            'totalIngresos',
            'ingresosMes',
            'totalTransacciones',
            'usuariosConDeuda',
            'gastosPorUsuario',
            'controlStock',
            'usuarios',
            'movimientosSaldo'
        ));
    }

    public function gestionarSaldo(Request $request)
    {
        $request->validate([
            'id_us'      => 'required|exists:users,id',
            'tipo'       => 'required|in:ingreso,descuento',
            'cantidad'   => 'required|numeric|min:0.01',
            'comentario' => 'nullable|string|max:500',
        ]);

        $usuario = User::findOrFail($request->id_us);
        $cantidad = $request->cantidad;

        if ($request->tipo === 'ingreso') {
            $usuario->increment('saldo', $cantidad);
        } else {
            // Límite de deuda: -20€
            $saldoTras = $usuario->saldo - $cantidad;
            if ($saldoTras < -20) {
                return back()->withErrors(['cantidad' => "No se puede dejar el saldo por debajo de -20€. Saldo actual: {$usuario->saldo}€"]);
            }
            $usuario->decrement('saldo', $cantidad);
        }

        MovimientoSaldo::create([
            'id_us'      => $usuario->id,
            'tipo'       => $request->tipo,
            'cantidad'   => $cantidad,
            'comentario' => $request->comentario,
            'fecha'      => now(),
        ]);

        $accion = $request->tipo === 'ingreso' ? 'añadido' : 'descontado';
        return back()->with('success', "✓ {$cantidad}€ {$accion} a {$usuario->nombre}. Nuevo saldo: {$usuario->fresh()->saldo}€");
    }
}