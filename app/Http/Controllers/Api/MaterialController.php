<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        return response()->json(Material::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_us'  => 'required|exists:users,id',
            'nombre' => 'required|string',
            'tipo'   => 'required|string',
            'estado' => 'sometimes|string|in:disponible,en_prestamo',
        ]);

        $material = Material::create($request->all());
        return response()->json($material, 201);
    }

    public function show(Material $material)
    {
        return response()->json($material);
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'id_us'  => 'sometimes|exists:users,id',
            'nombre' => 'sometimes|string',
            'tipo'   => 'sometimes|string',
            'estado' => 'sometimes|string|in:disponible,en_prestamo',
        ]);

        $material->update($request->all());
        return response()->json($material);
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return response()->json(['message' => 'Material eliminado']);
    }
}