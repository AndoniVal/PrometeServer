<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — PROMETE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        /* ── FUENTES ── */
        body { font-family: 'DM Sans', sans-serif; }
        .font-syne { font-family: 'Syne', sans-serif; }

        /* ── VARIABLES DE COLOR ── */
        :root {
            --color-bg:         #F5DDC4;
            --color-card:      rgba(224, 223, 215, 0.82);
            --color-card-hover: rgba(224, 223, 215, 0.95);
            --color-border:     rgba(102, 100, 96, 0.5);
            --color-border-inner: rgba(255,255,255,0.15);
            --color-text:      rgba(97, 97, 95);
            --color-text-soft: rgba(97, 97, 95);
            --color-text-muted:rgba(97, 97, 95);
            --color-accent:    rgba(97, 97, 95);
            --color-danger:     #DC2626;
        }

        /* ── TARJETA PRINCIPAL ── */
        .card {
            background-color: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: 0.6rem;
            color: var(--color-text);
            backdrop-filter: blur(4px);
        }

        /* ── CABECERA DE TARJETA ── */
        .card-header {
            border-bottom: 1px solid var(--color-border-inner);
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--color-text);
        }

        .card-subtitle {
            font-size: 0.7rem;
            color: var(--color-text-soft);
            margin-top: 0.15rem;
        }

        /* ── LINKS DE ACCIÓN ── */
        .card-link {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 500;
            color: var(--color-accent);
            transition: color 0.15s;
        }
        .card-link:hover { color: #FFFFFF; }

        /* ── TABLA ── */
        .tabla th {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--color-text-muted);
            border-bottom: 1px solid var(--color-border-inner);
            padding: 0.75rem 1.5rem;
            text-align: left;
        }

        .tabla td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--color-border-inner);
            font-size: 0.875rem;
        }

        .tabla tr:last-child td { border-bottom: none; }

        .tabla tbody tr {
            transition: background-color 0.15s;
        }
        .tabla tbody tr:hover {
            background-color: rgba(255,255,255,0.08);
        }

        .td-main  { color: var(--color-text); }
        .td-soft  { color: var(--color-text-soft); }
        .td-muted { color: var(--color-text-muted); }
        .td-accent { color: var(--color-accent); font-weight: 600; }

        /* ── ACCESO RÁPIDO ── */
        .acceso {
            background-color: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: 0.6rem;
            padding: 1.25rem 1.5rem;
            display: block;
            transition: background-color 0.15s, border-color 0.15s;
            color: var(--color-text);
        }
        .acceso:hover {
            background-color: var(--color-card-hover);
            border-color: var(--color-accent);
        }
        .acceso-icon {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            background-color: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 0.4rem;
        }
        .acceso-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            color: var(--color-text);
            font-size: 0.95rem;
        }
        .acceso-sub {
            font-size: 0.72rem;
            color: var(--color-text-soft);
            margin-top: 0.1rem;
        }

        /* ── BADGES ESTADO ── */
        .badge {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.2rem 0.6rem;
            border-radius: 0.25rem;
        }
        .badge-pendiente { background-color: rgba(255,255,255,0.1); color: rgba(255,255,255,0.5); }
        .badge-aprobado  { background-color: rgba(45,106,79,0.3);   color: #6EE7A0; }
        .badge-devuelto  { background-color: rgba(12,84,96,0.3);    color: #67E8F9; }

        /* ── DROPDOWN ── */
        .dropdown {
            background-color: #3A3836;
            border: 1px solid var(--color-border);
            border-radius: 0.5rem;
        }
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1rem;
            font-size: 0.875rem;
            color: var(--color-text-soft);
            transition: background-color 0.15s, color 0.15s;
        }
        .dropdown-item:hover {
            background-color: rgba(255,255,255,0.08);
            color: var(--color-text);
        }
        .dropdown-item.danger { color: var(--color-danger); }
        .dropdown-divider { border-top: 1px solid var(--color-border-inner); }

        /* ── SALDO EN NAVBAR ── */
        .saldo-pill {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.75rem;
            border: 1px solid rgba(212,184,122,0.35);
            border-radius: 999px;
            background-color: rgba(212,184,122,0.1);
            font-size: 0.72rem;
            font-weight: 600;
            font-family: 'Syne', sans-serif;
        }
        .saldo-ok     { color: var(--color-accent); }
        .saldo-danger { color: var(--color-danger); }
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
                @if(Auth::user()->rol === 'administrador')
                <a href="{{ route('inventario') }}"    class="text-sm uppercase tracking-widest transition" style="color: #D4B87A;" onmouseover="this.style.color='#F0D69C'" onmouseout="this.style.color='#D4B87A'">Inventario</a>
                @endif
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

    <main class="px-8 py-8 max-w-7xl mx-auto">

        <div class="mb-8">
            <h2 class="font-syne text-3xl font-bold" style="color: rgba(97,96,95);">Bienvenido, {{ $user->nombre }}</h2>
            <p class="text-sm mt-1" style="color: rgba(97,96,95);">Rol: <span class="capitalize font-medium" style="color: #2C2C2C;">{{ $user->rol }}</span></p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Últimos movimientos --}}
            <div class="card lg:col-span-2">
                <div class="card-header">
                    <div>
                        <p class="card-title">Últimos Movimientos</p>
                        <p class="card-subtitle">Las 5 transacciones más recientes del economato</p>
                    </div>
                    <a href="{{ route('transacciones') }}" class="card-link">Ver todos →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="tabla w-full">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosMovimientos as $t)
                            <tr>
                                <td class="td-muted">{{ \Carbon\Carbon::parse($t->fecha)->format('d/m/Y H:i') }}</td>
                                <td class="td-soft">{{ $t->usuario->nombre }}</td>
                                <td class="td-main">{{ $t->producto->nombre }}</td>
                                <td class="td-accent">{{ $t->cantidad }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="td-muted text-center py-10">No hay movimientos registrados aún.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Accesos rápidos --}}
            <div class="flex flex-col gap-4">
                <a href="{{ route('economato') }}" class="acceso">
                    <div class="flex items-center gap-4">
                        <div class="acceso-icon">🛒</div>
                        <div>
                            <p class="acceso-title">Economato</p>
                            <p class="acceso-sub">Gestión de productos y compras</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('transacciones') }}" class="acceso">
                    <div class="flex items-center gap-4">
                        <div class="acceso-icon">📋</div>
                        <div>
                            <p class="acceso-title">Transacciones</p>
                            <p class="acceso-sub">Historial de movimientos</p>
                        </div>
                    </div>
                </a>
                @if(Auth::user()->rol === 'administrador')
                <a href="{{ route('inventario') }}" class="acceso">
                    <div class="flex items-center gap-4">
                        <div class="acceso-icon">📦</div>
                        <div>
                            <p class="acceso-title">Inventario</p>
                            <p class="acceso-sub">Materiales y préstamos</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('finanzas') }}" class="acceso">
                    <div class="flex items-center gap-4">
                        <div class="acceso-icon">💰</div>
                        <div>
                            <p class="acceso-title">Finanzas</p>
                            <p class="acceso-sub">Control económico y saldos</p>
                        </div>
                    </div>
                </a>
                @endif
            </div>

        </div>

        {{-- ── ÚLTIMOS PRÉSTAMOS (solo admin) ── --}}
        @if(Auth::user()->rol === 'administrador')
        <div class="mt-6">
            <div class="card">
                <div class="card-header">
                    <div>
                        <p class="card-title">Últimos Préstamos de Material</p>
                        <p class="card-subtitle">Movimientos recientes de inventario de todos los usuarios</p>
                    </div>
                    <a href="{{ route('inventario.movimientos') }}" class="card-link">Ver todos →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="tabla w-full">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Material</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosPrestamos as $p)
                            <tr>
                                <td class="td-muted">{{ \Carbon\Carbon::parse($p->fecha)->format('d/m/Y H:i') }}</td>
                                <td class="td-soft">{{ $p->usuario->nombre }}</td>
                                <td class="td-main">{{ $p->nombre_material }}</td>
                                <td>
                                    @if($p->estado === 'pendiente')
                                        <span class="badge badge-pendiente">Pendiente</span>
                                    @elseif($p->estado === 'aprobado')
                                        <span class="badge badge-aprobado">Aprobado</span>
                                    @else
                                        <span class="badge badge-devuelto">Devuelto</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="td-muted text-center py-10">No hay préstamos registrados aún.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </main>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown');
            const chevron  = document.getElementById('chevron');
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
