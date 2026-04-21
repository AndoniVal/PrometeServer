<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    public function index()
    {
        return response()->json(Prestamo::with('usuario', 'material')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_us' => 'required|exists:users,id',
            'id_mat' => 'required|exists:materials,id',
            'nombre_material' => 'required|string|max:255',
            'fecha' => 'required|date',
        ]);

        $prestamo = Prestamo::create($request->all());
        return response()->json($prestamo, 201);
    }

    public function show($id)
    {
        $prestamo = Prestamo::with('usuario', 'material')->findOrFail($id);
        return response()->json($prestamo);
    }

    public function update(Request $request, $id)
    {
        $prestamo = Prestamo::findOrFail($id);
        $prestamo->update($request->all());
        return response()->json($prestamo);
    }

    public function destroy($id)
    {
        Prestamo::findOrFail($id)->delete();
        return response()->json(['message' => 'Préstamo eliminado']);
    }

    public function porUsuario($id_us)
    {
        $prestamos = Prestamo::with('material')
            ->where('id_us', $id_us)
            ->get();
        return response()->json($prestamos);
    }

    public function porMaterial($id_mat)
    {
        $prestamos = Prestamo::with('usuario')
            ->where('id_mat', $id_mat)
            ->get();
        return response()->json($prestamos);
    }
}