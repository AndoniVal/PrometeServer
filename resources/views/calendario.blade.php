<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario — PROMETE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
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

        .card {
            background-color: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: 0.6rem;
            color: var(--color-text);
            backdrop-filter: blur(4px);
        }

        /* saldo navbar */
        .saldo-pill {
            display: flex; align-items: center; gap: 0.4rem;
            padding: 0.3rem 0.75rem;
            border: 1px solid rgba(212,184,122,0.35);
            border-radius: 999px;
            background-color: rgba(212,184,122,0.1);
            font-size: 0.72rem; font-weight: 600; font-family: 'Syne', sans-serif;
        }
        .saldo-ok { color: var(--color-accent); }
        .saldo-danger { color: var(--color-danger); }

        /* dropdown usuario */
        .dropdown { background-color: #3A3836; border: 1px solid var(--color-border); border-radius: 0.5rem; }
        .dropdown-item {
            display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 1rem;
            font-size: 0.875rem; color: rgba(255,255,255,0.7); transition: background-color 0.15s, color 0.15s;
        }
        .dropdown-item:hover { background-color: rgba(255,255,255,0.08); color: #FFFFFF; }
        .dropdown-item.danger { color: var(--color-danger); }
        .dropdown-divider { border-top: 1px solid var(--color-border-inner); }

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

        .cal-grid {
            display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px;
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
        }
        .cal-day.otro-mes { background-color: rgba(224,223,215,0.4); }
        .cal-day.otro-mes .cal-daynum { color: rgba(97,97,95,0.35); }
        .cal-daynum { font-size: 0.78rem; color: var(--color-text-soft); align-self: flex-end; font-weight: 500; }
        .cal-day.hoy .cal-daynum {
            background-color: #D4B87A; color: #1C1C1C; font-weight: 700;
            width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border-radius: 50%;
        }
        .evento {
            background-color: rgba(212,184,122,0.22); border-left: 2px solid #D4B87A;
            padding: 0.22rem 0.4rem; font-size: 0.7rem; color: #5c4f30; cursor: pointer;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; border-radius: 0.2rem;
            transition: background-color 0.15s;
        }
        .evento:hover { background-color: rgba(212,184,122,0.35); }

        .conectar { text-align: center; padding: 4rem 2rem; }
        .conectar p { color: var(--color-text); margin-bottom: 1.5rem; }
        .btn-primary {
            background-color: #D4B87A; color: #1C1C1C; padding: 0.8rem 2.2rem;
            font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.9rem;
            text-transform: uppercase; letter-spacing: 0.05em; text-decoration: none;
            display: inline-block; border-radius: 0.4rem;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <a href="{{ route('calendario') }}"    class="text-sm uppercase tracking-widest transition" style="color: #F0D69C;">Calendario</a>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="saldo-pill {{ $user->saldo < 0 ? 'saldo-danger' : 'saldo-ok' }}">
                {{ number_format($user->saldo, 2) }}€
            </div>
            <div class="relative" id="user-menu">
                <button onclick="toggleDropdown()" class="flex items-center gap-3 focus:outline-none">
                    <div class="w-9 h-9 rounded-full overflow-hidden border-2 flex items-center justify-center" style="border-color: #D4B87A; background-color: rgba(212,184,122,0.2);">
                        <span class="font-syne text-xs font-bold" style="color: #D4B87A;">{{ strtoupper(substr($user->nombre, 0, 2)) }}</span>
                    </div>
                    <span class="text-sm hidden md:block" style="color: #D4B87A;">{{ $user->nombre }}</span>
                </button>
                <div id="dropdown" class="dropdown hidden absolute right-0 mt-3 w-52 shadow-xl z-50">
                    <div class="px-4 py-3 dropdown-divider">
                        <p class="text-sm font-medium" style="color: #FFFFFF;">{{ $user->nombre }}</p>
                        <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.45);">{{ ucfirst($user->rol) }}</p>
                    </div>
                    <div class="py-1 dropdown-divider">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item danger w-full">Cerrar sesión</button>
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
                                    {{-- Enganche futuro: clic → inventario de este evento (id en data-evento-id) --}}
                                <div class="evento" title="{{ $evento['titulo'] }}"
                                     onclick="abrirEvento('{{ $evento['id'] }}', '{{ addslashes($evento['titulo']) }}')">
                                    {{ $evento['titulo'] }}
                                </div>
                                @endforeach
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

        @endif

        {{-- ── MODAL DE EVENTO ── --}}
        <div id="modal-evento" class="hidden" style="position: fixed; inset: 0; background: rgba(28,28,28,0.6); z-index: 100; display: none; align-items: center; justify-content: center; padding: 1rem;">
            <div class="card" style="width: 100%; max-width: 460px; padding: 0;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.15);">
                    <div>
                        <h3 id="m-titulo" class="font-syne" style="font-size: 1.3rem; font-weight: 800; color: rgba(97,97,95);"></h3>
                        <p id="m-creador" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(97,97,95); opacity: 0.7; margin-top: 0.2rem;"></p>
                    </div>
                    <button onclick="cerrarModal()" style="background: none; border: none; font-size: 1.5rem; line-height: 1; cursor: pointer; color: rgba(97,97,95);">&times;</button>
                </div>

                <div style="padding: 1.25rem 1.5rem;">
                    <p id="m-horas" style="font-size: 0.85rem; color: rgba(97,97,95); margin-bottom: 1.25rem;"></p>

                    <p style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(97,97,95); opacity: 0.7; margin-bottom: 0.6rem;">Material asignado</p>
                    <div id="m-materiales" style="display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1.5rem;"></div>

                    <button id="m-agregar" style="width: 100%; background-color: #D4B87A; color: #1C1C1C; padding: 0.7rem; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; border: none; border-radius: 0.4rem; cursor: pointer;">
                        + Agregar material
                    </button>
                     <select id="m-selector" style="display:none; width:100%; margin-top:0.6rem; padding:0.6rem; border:1px solid rgba(102,100,96,0.5); border-radius:0.4rem; background:rgba(245,237,228,0.97); color:rgba(97,97,95); font-family:'DM Sans',sans-serif;"></select>
                </div>
            </div>
        </div>

    </main>

    <script>
        function toggleDropdown() {
            document.getElementById('dropdown').classList.toggle('hidden');
        }
        function toggleMes() {
            document.getElementById('mes-menu').classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            const userMenu = document.getElementById('user-menu');
            if (userMenu && !userMenu.contains(e.target)) {
                document.getElementById('dropdown').classList.add('hidden');
            }
            const mesSel = document.getElementById('mes-selector');
            if (mesSel && !mesSel.contains(e.target)) {
                document.getElementById('mes-menu').classList.add('hidden');
            }
        });

        let eventoActual = null;
        let tituloActual = null;
        const esAdmin = {{ auth()->user()->rol === 'administrador' ? 'true' : 'false' }};

        function abrirEvento(eventId, eventTitulo) {
            eventoActual = eventId;
            tituloActual = eventTitulo;

            const modal = document.getElementById('modal-evento');
            modal.style.display = 'flex';
            modal.classList.remove('hidden');

            document.getElementById('m-titulo').textContent = 'Cargando…';
            document.getElementById('m-creador').textContent = '';
            document.getElementById('m-horas').textContent = '';
            document.getElementById('m-materiales').innerHTML = '';
            document.getElementById('m-selector').style.display = 'none';

            fetch('/calendario/evento/' + encodeURIComponent(eventId))
                .then(res => res.json())
                .then(data => {
                    document.getElementById('m-titulo').textContent = data.titulo;
                    document.getElementById('m-creador').textContent = 'Creado por ' + data.creador;
                    document.getElementById('m-horas').textContent = data.inicio + '  —  ' + data.fin;
                    pintarMateriales(data.materiales);
                })
                .catch(() => {
                    document.getElementById('m-titulo').textContent = 'Error al cargar el evento';
                });
        }

        function pintarMateriales(materiales) {
            const cont = document.getElementById('m-materiales');
            if (materiales.length === 0) {
                cont.innerHTML = '<p style="font-size:0.8rem; color:rgba(97,97,95); opacity:0.6;">Sin material asignado todavía.</p>';
                return;
            }
            cont.innerHTML = materiales.map(m =>
                '<div style="display:flex; justify-content:space-between; align-items:center; background:rgba(212,184,122,0.18); border-left:2px solid #D4B87A; padding:0.4rem 0.6rem; font-size:0.8rem; color:#5c4f30; border-radius:0.2rem;">' +
                    '<span>' + m.nombre + '</span>' +
                    (esAdmin ? '<button onclick="quitarMaterial(' + m.id + ')" style="background:none;border:none;cursor:pointer;color:#a14444;font-size:0.9rem;">&times;</button>' : '') +
                '</div>'
            ).join('');
        }

        // Botón "+ Agregar material": carga los libres y muestra el selector
        document.getElementById('m-agregar').addEventListener('click', function() {
            const sel = document.getElementById('m-selector');
            sel.style.display = 'block';
            sel.innerHTML = '<option value="">Cargando…</option>';

            fetch('/materiales-libres')
                .then(res => res.json())
                .then(libres => {
                    if (libres.length === 0) {
                        sel.innerHTML = '<option value="">No hay materiales libres</option>';
                        return;
                    }
                    sel.innerHTML = '<option value="">Elige un material…</option>' +
                        libres.map(m => '<option value="' + m.id + '">' + m.nombre + '</option>').join('');
                });
        });

        // Al elegir un material del selector, lo asignamos
        document.getElementById('m-selector').addEventListener('change', function() {
            const idMat = this.value;
            if (!idMat) return;

            fetch('/calendario/evento/asignar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    google_event_id: eventoActual,
                    google_event_titulo: tituloActual,
                    id_mat: idMat
                })
            })
            .then(res => res.json())
            .then(() => abrirEvento(eventoActual, tituloActual)); // recargamos el modal
        });

        function quitarMaterial(asignacionId) {
            fetch('/calendario/evento/quitar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ asignacion_id: asignacionId })
            })
            .then(res => res.json())
            .then(() => abrirEvento(eventoActual, tituloActual));
        }

        function cerrarModal() {
            const modal = document.getElementById('modal-evento');
            modal.style.display = 'none';
            modal.classList.add('hidden');
        }

        document.getElementById('modal-evento').addEventListener('click', function(e) {
            if (e.target === this) cerrarModal();
        });

        function cerrarModal() {
            const modal = document.getElementById('modal-evento');
            modal.style.display = 'none';
            modal.classList.add('hidden');
        }

        
        document.getElementById('modal-evento').addEventListener('click', function(e) {
            if (e.target === this) cerrarModal();
        });
    </script>

</body>
</html>