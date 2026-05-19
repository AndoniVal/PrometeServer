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

        /* ── MENU CARDS ── */
        .menu-card {
            background-color: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: 0.6rem;
            backdrop-filter: blur(4px);
            position: relative;
            overflow: hidden;
            transition: transform 0.18s ease, border-color 0.18s ease, background-color 0.18s ease;
        }
        .menu-card:hover {
            transform: translateY(-4px);
            border-color: var(--color-accent);
            background-color: var(--color-card-hover);
        }
        .menu-card .card-icon {
            background-color: rgba(102, 100, 96, 0.1);
            border: 1px solid rgba(102, 100, 96, 0.2);
            transition: transform 0.18s ease, background-color 0.18s ease;
        }
        .menu-card:hover .card-icon {
            transform: scale(1.1);
            background-color: rgba(102, 100, 96, 0.2);
        }
        .menu-card .card-arrow {
            color: var(--color-text-muted);
            transition: transform 0.18s ease, opacity 0.18s ease;
            opacity: 0;
            transform: translateX(-6px);
        }
        .menu-card:hover .card-arrow {
            opacity: 1;
            transform: translateX(0);
        }
        .menu-card h3 { color: var(--color-text); }
        .menu-card p  { color: var(--color-text-muted); }

        /* ── ANIMACIONES ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.4s ease both; }
        .d1 { animation-delay: 0.05s; }
        .d2 { animation-delay: 0.10s; }

        /* ── DROPDOWN ── */
        .dropdown { background-color: #3A3836; border: 1px solid var(--color-border); border-radius: 0.5rem; }
        .dropdown-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 1rem; font-size: 0.875rem; color: rgba(255,255,255,0.65); transition: background-color 0.15s, color 0.15s; }
        .dropdown-item:hover { background-color: rgba(255,255,255,0.08); color: #fff; }
        .dropdown-item.danger { color: #DC2626; }
        .dropdown-divider { border-top: 1px solid rgba(255,255,255,0.1); }

        /* ── SALDO ── */
        .saldo-pill { display: flex; align-items: center; gap: 0.4rem; padding: 0.3rem 0.75rem; border: 1px solid rgba(212,184,122,0.35); border-radius: 999px; background-color: rgba(212,184,122,0.1); font-size: 0.72rem; font-weight: 600; font-family: 'Syne', sans-serif; }
        .saldo-ok     { color: #D4B87A; }
        .saldo-danger { color: #DC2626; }

        /* ── TEXTOS PÁGINA ── */
        .page-label    { color: var(--color-text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; }
        .page-title    { font-family: 'Syne', sans-serif; font-size: 2.25rem; font-weight: 700; color: var(--color-text); }
        .page-subtitle { font-size: 0.875rem; color: var(--color-text-muted); margin-top: 0.5rem; }
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
                <a href="{{ route('transacciones') }}" class="text-sm uppercase tracking-widest transition" style="color: #D4B87A;" onmouseover="this.style.color='#F0D69C'" onmouseout="this.style.color='#D4B87A'">Mis pedidos</a>
                <a href="{{ route('inventario') }}"    class="text-sm uppercase tracking-widest transition" style="color: #D4B87A;" onmouseover="this.style.color='#F0D69C'" onmouseout="this.style.color='#D4B87A'">Inventario</a>
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
            <a href="{{ route('carrito') }}" class="relative transition" style="color: #D4B87A;" onmouseover="this.style.color='#F0D69C'" onmouseout="this.style.color='#D4B87A'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </a>
            {{-- Dropdown usuario --}}
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
                        <p class="text-sm font-medium" style="color: #fff;">{{ $user->nombre }}</p>
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

    <main class="px-8 py-10 max-w-4xl mx-auto">

        {{-- ── CABECERA ── --}}
        <div class="mb-10 fade-up">
            <p class="page-label">Mi panel</p>
            <h2 class="page-title">Hola, {{ $user->nombre }} 👋</h2>
            <p class="page-subtitle">¿Qué quieres hacer hoy?</p>
        </div>

        {{-- ── GRID MENÚ ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Economato --}}
            <a href="{{ route('economato') }}" class="menu-card p-7 flex flex-col gap-5 fade-up d1">
                <div class="flex items-start justify-between">
                    <div class="card-icon w-14 h-14 flex items-center justify-center text-2xl">🛒</div>
                    <span class="card-arrow text-xl font-bold">→</span>
                </div>
                <div>
                    <h3 class="font-syne text-xl font-bold">Economato</h3>
                    <p class="text-sm mt-1">Explora y compra productos disponibles</p>
                </div>
            </a>

            {{-- Mis Pedidos --}}
            <a href="{{ route('transacciones') }}" class="menu-card p-7 flex flex-col gap-5 fade-up d2">
                <div class="flex items-start justify-between">
                    <div class="card-icon w-14 h-14 flex items-center justify-center text-2xl">📋</div>
                    <span class="card-arrow text-xl font-bold">→</span>
                </div>
                <div>
                    <h3 class="font-syne text-xl font-bold">Mis Pedidos</h3>
                    <p class="text-sm mt-1">Consulta el historial de tus compras</p>
                </div>
            </a>

            {{-- Inventario --}}
            <a href="{{ route('inventario') }}" class="menu-card p-7 flex flex-col gap-5 fade-up d2">
                <div class="flex items-start justify-between">
                    <div class="card-icon w-14 h-14 flex items-center justify-center text-2xl">📦</div>
                    <span class="card-arrow text-xl font-bold">→</span>
                </div>
                <div>
                    <h3 class="font-syne text-xl font-bold">Inventario</h3>
                    <p class="text-sm mt-1">Consulta y solicita préstamos de material</p>
                </div>
            </a>

        </div>

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