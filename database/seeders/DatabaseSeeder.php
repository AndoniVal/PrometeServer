<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Material;
use App\Models\Producto;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario administrador
        $admin = User::create([
            'nombre'   => 'Admin',
            'edad'     => 25,
            'rol'      => 'administrador',
            'email'    => 'admin@promete.com',
            'password' => Hash::make('admin123'),
        ]);

        // Usuario normal
        $user = User::create([
            'nombre'   => 'user',
            'edad'     => 22,
            'rol'      => 'usuario',
            'email'    => 'user@promete.com',
            'password' => Hash::make('user123'),
        ]);

        // Materiales
        Material::create(['id_us' => $admin->id, 'nombre' => 'Micrófono Shure SM7B', 'tipo' => 'Micrófono', 'estado' => 'disponible']);
        Material::create(['id_us' => $admin->id, 'nombre' => 'Interface Focusrite', 'tipo' => 'Interface', 'estado' => 'disponible']);
        Material::create(['id_us' => $user->id,  'nombre' => 'Cable XLR 3m', 'tipo' => 'Cable', 'estado' => 'disponible']);

        // Productos economato
        Producto::create(['nombre' => 'Agua', 'tipo' => 'Bebida', 'stock' => 20]);
        Producto::create(['nombre' => 'Café', 'tipo' => 'Bebida', 'stock' => 10]);
        Producto::create(['nombre' => 'Cuerdas de guitarra', 'tipo' => 'Accesorio', 'stock' => 5]);
    }
}