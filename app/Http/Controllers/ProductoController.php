<?php

namespace App\Http\Controllers;

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
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
        ]);

        $producto = Producto::create($request->all());
        return response()->json($producto, 201);
    }

    public function show($id)
    {
        $producto = Producto::with('transacciones')->findOrFail($id);
        return response()->json($producto);
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->update($request->all());
        return response()->json($producto);
    }

    public function destroy($id)
    {
        Producto::findOrFail($id)->delete();
        return response()->json(['message' => 'Producto eliminado']);
    }

    public function sinStock()
    {
        $productos = Producto::where('stock', 0)->get();
        return response()->json($productos);
    }

    public function actualizarStock(Request $request, $id)
    {
        $request->validate(['stock' => 'required|integer|min:0']);
        $producto = Producto::findOrFail($id);
        $producto->update(['stock' => $request->stock]);
        return response()->json($producto);
    }
}