<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta — PROMETE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-syne { font-family: 'Syne', sans-serif; }
    </style>
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen">

    {{-- ── NAVBAR ── --}}
    <nav class="bg-gray-900 border-b border-gray-800 px-8 py-4 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-6">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-yellow-500 transition text-sm uppercase tracking-widest">
                ← Volver
            </a>
            <span class="text-gray-700">|</span>
            <h1 class="font-syne text-xl font-bold text-white">
                Promet<span class="text-yellow-500">e</span>
                <span class="text-gray-500 font-normal text-base ml-2">/ Mi Cuenta</span>
            </h1>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-gray-400 text-sm">{{ $user->nombre }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-sm text-red-400 hover:text-red-300 transition">Cerrar sesión</button>
            </form>
        </div>
    </nav>

    <main class="px-8 py-8 max-w-3xl mx-auto">

        {{-- ── CABECERA ── --}}
        <div class="mb-8">
            <h2 class="font-syne text-3xl font-bold text-white">Mi Cuenta</h2>
            <p class="text-gray-400 text-sm mt-1">Gestiona tus datos personales y contraseña</p>
        </div>

        {{-- ── ALERTAS ── --}}
        @if(session('success'))
        <div class="mb-6 bg-green-900/30 border border-green-700 text-green-400 px-5 py-3 text-sm">
            {{ session('success') }}
        </div>
        @endif

        @if(session('success_pass'))
        <div class="mb-6 bg-green-900/30 border border-green-700 text-green-400 px-5 py-3 text-sm">
            {{ session('success_pass') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-red-900/30 border border-red-700 text-red-400 px-5 py-3 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        {{-- ── DATOS PERSONALES ── --}}
        <div class="bg-gray-900 border border-gray-800 mb-6">
            <div class="px-6 py-4 border-b border-gray-800">
                <h3 class="font-syne text-lg font-bold">Datos Personales</h3>
                <p class="text-gray-500 text-xs mt-1">Actualiza tu nombre, email y edad</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('mi-cuenta.actualizar') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}" required
                                class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500 transition">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Edad</label>
                            <input type="number" name="edad" value="{{ old('edad', $user->edad) }}" min="1" max="120"
                                class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500 transition">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500 transition">
                    </div>

                    {{-- Rol (solo lectura) --}}
                    <div class="mb-6">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Rol</label>
                        <div class="w-full bg-gray-800/50 border border-gray-700/50 text-gray-500 px-4 py-2.5 text-sm">
                            {{ ucfirst($user->rol) }}
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-yellow-500 text-gray-950 px-6 py-2.5 text-sm font-medium uppercase tracking-wider hover:bg-yellow-400 transition">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── CAMBIAR CONTRASEÑA ── --}}
        <div class="bg-gray-900 border border-gray-800">
            <div class="px-6 py-4 border-b border-gray-800">
                <h3 class="font-syne text-lg font-bold">Cambiar Contraseña</h3>
                <p class="text-gray-500 text-xs mt-1">Mínimo 8 caracteres</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('mi-cuenta.password') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Contraseña actual</label>
                        <input type="password" name="password_actual" required
                            class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500 transition">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Nueva contraseña</label>
                        <input type="password" name="password_nuevo" required
                            class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500 transition">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Confirmar nueva contraseña</label>
                        <input type="password" name="password_nuevo_confirmation" required
                            class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500 transition">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-yellow-500 text-gray-950 px-6 py-2.5 text-sm font-medium uppercase tracking-wider hover:bg-yellow-400 transition">
                            Actualizar contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </main>

</body>
</html>
