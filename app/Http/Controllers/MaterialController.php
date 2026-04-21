<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        return response()->json(Material::with('usuario')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_us' => 'required|exists:users,id',
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
        ]);

        $material = Material::create($request->all());
        return response()->json($material, 201);
    }

    public function show($id)
    {
        $material = Material::with('usuario', 'prestamos')->findOrFail($id);
        return response()->json($material);
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);
        $material->update($request->all());
        return response()->json($material);
    }

    public function destroy($id)
    {
        Material::findOrFail($id)->delete();
        return response()->json(['message' => 'Material eliminado']);
    }

    public function porUsuario($id_us)
    {
        $materiales = Material::with('prestamos')
            ->where('id_us', $id_us)
            ->get();
        return response()->json($materiales);
    }
}