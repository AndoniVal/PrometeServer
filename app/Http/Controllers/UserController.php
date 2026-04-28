<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function store(Request $request)
    {
        $user = User::create($request->all());
        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json($user);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'Usuario eliminado']);
    }
    public function miCuenta()
{
    $user = auth()->user();
    return view('mi-cuenta', compact('user'));
}

public function actualizarCuenta(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'nombre' => 'required|string|max:255',
        'email'  => 'required|email|unique:users,email,' . $user->id,
        'edad'   => 'nullable|integer|min:1|max:120',
    ]);

    $user->update($request->only('nombre', 'email', 'edad'));

    return back()->with('success', '✓ Datos actualizados correctamente.');
}

public function cambiarPassword(Request $request)
{
    $request->validate([
        'password_actual' => 'required',
        'password_nuevo'  => 'required|min:8|confirmed',
    ]);

    $user = auth()->user();

    if (!\Hash::check($request->password_actual, $user->password)) {
        return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.']);
    }

    $user->update(['password' => \Hash::make($request->password_nuevo)]);

    return back()->with('success_pass', '✓ Contraseña actualizada correctamente.');
}
}
