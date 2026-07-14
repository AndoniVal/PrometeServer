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

        :root {
            --color-card:         rgba(224, 223, 215, 0.82);
            --color-card-hover:   rgba(224, 223, 215, 0.95);
            --color-border:       rgba(102, 100, 96, 0.5);
            --color-border-inner: rgba(102, 100, 96, 0.2);
            --color-text:         rgba(97, 97, 95);
            --color-text-soft:    rgba(97, 97, 95, 0.8);
            --color-text-muted:   rgba(97, 97, 95, 0.55);
            --color-accent:       rgba(97, 97, 95);
            --color-danger:       #DC2626;
        }

        .card { background-color: var(--color-card); border: 1px solid var(--color-border); border-radius: 0.6rem; color: var(--color-text); backdrop-filter: blur(4px); }
        .card-header { border-bottom: 1px solid var(--color-border-inner); padding: 1rem 1.5rem; }
        .card-title { font-family: 'Syne', sans-serif; font-size: 1.1rem; font-weight: 700; color: var(--color-text); }
        .card-subtitle { font-size: 0.7rem; color: var(--color-text-muted); margin-top: 0.15rem; }

        .page-title    { font-family: 'Syne', sans-serif; font-size: 1.875rem; font-weight: 700; color: var(--color-text); }
        .page-subtitle { font-size: 0.875rem; color: var(--color-text-muted); margin-top: 0.25rem; }

        .form-input { width: 100%; background-color: rgba(102,100,96,0.08); border: 1px solid var(--color-border); border-radius: 0.35rem; color: var(--color-text); padding: 0.6rem 1rem; font-size: 0.875rem; outline: none; transition: border-color 0.15s; }
        .form-input:focus { border-color: var(--color-accent); }
        .form-label { display: block; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-text-muted); margin-bottom: 0.4rem; }

        .btn-primary { background-color: #1C1C1C; color: #F0D69C; padding: 0.6rem 1.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 0.35rem; border: 1px solid #333; transition: background-color 0.15s; cursor: pointer; }
        .btn-primary:hover { background-color: #333; }
        .btn-outline { background-color: transparent; color: var(--color-text-muted); padding: 0.6rem 1.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 0.35rem; border: 1px solid var(--color-border); transition: border-color 0.15s, color 0.15s; cursor: pointer; }
        .btn-outline:hover { border-color: var(--color-accent); color: var(--color-text); }
        .btn-ok { background-color: #2D6A4F; color: #fff; padding: 0.6rem 1.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 0.35rem; border: none; transition: opacity 0.15s; cursor: pointer; width: 100%; }
        .btn-ok:hover { opacity: 0.85; }
        .btn-danger { background-color: #DC2626; color: #fff; padding: 0.6rem 1.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 0.35rem; border: none; transition: opacity 0.15s; cursor: pointer; width: 100%; }
        .btn-danger:hover { opacity: 0.85; }

        .dropdown { background-color: #3A3836; border: 1px solid var(--color-border); border-radius: 0.5rem; }
        .dropdown-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 1rem; font-size: 0.875rem; color: rgba(255,255,255,0.65); transition: background-color 0.15s, color 0.15s; }
        .dropdown-item:hover { background-color: rgba(255,255,255,0.08); color: #fff; }
        .dropdown-item.danger { color: #DC2626; }
        .dropdown-divider { border-top: 1px solid rgba(255,255,255,0.1); }

        .saldo-pill { display: flex; align-items: center; gap: 0.4rem; padding: 0.3rem 0.75rem; border: 1px solid rgba(212,184,122,0.35); border-radius: 999px; background-color: rgba(212,184,122,0.1); font-size: 0.72rem; font-weight: 600; font-family: 'Syne', sans-serif; }
        .saldo-ok     { color: #D4B87A; }
        .saldo-danger { color: #DC2626; }

        .tabla th { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-text-muted); border-bottom: 1px solid var(--color-border-inner); padding: 0.75rem 1.5rem; text-align: left; }
        .tabla td { padding: 1rem 1.5rem; border-bottom: 1px solid var(--color-border-inner); font-size: 0.875rem; color: var(--color-text); }
        .tabla tr:last-child td { border-bottom: none; }

        /* ── ESPECÍFICO INVENTARIO ── */
        .material-card { background-color: var(--color-card); border: 1px solid var(--color-border); border-radius: 0.6rem; overflow: hidden; backdrop-filter: blur(4px); transition: background-color 0.15s, border-color 0.15s; }
        .material-card:hover { background-color: var(--color-card-hover); border-color: rgba(102,100,96,0.8); }
        .material-img { aspect-ratio: 1 / 1; background-color: rgba(102,100,96,0.1); overflow: hidden; position: relative; }
        .material-img img { width: 100%; height: 100%; object-fit: cover; }

        .badge { font-size: 0.62rem; text-transform: uppercase; letter-spacing: 0.08em; padding: 0.2rem 0.6rem; border-radius: 999px; font-weight: 600; }
        .badge-ok  { background-color: rgba(45,106,79,0.15);  border: 1px solid rgba(45,106,79,0.4);  color: #2D6A4F; }
        .badge-low { background-color: rgba(212,184,122,0.2); border: 1px solid rgba(212,184,122,0.5); color: #8A6D2F; }
        .badge-out { background-color: rgba(220,38,38,0.1);   border: 1px solid rgba(220,38,38,0.3);  color: #DC2626; }
        .badge-solid-ok  { background-color: rgba(224,223,215,0.9); border: 1px solid rgba(45,106,79,0.5);  color: #2D6A4F; }
        .badge-solid-low { background-color: rgba(224,223,215,0.9); border: 1px solid rgba(212,184,122,0.7); color: #8A6D2F; }
        .badge-solid-out { background-color: rgba(224,223,215,0.9); border: 1px solid rgba(220,38,38,0.5);  color: #DC2626; }

        .modal-overlay { position: fixed; inset: 0; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50; padding: 1rem; }
    </style>
</head>
<body class="min-h-screen" style="background-color: #F5DDC4; background-image: url('{{ asset('imagenes/PrometePuñal.png') }}'); background-size: 30%; background-repeat: no-repeat; background-position: center; background-attachment: fixed;">

    {{-- ── NAVBAR ── --}}
    <nav style="background-color: #1C1C1C; border-bottom: 1px solid #333333;" class="px-8 py-4 flex justify-between items-center sticky top-0 z-50 shadow-md">
        <div class="flex items-center gap-6">
            <a href="{{ route('dashboard') }}" class="font-syne text-xl font-bold hover:opacity-80 transition" style="color: #F0D69C;">
                Promet<span style="color: #D4B87A;">e</span>
            </a>
            <div class="flex items-center gap-5 ml-4">
                <a href="{{ route('economato') }}"     class="text-sm uppercase tracking-widest transition" style="color: #D4B87A;" onmouseover="this.style.color='#F0D69C'" onmouseout="this.style.color='#D4B87A'">Economato</a>
                <a href="{{ route('transacciones') }}" class="text-sm uppercase tracking-widest transition" style="color: #D4B87A;" onmouseover="this.style.color='#F0D69C'" onmouseout="this.style.color='#D4B87A'">Transacciones</a>
                <a href="{{ route('inventario') }}"    class="text-sm uppercase tracking-widest transition" style="color: #F0D69C;">Inventario</a>
                <a href="{{ route('calendario') }}"    class="text-sm uppercase tracking-widest transition" style="color: #D4B87A;" onmouseover="this.style.color='#F0D69C'" onmouseout="this.style.color='#D4B87A'">Calendario</a>
            </div>
        </div>
        <div class="flex items-center gap-4">
            {{-- Saldo --}}
            <div class="saldo-pill {{ $user->saldo < 0 ? 'saldo-danger' : 'saldo-ok' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                {{ number_format($user->saldo, 2) }}€
            </div>
            {{-- Carrito --}}
            <a href="{{ route('carrito') }}" class="relative transition" style="color: #F0D69C;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                @if(count(session('carrito', [])) > 0)
                <span class="absolute -top-2 -right-2 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center" style="background-color: #D4B87A; color: #1C1C1C;">
                    {{ count(session('carrito', [])) }}
                </span>
                @endif
            </a>
            {{-- Avatar dropdown --}}
            <div class="relative" id="user-menu">
                <button onclick="toggleDropdown()" class="flex items-center gap-3 focus:outline-none">
                    <div class="w-9 h-9 rounded-full overflow-hidden border-2" style="border-color: #D4B87A;">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->nombre }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center" style="background-color: rgba(212,184,122,0.2);">
                                <span class="font-syne text-xs font-bold" style="color: #D4B87A;">{{ strtoupper(substr($user->nombre, 0, 2)) }}</span>
                            </div>
                        @endif
                    </div>
                    <span class="text-sm hidden md:block" style="color: #D4B87A;">{{ $user->nombre }}</span>
                    <svg id="chevron" class="w-4 h-4 transition-transform duration-200" style="color: #D4B87A;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="dropdown" class="dropdown hidden absolute right-0 mt-3 w-52 shadow-xl z-50">
                    <div class="px-4 py-3 dropdown-divider">
                        <p class="text-sm font-medium" style="color: #FFFFFF;">{{ $user->nombre }}</p>
                        <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.45);">{{ ucfirst($user->rol) }}</p>
                    </div>
                    <div class="py-1">
                        <a href="{{ route('mi-cuenta') }}" class="dropdown-item">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Mi cuenta
                        </a>
                    </div>
                    <div class="py-1 dropdown-divider">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item danger w-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="px-8 py-8 max-w-6xl mx-auto">

        {{-- ── MENSAJES ── --}}
        @if(session('success'))
        <div class="mb-6 px-5 py-3 text-sm rounded-md" style="background-color: rgba(45,106,79,0.15); border: 1px solid rgba(45,106,79,0.4); color: #2D6A4F;">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="mb-6 px-5 py-3 text-sm rounded-md" style="background-color: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.3); color: #DC2626;">{{ $errors->first() }}</div>
        @endif

        {{-- ── CABECERA ── --}}
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h2 class="page-title">Inventario</h2>
                <p class="page-subtitle">Materiales y equipamiento del servicio</p>
            </div>
            @if($user->rol === 'administrador')
            <div class="flex gap-3 items-center">
                @if($solicitudesPendientes->count() > 0)
                <button onclick="document.getElementById('modal-solicitudes').classList.remove('hidden')" class="btn-outline relative">
                    Solicitudes
                    <span class="ml-2 text-xs font-bold px-1.5 py-0.5 rounded-full" style="background-color: #1C1C1C; color: #F0D69C;">{{ $solicitudesPendientes->count() }}</span>
                </button>
                @endif
                <button onclick="document.getElementById('modal-nuevo-material').classList.remove('hidden')" class="btn-primary">
                    + Añadir material
                </button>
            </div>
            @endif
        </div>

        {{-- ── STATS ── --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="card p-5">
                <p class="text-xs uppercase tracking-widest mb-2" style="color: var(--color-text-muted);">Total</p>
                <p class="font-syne text-3xl font-bold" style="color: var(--color-text);">{{ $totalMateriales }}</p>
            </div>
            <div class="card p-5">
                <p class="text-xs uppercase tracking-widest mb-2" style="color: var(--color-text-muted);">Disponibles</p>
                <p class="font-syne text-3xl font-bold" style="color: #2D6A4F;">{{ $disponibles }}</p>
            </div>
            <div class="card p-5">
                <p class="text-xs uppercase tracking-widest mb-2" style="color: var(--color-text-muted);">Prestados</p>
                <p class="font-syne text-3xl font-bold" style="color: #8A6D2F;">{{ $prestados }}</p>
            </div>
            <div class="card p-5">
                <p class="text-xs uppercase tracking-widest mb-2" style="color: var(--color-text-muted);">Mantenimiento</p>
                <p class="font-syne text-3xl font-bold" style="color: #DC2626;">{{ $mantenimiento }}</p>
            </div>
        </div>

        {{-- ── BUSCADOR ── --}}
        <div class="mb-6 flex justify-between items-center">
            <h3 class="font-syne text-lg font-bold" style="color: var(--color-text);">Materiales</h3>
            <input type="text" id="buscador" placeholder="Buscar material..." onkeyup="filtrarGrid()" class="form-input" style="width: 16rem;">
        </div>

        {{-- ── GRID DE MATERIALES ── --}}
        <div id="grid-materiales" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-10">
            @forelse($materiales as $material)
            <div class="material-card">
                <div class="cursor-pointer" onclick="abrirModalMaterial(
                        {{ $material->id }},
                        '{{ addslashes($material->nombre) }}',
                        '{{ addslashes($material->descripcion ?? '') }}',
                        '{{ addslashes($material->tipo) }}',
                        '{{ $material->estado }}',
                        '{{ $material->imagen ? asset('storage/' . $material->imagen) : '' }}',
                        '{{ addslashes($material->usuario->nombre ?? '') }}',
                        '{{ addslashes($material->usuarioPrestado->nombre ?? '') }}',
                        {{ $material->id_prestado === $user->id ? 'true' : 'false' }}
                    )">
                    <div class="material-img">
                        @if($material->imagen)
                            <img src="{{ asset('storage/' . $material->imagen) }}" alt="{{ $material->nombre }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center" style="color: rgba(102,100,96,0.35);">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-2 left-2">
                            @if($material->estado === 'disponible')
                                <span class="badge badge-solid-ok">Disponible</span>
                            @elseif($material->estado === 'prestado')
                                <span class="badge badge-solid-low">Prestado</span>
                            @else
                                <span class="badge badge-solid-out">Mantenimiento</span>
                            @endif
                        </div>
                    </div>
                    <div class="p-4 pb-2">
                        <p class="font-syne font-bold text-sm mb-1" style="color: var(--color-text);">{{ $material->nombre }}</p>
                        <p class="text-xs mb-1" style="color: var(--color-text-soft);">{{ $material->tipo }}</p>
                        <p class="text-xs" style="color: var(--color-text-muted);">Propietario: {{ $material->usuario->nombre ?? '—' }}</p>
                        @if($material->estado === 'prestado' && $material->usuarioPrestado)
                        <p class="text-xs mt-1" style="color: #8A6D2F;">Con: {{ $material->usuarioPrestado->nombre }}</p>
                        @endif
                    </div>
                </div>
                @if($user->rol === 'administrador')
                <div class="px-4 pb-3 pt-2 mx-4 mb-1" style="border-top: 1px solid var(--color-border-inner); margin-left: 1rem; margin-right: 1rem; padding-left: 0; padding-right: 0;">
                    <form method="POST" action="{{ route('materiales.destroy', $material->id) }}" onsubmit="return confirm('¿Eliminar {{ addslashes($material->nombre) }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs uppercase tracking-wider" style="color: #DC2626;">Eliminar</button>
                    </form>
                </div>
                @endif
            </div>
            @empty
            <div class="card p-16 text-center col-span-full">
                <p style="color: var(--color-text-muted);">No hay materiales registrados aún.</p>
            </div>
            @endforelse
        </div>

        {{-- ── ÚLTIMOS PRÉSTAMOS ── --}}
        <div class="card mb-8">
            <div class="card-header">
                <p class="card-title">Últimos préstamos</p>
                <p class="card-subtitle">{{ $user->rol === 'administrador' ? 'Movimientos recientes de todos los usuarios' : 'Tus movimientos recientes' }}</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full tabla">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            @if($user->rol === 'administrador')<th>Usuario</th>@endif
                            <th>Material</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimosPrestamos as $p)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($p->fecha)->format('d/m/Y H:i') }}</td>
                            @if($user->rol === 'administrador')<td>{{ $p->usuario->nombre ?? '—' }}</td>@endif
                            <td>{{ $p->nombre_material }}</td>
                            <td>
                                @if($p->estado === 'aprobado')
                                    <span class="badge badge-low">Aprobado</span>
                                @elseif($p->estado === 'pendiente')
                                    <span class="badge badge-ok">Pendiente</span>
                                @else
                                    <span class="badge" style="background-color: rgba(102,100,96,0.1); border: 1px solid var(--color-border-inner); color: var(--color-text-muted);">Devuelto</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="{{ $user->rol === 'administrador' ? 4 : 3 }}" class="text-center py-8" style="color: var(--color-text-muted);">No hay préstamos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    {{-- ── MODAL DETALLE MATERIAL ── --}}
    <div id="modal-material" class="hidden modal-overlay">
        <div class="card w-full max-w-md overflow-hidden">
            <div class="material-img" style="max-height: 18rem;">
                <img id="mat-img" src="" alt="" class="hidden" style="max-height: 18rem;">
                <div id="mat-img-placeholder" class="w-full h-48 flex items-center justify-center" style="color: rgba(102,100,96,0.35);">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <span id="mat-estado-badge" class="badge badge-solid-ok" style="position: absolute; top: 0.75rem; left: 0.75rem;"></span>
            </div>
            <div class="p-6">
                <h3 id="mat-nombre" class="font-syne text-xl font-bold mb-1" style="color: var(--color-text);"></h3>
                <p id="mat-tipo" class="text-xs uppercase tracking-wider mb-2" style="color: var(--color-text-soft);"></p>
                <p id="mat-descripcion" class="text-sm mb-2" style="color: var(--color-text-muted);"></p>
                <p id="mat-propietario" class="text-xs mb-2" style="color: var(--color-text-muted);"></p>
                <p id="mat-asignado" class="text-sm mb-4" style="color: var(--color-text-soft);"></p>

                <form method="POST" action="{{ route('inventario.prestar') }}" id="form-prestar" class="hidden mb-3">
                    @csrf
                    <input type="hidden" name="id_mat" id="mat-id-prestar">
                    <button type="submit" class="btn-primary w-full">Solicitar préstamo</button>
                </form>

                <form method="POST" action="{{ route('inventario.devolver') }}" id="form-devolver" class="hidden mb-3">
                    @csrf
                    <input type="hidden" name="id_mat" id="mat-id-devolver">
                    <button type="submit" class="btn-ok">Devolver material</button>
                </form>

                <div id="mat-pendiente-msg" class="hidden text-center text-sm py-2 mb-3" style="color: var(--color-text-muted);">
                    Ya tienes una solicitud pendiente para este material.
                </div>

                @if($user->rol === 'administrador')
                <form method="POST" action="{{ route('materiales.estado') }}" class="mb-3 pt-3" style="border-top: 1px solid var(--color-border-inner);">
                    @csrf
                    <input type="hidden" name="id_mat" id="mat-id-estado">
                    <label class="form-label">Cambiar estado (admin)</label>
                    <div class="flex gap-2">
                        <select name="estado" id="mat-estado-select" class="form-input" style="flex: 1;">
                            <option value="disponible">Disponible</option>
                            <option value="prestado">Prestado</option>
                            <option value="mantenimiento">Mantenimiento</option>
                        </select>
                        <button type="submit" class="btn-outline" style="padding: 0.6rem 1rem;">Aplicar</button>
                    </div>
                </form>
                @endif

                <button onclick="cerrarModalMaterial()" class="btn-outline w-full">Cerrar</button>
            </div>
        </div>
    </div>

    @if($user->rol === 'administrador')

    {{-- ── MODAL NUEVO MATERIAL ── --}}
    <div id="modal-nuevo-material" class="hidden modal-overlay">
        <div class="card p-8 w-full max-w-md">
            <h3 class="font-syne text-xl font-bold mb-6" style="color: var(--color-text);">Nuevo material</h3>
            <form method="POST" action="{{ route('materiales.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" required class="form-input">
                </div>
                <div class="mb-4">
                    <label class="form-label">Descripción (opcional)</label>
                    <input type="text" name="descripcion" maxlength="500" class="form-input">
                </div>
                <div class="mb-4">
                    <label class="form-label">Tipo</label>
                    <input type="text" name="tipo" required class="form-input" placeholder="Instrumento, cable, micrófono...">
                </div>
                <div class="mb-4">
                    <label class="form-label">Propietario</label>
                    <select name="id_us" required class="form-input">
                        @foreach($usuarios as $u)
                        <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
                    <label class="form-label">Imagen (opcional · máx. 5 MB)</label>
                    <input type="file" name="imagen" accept="image/*" class="form-input" style="padding: 0.45rem 1rem;">
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('modal-nuevo-material').classList.add('hidden')" class="btn-outline">Cancelar</button>
                    <button type="submit" class="btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODAL SOLICITUDES PENDIENTES ── --}}
    <div id="modal-solicitudes" class="hidden modal-overlay">
        <div class="card w-full max-w-2xl" style="max-height: 90vh; overflow-y: auto;">
            <div class="card-header flex justify-between items-center">
                <p class="card-title">Solicitudes pendientes</p>
                <button onclick="document.getElementById('modal-solicitudes').classList.add('hidden')" class="transition" style="color: var(--color-text-muted);">✕</button>
            </div>
            <div class="p-6">
                @forelse($solicitudesPendientes as $s)
                <div class="mb-3 px-5 py-4 rounded-md" style="background-color: rgba(102,100,96,0.06); border: 1px solid var(--color-border-inner);">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-medium" style="color: var(--color-text);">{{ $s->usuario->nombre }}</p>
                            <p class="text-sm mt-0.5" style="color: #8A6D2F;">{{ $s->nombre_material }}</p>
                            <p class="text-xs mt-0.5" style="color: var(--color-text-muted);">{{ \Carbon\Carbon::parse($s->fecha)->format('d/m/Y H:i') }}</p>
                        </div>
                        <span class="badge badge-ok">Pendiente</span>
                    </div>
                    <div class="flex gap-3">
                        <form method="POST" action="{{ route('inventario.aprobar') }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="id_prestamo" value="{{ $s->id }}">
                            <button type="submit" class="btn-ok">✓ Aprobar</button>
                        </form>
                        <form method="POST" action="{{ route('inventario.rechazar') }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="id_prestamo" value="{{ $s->id }}">
                            <button type="submit" class="btn-danger">✕ Rechazar</button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-center py-8" style="color: var(--color-text-muted);">No hay solicitudes pendientes.</p>
                @endforelse
            </div>
        </div>
    </div>

    @endif

    <script>
        // Materiales para los que el usuario actual ya tiene una solicitud pendiente
        const misPendientes = @json($solicitudesPendientes->where('id_us', auth()->id())->pluck('id_mat')->values());

        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown');
            const chevron  = document.getElementById('chevron');
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

        function filtrarGrid() {
            const filtro = document.getElementById('buscador').value.toLowerCase();
            document.querySelectorAll('#grid-materiales .material-card').forEach(card => {
                card.style.display = card.textContent.toLowerCase().includes(filtro) ? '' : 'none';
            });
        }

        function abrirModalMaterial(id, nombre, descripcion, tipo, estado, imagen, propietario, prestadoA, esMio) {
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

            const badge        = document.getElementById('mat-estado-badge');
            const asignadoEl   = document.getElementById('mat-asignado');
            const formPrestar  = document.getElementById('form-prestar');
            const formDevolver = document.getElementById('form-devolver');
            const pendienteMsg = document.getElementById('mat-pendiente-msg');

            formPrestar.classList.add('hidden');
            formDevolver.classList.add('hidden');
            pendienteMsg.classList.add('hidden');

            if (estado === 'disponible') {
                badge.textContent = 'Disponible';
                badge.className = 'badge badge-solid-ok';
                asignadoEl.textContent = 'Este material está disponible para préstamo';
                if (misPendientes.includes(id)) {
                    pendienteMsg.classList.remove('hidden');
                } else {
                    document.getElementById('mat-id-prestar').value = id;
                    formPrestar.classList.remove('hidden');
                }
            } else if (estado === 'prestado') {
                badge.textContent = 'Prestado';
                badge.className = 'badge badge-solid-low';
                asignadoEl.textContent = prestadoA ? 'Actualmente con: ' + prestadoA : 'Prestado';
                if (esMio) {
                    document.getElementById('mat-id-devolver').value = id;
                    formDevolver.classList.remove('hidden');
                }
            } else {
                badge.textContent = 'Mantenimiento';
                badge.className = 'badge badge-solid-out';
                asignadoEl.textContent = 'Este material está en mantenimiento';
            }

            @if($user->rol === 'administrador')
            document.getElementById('mat-id-estado').value = id;
            document.getElementById('mat-estado-select').value = estado;
            @endif

            badge.style.position = 'absolute';
            badge.style.top = '0.75rem';
            badge.style.left = '0.75rem';

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