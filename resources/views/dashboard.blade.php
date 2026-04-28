<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

$algo = 1;

    {{-- Navbar --}}
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">Panel de Control</h1>
        <div class="flex items-center gap-4">
            <span class="text-gray-600">Hola, {{ $user->nombre }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </nav>

    {{-- Contenido --}}
    <main class="p-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Bienvenido, {{ $user->nombre }}</h2>
            <p class="text-gray-500">Rol: <span class="font-medium capitalize">{{ $user->rol }}</span></p>
        </div>
    </main>

</body>
</html>