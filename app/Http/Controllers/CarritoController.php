<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Transaccion;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    // Añadir producto al carrito
    public function agregar(Request $request)
    {
        $request->validate([
            'id_prod'  => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($request->id_prod);

        if ($producto->stock < $request->cantidad) {
            return back()->withErrors(['cantidad' => 'Stock insuficiente.']);
        }

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$producto->id])) {
            $carrito[$producto->id]['cantidad'] += $request->cantidad;
        } else {
            $carrito[$producto->id] = [
                'id'       => $producto->id,
                'nombre'   => $producto->nombre,
                'precio'   => $producto->precio,
                'imagen'   => $producto->imagen,
                'cantidad' => $request->cantidad,
            ];
        }

        session()->put('carrito', $carrito);
        return back()->with('success', '✓ Producto añadido al carrito.');
    }

    // Eliminar un producto del carrito
    public function eliminar($id)
    {
        $carrito = session()->get('carrito', []);
        unset($carrito[$id]);
        session()->put('carrito', $carrito);
        return back()->with('success', '✓ Producto eliminado del carrito.');
    }

    // Vaciar el carrito
    public function vaciar()
    {
        session()->forget('carrito');
        return back()->with('success', '✓ Carrito vaciado.');
    }

    // Ver el carrito
    public function ver()
    {
        $carrito = session()->get('carrito', []);
        $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));
        $user = auth()->user();
        return view('carrito', compact('user', 'carrito', 'total'));
    }

    // Confirmar pedido — crea las transacciones
    public function confirmar()
    {
        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return back()->withErrors(['carrito' => 'El carrito está vacío.']);
        }

        foreach ($carrito as $item) {
            $producto = Producto::findOrFail($item['id']);

            if ($producto->stock < $item['cantidad']) {
                return back()->withErrors(['stock' => "Stock insuficiente para {$producto->nombre}."]);
            }

            $producto->decrement('stock', $item['cantidad']);

            Transaccion::create([
                'id_us'         => auth()->id(),
                'id_prod'       => $item['id'],
                'cantidad'      => $item['cantidad'],
                'precio_unidad' => $item['precio'],
                'total'         => $item['precio'] * $item['cantidad'],
                'fecha'         => now(),
            ]);
        }

        session()->forget('carrito');
        return redirect()->route('economato')->with('success', '✓ Pedido confirmado correctamente.');
    }
}