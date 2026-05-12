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

        $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));
        $user = auth()->user();

        // Verificar stock antes de procesar
        foreach ($carrito as $item) {
            $producto = \App\Models\Producto::findOrFail($item['id']);
            if ($producto->stock < $item['cantidad']) {
                return back()->withErrors(['stock' => "Stock insuficiente para {$producto->nombre}."]);
            }
        }

        // Descontar saldo (permite negativo para sistema de fiar)
        $user->decrement('saldo', $total);

        // Crear transacciones y descontar stock
        foreach ($carrito as $item) {
            $producto = \App\Models\Producto::findOrFail($item['id']);
            $producto->decrement('stock', $item['cantidad']);

            \App\Models\Transaccion::create([
                'id_us'         => $user->id,
                'id_prod'       => $item['id'],
                'cantidad'      => $item['cantidad'],
                'precio_unidad' => $item['precio'],
                'total'         => $item['precio'] * $item['cantidad'],
                'fecha'         => now(),
            ]);
        }

        session()->forget('carrito');

        $saldoRestante = $user->fresh()->saldo;
        $mensaje = $saldoRestante < 0
            ? "✓ Pedido confirmado. Tu saldo actual es {$saldoRestante}€ (deuda pendiente)."
            : "✓ Pedido confirmado. Saldo restante: {$saldoRestante}€.";

        return redirect()->route('economato')->with('success', $mensaje);
    }
}