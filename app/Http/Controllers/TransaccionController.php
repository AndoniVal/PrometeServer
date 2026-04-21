<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use App\Models\Producto;
use Illuminate\Http\Request;

class TransaccionController extends Controller
{
    public function index()
    {
        return response()->json(Transaccion::with('usuario', 'producto')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_us' => 'required|exists:users,id',
            'id_prod' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'fecha' => 'required|date',
        ]);

        $producto = Producto::findOrFail($request->id_prod);

        if ($producto->stock < $request->cantidad) {
            return response()->json(['message' => 'Stock insuficiente'], 422);
        }

        $producto->decrement('stock', $request->cantidad);

        $transaccion = Transaccion::create($request->all());
        return response()->json($transaccion, 201);
    }

    public function show($id)
    {
        $transaccion = Transaccion::with('usuario', 'producto')->findOrFail($id);
        return response()->json($transaccion);
    }

    public function update(Request $request, $id)
    {
        $transaccion = Transaccion::findOrFail($id);
        $transaccion->update($request->all());
        return response()->json($transaccion);
    }

    public function destroy($id)
    {
        Transaccion::findOrFail($id)->delete();
        return response()->json(['message' => 'Transacción eliminada']);
    }

    public function porUsuario($id_us)
    {
        $transacciones = Transaccion::with('producto')
            ->where('id_us', $id_us)
            ->get();
        return response()->json($transacciones);
    }

    public function porProducto($id_prod)
    {
        $transacciones = Transaccion::with('usuario')
            ->where('id_prod', $id_prod)
            ->get();
        return response()->json($transacciones);
    }
    // ── MÉTODOS WEB (economato) ──────────────────────────────

    public function economato()
    {
        $productos = Producto::orderBy('nombre')->get();
        $transacciones = Transaccion::with(['usuario', 'producto'])
            ->orderBy('fecha', 'desc')
            ->take(10)
            ->get();

        $totalProductos = $productos->count();
        $stockBajo = $productos->where('stock', '<', 10)->count();
        $comprasMes = Transaccion::whereMonth('fecha', now()->month)->count();

        $user = auth()->user();

        return view('economato', compact(
            'user',
            'productos',
            'transacciones',
            'totalProductos',
            'stockBajo',
            'comprasMes'
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
            return back()->withErrors(['cantidad' => 'Stock insuficiente para este producto.']);
        }

        $producto->decrement('stock', $request->cantidad);

        Transaccion::create([
            'id_us'    => auth()->id(),
            'id_prod'  => $request->id_prod,
            'cantidad' => $request->cantidad,
            'fecha'    => now(),
        ]);

        return back()->with('success', '✓ Compra registrada correctamente.');
    }
    }