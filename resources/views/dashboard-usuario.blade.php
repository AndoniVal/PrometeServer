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

        .menu-card {
            position: relative;
            overflow: hidden;
            transition: transform 0.18s ease, border-color 0.18s ease, background-color 0.18s ease;
        }
        .menu-card:hover {
            transform: translateY(-4px);
            border-color: rgba(234,179,8,0.5);
            background-color: #161b22;
        }
        .menu-card .card-icon {
            transition: transform 0.18s ease, background-color 0.18s ease;
        }
        .menu-card:hover .card-icon {
            transform: scale(1.1);
            background-color: rgba(234,179,8,0.2);
        }
        .menu-card .card-arrow {
            transition: transform 0.18s ease, opacity 0.18s ease;
            opacity: 0;
            transform: translateX(-6px);
        }
        .menu-card:hover .card-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.4s ease both; }
        .d1 { animation-delay: 0.05s; }
        .d2 { animation-delay: 0.10s; }
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
                <a href="{{ route('economato') }}"     class="text-gray-400 hover:text-yellow-500 transition text-sm uppercase tracking-widest">Economato</a>
                <a href="{{ route('transacciones') }}" class="text-gray-400 hover:text-yellow-500 transition text-sm uppercase tracking-widest">Mis pedidos</a>
            </div>
        </div>

        <div class="flex items-center gap-4">

            {{-- Icono carrito --}}
            <a href="{{ route('carrito') }}" class="relative text-gray-400 hover:text-yellow-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </a>

            <span class="text-gray-700">|</span>

            {{-- Dropdown usuario --}}
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

    <main class="px-8 py-10 max-w-4xl mx-auto">

        {{-- ── CABECERA ── --}}
        <div class="mb-10 fade-up">
            <p class="text-yellow-500 text-xs uppercase tracking-widest mb-1">Mi panel</p>
            <h2 class="font-syne text-4xl font-bold text-white">Hola, {{ $user->nombre }} 👋</h2>
            <p class="text-gray-500 text-sm mt-2">¿Qué quieres hacer hoy?</p>
        </div>

        {{-- ── GRID MENÚ (2 tarjetas) ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Economato --}}
            <a href="{{ route('economato') }}"
               class="menu-card bg-gray-900 border border-gray-800 p-7 flex flex-col gap-5 fade-up d1">
                <div class="flex items-start justify-between">
                    <div class="card-icon w-14 h-14 bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-2xl">
                        🛒
                    </div>
                    <span class="card-arrow text-yellow-500 text-xl font-bold">→</span>
                </div>
                <div>
                    <h3 class="font-syne text-xl font-bold text-white">Economato</h3>
                    <p class="text-gray-500 text-sm mt-1">Explora y compra productos disponibles</p>
                </div>
            </a>

            {{-- Mis Pedidos --}}
            <a href="{{ route('transacciones') }}"
               class="menu-card bg-gray-900 border border-gray-800 p-7 flex flex-col gap-5 fade-up d2">
                <div class="flex items-start justify-between">
                    <div class="card-icon w-14 h-14 bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-2xl">
                        📋
                    </div>
                    <span class="card-arrow text-yellow-500 text-xl font-bold">→</span>
                </div>
                <div>
                    <h3 class="font-syne text-xl font-bold text-white">Mis Pedidos</h3>
                    <p class="text-gray-500 text-sm mt-1">Consulta el historial de tus compras</p>
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
