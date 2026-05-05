<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario — PROMETE</title>
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
            <a href="{{ route('dashboard') }}" class="font-syne text-xl font-bold text-white hover:opacity-80 transition">
                Promet<span class="text-yellow-500">e</span>
                <span class="text-gray-500 font-normal text-base ml-2">/ Inventario</span>
            </a>
            <div class="flex items-center gap-5 ml-4">
                <a href="{{ route('economato') }}" class="text-gray-400 hover:text-yellow-500 transition text-sm uppercase tracking-widest">Economato</a>
                <a href="{{ route('transacciones') }}" class="text-gray-400 hover:text-yellow-500 transition text-sm uppercase tracking-widest">Transacciones</a>
                <a href="{{ route('inventario') }}" class="text-yellow-500 text-sm uppercase tracking-widest">Inventario</a>
            </div>
        </div>

        {{-- Avatar dropdown --}}
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
    </nav>

    <main class="px-8 py-8 max-w-7xl mx-auto">

        {{-- ── CABECERA ── --}}
        <div class="mb-8">
            <h2 class="font-syne text-3xl font-bold text-white">Inventario</h2>
            <p class="text-gray-400 text-sm mt-1">Gestión de materiales y movimientos de préstamo</p>
        </div>

        {{-- ── STATS ── --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-gray-900 border border-gray-800 p-5">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Total Materiales</p>
                <p class="font-syne text-3xl font-bold text-white">{{ $totalMateriales }}</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-5">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Disponibles</p>
                <p class="font-syne text-3xl font-bold text-green-400">{{ $disponibles }}</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-5">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Prestados</p>
                <p class="font-syne text-3xl font-bold text-yellow-500">{{ $prestados }}</p>
            </div>
        </div>

        {{-- ── SECCIÓN PRÉSTAMOS ── --}}
        <div class="bg-gray-900 border border-gray-800 mb-6">
            <div class="px-6 py-4 border-b border-gray-800">
                <h3 class="font-syne text-lg font-bold">Movimientos Recientes</h3>
                <p class="text-gray-500 text-xs mt-1">Últimos 20 préstamos registrados</p>
            </div>

            {{-- FUTURO: pestañas por tipo de material aquí --}}
            {{-- <div class="px-6 py-3 border-b border-gray-800 flex gap-4">
                <button class="text-yellow-500 text-xs uppercase tracking-wider border-b border-yellow-500 pb-1">Todos</button>
                <button class="text-gray-500 text-xs uppercase tracking-wider hover:text-gray-300">Amplís</button>
                <button class="text-gray-500 text-xs uppercase tracking-wider hover:text-gray-300">Baterías</button>
            </div> --}}

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-widest">
                            <th class="text-left px-6 py-3">Fecha</th>
                            <th class="text-left px-6 py-3">Usuario</th>
                            <th class="text-left px-6 py-3">Material</th>
                            <th class="text-left px-6 py-3">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prestamos as $p)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                            <td class="px-6 py-4 text-gray-400">
                                {{ \Carbon\Carbon::parse($p->fecha)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-white">{{ $p->usuario->nombre }}</td>
                            <td class="px-6 py-4 font-medium text-white">{{ $p->material->nombre }}</td>
                            <td class="px-6 py-4">
                                @php $estado = $p->material->estado ?? 'desconocido'; @endphp
                                @if($estado === 'disponible')
                                    <span class="bg-green-900/30 text-green-400 text-xs px-3 py-1 uppercase tracking-wider">Disponible</span>
                                @elseif($estado === 'prestado')
                                    <span class="bg-yellow-900/30 text-yellow-400 text-xs px-3 py-1 uppercase tracking-wider">Prestado</span>
                                @elseif($estado === 'mantenimiento')
                                    <span class="bg-red-900/30 text-red-400 text-xs px-3 py-1 uppercase tracking-wider">Mantenimiento</span>
                                @else
                                    <span class="bg-gray-800 text-gray-500 text-xs px-3 py-1 uppercase tracking-wider">{{ $estado }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-600">
                                No hay movimientos registrados aún.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── SECCIÓN INVENTARIO ── --}}
        <div class="bg-gray-900 border border-gray-800">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <div>
                    <h3 class="font-syne text-lg font-bold">Catálogo de Materiales</h3>
                    <p class="text-gray-500 text-xs mt-1">Todos los materiales registrados en el sistema</p>
                </div>

                {{-- FUTURO: pestañas por categoría --}}
                <div class="flex gap-2" id="pestanas">
                    <button class="text-yellow-500 text-xs uppercase tracking-wider border border-yellow-500/30 px-3 py-1">
                        Todos
                    </button>
                    {{-- Se añadirán más pestañas aquí según los tipos --}}
                </div>
            </div>

            {{-- FUTURO: contenido por pestaña aquí --}}
            <div id="contenido-inventario">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="tabla-materiales">
                        <thead>
                            <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-widest">
                                <th class="text-left px-6 py-3">ID</th>
                                <th class="text-left px-6 py-3">Nombre</th>
                                <th class="text-left px-6 py-3">Tipo</th>
                                <th class="text-left px-6 py-3">Asignado a</th>
                                <th class="text-left px-6 py-3">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($materiales as $m)
                            <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition" data-tipo="{{ $m->tipo }}">
                                <td class="px-6 py-4 text-gray-500">#{{ $m->id }}</td>
                                <td class="px-6 py-4 font-medium text-white">{{ $m->nombre }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ $m->tipo }}</td>
                                <td class="px-6 py-4 text-white">{{ $m->usuario->nombre ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @if($m->estado === 'disponible')
                                        <span class="bg-green-900/30 text-green-400 text-xs px-3 py-1 uppercase tracking-wider">Disponible</span>
                                    @elseif($m->estado === 'prestado')
                                        <span class="bg-yellow-900/30 text-yellow-400 text-xs px-3 py-1 uppercase tracking-wider">Prestado</span>
                                    @elseif($m->estado === 'mantenimiento')
                                        <span class="bg-red-900/30 text-red-400 text-xs px-3 py-1 uppercase tracking-wider">Mantenimiento</span>
                                    @else
                                        <span class="bg-gray-800 text-gray-500 text-xs px-3 py-1 uppercase tracking-wider">{{ $m->estado ?? '—' }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-600">
                                    No hay materiales registrados aún.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($materiales->count() > 0)
            <div class="px-6 py-3 border-t border-gray-800 text-gray-600 text-xs">
                {{ $materiales->count() }} {{ $materiales->count() === 1 ? 'material' : 'materiales' }} en total
            </div>
            @endif
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

        // FUTURO: lógica de pestañas por tipo
        // function filtrarPorTipo(tipo) {
        //     document.querySelectorAll('#tabla-materiales tbody tr').forEach(fila => {
        //         fila.style.display = tipo === 'todos' || fila.dataset.tipo === tipo ? '' : 'none';
        //     });
        // }
    </script>

</body>
</html>