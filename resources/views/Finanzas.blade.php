<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finanzas — PROMETE</title>
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
            </a>
            <div class="flex items-center gap-5 ml-4">
                <a href="{{ route('economato') }}" class="text-gray-400 hover:text-yellow-500 transition text-sm uppercase tracking-widest">Economato</a>
                <a href="{{ route('transacciones') }}" class="text-gray-400 hover:text-yellow-500 transition text-sm uppercase tracking-widest">Transacciones</a>
                <a href="{{ route('inventario') }}" class="text-gray-400 hover:text-yellow-500 transition text-sm uppercase tracking-widest">Inventario</a>
                <a href="{{ route('finanzas') }}" class="text-yellow-500 text-sm uppercase tracking-widest">Finanzas</a>
            </div>
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
    </nav>

    <main class="px-8 py-8 max-w-7xl mx-auto">

        {{-- Cabecera --}}
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h2 class="font-syne text-3xl font-bold text-white">Finanzas</h2>
                <p class="text-gray-400 text-sm mt-1">Control económico del economato — Vista administrador</p>
            </div>
            <a href="{{ route('economato') }}" class="text-gray-400 hover:text-yellow-500 text-sm uppercase tracking-widest transition">← Volver al economato</a>
        </div>

        {{-- ── STATS GENERALES ── --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-gray-900 border border-gray-800 p-5">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Ingresos Totales</p>
                <p class="font-syne text-3xl font-bold text-green-400">{{ number_format($totalIngresos, 2) }}€</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-5">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Este Mes</p>
                <p class="font-syne text-3xl font-bold text-white">{{ number_format($ingresosMes, 2) }}€</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-5">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Transacciones</p>
                <p class="font-syne text-3xl font-bold text-white">{{ $totalTransacciones }}</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-5">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Usuarios con Deuda</p>
                <p class="font-syne text-3xl font-bold {{ $usuariosConDeuda > 0 ? 'text-red-400' : 'text-green-400' }}">
                    {{ $usuariosConDeuda }}
                </p>
            </div>
        </div>

        {{-- ── AVISO DEUDAS ── --}}
        @if($usuariosConDeuda > 0)
        <div class="mb-6 bg-red-900/20 border border-red-800 px-5 py-4 flex items-center gap-3">
            <span class="text-red-400 text-xl">⚠</span>
            <div>
                <p class="text-red-400 font-medium text-sm">Hay {{ $usuariosConDeuda }} {{ $usuariosConDeuda === 1 ? 'usuario' : 'usuarios' }} con deuda pendiente</p>
                <p class="text-red-600 text-xs mt-0.5">Revisa la tabla de gastos por usuario — Funcionalidad de "fiar" próximamente</p>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- ── GASTOS POR USUARIO ── --}}
            <div class="bg-gray-900 border border-gray-800">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h3 class="font-syne text-lg font-bold">Gastos por Usuario</h3>
                    <p class="text-gray-500 text-xs mt-0.5">Total acumulado de cada usuario</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-widest">
                                <th class="text-left px-6 py-3">Usuario</th>
                                <th class="text-left px-6 py-3">Compras</th>
                                <th class="text-left px-6 py-3">Total gastado</th>
                                <th class="text-left px-6 py-3">Deuda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gastosPorUsuario as $gasto)
                            <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                                <td class="px-6 py-4 text-white font-medium">{{ $gasto->nombre }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ $gasto->total_compras }}</td>
                                <td class="px-6 py-4 text-yellow-400 font-bold">{{ number_format($gasto->total_gastado, 2) }}€</td>
                                <td class="px-6 py-4">
                                    {{-- Placeholder para sistema de fiar --}}
                                    <span class="bg-gray-800 text-gray-600 text-xs px-2 py-0.5 uppercase tracking-wider">
                                        — Próximamente —
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-600">No hay datos aún.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── CONTROL DE INVENTARIO ── --}}
            <div class="bg-gray-900 border border-gray-800">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h3 class="font-syne text-lg font-bold">Control de Inventario</h3>
                    <p class="text-gray-500 text-xs mt-0.5">Estado actual del stock por producto</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-widest">
                                <th class="text-left px-6 py-3">Producto</th>
                                <th class="text-left px-6 py-3">Stock</th>
                                <th class="text-left px-6 py-3">Salidas</th>
                                <th class="text-left px-6 py-3">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($controlStock as $item)
                            <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                                <td class="px-6 py-4 text-white font-medium">{{ $item->nombre }}</td>
                                <td class="px-6 py-4 text-white">{{ $item->stock }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ $item->total_salidas ?? 0 }}</td>
                                <td class="px-6 py-4">
                                    @if($item->stock === 0)
                                        <span class="bg-red-900/30 text-red-400 text-xs px-2 py-0.5 uppercase tracking-wider">Sin stock</span>
                                    @elseif($item->stock < 10)
                                        <span class="bg-yellow-900/30 text-yellow-400 text-xs px-2 py-0.5 uppercase tracking-wider">Stock bajo</span>
                                    @else
                                        <span class="bg-green-900/30 text-green-400 text-xs px-2 py-0.5 uppercase tracking-wider">OK</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-600">No hay productos.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- ── PLACEHOLDER SISTEMA DE FIAR ── --}}
        <div class="bg-gray-900 border border-gray-800 border-dashed p-8 text-center">
            <p class="text-gray-600 text-2xl mb-3">💳</p>
            <p class="font-syne text-lg font-bold text-gray-600">Sistema de Fiar</p>
            <p class="text-gray-700 text-sm mt-2">Próximamente — Control de deudas y pagos pendientes por usuario</p>
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
    </script>

</body>
</html>