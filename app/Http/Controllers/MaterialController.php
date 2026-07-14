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
    public function inventario(){
        $materiales = Material::with('usuario')->orderBy('created_at', 'desc')->get();
        $prestamos = \App\Models\Prestamo::with(['usuario', 'material'])
            ->orderBy('fecha', 'desc')
            ->take(20)
            ->get();

        $totalMateriales = $materiales->count();
        $disponibles = $materiales->where('estado', 'disponible')->count();
        $prestados = $materiales->where('estado', 'prestado')->count();

        $user = auth()->user();

        return view('inventario', compact(
            'user',
            'materiales',
            'prestamos',
            'totalMateriales',
            'disponibles',
            'prestados'
        ));
    }

    public function inventarioWeb()
    {
        $materiales = Material::with(['usuario', 'usuarioPrestado'])->orderBy('nombre')->get();
        $usuarios = \App\Models\User::orderBy('nombre')->get();
        $user = auth()->user();

        $totalMateriales = $materiales->count();
        $disponibles = $materiales->where('estado', 'disponible')->count();
        $prestados = $materiales->where('estado', 'prestado')->count();
        $mantenimiento = $materiales->where('estado', 'mantenimiento')->count();

        $solicitudesPendientes = \App\Models\Prestamo::with(['usuario', 'material'])
            ->where('estado', 'pendiente')
            ->orderBy('fecha', 'asc')
            ->get();

        if ($user->rol === 'administrador') {
            $ultimosPrestamos = \App\Models\Prestamo::with(['usuario', 'material'])
                ->orderBy('fecha', 'desc')
                ->take(15)
                ->get();
        } else {
            $ultimosPrestamos = \App\Models\Prestamo::with(['material'])
                ->where('id_us', $user->id)
                ->orderBy('fecha', 'desc')
                ->take(10)
                ->get();
        }

        return view('inventario', compact(
            'user', 'materiales', 'usuarios',
            'ultimosPrestamos', 'solicitudesPendientes',
            'totalMateriales', 'disponibles', 'prestados', 'mantenimiento'
        ));
    }

    public function solicitarPrestamo(Request $request)
    {
        $request->validate([
            'id_mat' => 'required|exists:materiales,id',
        ]);

        $material = Material::findOrFail($request->id_mat);

        if ($material->estado !== 'disponible') {
            return back()->withErrors(['material' => 'Este material no está disponible.']);
        }

        // Crear solicitud pendiente
        \App\Models\Prestamo::create([
            'id_us'           => auth()->id(),
            'id_mat'          => $material->id,
            'nombre_material' => $material->nombre,
            'fecha'           => now(),
            'estado'          => 'pendiente',
        ]);

        return back()->with('success', '✓ Solicitud de préstamo enviada. Pendiente de aprobación.');
    }

    public function storeMaterial(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'tipo'        => 'required|string|max:255',
            'id_us'       => 'required|exists:users,id',
            'imagen'      => 'nullable|file|max:5120',
        ]);

        $data = $request->only('nombre', 'descripcion', 'tipo', 'id_us');
        $data['estado'] = 'disponible';
        $data['id_prestado'] = null;

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('materiales', 'public');
        }

        Material::create($data);
        return back()->with('success', '✓ Material creado correctamente.');
    }

public function destroyMaterial($id)
{
    $material = Material::findOrFail($id);

    if ($material->imagen && \Storage::disk('public')->exists($material->imagen)) {
        \Storage::disk('public')->delete($material->imagen);
    }

    $material->delete();
    return back()->with('success', '✓ Material eliminado correctamente.');
}

    public function cambiarEstado(Request $request)
    {
        $request->validate([
            'id_mat' => 'required|exists:materiales,id',
            'estado' => 'required|in:disponible,prestado,mantenimiento',
        ]);

        $material = Material::findOrFail($request->id_mat);
        $material->update(['estado' => $request->estado]);

        return back()->with('success', '✓ Estado actualizado correctamente.');
    }

    public function aprobarPrestamo(Request $request)
    {
        $request->validate([
            'id_prestamo' => 'required|exists:prestamos,id',
        ]);

        $prestamo = \App\Models\Prestamo::findOrFail($request->id_prestamo);
        $material = Material::findOrFail($prestamo->id_mat);

        $prestamo->update(['estado' => 'aprobado']);
        $material->update([
            'estado'      => 'prestado',
            'id_prestado' => $prestamo->id_us,
        ]);

        return back()->with('success', '✓ Préstamo aprobado correctamente.');
    }

    public function rechazarPrestamo(Request $request)
    {
        $request->validate([
            'id_prestamo' => 'required|exists:prestamos,id',
        ]);

        $prestamo = \App\Models\Prestamo::findOrFail($request->id_prestamo);
        $prestamo->update(['estado' => 'devuelto']);

        return back()->with('success', '✓ Solicitud rechazada.');
    }

    public function devolverMaterial(Request $request)
    {
        $request->validate([
            'id_mat' => 'required|exists:materiales,id',
        ]);

        $material = Material::findOrFail($request->id_mat);

        $prestamo = \App\Models\Prestamo::where('id_mat', $material->id)
            ->where('id_us', auth()->id())
            ->where('estado', 'aprobado')
            ->latest()
            ->first();

        if (!$prestamo) {
            return back()->withErrors(['material' => 'No tienes un préstamo aprobado de este material.']);
        }

        $prestamo->update([
            'fecha_devolucion' => now(),
            'estado'           => 'devuelto',
        ]);

        $material->update([
            'estado'      => 'disponible',
            'id_prestado' => null,
        ]);

        return back()->with('success', '✓ Material devuelto correctamente.');
    }

    public function movimientos()
    {
        $user = auth()->user();

        $todosMovimientos = \App\Models\Prestamo::with(['usuario', 'material'])
            ->orderBy('fecha', 'desc')
            ->get();

        $aprobados = $todosMovimientos->where('estado', 'aprobado');
        $rechazados = $todosMovimientos->where('estado', 'devuelto');
        $pendientes = $todosMovimientos->where('estado', 'pendiente');

        return view('movimientos-inventario', compact(
            'user', 'todosMovimientos', 'aprobados', 'rechazados', 'pendientes'
        ));
    }
}