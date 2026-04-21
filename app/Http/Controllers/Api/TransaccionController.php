<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaccion;
use App\Models\Producto;
use Illuminate\Http\Request;

class TransaccionController extends Controller
{
    public function index()
    {
        return response()->json(Transaccion::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_us'    => 'required|exists:users,id',
            'id_prod'  => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($request->id_prod);

        if ($producto->stock < $request->cantidad) {
            return response()->json(['message' => 'Stock insuficiente'], 409);
        }

        $transaccion = Transaccion::create($request->all());
        $producto->decrement('stock', $request->cantidad);

        return response()->json($transaccion, 201);
    }

    public function show(Transaccion $transaccion)
    {
        return response()->json($transaccion);
    }

    public function update(Request $request, Transaccion $transaccion)
    {
        $transaccion->update($request->all());
        return response()->json($transaccion);
    }

    public function destroy(Transaccion $transaccion)
    {
        $transaccion->delete();
        return response()->json(['message' => 'Transacción eliminada']);
    }
}