<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — PROMETE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-syne { font-family: 'Syne', sans-serif; }
    </style>
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

    <main class="px-8 py-8 max-w-7xl mx-auto">

        {{-- ── CABECERA ── --}}
        <div class="mb-8">
            <h2 class="font-syne text-3xl font-bold text-white">Bienvenido, {{ $user->nombre }}</h2>
            <p class="text-gray-500 text-sm mt-1">Rol: <span class="text-yellow-500 capitalize">{{ $user->rol }}</span></p>
        </div>

        {{-- ── GRID PRINCIPAL ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ── ÚLTIMOS MOVIMIENTOS ── --}}
            <div class="lg:col-span-2 bg-gray-900 border border-gray-800">
                <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                    <div>
                        <h3 class="font-syne text-lg font-bold">Últimos Movimientos</h3>
                        <p class="text-gray-500 text-xs mt-0.5">Las 5 transacciones más recientes</p>
                    </div>
                    <a href="{{ route('transacciones') }}" class="text-yellow-500 hover:text-yellow-400 text-xs uppercase tracking-wider transition">Ver todos →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-widest">
                                <th class="text-left px-6 py-3">Fecha</th>
                                <th class="text-left px-6 py-3">Usuario</th>
                                <th class="text-left px-6 py-3">Producto</th>
                                <th class="text-left px-6 py-3">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosMovimientos as $t)
                            <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                                <td class="px-6 py-4 text-gray-400">{{ \Carbon\Carbon::parse($t->fecha)->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-white">{{ $t->usuario->nombre }}</td>
                                <td class="px-6 py-4 font-medium text-white">{{ $t->producto->nombre }}</td>
                                <td class="px-6 py-4 text-yellow-400 font-medium">{{ $t->cantidad }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-600">No hay movimientos registrados aún.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── ACCESOS RÁPIDOS ── --}}
            <div class="flex flex-col gap-4">
                <a href="{{ route('economato') }}" class="bg-gray-900 border border-gray-800 p-6 hover:border-yellow-500/40 transition group block">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-xl group-hover:bg-yellow-500/20 transition">🛒</div>
                        <div>
                            <p class="font-syne font-bold text-white">Economato</p>
                            <p class="text-gray-500 text-xs mt-0.5">Gestión de productos y compras</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('transacciones') }}" class="bg-gray-900 border border-gray-800 p-6 hover:border-yellow-500/40 transition group block">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-xl group-hover:bg-yellow-500/20 transition">📋</div>
                        <div>
                            <p class="font-syne font-bold text-white">Transacciones</p>
                            <p class="text-gray-500 text-xs mt-0.5">Historial de movimientos</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('inventario') }}" class="bg-gray-900 border border-gray-800 p-6 hover:border-yellow-500/40 transition group block">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-xl group-hover:bg-yellow-500/20 transition">📦</div>
                        <div>
                            <p class="font-syne font-bold text-white">Inventario</p>
                            <p class="text-gray-500 text-xs mt-0.5">Materiales y préstamos</p>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        {{-- ── DOS DIVS VACÍOS PARA FUTURO CONTENIDO ── --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="bg-gray-900 border border-gray-800 p-6 min-h-[180px] flex items-center justify-center">
                <p class="text-gray-700 text-xs uppercase tracking-widest">Próximamente</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-6 min-h-[180px] flex items-center justify-center">
                <p class="text-gray-700 text-xs uppercase tracking-widest">Próximamente</p>
            </div>
        </div>

    </main>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown');
            const chevron = document.getElementById('chevron');
            dropdown.classList.toggle('hidden');
            chevron.style.transform = dropdown.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        }

        document.addEventListener('click', function(e) {
            const menu = document.getElementById('user-menu');
            if (!menu.contains(e.target)) {
                document.getElementById('dropdown').classList.add('hidden');
                document.getElementById('chevron').style.transform = 'rotate(0deg)';
            }
        });
    </script>

</body>
</html>