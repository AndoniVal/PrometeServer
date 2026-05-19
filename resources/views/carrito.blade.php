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

        .card { background-color: var(--color-card); border: 1px solid var(--color-border); border-radius: 0.6rem; backdrop-filter: blur(4px); }

        .card-row { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.5rem; border-bottom: 1px solid var(--color-border-inner); }
        .card-row:last-child { border-bottom: none; }

        .card-footer { padding: 1rem 1.5rem; background-color: rgba(102,100,96,0.06); border-top: 1px solid var(--color-border-inner); border-radius: 0 0 0.6rem 0.6rem; }

        .btn-primary { background-color: rgba(128, 128, 122); color: #F0D69C; padding: 0.6rem 1.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 0.35rem; border: 1px solid #333; transition: background-color 0.15s; display: inline-block; }
        .btn-primary:hover { background-color: rgb(100, 100, 95); }

        .btn-outline { background-color: transparent; color: var(--color-text); padding: 0.6rem 1.25rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 0.35rem; border: 1px solid var(--color-border); transition: border-color 0.15s; display: inline-block; }
        .btn-outline:hover { border-color: var(--color-accent); }

        .btn-danger { background-color: transparent; color: #DC2626; padding: 0.6rem 1.25rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 0.35rem; border: 1px solid rgba(220,38,38,0.3); transition: border-color 0.15s; display: inline-block; }
        .btn-danger:hover { border-color: #DC2626; }

        .dropdown { background-color: #3A3836; border: 1px solid var(--color-border); border-radius: 0.5rem; }
        .dropdown-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 1rem; font-size: 0.875rem; color: rgba(255,255,255,0.65); transition: background-color 0.15s, color 0.15s; }
        .dropdown-item:hover { background-color: rgba(255,255,255,0.08); color: #fff; }
        .dropdown-item.danger { color: #DC2626; }
        .dropdown-divider { border-top: 1px solid rgba(255,255,255,0.1); }

        .saldo-pill { display: flex; align-items: center; gap: 0.4rem; padding: 0.3rem 0.75rem; border: 1px solid rgba(212,184,122,0.35); border-radius: 999px; background-color: rgba(212,184,122,0.1); font-size: 0.72rem; font-weight: 600; font-family: 'Syne', sans-serif; }
        .saldo-ok     { color: #D4B87A; }
        .saldo-danger { color: #DC2626; }

        .resumen-label { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-text-muted); }
        .resumen-valor { font-family: 'Syne', sans-serif; font-weight: 700; color: var(--color-text); }
        .resumen-divider { border-top: 1px solid var(--color-border-inner); margin: 0.5rem 0; padding-top: 0.5rem; }
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
            <a href="{{ route('carrito') }}" class="relative transition" style="color: #F0D69C;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                @if(count($carrito) > 0)
                <span class="absolute -top-2 -right-2 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center" style="background-color: #D4B87A; color: #1C1C1C;">
                    {{ count($carrito) }}
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

    <main class="px-8 py-8 max-w-3xl mx-auto">

        @if(session('success'))
        <div class="mb-6 px-5 py-3 text-sm rounded-md" style="background-color: rgba(45,106,79,0.15); border: 1px solid rgba(45,106,79,0.4); color: #2D6A4F;">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="mb-6 px-5 py-3 text-sm rounded-md" style="background-color: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.3); color: #DC2626;">{{ $errors->first() }}</div>
        @endif

        <div class="mb-8 flex justify-between items-center">
            <div>
                <h2 class="font-syne text-3xl font-bold" style="color: var(--color-text);">Mi Carrito</h2>
                <p class="text-sm mt-1" style="color: var(--color-text-muted);">Revisa tu pedido antes de confirmar</p>
            </div>
            <a href="{{ route('economato') }}" class="btn-outline">← Seguir comprando</a>
        </div>

        @if(empty($carrito))
        {{-- Carrito vacío --}}
        <div class="card p-16 text-center">
            <p class="text-5xl mb-4">🛒</p>
            <p class="mb-6" style="color: var(--color-text-muted);">Tu carrito está vacío.</p>
            <a href="{{ route('economato') }}" class="btn-primary">Ir al economato</a>
        </div>

        @else

        {{-- Lista de productos --}}
        <div class="card mb-6">
            @foreach($carrito as $item)
            <div class="card-row">
                <div class="w-16 h-16 flex-shrink-0 overflow-hidden rounded" style="background-color: rgba(102,100,96,0.1);">
                    @if($item['imagen'])
                        <img src="{{ asset('storage/' . $item['imagen']) }}" alt="{{ $item['nombre'] }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="color: var(--color-text-muted);">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="font-syne font-bold" style="color: var(--color-text);">{{ $item['nombre'] }}</p>
                    <p class="text-xs mt-0.5" style="color: var(--color-text-muted);">{{ number_format($item['precio'], 2) }}€ / unidad</p>
                </div>
                <span class="text-sm" style="color: var(--color-text-soft);">x{{ $item['cantidad'] }}</span>
                <span class="font-syne font-bold w-20 text-right" style="color: var(--color-text);">
                    {{ number_format($item['precio'] * $item['cantidad'], 2) }}€
                </span>
                <form method="POST" action="{{ route('carrito.eliminar', $item['id']) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs uppercase tracking-wider transition" style="color: #DC2626;">Quitar</button>
                </form>
            </div>
            @endforeach

            {{-- Total --}}
            <div class="flex justify-between items-center px-6 py-4" style="border-top: 1px solid var(--color-border-inner);">
                <span class="resumen-label">Total pedido</span>
                <span class="font-syne text-2xl font-bold" style="color: var(--color-text);">{{ number_format($total, 2) }}€</span>
            </div>

            {{-- Resumen saldo --}}
            <div class="card-footer">
                <div class="flex justify-between items-center mb-2">
                    <span class="resumen-label">Saldo actual</span>
                    <span class="resumen-valor {{ $user->saldo < 0 ? 'text-red-500' : '' }}">{{ number_format($user->saldo, 2) }}€</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="resumen-label">Este pedido</span>
                    <span style="color: var(--color-text-soft); font-weight: 500;">− {{ number_format($total, 2) }}€</span>
                </div>
                <div class="resumen-divider flex justify-between items-center">
                    <span class="resumen-label">Saldo tras compra</span>
                    @php $saldoTras = $user->saldo - $total; @endphp
                    <span class="font-syne font-bold text-lg" style="color: {{ $saldoTras < 0 ? '#DC2626' : '#2D6A4F' }};">
                        {{ number_format($saldoTras, 2) }}€
                    </span>
                </div>
                @if($saldoTras < 0)
                <p class="text-xs mt-2" style="color: #DC2626;">⚠ Esta compra generará una deuda de {{ number_format(abs($saldoTras), 2) }}€</p>
                @endif
            </div>
        </div>

        {{-- Acciones --}}
        <div class="flex gap-3 justify-between">
            <form method="POST" action="{{ route('carrito.vaciar') }}">
                @csrf
                <button type="submit" class="btn-danger">Vaciar carrito</button>
            </form>
            <form method="POST" action="{{ route('carrito.confirmar') }}">
                @csrf
                <button type="submit" class="btn-primary px-8">Confirmar pedido</button>
            </form>
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
            if (menu && !menu.contains(e.target)) {
                document.getElementById('dropdown').classList.add('hidden');
                document.getElementById('chevron').style.transform = 'rotate(0deg)';
            }
        });
    </script>

</body>
</html>