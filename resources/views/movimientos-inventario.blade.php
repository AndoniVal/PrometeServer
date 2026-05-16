<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimientos Inventario — PROMETE</title>
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
            <a href="{{ route('inventario') }}" class="font-syne text-xl font-bold text-white hover:opacity-80 transition">
                Promet<span class="text-yellow-500">e</span>
                <span class="text-gray-500 font-normal text-base ml-2">/ Movimientos</span>
            </a>
            <div class="flex items-center gap-5 ml-4">
                <a href="{{ route('economato') }}" class="text-gray-400 hover:text-yellow-500 transition text-sm uppercase tracking-widest">Economato</a>
                <a href="{{ route('transacciones') }}" class="text-gray-400 hover:text-yellow-500 transition text-sm uppercase tracking-widest">Transacciones</a>
                <a href="{{ route('inventario') }}" class="text-gray-400 hover:text-yellow-500 transition text-sm uppercase tracking-widest">Inventario</a>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-1.5 border border-gray-800 px-3 py-1.5">
                <svg class="w-3.5 h-3.5 {{ $user->saldo < 0 ? 'text-red-400' : 'text-yellow-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <span class="text-xs font-medium font-syne {{ $user->saldo < 0 ? 'text-red-400' : 'text-yellow-500' }}">
                    {{ number_format($user->saldo, 2) }}€
                </span>
            </div>
            <div class="relative" id="user-menu">
                <button onclick="toggleDropdown()" class="flex items-center gap-3 focus:outline-none group">
                    <div class="w-9 h-9 rounded-full overflow-hidden border-2 border-gray-700 group-hover:border-yellow-500 transition">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->nombre }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-yellow-500/20 flex items-center justify-center">
                                <span class="text-yellow-500 text-xs font-bold font-syne">{{ strtoupper(substr($user->nombre, 0, 2)) }}</span>
                            </div>
                        @endif
                    </div>
                    <span class="text-gray-400 text-sm hidden md:block">{{ $user->nombre }}</span>
                    <svg id="chevron" class="w-4 h-4 text-gray-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="dropdown" class="hidden absolute right-0 mt-3 w-52 bg-gray-900 border border-gray-700 shadow-xl z-50">
                    <div class="px-4 py-3 border-b border-gray-800">
                        <p class="text-white text-sm font-medium">{{ $user->nombre }}</p>
                        <p class="text-gray-500 text-xs mt-0.5">{{ ucfirst($user->rol) }}</p>
                    </div>
                    <div class="py-1">
                        <a href="{{ route('mi-cuenta') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-800 hover:text-yellow-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Mi cuenta
                        </a>
                    </div>
                    <div class="border-t border-gray-800 py-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:bg-gray-800 hover:text-red-300 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="px-8 py-8 max-w-7xl mx-auto">

        {{-- Cabecera --}}
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h2 class="font-syne text-3xl font-bold text-white">Movimientos de Inventario</h2>
                <p class="text-gray-400 text-sm mt-1">Historial completo de préstamos — Vista administrador</p>
            </div>
            <a href="{{ route('inventario') }}" class="text-gray-400 hover:text-yellow-500 text-sm uppercase tracking-widest transition">← Volver al inventario</a>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-gray-900 border border-gray-800 p-5">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Total movimientos</p>
                <p class="font-syne text-3xl font-bold text-white">{{ $todosMovimientos->count() }}</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-5">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Pendientes</p>
                <p class="font-syne text-3xl font-bold text-gray-400">{{ $pendientes->count() }}</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-5">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Aprobados</p>
                <p class="font-syne text-3xl font-bold text-yellow-500">{{ $aprobados->count() }}</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-5">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Devueltos</p>
                <p class="font-syne text-3xl font-bold text-green-400">{{ $rechazados->count() }}</p>
            </div>
        </div>

        {{-- Buscador --}}
        <div class="flex justify-end mb-4">
            <input type="text" id="buscador" placeholder="Buscar usuario o material..."
                onkeyup="filtrarTabla()"
                class="bg-gray-800 border border-gray-700 text-gray-200 text-sm px-4 py-2 focus:outline-none focus:border-yellow-500 w-72">
        </div>

        {{-- ── TODOS LOS MOVIMIENTOS ── --}}
        <div class="bg-gray-900 border border-gray-800 mb-6">
            <div class="px-6 py-4 border-b border-gray-800">
                <h3 class="font-syne text-lg font-bold">Todos los Movimientos</h3>
                <p class="text-gray-500 text-xs mt-0.5">Historial completo ordenado por fecha</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="tabla-movimientos">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-widest">
                            <th class="text-left px-6 py-3">Fecha</th>
                            <th class="text-left px-6 py-3">Usuario</th>
                            <th class="text-left px-6 py3">Material</th>
                            <th class="text-left px-6 py-3">Estado</th>
                            <th class="text-left px-6 py-3">Devolución</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todosMovimientos as $p)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                            <td class="px-6 py-4 text-gray-400">{{ \Carbon\Carbon::parse($p->fecha)->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-white font-medium">{{ $p->usuario->nombre }}</td>
                            <td class="px-6 py-4 text-white">{{ $p->nombre_material }}</td>
                            <td class="px-6 py-4">
                                @if($p->estado === 'pendiente')
                                    <span class="bg-gray-800 text-gray-400 text-xs px-3 py-1 uppercase tracking-wider">Pendiente</span>
                                @elseif($p->estado === 'aprobado')
                                    <span class="bg-yellow-900/30 text-yellow-400 text-xs px-3 py-1 uppercase tracking-wider">Aprobado</span>
                                @else
                                    <span class="bg-green-900/30 text-green-400 text-xs px-3 py-1 uppercase tracking-wider">Devuelto</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-400">
                                {{ $p->fecha_devolucion ? \Carbon\Carbon::parse($p->fecha_devolucion)->format('d/m/Y H:i') : '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-600">No hay movimientos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($todosMovimientos->count() > 0)
            <div class="px-6 py-3 border-t border-gray-800 text-gray-600 text-xs">
                {{ $todosMovimientos->count() }} movimientos en total
            </div>
            @endif
        </div>

        {{-- ── DOS DIVS: APROBADOS Y RECHAZADOS ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Aprobados --}}
            <div class="bg-gray-900 border border-gray-800">
                <div class="px-6 py-4 border-b border-gray-800 flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-yellow-500 flex-shrink-0"></span>
                    <div>
                        <h3 class="font-syne text-lg font-bold">Préstamos Aprobados</h3>
                        <p class="text-gray-500 text-xs mt-0.5">Actualmente en circulación</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-widest">
                                <th class="text-left px-6 py-3">Usuario</th>
                                <th class="text-left px-6 py-3">Material</th>
                                <th class="text-left px-6 py-3">Desde</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aprobados as $p)
                            <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                                <td class="px-6 py-4 text-white font-medium">{{ $p->usuario->nombre }}</td>
                                <td class="px-6 py-4 text-yellow-400">{{ $p->nombre_material }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ \Carbon\Carbon::parse($p->fecha)->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="px-6 py-8 text-center text-gray-600">No hay préstamos activos.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Devueltos --}}
            <div class="bg-gray-900 border border-gray-800">
                <div class="px-6 py-4 border-b border-gray-800 flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>
                    <div>
                        <h3 class="font-syne text-lg font-bold">Préstamos Devueltos</h3>
                        <p class="text-gray-500 text-xs mt-0.5">Historial de devoluciones</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-widest">
                                <th class="text-left px-6 py-3">Usuario</th>
                                <th class="text-left px-6 py-3">Material</th>
                                <th class="text-left px-6 py-3">Devuelto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rechazados as $p)
                            <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                                <td class="px-6 py-4 text-white font-medium">{{ $p->usuario->nombre }}</td>
                                <td class="px-6 py-4 text-green-400">{{ $p->nombre_material }}</td>
                                <td class="px-6 py-4 text-gray-400">
                                    {{ $p->fecha_devolucion ? \Carbon\Carbon::parse($p->fecha_devolucion)->format('d/m/Y') : '—' }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="px-6 py-8 text-center text-gray-600">No hay devoluciones registradas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
            if (menu && !menu.contains(e.target)) {
                document.getElementById('dropdown').classList.add('hidden');
                document.getElementById('chevron').style.transform = 'rotate(0deg)';
            }
        });

        function filtrarTabla() {
            const input = document.getElementById('buscador').value.toLowerCase();
            document.querySelectorAll('#tabla-movimientos tbody tr').forEach(fila => {
                fila.style.display = fila.textContent.toLowerCase().includes(input) ? '' : 'none';
            });
        }
    </script>

</body>
</html>