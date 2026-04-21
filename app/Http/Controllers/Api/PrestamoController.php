<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prestamo;
use App\Models\Material;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    public function index()
    {
        return response()->json(Prestamo::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_us'          => 'required|exists:users,id',
            'id_mat'         => 'required|exists:materiales,id',
            'nombre_material' => 'required|string',
        ]);

        $material = Material::findOrFail($request->id_mat);

        if ($material->estado === 'en_prestamo') {
            return response()->json(['message' => 'El material no está disponible'], 409);
        }

        $prestamo = Prestamo::create($request->all());
        $material->update(['estado' => 'en_prestamo']);

        return response()->json($prestamo, 201);
    }

    public function show(Prestamo $prestamo)
    {
        return response()->json($prestamo);
    }

    public function update(Request $request, Prestamo $prestamo)
    {
        // Aquí se gestiona la devolución del material
        $material = Material::findOrFail($prestamo->id_mat);
        $material->update(['estado' => 'disponible']);

        $prestamo->delete();
        return response()->json(['message' => 'Material devuelto correctamente']);
    }

    public function destroy(Prestamo $prestamo)
    {
        $prestamo->delete();
        return response()->json(['message' => 'Préstamo eliminado']);
    }
}