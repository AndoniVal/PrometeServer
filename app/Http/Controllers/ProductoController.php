<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'tipo'   => 'required|string|max:255',
                'stock'  => 'required|integer|min:0',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect('/economato')
                ->withErrors($e->errors())
                ->withInput();
        }

        Producto::create($validated);

        return redirect('/economato')->with('success', 'Producto creado con éxito');
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'tipo'   => 'required|string|max:255',
                'stock'  => 'required|integer|min:0',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect('/economato')
                ->withErrors($e->errors())
                ->withInput();
        }

        $producto = Producto::findOrFail($id);
        $producto->update($validated);

        return redirect('/economato')->with('success', 'Producto actualizado');
    }

    public function destroy($id)
    {
        Producto::findOrFail($id)->delete();
        return redirect('/economato')->with('success', 'Producto eliminado');
    }
}
