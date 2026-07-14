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
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'stock'       => 'required|integer|min:0',
            'precio'      => 'required|numeric|min:0',
            'imagen'      => 'nullable|file|max:5120',
        ]);

        $data = $request->only('nombre', 'descripcion', 'stock', 'precio');

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create($data);
        return back()->with('success', '✓ Producto creado correctamente.');
    }


    public function show($id)
    {
        $producto = Producto::with('transacciones')->findOrFail($id);
        return response()->json($producto);
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'nombre'      => 'required|string|max:255',
        'descripcion' => 'required|string|max:255',
        'stock'       => 'required|integer|min:0',
        'precio'      => 'required|numeric|min:0',
        'imagen'      => 'nullable|file|max:5120',
    ]);

    $producto = Producto::findOrFail($id);
    $data = $request->only('nombre', 'descripcion', 'stock', 'precio');

    if ($request->hasFile('imagen')) {
        // Borrar la imagen anterior para no acumular huérfanas en storage
        if ($producto->imagen && \Storage::disk('public')->exists($producto->imagen)) {
            \Storage::disk('public')->delete($producto->imagen);
        }
        $data['imagen'] = $request->file('imagen')->store('productos', 'public');
    }

    $producto->update($data);
    return back()->with('success', '✓ Producto actualizado correctamente.');
}

public function destroy($id)
{
    $producto = Producto::findOrFail($id);

    if ($producto->imagen && \Storage::disk('public')->exists($producto->imagen)) {
        \Storage::disk('public')->delete($producto->imagen);
    }

    $producto->delete();
    return back()->with('success', '✓ Producto eliminado correctamente.');
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

    public function añadirStock(Request $request)
    {
        $request->validate([
            'id_prod'  => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);
        $producto = Producto::findOrFail($request->id_prod);
        $producto->increment('stock', $request->cantidad);
        return back()->with('success', '✓ Stock añadido correctamente.');
    }

    public function eliminarStock(Request $request)
    {
        $request->validate([
            'id_prod'  => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);
        $producto = Producto::findOrFail($request->id_prod);
        if ($producto->stock < $request->cantidad) {
            return back()->withErrors(['cantidad' => 'No hay suficiente stock para retirar esa cantidad.']);
        }
        $producto->decrement('stock', $request->cantidad);
        return back()->with('success', '✓ Stock retirado correctamente.');
    }
}
