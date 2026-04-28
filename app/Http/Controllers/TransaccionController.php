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
        public function transacciones()
    {
        $transacciones = Transaccion::with(['usuario', 'producto'])
            ->orderBy('fecha', 'desc')
            ->take(20)
            ->get();

        $user = auth()->user();

        return view('transacciones', compact('user', 'transacciones'));
    }
}
