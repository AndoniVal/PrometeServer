<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string',
            'edad'     => 'required|integer',
            'rol'      => 'sometimes|string|in:usuario,administrador',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nombre'   => 'sometimes|string',
            'edad'     => 'sometimes|integer',
            'rol'      => 'sometimes|string|in:usuario,administrador',
            'email'    => 'sometimes|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|string|min:6',
        ]);

        $data = $request->all();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado']);
    }
}