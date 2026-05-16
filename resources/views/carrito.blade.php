<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito — PROMETE</title>
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

            {{-- Icono carrito --}}
            <a href="{{ route('carrito') }}" class="relative text-yellow-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                @if(count($carrito) > 0)
                <span class="absolute -top-2 -right-2 bg-yellow-500 text-gray-950 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                    {{ count($carrito) }}
                </span>
                @endif
            </a>

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
        </div>
    </nav>

    <main class="px-8 py-8 max-w-3xl mx-auto">

        @if(session('success'))
        <div class="mb-6 bg-green-900/30 border border-green-700 text-green-400 px-5 py-3 text-sm">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="mb-6 bg-red-900/30 border border-red-700 text-red-400 px-5 py-3 text-sm">{{ $errors->first() }}</div>
        @endif

        <div class="mb-8 flex justify-between items-center">
            <div>
                <h2 class="font-syne text-3xl font-bold text-white">Mi Carrito</h2>
                <p class="text-gray-400 text-sm mt-1">Revisa tu pedido antes de confirmar</p>
            </div>
            <a href="{{ route('economato') }}" class="text-gray-400 hover:text-yellow-500 text-sm uppercase tracking-widest transition">
                ← Seguir comprando
            </a>
        </div>

        @if(empty($carrito))
        <div class="bg-gray-900 border border-gray-800 p-16 text-center">
            <p class="text-5xl mb-4">🛒</p>
            <p class="text-gray-500 mb-4">Tu carrito está vacío.</p>
            <a href="{{ route('economato') }}" class="bg-yellow-500 text-gray-950 px-6 py-2.5 text-sm font-medium uppercase tracking-wider hover:bg-yellow-400 transition inline-block">
                Ir al economato
            </a>
        </div>
        @else

        {{-- Lista de productos --}}
        <div class="bg-gray-900 border border-gray-800 mb-6">
            @foreach($carrito as $item)
            <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-800/50">
                <div class="w-16 h-16 bg-gray-800 flex-shrink-0 overflow-hidden">
                    @if($item['imagen'])
                        <img src="{{ asset('storage/' . $item['imagen']) }}" alt="{{ $item['nombre'] }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="font-syne font-bold text-white">{{ $item['nombre'] }}</p>
                    <p class="text-gray-500 text-xs mt-0.5">{{ number_format($item['precio'], 2) }}€ / unidad</p>
                </div>
                <span class="text-gray-400 text-sm">x{{ $item['cantidad'] }}</span>
                <span class="font-syne font-bold text-yellow-500 w-20 text-right">
                    {{ number_format($item['precio'] * $item['cantidad'], 2) }}€
                </span>
                <form method="POST" action="{{ route('carrito.eliminar', $item['id']) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-400 transition text-xs uppercase tracking-wider">
                        Quitar
                    </button>
                </form>
            </div>
            @endforeach

            {{-- Total pedido --}}
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <span class="text-gray-400 uppercase tracking-widest text-xs">Total pedido</span>
                <span class="font-syne text-2xl font-bold text-white">{{ number_format($total, 2) }}€</span>
            </div>

            {{-- Resumen saldo --}}
            <div class="px-6 py-4 bg-gray-800/50">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-400 text-xs uppercase tracking-widest">Saldo actual</span>
                    <span class="font-syne font-bold {{ $user->saldo < 0 ? 'text-red-400' : 'text-yellow-500' }}">
                        {{ number_format($user->saldo, 2) }}€
                    </span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-400 text-xs uppercase tracking-widest">Este pedido</span>
                    <span class="text-white font-medium">− {{ number_format($total, 2) }}€</span>
                </div>
                <div class="border-t border-gray-700 pt-2 mt-2 flex justify-between items-center">
                    <span class="text-gray-400 text-xs uppercase tracking-widest">Saldo tras compra</span>
                    @php $saldoTras = $user->saldo - $total; @endphp
                    <span class="font-syne font-bold text-lg {{ $saldoTras < 0 ? 'text-red-400' : 'text-green-400' }}">
                        {{ number_format($saldoTras, 2) }}€
                    </span>
                </div>
                @if($saldoTras < 0)
                <p class="text-red-500 text-xs mt-2">⚠ Esta compra generará una deuda de {{ number_format(abs($saldoTras), 2) }}€</p>
                @endif
            </div>
        </div>

        {{-- Acciones --}}
        <div class="flex gap-3 justify-between">
            <form method="POST" action="{{ route('carrito.vaciar') }}">
                @csrf
                <button type="submit" class="px-5 py-2.5 text-sm text-red-400 border border-red-900/50 hover:border-red-500 transition uppercase tracking-wider">
                    Vaciar carrito
                </button>
            </form>
            <form method="POST" action="{{ route('carrito.confirmar') }}">
                @csrf
                <button type="submit" class="bg-yellow-500 text-gray-950 px-8 py-2.5 text-sm font-medium uppercase tracking-wider hover:bg-yellow-400 transition">
                    Confirmar pedido
                </button>
            </form>
        </div>

        @endif

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