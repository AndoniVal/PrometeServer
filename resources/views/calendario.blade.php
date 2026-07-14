<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario — PROMETE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            background-color: #F5DDC4;
            background-image: url('{{ asset('imagenes/PrometePuñal.png') }}');
            background-size: 30%;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
        }
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

        .saldo-pill { display: flex; align-items: center; gap: 0.4rem; padding: 0.3rem 0.75rem; border: 1px solid rgba(212,184,122,0.35); border-radius: 999px; background-color: rgba(212,184,122,0.1); font-size: 0.72rem; font-weight: 600; font-family: 'Syne', sans-serif; }
        .saldo-ok     { color: #D4B87A; }
        .saldo-danger { color: #DC2626; }

        .dropdown { background-color: #3A3836; border: 1px solid var(--color-border); border-radius: 0.5rem; }
        .dropdown-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 1rem; font-size: 0.875rem; color: rgba(255,255,255,0.65); transition: background-color 0.15s, color 0.15s; }
        .dropdown-item:hover { background-color: rgba(255,255,255,0.08); color: #fff; }
        .dropdown-item.danger { color: #DC2626; }
        .dropdown-divider { border-top: 1px solid rgba(255,255,255,0.1); }

        .btn-primary { background-color: #1C1C1C; color: #F0D69C; padding: 0.6rem 1.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 0.35rem; border: 1px solid #333; transition: background-color 0.15s; cursor: pointer; display: inline-block; text-decoration: none; }
        .btn-primary:hover { background-color: #333; }
        .btn-outline { background-color: transparent; color: var(--color-text-muted); padding: 0.6rem 1.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 0.35rem; border: 1px solid var(--color-border); transition: border-color 0.15s, color 0.15s; cursor: pointer; }
        .btn-outline:hover { border-color: var(--color-accent); color: var(--color-text); }

        /* ── CALENDARIO ── */
        .cal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .cal-heading { font-family: 'Syne', sans-serif; font-size: 1.6rem; font-weight: 800; color: var(--color-text); }

        .mes-selector { position: relative; }
        .mes-btn {
            display: flex; align-items: center; gap: 0.6rem;
            background-color: rgba(212,184,122,0.15);
            border: 1px solid rgba(212,184,122,0.45);
            color: #8a7647; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.95rem;
            padding: 0.55rem 1.1rem; border-radius: 0.5rem; cursor: pointer;
            text-transform: capitalize; transition: background-color 0.15s;
        }
        .mes-btn:hover { background-color: rgba(212,184,122,0.25); }
        .mes-menu {
            position: absolute; right: 0; top: calc(100% + 0.5rem);
            width: 220px; max-height: 320px; overflow-y: auto;
            background-color: rgba(245,237,228,0.97);
            border: 1px solid var(--color-border); border-radius: 0.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15); backdrop-filter: blur(6px);
            z-index: 50; padding: 0.3rem;
        }
        .mes-opcion {
            display: block; padding: 0.55rem 0.8rem; font-size: 0.85rem;
            color: var(--color-text); text-decoration: none; border-radius: 0.35rem;
            text-transform: capitalize; transition: background-color 0.15s;
        }
        .mes-opcion:hover { background-color: rgba(212,184,122,0.18); }
        .mes-opcion.activo { background-color: rgba(212,184,122,0.3); font-weight: 600; }

        /* FIX: minmax(0, 1fr) — con 1fr a secas el mínimo es "auto" y un título largo
           con white-space:nowrap ensancha su columna, rompiendo las celdas y
           empujando la columna del domingo fuera del contenedor. */
        .cal-grid {
            display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 1px;
            background-color: var(--color-border); border: 1px solid var(--color-border);
            border-radius: 0.5rem; overflow: hidden;
        }
        .cal-weekday {
            background-color: rgba(224,223,215,0.92); padding: 0.7rem; text-align: center;
            font-size: 0.62rem; letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--color-text-muted); font-weight: 500;
        }
        .cal-day {
            background-color: rgba(224,223,215,0.78); min-height: 108px; padding: 0.45rem;
            display: flex; flex-direction: column; gap: 0.25rem;
            min-width: 0; /* refuerzo del mismo fix a nivel de celda */
        }
        .cal-day.otro-mes { background-color: rgba(224,223,215,0.4); }
        .cal-day.otro-mes .cal-daynum { color: rgba(97,97,95,0.35); }
        .cal-daynum { font-size: 0.78rem; color: var(--color-text-soft); align-self: flex-end; font-weight: 500; }
        .cal-day.hoy .cal-daynum {
            background-color: #D4B87A; color: #1C1C1C; font-weight: 700;
            width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border-radius: 50%;
        }
        .evento {
            display: flex; align-items: center; gap: 0.3rem;
            background-color: rgba(212,184,122,0.22); border-left: 2px solid #D4B87A;
            padding: 0.22rem 0.4rem; font-size: 0.7rem; color: #5c4f30; cursor: pointer;
            border-radius: 0.2rem; transition: background-color 0.15s;
            min-width: 0;
        }
        .evento:hover { background-color: rgba(212,184,122,0.35); }
        .evento-titulo { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; min-width: 0; }
        .evento-badge {
            flex-shrink: 0; display: none; align-items: center; justify-content: center;
            min-width: 16px; height: 16px; padding: 0 0.25rem;
            background-color: #1C1C1C; color: #F0D69C;
            font-size: 0.6rem; font-weight: 700; border-radius: 999px;
        }
        .evento-badge.visible { display: inline-flex; }

        .conectar { text-align: center; padding: 4rem 2rem; }
        .conectar p { color: var(--color-text); margin-bottom: 1.5rem; }

        /* ── GRID DE MATERIALES DEL MODAL ── */
        .mat-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0.6rem; }
        .mat-thumb {
            position: relative; border: 1px solid var(--color-border-inner); border-radius: 0.4rem;
            overflow: hidden; background-color: rgba(102,100,96,0.06);
        }
        .mat-thumb-img { aspect-ratio: 1 / 1; overflow: hidden; display: flex; align-items: center; justify-content: center; color: rgba(102,100,96,0.35); }
        .mat-thumb-img img { width: 100%; height: 100%; object-fit: cover; }
        .mat-thumb-nombre { font-size: 0.68rem; color: var(--color-text); padding: 0.3rem 0.4rem; text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .mat-thumb-quitar {
            position: absolute; top: 0.25rem; right: 0.25rem;
            width: 20px; height: 20px; border-radius: 50%; border: none; cursor: pointer;
            background-color: rgba(28,28,28,0.75); color: #fff; font-size: 0.7rem; line-height: 1;
            display: flex; align-items: center; justify-content: center; transition: background-color 0.15s;
        }
        .mat-thumb-quitar:hover { background-color: #DC2626; }
        .mat-thumb.seleccionable { cursor: pointer; transition: border-color 0.15s, transform 0.1s; }
        .mat-thumb.seleccionable:hover { border-color: #D4B87A; transform: scale(1.03); }
    </style>
</head>
<body class="min-h-screen">

    {{-- ── NAVBAR ── --}}
    <nav style="background-color: #1C1C1C; border-bottom: 1px solid #333333;" class="px-8 py-4 flex justify-between items-center sticky top-0 z-50 shadow-md">
        <div class="flex items-center gap-6">
            <a href="{{ route('dashboard') }}" class="font-syne text-xl font-bold hover:opacity-80 transition" style="color: #F0D69C;">
                Promet<span style="color: #D4B87A;">e</span>
            </a>
            <div class="flex items-center gap-5 ml-4">
                <a href="{{ route('economato') }}"     class="text-sm uppercase tracking-widest transition" style="color: #D4B87A;" onmouseover="this.style.color='#F0D69C'" onmouseout="this.style.color='#D4B87A'">Economato</a>
                <a href="{{ route('transacciones') }}" class="text-sm uppercase tracking-widest transition" style="color: #D4B87A;" onmouseover="this.style.color='#F0D69C'" onmouseout="this.style.color='#D4B87A'">Transacciones</a>
                <a href="{{ route('inventario') }}"    class="text-sm uppercase tracking-widest transition" style="color: #D4B87A;" onmouseover="this.style.color='#F0D69C'" onmouseout="this.style.color='#D4B87A'">Inventario</a>
                <a href="{{ route('calendario') }}"    class="text-sm uppercase tracking-widest transition" style="color: #F0D69C;">Calendario</a>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="saldo-pill {{ $user->saldo < 0 ? 'saldo-danger' : 'saldo-ok' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                {{ number_format($user->saldo, 2) }}€
            </div>
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

        @if (! $conectado)
            <div class="card conectar">
                <p>No has conectado tu cuenta de Google.</p>
                <a href="{{ route('google.redirect') }}" class="btn-primary">Conectar con Google</a>
            </div>
        @else

            <div class="cal-header">
                <h2 class="cal-heading">Calendario</h2>
                {{-- Selector de mes desplegable --}}
                <div class="mes-selector" id="mes-selector">
                    <button class="mes-btn" onclick="toggleMes()">
                        {{ $tituloMes }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="mes-menu hidden" id="mes-menu">
                        @foreach ($mesesDisponibles as $mes)
                            <a href="{{ route('calendario', ['mes' => $mes['valor']]) }}"
                               class="mes-opcion {{ $mes['valor'] === $mesActualValor ? 'activo' : '' }}">
                                {{ $mes['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card" style="padding: 1.25rem;">
                <div class="cal-grid">
                    @foreach (['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'] as $dia)
                        <div class="cal-weekday">{{ $dia }}</div>
                    @endforeach

                    @foreach ($semanas as $semana)
                        @foreach ($semana as $dia)
                            <div class="cal-day {{ $dia['delMes'] ? '' : 'otro-mes' }} {{ $dia['esHoy'] ? 'hoy' : '' }}">
                                <span class="cal-daynum">{{ $dia['numero'] }}</span>
                                @foreach ($dia['eventos'] as $evento)
                                <div class="evento" title="{{ $evento['titulo'] }}"
                                     data-evento-id="{{ $evento['id'] }}"
                                     onclick="abrirEvento('{{ $evento['id'] }}')">
                                    <span class="evento-titulo">{{ $evento['titulo'] }}</span>
                                    <span class="evento-badge {{ $evento['materiales'] > 0 ? 'visible' : '' }}">{{ $evento['materiales'] }}</span>
                                </div>
                                @endforeach
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

        @endif

        {{-- ── MODAL DE EVENTO ── --}}
        <div id="modal-evento" style="position: fixed; inset: 0; background: rgba(28,28,28,0.6); z-index: 100; display: none; align-items: center; justify-content: center; padding: 1rem;">
            <div class="card" style="width: 100%; max-width: 480px; padding: 0; max-height: 90vh; overflow-y: auto;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--color-border-inner);">
                    <div>
                        <h3 id="m-titulo" class="font-syne" style="font-size: 1.2rem; font-weight: 700; color: var(--color-text);"></h3>
                        <p id="m-creador" style="font-size: 0.75rem; color: var(--color-text-muted); margin-top: 0.2rem;"></p>
                        <p id="m-horas" style="font-size: 0.8rem; color: var(--color-text-soft); margin-top: 0.3rem;"></p>
                    </div>
                    <button onclick="cerrarModal()" style="color: var(--color-text-muted); font-size: 1.1rem; line-height: 1; background: none; border: none; cursor: pointer;">✕</button>
                </div>

                <div style="padding: 1.25rem 1.5rem;">
                    <p style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-text-muted); margin-bottom: 0.6rem;">Material asignado</p>
                    <div id="m-materiales" class="mat-grid"></div>
                    <p id="m-sin-materiales" style="display: none; font-size: 0.8rem; color: var(--color-text-muted);">Sin material asignado todavía.</p>

                    @if($user->rol === 'administrador')
                    <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--color-border-inner);">
                        <button id="m-btn-agregar" class="btn-outline" style="width: 100%;" onclick="toggleSelector()">+ Agregar material</button>
                        <div id="m-selector" style="display: none; margin-top: 1rem;">
                            <p style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-text-muted); margin-bottom: 0.6rem;">Elige un material libre</p>
                            <div id="m-libres" class="mat-grid"></div>
                            <p id="m-sin-libres" style="display: none; font-size: 0.8rem; color: var(--color-text-muted);">No quedan materiales libres.</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </main>

    <script>
        const esAdmin = {{ $user->rol === 'administrador' ? 'true' : 'false' }};
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const placeholderSVG = '<svg style="width:2.5rem;height:2.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>';
        let eventoActual = null;

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
            const mesSel = document.getElementById('mes-selector');
            if (mesSel && !mesSel.contains(e.target)) {
                document.getElementById('mes-menu').classList.add('hidden');
            }
        });

        function toggleMes() {
            document.getElementById('mes-menu').classList.toggle('hidden');
        }

        // ── MODAL DE EVENTO ──

        function abrirEvento(eventId) {
            eventoActual = eventId;
            const modal = document.getElementById('modal-evento');
            modal.style.display = 'flex';

            document.getElementById('m-titulo').textContent = 'Cargando…';
            document.getElementById('m-creador').textContent = '';
            document.getElementById('m-horas').textContent = '';
            document.getElementById('m-materiales').innerHTML = '';
            document.getElementById('m-sin-materiales').style.display = 'none';
            if (esAdmin) {
                document.getElementById('m-selector').style.display = 'none';
            }

            cargarEvento();
        }

        function cargarEvento() {
            fetch('/calendario/evento/' + encodeURIComponent(eventoActual))
                .then(res => res.json())
                .then(data => {
                    document.getElementById('m-titulo').textContent = data.titulo;
                    document.getElementById('m-creador').textContent = 'Creado por ' + data.creador;
                    document.getElementById('m-horas').textContent = data.inicio + '  —  ' + data.fin;
                    pintarMateriales(data.materiales);
                    actualizarBadge(eventoActual, data.materiales.length);
                })
                .catch(() => {
                    document.getElementById('m-titulo').textContent = 'Error al cargar el evento';
                });
        }

        function pintarMateriales(materiales) {
            const cont = document.getElementById('m-materiales');
            const vacio = document.getElementById('m-sin-materiales');

            if (materiales.length === 0) {
                cont.innerHTML = '';
                vacio.style.display = 'block';
                return;
            }
            vacio.style.display = 'none';

            cont.innerHTML = materiales.map(m =>
                '<div class="mat-thumb">' +
                    '<div class="mat-thumb-img">' +
                        (m.imagen ? '<img src="' + m.imagen + '" alt="">' : placeholderSVG) +
                    '</div>' +
                    '<p class="mat-thumb-nombre" title="' + escapeHtml(m.nombre) + '">' + escapeHtml(m.nombre) + '</p>' +
                    (esAdmin ? '<button class="mat-thumb-quitar" title="Quitar material" onclick="quitarMaterial(' + m.id + ')">✕</button>' : '') +
                '</div>'
            ).join('');
        }

        // ── ACCIONES ADMIN ──

        function toggleSelector() {
            const selector = document.getElementById('m-selector');
            const abierto = selector.style.display !== 'none';
            if (abierto) {
                selector.style.display = 'none';
                return;
            }
            selector.style.display = 'block';
            cargarLibres();
        }

        function cargarLibres() {
            const cont = document.getElementById('m-libres');
            const vacio = document.getElementById('m-sin-libres');
            cont.innerHTML = '<p style="font-size:0.8rem; color:var(--color-text-muted);">Cargando…</p>';
            vacio.style.display = 'none';

            fetch('/materiales-libres')
                .then(res => res.json())
                .then(libres => {
                    if (libres.length === 0) {
                        cont.innerHTML = '';
                        vacio.style.display = 'block';
                        return;
                    }
                    cont.innerHTML = libres.map(m =>
                        '<div class="mat-thumb seleccionable" onclick="asignarMaterial(' + m.id + ')" title="Asignar ' + escapeHtml(m.nombre) + '">' +
                            '<div class="mat-thumb-img">' +
                                (m.imagen ? '<img src="' + m.imagen + '" alt="">' : placeholderSVG) +
                            '</div>' +
                            '<p class="mat-thumb-nombre">' + escapeHtml(m.nombre) + '</p>' +
                        '</div>'
                    ).join('');
                })
                .catch(() => {
                    cont.innerHTML = '<p style="font-size:0.8rem; color:#DC2626;">Error al cargar materiales.</p>';
                });
        }

        function asignarMaterial(idMat) {
            const titulo = document.getElementById('m-titulo').textContent;
            fetch('/calendario/evento/asignar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ google_event_id: eventoActual, google_event_titulo: titulo, id_mat: idMat })
            })
            .then(res => { if (!res.ok) throw new Error(); return res.json(); })
            .then(() => {
                cargarEvento();   // refresca el grid de asignados (y el badge)
                cargarLibres();   // refresca el selector, el material elegido desaparece de libres
            })
            .catch(() => alert('No se pudo asignar el material (puede que ya no esté libre).'));
        }

        function quitarMaterial(asignacionId) {
            fetch('/calendario/evento/quitar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ asignacion_id: asignacionId })
            })
            .then(res => { if (!res.ok) throw new Error(); return res.json(); })
            .then(() => {
                cargarEvento();
                // Si el selector está abierto, el material liberado vuelve a la lista
                if (esAdmin && document.getElementById('m-selector').style.display !== 'none') {
                    cargarLibres();
                }
            })
            .catch(() => alert('No se pudo quitar el material.'));
        }

        // ── UTILIDADES ──

        function actualizarBadge(eventoId, n) {
            document.querySelectorAll('.evento[data-evento-id="' + CSS.escape(eventoId) + '"] .evento-badge').forEach(b => {
                b.textContent = n;
                b.classList.toggle('visible', n > 0);
            });
        }

        function escapeHtml(texto) {
            const div = document.createElement('div');
            div.textContent = texto;
            return div.innerHTML;
        }

        function cerrarModal() {
            document.getElementById('modal-evento').style.display = 'none';
        }

        document.getElementById('modal-evento').addEventListener('click', function(e) {
            if (e.target === this) cerrarModal();
        });
    </script>

</body>
</html>