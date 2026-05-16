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
            </a>
            <div class="flex items-center gap-5 ml-4">
                <a href="{{ route('economato') }}" class="text-gray-400 hover:text-yellow-500 transition text-sm uppercase tracking-widest">Economato</a>
                <a href="{{ route('transacciones') }}" class="text-gray-400 hover:text-yellow-500 transition text-sm uppercase tracking-widest">Transacciones</a>
                <a href="{{ route('inventario') }}" class="text-yellow-500 text-sm uppercase tracking-widest">Inventario</a>
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

        @if(session('success'))
        <div class="mb-6 bg-green-900/30 border border-green-700 text-green-400 px-5 py-3 text-sm">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="mb-6 bg-red-900/30 border border-red-700 text-red-400 px-5 py-3 text-sm">{{ $errors->first() }}</div>
        @endif

        {{-- ── CABECERA ── --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="font-syne text-3xl font-bold text-white">Inventario</h2>
                <p class="text-gray-400 text-sm mt-1">Materiales y equipamiento del servicio</p>
            </div>
            @if(Auth::user()->rol === 'administrador')
            <div class="flex gap-3 items-center">
                @if($solicitudesPendientes->count() > 0)
                <button onclick="document.getElementById('modal-solicitudes').classList.remove('hidden')"
                    class="bg-yellow-500 text-gray-950 px-5 py-2.5 text-sm font-medium uppercase tracking-wider hover:bg-yellow-400 transition relative">
                    📋 Solicitudes
                    <span class="ml-2 bg-gray-950 text-yellow-500 text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $solicitudesPendientes->count() }}</span>
                </button>
                @endif
                <button onclick="document.getElementById('modal-admin-inv').classList.remove('hidden')"
                    class="bg-gray-800 border border-gray-700 text-gray-300 px-5 py-2.5 text-sm font-medium uppercase tracking-wider hover:border-yellow-500 hover:text-yellow-500 transition">
                    ⚙ Administrar
                </button>
            </div>
            @endif
        </div>

        {{-- ── STATS ── --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-gray-900 border border-gray-800 p-5">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Total</p>
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
            <div class="bg-gray-900 border border-gray-800 p-5">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Mantenimiento</p>
                <p class="font-syne text-3xl font-bold text-red-400">{{ $mantenimiento }}</p>
            </div>
        </div>

        {{-- ── GRID DE MATERIALES ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-8">
            @forelse($materiales as $material)
            <div onclick="abrirModalMaterial(
                    {{ $material->id }},
                    '{{ addslashes($material->nombre) }}',
                    '{{ addslashes($material->descripcion ?? '') }}',
                    '{{ addslashes($material->tipo) }}',
                    '{{ $material->estado }}',
                    '{{ $material->imagen ? asset('storage/' . $material->imagen) : '' }}',
                    '{{ addslashes($material->usuario->nombre ?? '') }}',
                    '{{ addslashes($material->usuarioPrestado->nombre ?? '') }}'
                )"
                class="bg-gray-900 border border-gray-800 hover:border-yellow-500/40 transition cursor-pointer group">
                <div class="aspect-square bg-gray-800 overflow-hidden relative">
                    @if($material->imagen)
                        <img src="{{ asset('storage/' . $material->imagen) }}" alt="{{ $material->nombre }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-700">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                    @if($material->estado === 'prestado')
                    <div class="absolute top-2 left-2 bg-yellow-900/80 text-yellow-300 text-xs px-2 py-0.5 uppercase tracking-wider">Prestado</div>
                    @elseif($material->estado === 'mantenimiento')
                    <div class="absolute top-2 left-2 bg-red-900/80 text-red-300 text-xs px-2 py-0.5 uppercase tracking-wider">Mantenimiento</div>
                    @endif
                </div>
                <div class="p-4">
                    <p class="font-syne font-bold text-white text-sm mb-1">{{ $material->nombre }}</p>
                    <p class="text-gray-500 text-xs mb-1">{{ $material->tipo }}</p>
                    <p class="text-gray-600 text-xs mb-2">Propietario: {{ $material->usuario->nombre ?? '—' }}</p>
                    @if($material->estado === 'disponible')
                        <span class="bg-green-900/30 text-green-400 text-xs px-2 py-0.5 uppercase tracking-wider">Disponible</span>
                    @elseif($material->estado === 'prestado')
                        <span class="bg-yellow-900/30 text-yellow-400 text-xs px-2 py-0.5 uppercase tracking-wider">{{ $material->usuarioPrestado->nombre ?? 'Prestado' }}</span>
                    @else
                        <span class="bg-red-900/30 text-red-400 text-xs px-2 py-0.5 uppercase tracking-wider">Mantenimiento</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-4 py-20 text-center text-gray-600">
                <p class="text-4xl mb-3">📦</p>
                <p>No hay materiales registrados aún.</p>
            </div>
            @endforelse
        </div>

        {{-- ── ÚLTIMOS MOVIMIENTOS ── --}}
        <div class="bg-gray-900 border border-gray-800">
            <div class="px-6 py-4 border-b border-gray-800">
                <h3 class="font-syne text-lg font-bold">
                    {{ Auth::user()->rol === 'administrador' ? 'Últimos Movimientos (todos)' : 'Mis Últimos Préstamos' }}
                </h3>
                <p class="text-gray-500 text-xs mt-0.5">Historial de préstamos y devoluciones</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-widest">
                            <th class="text-left px-6 py-3">Fecha</th>
                            @if(Auth::user()->rol === 'administrador')
                            <th class="text-left px-6 py-3">Usuario</th>
                            @endif
                            <th class="text-left px-6 py-3">Material</th>
                            <th class="text-left px-6 py-3">Estado</th>
                            <th class="text-left px-6 py-3">Devolución</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimosPrestamos as $p)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                            <td class="px-6 py-4 text-gray-400">{{ \Carbon\Carbon::parse($p->fecha)->format('d/m/Y H:i') }}</td>
                            @if(Auth::user()->rol === 'administrador')
                            <td class="px-6 py-4 text-white">{{ $p->usuario->nombre }}</td>
                            @endif
                            <td class="px-6 py-4 font-medium text-white">{{ $p->nombre_material }}</td>
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
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-600">No hay movimientos registrados aún.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    {{-- ── MODAL DETALLE MATERIAL ── --}}
    <div id="modal-material" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4">
        <div class="bg-gray-900 border border-gray-700 w-full max-w-2xl">
            <div class="aspect-video bg-gray-800 overflow-hidden relative">
                <img id="mat-img" src="" alt="" class="w-full h-full object-cover">
                <div id="mat-img-placeholder" class="hidden absolute inset-0 flex items-center justify-center text-gray-700">
                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <button onclick="cerrarModalMaterial()" class="absolute top-3 right-3 bg-black/50 text-white hover:bg-black/80 transition w-8 h-8 flex items-center justify-center">✕</button>
                <div id="mat-estado-badge" class="absolute top-3 left-3 text-xs px-3 py-1 uppercase tracking-wider"></div>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 id="mat-nombre" class="font-syne text-2xl font-bold text-white"></h3>
                        <p id="mat-tipo" class="text-gray-500 text-sm mt-0.5"></p>
                    </div>
                </div>
                <p id="mat-descripcion" class="text-gray-400 text-sm mb-2"></p>
                <p id="mat-propietario" class="text-gray-600 text-xs mb-1"></p>
                <p id="mat-asignado" class="text-gray-500 text-xs mb-6"></p>

                <form method="POST" action="{{ route('inventario.prestar') }}" id="form-prestar" class="hidden">
                    @csrf
                    <input type="hidden" name="id_mat" id="mat-id-prestar">
                    <button type="submit" class="w-full bg-yellow-500 text-gray-950 py-2.5 text-sm font-medium uppercase tracking-wider hover:bg-yellow-400 transition">
                        Solicitar Préstamo
                    </button>
                </form>

                <form method="POST" action="{{ route('inventario.devolver') }}" id="form-devolver" class="hidden">
                    @csrf
                    <input type="hidden" name="id_mat" id="mat-id-devolver">
                    <button type="submit" class="w-full bg-green-600 text-white py-2.5 text-sm font-medium uppercase tracking-wider hover:bg-green-500 transition">
                        Devolver Material
                    </button>
                </form>

                <div id="mat-pendiente-msg" class="hidden text-center text-gray-500 text-sm py-2">
                    Ya tienes una solicitud pendiente para este material.
                </div>
            </div>
        </div>
    </div>

    {{-- ── MODAL SOLICITUDES PENDIENTES (solo admin) ── --}}
    @if(Auth::user()->rol === 'administrador')
    <div id="modal-solicitudes" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4">
        <div class="bg-gray-900 border border-gray-700 w-full max-w-2xl max-h-screen overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center sticky top-0 bg-gray-900">
                <h3 class="font-syne text-xl font-bold">📋 Solicitudes Pendientes</h3>
                <button onclick="document.getElementById('modal-solicitudes').classList.add('hidden')" class="text-gray-500 hover:text-white transition">✕</button>
            </div>
            <div class="p-6">
                @forelse($solicitudesPendientes as $s)
                <div class="bg-gray-800 border border-gray-700 px-5 py-4 mb-3">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="text-white font-medium">{{ $s->usuario->nombre }}</p>
                            <p class="text-yellow-500 text-sm mt-0.5">{{ $s->nombre_material }}</p>
                            <p class="text-gray-500 text-xs mt-0.5">{{ \Carbon\Carbon::parse($s->fecha)->format('d/m/Y H:i') }}</p>
                        </div>
                        <span class="bg-gray-700 text-gray-400 text-xs px-2 py-0.5 uppercase">Pendiente</span>
                    </div>
                    <div class="flex gap-3">
                        <form method="POST" action="{{ route('inventario.aprobar') }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="id_prestamo" value="{{ $s->id }}">
                            <button type="submit" class="w-full bg-green-600 text-white py-2 text-xs font-medium uppercase tracking-wider hover:bg-green-500 transition">
                                ✓ Aprobar
                            </button>
                        </form>
                        <form method="POST" action="{{ route('inventario.rechazar') }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="id_prestamo" value="{{ $s->id }}">
                            <button type="submit" class="w-full bg-red-600 text-white py-2 text-xs font-medium uppercase tracking-wider hover:bg-red-500 transition">
                                ✕ Rechazar
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-gray-600 text-center py-8">No hay solicitudes pendientes.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── MODAL ADMINISTRAR INVENTARIO ── --}}
    <div id="modal-admin-inv" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4">
        <div class="bg-gray-900 border border-gray-700 w-full max-w-3xl max-h-screen overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center sticky top-0 bg-gray-900 z-10">
                <h3 class="font-syne text-xl font-bold">⚙ Administrar Inventario</h3>
                <button onclick="document.getElementById('modal-admin-inv').classList.add('hidden')" class="text-gray-500 hover:text-white transition">✕</button>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col gap-3">
                    <h4 class="text-gray-500 text-xs uppercase tracking-widest mb-1">Acciones</h4>
                    <button onclick="abrirSubModalInv('modal-nuevo-material')"
                        class="w-full bg-gray-800 border border-gray-700 text-left px-5 py-4 hover:border-yellow-500 hover:text-yellow-500 transition">
                        <p class="font-medium">+ Añadir material</p>
                        <p class="text-gray-500 text-xs mt-0.5">Registrar un nuevo material en el inventario</p>
                    </button>
                    <button onclick="abrirSubModalInv('modal-eliminar-material')"
                        class="w-full bg-gray-800 border border-gray-700 text-left px-5 py-4 hover:border-red-500 hover:text-red-400 transition">
                        <p class="font-medium">🗑 Eliminar material</p>
                        <p class="text-gray-500 text-xs mt-0.5">Eliminar un material del inventario</p>
                    </button>
                    <button onclick="abrirSubModalInv('modal-cambiar-estado')"
                        class="w-full bg-gray-800 border border-gray-700 text-left px-5 py-4 hover:border-yellow-500 hover:text-yellow-500 transition">
                        <p class="font-medium">🔄 Cambiar estado</p>
                        <p class="text-gray-500 text-xs mt-0.5">Disponible, prestado o mantenimiento</p>
                    </button>
                    <a href="{{ route('inventario.movimientos') }}"
                        class="w-full bg-gray-800 border border-gray-700 text-left px-5 py-4 hover:border-yellow-500 hover:text-yellow-500 transition block">
                        <p class="font-medium">📊 Ver movimientos</p>
                        <p class="text-gray-500 text-xs mt-0.5">Historial completo de préstamos y devoluciones</p>
                    </a>
                </div>
                <div>
                    <h4 class="text-gray-500 text-xs uppercase tracking-widest mb-3">Últimos movimientos</h4>
                    <div class="flex flex-col gap-2 max-h-80 overflow-y-auto">
                        @forelse($ultimosPrestamos as $p)
                        <div class="bg-gray-800 border border-gray-700/50 px-4 py-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-white text-sm font-medium">{{ $p->usuario->nombre }}</p>
                                    <p class="text-gray-500 text-xs mt-0.5">{{ $p->nombre_material }}</p>
                                </div>
                                <div class="text-right">
                                    @if($p->estado === 'pendiente')
                                        <span class="bg-gray-700 text-gray-400 text-xs px-2 py-0.5 uppercase">Pendiente</span>
                                    @elseif($p->estado === 'aprobado')
                                        <span class="bg-yellow-900/30 text-yellow-400 text-xs px-2 py-0.5 uppercase">Aprobado</span>
                                    @else
                                        <span class="bg-green-900/30 text-green-400 text-xs px-2 py-0.5 uppercase">Devuelto</span>
                                    @endif
                                    <p class="text-gray-600 text-xs mt-1">{{ \Carbon\Carbon::parse($p->fecha)->format('d/m H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-600 text-sm text-center py-8">No hay movimientos aún.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Submodal: Nuevo material --}}
    <div id="modal-nuevo-material" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-[60] p-4">
        <div class="bg-gray-900 border border-gray-700 w-full max-w-md">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="font-syne text-xl font-bold">Nuevo Material</h3>
                <button onclick="document.getElementById('modal-nuevo-material').classList.add('hidden')" class="text-gray-500 hover:text-white transition">✕</button>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('materiales.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Nombre</label>
                        <input type="text" name="nombre" required class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Tipo / Categoría</label>
                        <input type="text" name="tipo" required class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Descripción</label>
                        <input type="text" name="descripcion" class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Propietario</label>
                        <select name="id_us" required class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                            <option value="">— Selecciona un usuario —</option>
                            @foreach($usuarios as $u)
                            <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Imagen</label>
                        <input type="file" name="imagen" accept="image/*" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-medium file:uppercase file:bg-yellow-500 file:text-gray-950 hover:file:bg-yellow-400 file:cursor-pointer">
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('modal-nuevo-material').classList.add('hidden')" class="px-5 py-2.5 text-sm text-gray-400 border border-gray-700 hover:border-gray-500 transition uppercase tracking-wider">Cancelar</button>
                        <button type="submit" class="px-5 py-2.5 text-sm bg-yellow-500 text-gray-950 font-medium hover:bg-yellow-400 transition uppercase tracking-wider">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Submodal: Eliminar material --}}
    <div id="modal-eliminar-material" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-[60] p-4">
        <div class="bg-gray-900 border border-gray-700 w-full max-w-md">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="font-syne text-xl font-bold text-red-400">Eliminar Material</h3>
                <button onclick="document.getElementById('modal-eliminar-material').classList.add('hidden')" class="text-gray-500 hover:text-white transition">✕</button>
            </div>
            <div class="p-6">
                <p class="text-gray-400 text-sm mb-4">Selecciona el material a eliminar. Esta acción no se puede deshacer.</p>
                <form method="POST" id="form-eliminar-mat" action="" onsubmit="return confirm('¿Seguro que quieres eliminar este material?')">
                    @csrf
                    @method('DELETE')
                    <div class="mb-6">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Material</label>
                        <select onchange="document.getElementById('form-eliminar-mat').action = '/materiales/' + this.value"
                            class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-red-500">
                            <option value="">— Selecciona un material —</option>
                            @foreach($materiales as $m)
                            <option value="{{ $m->id }}">{{ $m->nombre }} ({{ $m->estado }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('modal-eliminar-material').classList.add('hidden')" class="px-5 py-2.5 text-sm text-gray-400 border border-gray-700 hover:border-gray-500 transition uppercase tracking-wider">Cancelar</button>
                        <button type="submit" class="px-5 py-2.5 text-sm bg-red-600 text-white font-medium hover:bg-red-500 transition uppercase tracking-wider">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Submodal: Cambiar estado --}}
    <div id="modal-cambiar-estado" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-[60] p-4">
        <div class="bg-gray-900 border border-gray-700 w-full max-w-md">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="font-syne text-xl font-bold">Cambiar Estado</h3>
                <button onclick="document.getElementById('modal-cambiar-estado').classList.add('hidden')" class="text-gray-500 hover:text-white transition">✕</button>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('materiales.estado') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Material</label>
                        <select name="id_mat" required class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                            <option value="">— Selecciona un material —</option>
                            @foreach($materiales as $m)
                            <option value="{{ $m->id }}">{{ $m->nombre }} ({{ $m->estado }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Nuevo estado</label>
                        <select name="estado" required class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                            <option value="disponible">Disponible</option>
                            <option value="prestado">Prestado</option>
                            <option value="mantenimiento">Mantenimiento</option>
                        </select>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('modal-cambiar-estado').classList.add('hidden')" class="px-5 py-2.5 text-sm text-gray-400 border border-gray-700 hover:border-gray-500 transition uppercase tracking-wider">Cancelar</button>
                        <button type="submit" class="px-5 py-2.5 text-sm bg-yellow-500 text-gray-950 font-medium hover:bg-yellow-400 transition uppercase tracking-wider">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endif

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

        function abrirSubModalInv(id) {
            document.getElementById('modal-admin-inv').classList.add('hidden');
            document.getElementById(id).classList.remove('hidden');
        }

        function abrirModalMaterial(id, nombre, descripcion, tipo, estado, imagen, propietario, prestadoA) {
            document.getElementById('mat-nombre').textContent = nombre;
            document.getElementById('mat-tipo').textContent = tipo;
            document.getElementById('mat-descripcion').textContent = descripcion || '';
            document.getElementById('mat-propietario').textContent = propietario ? 'Propietario: ' + propietario : '';

            const img = document.getElementById('mat-img');
            const placeholder = document.getElementById('mat-img-placeholder');
            if (imagen) {
                img.src = imagen;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            const badge = document.getElementById('mat-estado-badge');
            const asignadoEl = document.getElementById('mat-asignado');
            const formPrestar = document.getElementById('form-prestar');
            const formDevolver = document.getElementById('form-devolver');
            const pendienteMsg = document.getElementById('mat-pendiente-msg');

            formPrestar.classList.add('hidden');
            formDevolver.classList.add('hidden');
            pendienteMsg.classList.add('hidden');

            if (estado === 'disponible') {
                badge.textContent = 'Disponible';
                badge.className = 'absolute top-3 left-3 text-xs px-3 py-1 uppercase tracking-wider bg-green-900/80 text-green-300';
                asignadoEl.textContent = 'Este material está disponible para préstamo';
                document.getElementById('mat-id-prestar').value = id;
                formPrestar.classList.remove('hidden');
            } else if (estado === 'prestado') {
                badge.textContent = 'Prestado';
                badge.className = 'absolute top-3 left-3 text-xs px-3 py-1 uppercase tracking-wider bg-yellow-900/80 text-yellow-300';
                asignadoEl.textContent = prestadoA ? 'Actualmente con: ' + prestadoA : 'Prestado';
                document.getElementById('mat-id-devolver').value = id;
                formDevolver.classList.remove('hidden');
            } else {
                badge.textContent = 'Mantenimiento';
                badge.className = 'absolute top-3 left-3 text-xs px-3 py-1 uppercase tracking-wider bg-red-900/80 text-red-300';
                asignadoEl.textContent = 'Este material está en mantenimiento';
            }

            document.getElementById('modal-material').classList.remove('hidden');
        }

        function cerrarModalMaterial() {
            document.getElementById('modal-material').classList.add('hidden');
        }

        document.getElementById('modal-material').addEventListener('click', function(e) {
            if (e.target === this) cerrarModalMaterial();
        });
    </script>

</body>
</html>