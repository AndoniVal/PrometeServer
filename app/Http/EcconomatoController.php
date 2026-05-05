<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EconomatoController extends Controller
{
    public function index()
    {
        $productos = Producto::orderBy('nombre')->get();

        $transacciones = Transaccion::with(['producto', 'usuario'])
            ->orderBy('fecha', 'desc')
            ->take(10)
            ->get();

        $totalProductos = Producto::count();
        $stockBajo = Producto::where('stock', '<', 10)->count();
        $comprasMes = Transaccion::whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->count();

        return view('economato', compact(
            'productos', 'transacciones', 'totalProductos', 'stockBajo', 'comprasMes'
        ));
    }

    public function comprar(Request $request)
    {
        $request->validate([
            'id_prod'  => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($request->id_prod);

        if ($producto->stock < $request->cantidad) {
            return back()->withErrors(['cantidad' => 'No hay suficiente stock.']);
        }

        $producto->decrement('stock', $request->cantidad);

        Transaccion::create([
            'id_prod'  => $producto->id,
            'id_us'    => Auth::id(),
            'cantidad' => $request->cantidad,
            'fecha'    => now(),
        ]);

        return back()->with('success', 'Compra registrada correctamente.');
    }
}
