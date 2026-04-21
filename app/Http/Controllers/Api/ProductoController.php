<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        return response()->json(Producto::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'tipo'   => 'required|string',
            'stock'  => 'required|integer|min:0',
        ]);

        $producto = Producto::create($request->all());
        return response()->json($producto, 201);
    }

    public function show(Producto $producto)
    {
        return response()->json($producto);
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'sometimes|string',
            'tipo'   => 'sometimes|string',
            'stock'  => 'sometimes|integer|min:0',
        ]);

        $producto->update($request->all());
        return response()->json($producto);
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return response()->json(['message' => 'Producto eliminado']);
    }
}