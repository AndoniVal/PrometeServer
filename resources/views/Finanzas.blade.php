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
        <div class="flex items-center gap-4">
            {{-- Saldo --}}
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

        @if(session('success'))
        <div class="mb-6 bg-green-900/30 border border-green-700 text-green-400 px-5 py-3 text-sm">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="mb-6 bg-red-900/30 border border-red-700 text-red-400 px-5 py-3 text-sm">{{ $errors->first() }}</div>
        @endif

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
                <p class="text-red-600 text-xs mt-0.5">Revisa la tabla de saldos — Límite de deuda: -20€</p>
            </div>
        </div>
        @endif

        {{-- ── GRID PRINCIPAL ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- ── SALDOS POR USUARIO ── --}}
            <div class="bg-gray-900 border border-gray-800">
                <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                    <div>
                        <h3 class="font-syne text-lg font-bold">Saldo de Usuarios</h3>
                        <p class="text-gray-500 text-xs mt-0.5">Estado de cartera de cada usuario</p>
                    </div>
                    <button onclick="document.getElementById('modal-saldo').classList.remove('hidden')"
                        class="bg-yellow-500 text-gray-950 px-4 py-1.5 text-xs font-medium uppercase tracking-wider hover:bg-yellow-400 transition">
                        + Gestionar Saldo
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-widest">
                                <th class="text-left px-6 py-3">Usuario</th>
                                <th class="text-left px-6 py-3">Compras</th>
                                <th class="text-left px-6 py-3">Gastado</th>
                                <th class="text-left px-6 py-3">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gastosPorUsuario as $gasto)
                            <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                                <td class="px-6 py-4 text-white font-medium">{{ $gasto->nombre }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ $gasto->total_compras ?? 0 }}</td>
                                <td class="px-6 py-4 text-yellow-400 font-bold">{{ number_format($gasto->total_gastado ?? 0, 2) }}€</td>
                                <td class="px-6 py-4">
                                    <span class="font-syne font-bold {{ $gasto->saldo < 0 ? 'text-red-400' : 'text-green-400' }}">
                                        {{ number_format($gasto->saldo, 2) }}€
                                    </span>
                                    @if($gasto->saldo < 0)
                                    <span class="ml-2 text-red-600 text-xs">⚠ deuda</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-600">No hay usuarios aún.</td></tr>
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

        {{-- ── HISTORIAL DE MOVIMIENTOS DE SALDO ── --}}
        <div class="bg-gray-900 border border-gray-800 mb-6">
            <div class="px-6 py-4 border-b border-gray-800">
                <h3 class="font-syne text-lg font-bold">Historial de Movimientos de Saldo</h3>
                <p class="text-gray-500 text-xs mt-0.5">Últimos 20 movimientos registrados</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-widest">
                            <th class="text-left px-6 py-3">Fecha</th>
                            <th class="text-left px-6 py-3">Usuario</th>
                            <th class="text-left px-6 py-3">Tipo</th>
                            <th class="text-left px-6 py-3">Cantidad</th>
                            <th class="text-left px-6 py-3">Comentario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimientosSaldo as $m)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                            <td class="px-6 py-4 text-gray-400">{{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-white font-medium">{{ $m->usuario->nombre }}</td>
                            <td class="px-6 py-4">
                                @if($m->tipo === 'ingreso')
                                    <span class="bg-green-900/30 text-green-400 text-xs px-3 py-1 uppercase tracking-wider">+ Ingreso</span>
                                @else
                                    <span class="bg-red-900/30 text-red-400 text-xs px-3 py-1 uppercase tracking-wider">− Descuento</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold {{ $m->tipo === 'ingreso' ? 'text-green-400' : 'text-red-400' }}">
                                {{ $m->tipo === 'ingreso' ? '+' : '-' }}{{ number_format($m->cantidad, 2) }}€
                            </td>
                            <td class="px-6 py-4 text-gray-400">{{ $m->comentario ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-600">No hay movimientos de saldo aún.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── PLACEHOLDER SISTEMA DE FIAR ── --}}
        <div class="bg-gray-900 border border-gray-800 border-dashed p-8 text-center">
            <p class="text-gray-600 text-2xl mb-3">💳</p>
            <p class="font-syne text-lg font-bold text-gray-600">Sistema de Fiar</p>
            <p class="text-gray-700 text-sm mt-2">Próximamente — Control de deudas y pagos pendientes por usuario</p>
        </div>

    </main>

    {{-- ── MODAL GESTIONAR SALDO ── --}}
    <div id="modal-saldo" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4">
        <div class="bg-gray-900 border border-gray-700 w-full max-w-md">

            {{-- Tabs --}}
            <div class="flex border-b border-gray-800">
                <button id="tab-ingreso" onclick="cambiarTabSaldo('ingreso')"
                    class="flex-1 px-6 py-4 text-sm font-medium uppercase tracking-wider transition border-b-2 border-green-500 text-green-400">
                    + Añadir Saldo
                </button>
                <button id="tab-descuento" onclick="cambiarTabSaldo('descuento')"
                    class="flex-1 px-6 py-4 text-sm font-medium uppercase tracking-wider transition border-b-2 border-transparent text-gray-500 hover:text-gray-300">
                    − Descontar Saldo
                </button>
                <button onclick="document.getElementById('modal-saldo').classList.add('hidden')"
                    class="px-4 text-gray-500 hover:text-white transition">✕</button>
            </div>

            {{-- Contenido ingreso --}}
            <div id="contenido-ingreso" class="p-6">
                <form method="POST" action="{{ route('finanzas.saldo') }}">
                    @csrf
                    <input type="hidden" name="tipo" value="ingreso">
                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Usuario</label>
                        <select name="id_us" required class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-green-500">
                            <option value="">— Selecciona un usuario —</option>
                            @foreach($usuarios as $u)
                            <option value="{{ $u->id }}">{{ $u->nombre }} (saldo: {{ number_format($u->saldo, 2) }}€)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Cantidad (€)</label>
                        <input type="number" name="cantidad" min="0.01" step="0.01" required
                            class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-green-500">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Comentario</label>
                        <textarea name="comentario" rows="2" placeholder="Motivo del ingreso..."
                            class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-green-500 resize-none"></textarea>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('modal-saldo').classList.add('hidden')"
                            class="px-5 py-2.5 text-sm text-gray-400 border border-gray-700 hover:border-gray-500 transition uppercase tracking-wider">Cancelar</button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm bg-green-600 text-white font-medium hover:bg-green-500 transition uppercase tracking-wider">Añadir</button>
                    </div>
                </form>
            </div>

            {{-- Contenido descuento --}}
            <div id="contenido-descuento" class="p-6 hidden">
                <form method="POST" action="{{ route('finanzas.saldo') }}">
                    @csrf
                    <input type="hidden" name="tipo" value="descuento">
                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Usuario</label>
                        <select name="id_us" required class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-red-500">
                            <option value="">— Selecciona un usuario —</option>
                            @foreach($usuarios as $u)
                            <option value="{{ $u->id }}">{{ $u->nombre }} (saldo: {{ number_format($u->saldo, 2) }}€)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Cantidad (€)</label>
                        <input type="number" name="cantidad" min="0.01" step="0.01" required
                            class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-red-500">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Comentario</label>
                        <textarea name="comentario" rows="2" placeholder="Motivo del descuento..."
                            class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-red-500 resize-none"></textarea>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('modal-saldo').classList.add('hidden')"
                            class="px-5 py-2.5 text-sm text-gray-400 border border-gray-700 hover:border-gray-500 transition uppercase tracking-wider">Cancelar</button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm bg-red-600 text-white font-medium hover:bg-red-500 transition uppercase tracking-wider">Descontar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

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

        function cambiarTabSaldo(tab) {
            const esIngreso = tab === 'ingreso';
            document.getElementById('contenido-ingreso').classList.toggle('hidden', !esIngreso);
            document.getElementById('contenido-descuento').classList.toggle('hidden', esIngreso);
            document.getElementById('tab-ingreso').className = `flex-1 px-6 py-4 text-sm font-medium uppercase tracking-wider transition border-b-2 ${esIngreso ? 'border-green-500 text-green-400' : 'border-transparent text-gray-500 hover:text-gray-300'}`;
            document.getElementById('tab-descuento').className = `flex-1 px-6 py-4 text-sm font-medium uppercase tracking-wider transition border-b-2 ${!esIngreso ? 'border-red-500 text-red-400' : 'border-transparent text-gray-500 hover:text-gray-300'}`;
        }
    </script>

</body>
</html>