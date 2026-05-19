<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Economato — PROMETE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        /* ── FUENTES ── */
        body { font-family: 'DM Sans', sans-serif; }
        .font-syne { font-family: 'Syne', sans-serif; }

        /* ── VARIABLES DE COLOR ── */
        :root {
            --color-bg:           #F5DDC4;
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

        /* ── TARJETA ── */
        .card {
            background-color: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: 0.6rem;
            color: var(--color-text);
            backdrop-filter: blur(4px);
        }
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
            color: var(--color-text-muted);
            margin-top: 0.15rem;
        }
        .card-link {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 500;
            color: var(--color-accent);
            transition: color 0.15s;
        }
        .card-link:hover { color: #1C1C1C; }

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
        .tabla tbody tr { transition: background-color 0.15s; }
        .tabla tbody tr:hover { background-color: rgba(102,100,96,0.08); }
        .td-main   { color: var(--color-text); }
        .td-soft   { color: var(--color-text-soft); }
        .td-muted  { color: var(--color-text-muted); }
        .td-accent { color: var(--color-accent); font-weight: 600; }

        /* ── STAT CARD ── */
        .stat-card {
            background-color: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: 0.6rem;
            padding: 1.25rem;
            backdrop-filter: blur(4px);
        }
        .stat-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--color-text-muted);
            margin-bottom: 0.5rem;
        }
        .stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--color-text);
        }

        /* ── PRODUCTO CARD ── */
        .producto-card {
            background-color: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: 0.6rem;
            cursor: pointer;
            transition: border-color 0.15s, background-color 0.15s;
            overflow: hidden;
            backdrop-filter: blur(4px);
        }
        .producto-card:hover {
            border-color: var(--color-accent);
            background-color: var(--color-card-hover);
        }

        /* ── BOTONES ── */
        .btn-primary {
            background-color: #1C1C1C;
            color: #F0D69C;
            padding: 0.6rem 1.25rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 0.35rem;
            border: 1px solid #333;
            transition: background-color 0.15s;
        }
        .btn-primary:hover { background-color: #333; }

        .btn-outline {
            background-color: transparent;
            color: var(--color-text);
            padding: 0.6rem 1.25rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 0.35rem;
            border: 1px solid var(--color-border);
            transition: border-color 0.15s, color 0.15s;
        }
        .btn-outline:hover { border-color: var(--color-accent); }

        /* ── MODAL ── */
        .modal-card {
            background-color: #F5F0E8;
            border: 1px solid var(--color-border);
            border-radius: 0.6rem;
            color: var(--color-text);
        }
        .modal-header {
            border-bottom: 1px solid var(--color-border-inner);
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--color-text);
        }
        .modal-btn-close {
            color: var(--color-text-muted);
            transition: color 0.15s;
            font-size: 1.1rem;
        }
        .modal-btn-close:hover { color: var(--color-text); }

        .modal-action-btn {
            width: 100%;
            background-color: rgba(102,100,96,0.08);
            border: 1px solid var(--color-border);
            border-radius: 0.4rem;
            text-align: left;
            padding: 1rem 1.25rem;
            transition: border-color 0.15s, background-color 0.15s;
            color: var(--color-text);
        }
        .modal-action-btn:hover {
            border-color: var(--color-accent);
            background-color: rgba(102,100,96,0.15);
        }
        .modal-action-btn p:first-child { font-weight: 600; font-size: 0.9rem; }
        .modal-action-btn p:last-child  { font-size: 0.7rem; color: var(--color-text-muted); margin-top: 0.2rem; }

        .form-input {
            width: 100%;
            background-color: rgba(102,100,96,0.08);
            border: 1px solid var(--color-border);
            border-radius: 0.35rem;
            color: var(--color-text);
            padding: 0.6rem 1rem;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.15s;
        }
        .form-input:focus { border-color: var(--color-accent); }
        .form-label {
            display: block;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--color-text-muted);
            margin-bottom: 0.4rem;
        }

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
            color: rgba(255,255,255,0.65);
            transition: background-color 0.15s, color 0.15s;
        }
        .dropdown-item:hover { background-color: rgba(255,255,255,0.08); color: #fff; }
        .dropdown-item.danger { color: #DC2626; }
        .dropdown-divider { border-top: 1px solid rgba(255,255,255,0.1); }

        /* ── SALDO PILL ── */
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
        .saldo-ok     { color: #D4B87A; }
        .saldo-danger { color: #DC2626; }

        /* ── BUSCADOR ── */
        .buscador {
            width: 100%;
            background-color: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: 0.4rem;
            color: var(--color-text);
            padding: 0.65rem 1rem;
            font-size: 0.875rem;
            outline: none;
            backdrop-filter: blur(4px);
            transition: border-color 0.15s;
        }
        .buscador:focus { border-color: var(--color-accent); }
        .buscador::placeholder { color: var(--color-text-muted); }

        /* ── BADGE STOCK ── */
        .badge-sin-stock  { background-color: rgba(220,38,38,0.7);  color: #fff; }
        .badge-stock-bajo { background-color: rgba(180,140,40,0.7); color: #fff; }
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
                <a href="{{ route('economato') }}"     class="text-sm uppercase tracking-widest font-semibold" style="color: #F0D69C;">Economato</a>
                <a href="{{ route('transacciones') }}" class="text-sm uppercase tracking-widest transition" style="color: #D4B87A;" onmouseover="this.style.color='#F0D69C'" onmouseout="this.style.color='#D4B87A'">Transacciones</a>
                <a href="{{ route('inventario') }}"    class="text-sm uppercase tracking-widest transition" style="color: #D4B87A;" onmouseover="this.style.color='#F0D69C'" onmouseout="this.style.color='#D4B87A'">Inventario</a>
            </div>
        </div>
        <div class="flex items-center gap-4">
            {{-- Carrito --}}
            <a href="{{ route('carrito') }}" class="relative transition" style="color: #D4B87A;" onmouseover="this.style.color='#F0D69C'" onmouseout="this.style.color='#D4B87A'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                @php $cantidadCarrito = count(session()->get('carrito', [])); @endphp
                @if($cantidadCarrito > 0)
                <span class="absolute -top-2 -right-2 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center" style="background-color: #D4B87A; color: #1C1C1C;">
                    {{ $cantidadCarrito }}
                </span>
                @endif
            </a>
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

    <main class="px-8 py-8 max-w-7xl mx-auto">

        @if(session('success'))
        <div class="mb-6 px-5 py-3 text-sm rounded-md" style="background-color: rgba(45,106,79,0.15); border: 1px solid rgba(45,106,79,0.4); color: #2D6A4F;">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="mb-6 px-5 py-3 text-sm rounded-md" style="background-color: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.3); color: #DC2626;">{{ $errors->first() }}</div>
        @endif

        {{-- ── CABECERA ── --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="font-syne text-3xl font-bold" style="color: rgba(97,97,95);">Economato</h2>
                <p class="text-sm mt-1" style="color: rgba(97,97,95,0.7);">Selecciona los productos que necesitas</p>
            </div>
            @if(Auth::user()->rol === 'administrador')
            <div class="flex gap-3">
                <button onclick="abrirModalReponer('añadir')" class="btn-outline">+ Reponer Stock</button>
                <button onclick="document.getElementById('modal-admin').classList.remove('hidden')" class="btn-primary">⚙ Administrar</button>
            </div>
            @endif
        </div>

        {{-- ── STATS ── --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="stat-card">
                <p class="stat-label">Total Productos</p>
                <p class="stat-value">{{ $totalProductos }}</p>
            </div>
            <div class="stat-card">
                <p class="stat-label">Stock Bajo</p>
                <p class="stat-value">{{ $stockBajo }}</p>
            </div>
            <div class="stat-card">
                <p class="stat-label">Compras Este Mes</p>
                <p class="stat-value">{{ $comprasMes }}</p>
            </div>
        </div>

        {{-- ── BUSCADOR ── --}}
        <div class="mb-5">
            <input type="text" id="buscador-productos" placeholder="Buscar producto..." onkeyup="filtrarProductos()" class="buscador">
        </div>

        {{-- ── GRID DE PRODUCTOS ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-8">
            @forelse($productos as $producto)
            <div onclick="abrirModalProducto({{ $producto->id }},'{{ addslashes($producto->nombre) }}','{{ addslashes($producto->descripcion) }}',{{ $producto->stock }},{{ $producto->precio }},'{{ $producto->imagen ? asset('storage/' . $producto->imagen) : '' }}')"
                data-nombre="{{ strtolower($producto->nombre) }}"
                class="producto-card">
                <div class="aspect-square overflow-hidden relative" style="background-color: rgba(102,100,96,0.1);">
                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="color: rgba(102,100,96,0.3);">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                    @if($producto->stock === 0)
                    <div class="badge-sin-stock absolute top-2 left-2 text-xs px-2 py-0.5 uppercase tracking-wider rounded">Sin stock</div>
                    @elseif($producto->stock < 10)
                    <div class="badge-stock-bajo absolute top-2 left-2 text-xs px-2 py-0.5 uppercase tracking-wider rounded">⚠ Stock bajo</div>
                    @endif
                </div>
                <div class="p-4">
                    <p class="font-syne font-bold text-sm mb-1" style="color: var(--color-text);">{{ $producto->nombre }}</p>
                    <p class="text-xs mb-3" style="color: var(--color-text-muted);">{{ $producto->descripcion }}</p>
                    <div class="flex justify-between items-center">
                        <span class="font-syne font-bold" style="color: var(--color-text);">{{ number_format($producto->precio, 2) }}€</span>
                        <span class="text-xs" style="color: var(--color-text-muted);">{{ $producto->stock }} uds.</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-4 py-20 text-center" style="color: var(--color-text-muted);">
                <p class="text-4xl mb-3">📦</p>
                <p>No hay productos en el economato aún.</p>
            </div>
            @endforelse
        </div>

        {{-- ── MIS ÚLTIMOS MOVIMIENTOS ── --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <p class="card-title">Mis Últimas Compras</p>
                    <p class="card-subtitle">Tus 10 movimientos más recientes en el economato</p>
                </div>
                <a href="{{ route('transacciones') }}" class="card-link">Ver todos →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="tabla w-full">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio/ud</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($misMovimientos as $t)
                        <tr>
                            <td class="td-muted">{{ \Carbon\Carbon::parse($t->fecha)->format('d/m/Y H:i') }}</td>
                            <td class="td-main">{{ $t->producto->nombre }}</td>
                            <td class="td-soft">{{ $t->cantidad }}</td>
                            <td class="td-muted">{{ number_format($t->precio_unidad, 2) }}€</td>
                            <td class="td-accent">{{ number_format($t->total, 2) }}€</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="td-muted text-center py-10">No has realizado ninguna compra aún.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    {{-- ── MODAL DETALLE PRODUCTO ── --}}
    <div id="modal-producto" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
        <div class="modal-card w-full max-w-2xl">
            <div class="aspect-video overflow-hidden relative" style="background-color: rgba(102,100,96,0.1);">
                <img id="modal-img" src="" alt="" class="w-full h-full object-cover">
                <div id="modal-img-placeholder" class="hidden absolute inset-0 flex items-center justify-center" style="color: rgba(102,100,96,0.3);">
                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <button onclick="cerrarModalProducto()" class="modal-btn-close absolute top-3 right-3 w-8 h-8 flex items-center justify-center rounded" style="background-color: rgba(0,0,0,0.4);">✕</button>
                <div id="modal-stock-aviso"    class="hidden absolute top-3 left-3 badge-stock-bajo text-xs px-3 py-1 uppercase tracking-wider rounded">⚠ Stock bajo</div>
                <div id="modal-sin-stock-aviso" class="hidden absolute top-3 left-3 badge-sin-stock text-xs px-3 py-1 uppercase tracking-wider rounded">Sin stock</div>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 id="modal-nombre" class="font-syne text-2xl font-bold" style="color: var(--color-text);"></h3>
                        <p id="modal-tipo"    class="text-sm mt-1" style="color: var(--color-text-muted);"></p>
                    </div>
                    <span id="modal-precio" class="font-syne text-2xl font-bold" style="color: var(--color-text);"></span>
                </div>
                <p id="modal-stock-texto" class="text-xs mb-6" style="color: var(--color-text-muted);"></p>
                <form method="POST" action="{{ route('carrito.agregar') }}">
                    @csrf
                    <input type="hidden" name="id_prod" id="modal-id-prod">
                    <div class="flex gap-3 items-center">
                        <div class="flex items-center rounded" style="border: 1px solid var(--color-border); background-color: rgba(102,100,96,0.08);">
                            <button type="button" onclick="cambiarCantidad(-1)" class="px-3 py-2 text-lg transition" style="color: var(--color-text-muted);">−</button>
                            <input type="number" name="cantidad" id="modal-cantidad" value="1" min="1" class="w-16 bg-transparent text-center py-2 focus:outline-none" style="color: var(--color-text);">
                            <button type="button" onclick="cambiarCantidad(1)" class="px-3 py-2 text-lg transition" style="color: var(--color-text-muted);">+</button>
                        </div>
                        <button type="submit" id="btn-agregar" class="flex-1 btn-primary">Añadir al carrito</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── MODALES ADMIN ── --}}
    @if(Auth::user()->rol === 'administrador')

    {{-- Modal administrar --}}
    <div id="modal-admin" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
        <div class="modal-card w-full max-w-3xl max-h-screen overflow-y-auto">
            <div class="modal-header sticky top-0" style="background-color: #F5F0E8; z-index: 10;">
                <p class="modal-title">⚙ Administrar Economato</p>
                <button onclick="document.getElementById('modal-admin').classList.add('hidden')" class="modal-btn-close">✕</button>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col gap-3">
                    <p class="stat-label mb-1">Acciones</p>
                    <button onclick="abrirSubModal('modal-nuevo-producto')" class="modal-action-btn">
                        <p>+ Añadir producto</p>
                        <p>Crear un nuevo producto en el economato</p>
                    </button>
                    <button onclick="abrirSubModal('modal-eliminar-producto')" class="modal-action-btn" style="border-color: rgba(220,38,38,0.3);">
                        <p style="color: #DC2626;">🗑 Eliminar producto</p>
                        <p>Eliminar un producto del economato</p>
                    </button>
                    <button onclick="abrirSubModal('modal-stock')" class="modal-action-btn">
                        <p>📦 Gestionar stock</p>
                        <p>Actualizar cantidades de productos</p>
                    </button>
                    <button onclick="abrirSubModal('modal-precios')" class="modal-action-btn">
                        <p>💰 Gestionar precios</p>
                        <p>Actualizar precios de productos</p>
                    </button>
                    <a href="{{ route('finanzas') }}" class="modal-action-btn block">
                        <p>📊 Vista Finanzas</p>
                        <p>Control de gastos, deudas e inventario</p>
                    </a>
                </div>
                <div>
                    <p class="stat-label mb-3">Últimos movimientos</p>
                    <div class="flex flex-col gap-2 max-h-80 overflow-y-auto">
                        @forelse($todosMovimientos as $t)
                        <div class="px-4 py-3 rounded" style="background-color: rgba(102,100,96,0.08); border: 1px solid var(--color-border-inner);">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium td-main">{{ $t->usuario->nombre }}</p>
                                    <p class="text-xs td-muted mt-0.5">{{ $t->producto->nombre }} × {{ $t->cantidad }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold td-accent">{{ number_format($t->total, 2) }}€</p>
                                    <p class="text-xs td-muted">{{ \Carbon\Carbon::parse($t->fecha)->format('d/m H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="td-muted text-sm text-center py-8">No hay movimientos aún.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Submodal: Nuevo producto --}}
    <div id="modal-nuevo-producto" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-[60] p-4">
        <div class="modal-card w-full max-w-md">
            <div class="modal-header">
                <p class="modal-title">Nuevo Producto</p>
                <button onclick="document.getElementById('modal-nuevo-producto').classList.add('hidden')" class="modal-btn-close">✕</button>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" required class="form-input">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Descripción</label>
                        <input type="text" name="descripcion" required class="form-input">
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="form-label">Stock inicial</label>
                            <input type="number" name="stock" min="0" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Precio (€)</label>
                            <input type="number" name="precio" min="0" step="0.01" required class="form-input">
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="form-label">Imagen</label>
                        <input type="file" name="imagen" accept="image/*" class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-medium file:uppercase file:rounded file:cursor-pointer file:transition" style="color: var(--color-text-muted); --tw-file-bg: #1C1C1C;">
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('modal-nuevo-producto').classList.add('hidden')" class="btn-outline">Cancelar</button>
                        <button type="submit" class="btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Submodal: Eliminar producto --}}
    <div id="modal-eliminar-producto" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-[60] p-4">
        <div class="modal-card w-full max-w-md">
            <div class="modal-header">
                <p class="modal-title" style="color: #DC2626;">🗑 Eliminar Producto</p>
                <button onclick="document.getElementById('modal-eliminar-producto').classList.add('hidden')" class="modal-btn-close">✕</button>
            </div>
            <div class="p-6">
                <p class="text-sm mb-4" style="color: var(--color-text-soft);">Selecciona el producto que deseas eliminar. Esta acción no se puede deshacer.</p>
                <form method="POST" id="form-eliminar" action="" onsubmit="return confirm('¿Seguro que quieres eliminar este producto?')">
                    @csrf
                    @method('DELETE')
                    <div class="mb-6">
                        <label class="form-label">Producto</label>
                        <select id="select-eliminar" onchange="setEliminarAction(this.value)" class="form-input">
                            <option value="">— Selecciona un producto —</option>
                            @foreach($productos as $producto)
                            <option value="{{ route('productos.destroy', $producto->id) }}">{{ $producto->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('modal-eliminar-producto').classList.add('hidden')" class="btn-outline">Cancelar</button>
                        <button type="submit" class="px-5 py-2 text-sm font-medium uppercase tracking-wider rounded transition text-white" style="background-color: #DC2626;">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Submodal: Gestionar stock --}}
    <div id="modal-stock" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-[60] p-4">
        <div class="modal-card w-full max-w-lg">
            <div class="modal-header">
                <p class="modal-title">📦 Gestionar Stock</p>
                <button onclick="document.getElementById('modal-stock').classList.add('hidden')" class="modal-btn-close">✕</button>
            </div>
            <div class="p-6 max-h-96 overflow-y-auto">
                @foreach($productos as $producto)
                <form method="POST" action="{{ route('productos.update', $producto->id) }}" class="flex items-center gap-3 mb-3">
                    @csrf
                    @method('PUT')
                    <span class="text-sm flex-1 truncate td-main">{{ $producto->nombre }}</span>
                    <input type="number" name="stock" value="{{ $producto->stock }}" min="0" class="form-input w-24">
                    <input type="hidden" name="nombre" value="{{ $producto->nombre }}">
                    <input type="hidden" name="descripcion" value="{{ $producto->descripcion }}">
                    <input type="hidden" name="precio" value="{{ $producto->precio }}">
                    <button type="submit" class="btn-primary px-3 py-1.5 text-xs">OK</button>
                </form>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Submodal: Gestionar precios --}}
    <div id="modal-precios" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-[60] p-4">
        <div class="modal-card w-full max-w-lg">
            <div class="modal-header">
                <p class="modal-title">💰 Gestionar Precios</p>
                <button onclick="document.getElementById('modal-precios').classList.add('hidden')" class="modal-btn-close">✕</button>
            </div>
            <div class="p-6 max-h-96 overflow-y-auto">
                @foreach($productos as $producto)
                <form method="POST" action="{{ route('productos.update', $producto->id) }}" class="flex items-center gap-3 mb-3">
                    @csrf
                    @method('PUT')
                    <span class="text-sm flex-1 truncate td-main">{{ $producto->nombre }}</span>
                    <div class="flex items-center gap-1">
                        <input type="number" name="precio" value="{{ $producto->precio }}" min="0" step="0.01" class="form-input w-24">
                        <span class="text-xs td-muted">€</span>
                    </div>
                    <input type="hidden" name="nombre" value="{{ $producto->nombre }}">
                    <input type="hidden" name="descripcion" value="{{ $producto->descripcion }}">
                    <input type="hidden" name="stock" value="{{ $producto->stock }}">
                    <button type="submit" class="btn-primary px-3 py-1.5 text-xs">OK</button>
                </form>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Modal reponer stock --}}
    <div id="modal-reponer" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-[60] p-4">
        <div class="modal-card w-full max-w-md">
            <div class="flex" style="border-bottom: 1px solid var(--color-border-inner);">
                <button id="tab-añadir" onclick="cambiarTab('añadir')" class="flex-1 px-6 py-4 text-sm font-medium uppercase tracking-wider transition border-b-2" style="border-color: #2D6A4F; color: #2D6A4F;">+ Añadir Stock</button>
                <button id="tab-eliminar" onclick="cambiarTab('eliminar')" class="flex-1 px-6 py-4 text-sm font-medium uppercase tracking-wider transition border-b-2 border-transparent td-muted">− Retirar Stock</button>
                <button onclick="document.getElementById('modal-reponer').classList.add('hidden')" class="px-4 modal-btn-close">✕</button>
            </div>
            <div id="contenido-añadir" class="p-6">
                <p class="text-sm mb-5 td-soft">Selecciona el producto y las unidades a reponer.</p>
                <form method="POST" action="{{ route('stock.añadir') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Producto</label>
                        <select name="id_prod" required class="form-input">
                            <option value="">— Selecciona un producto —</option>
                            @foreach($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombre }} (stock: {{ $producto->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="form-label">Unidades a añadir</label>
                        <input type="number" name="cantidad" min="1" required class="form-input">
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('modal-reponer').classList.add('hidden')" class="btn-outline">Cancelar</button>
                        <button type="submit" class="px-5 py-2 text-sm font-medium uppercase tracking-wider rounded text-white transition" style="background-color: #2D6A4F;">Añadir</button>
                    </div>
                </form>
            </div>
            <div id="contenido-eliminar" class="p-6 hidden">
                <p class="text-sm mb-5 td-soft">Selecciona el producto y las unidades a retirar del stock.</p>
                <form method="POST" action="{{ route('stock.eliminar') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Producto</label>
                        <select name="id_prod" required class="form-input">
                            <option value="">— Selecciona un producto —</option>
                            @foreach($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombre }} (stock: {{ $producto->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="form-label">Unidades a retirar</label>
                        <input type="number" name="cantidad" min="1" required class="form-input">
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('modal-reponer').classList.add('hidden')" class="btn-outline">Cancelar</button>
                        <button type="submit" class="px-5 py-2 text-sm font-medium uppercase tracking-wider rounded text-white transition" style="background-color: #DC2626;">Retirar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endif

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

        function abrirSubModal(id) {
            document.getElementById('modal-admin').classList.add('hidden');
            document.getElementById(id).classList.remove('hidden');
        }

        function abrirModalProducto(id, nombre, tipo, stock, precio, imagen) {
            document.getElementById('modal-id-prod').value = id;
            document.getElementById('modal-nombre').textContent = nombre;
            document.getElementById('modal-tipo').textContent = tipo;
            document.getElementById('modal-precio').textContent = precio.toFixed(2) + '€';
            document.getElementById('modal-cantidad').value = 1;
            document.getElementById('modal-cantidad').max = stock;

            const img = document.getElementById('modal-img');
            const placeholder = document.getElementById('modal-img-placeholder');
            if (imagen) { img.src = imagen; img.classList.remove('hidden'); placeholder.classList.add('hidden'); }
            else { img.classList.add('hidden'); placeholder.classList.remove('hidden'); }

            document.getElementById('modal-stock-aviso').classList.add('hidden');
            document.getElementById('modal-sin-stock-aviso').classList.add('hidden');
            const btn = document.getElementById('btn-agregar');
            const txt = document.getElementById('modal-stock-texto');

            if (stock === 0) {
                document.getElementById('modal-sin-stock-aviso').classList.remove('hidden');
                txt.textContent = 'Producto sin stock disponible';
                btn.disabled = true; btn.classList.add('opacity-50','cursor-not-allowed');
            } else if (stock < 10) {
                document.getElementById('modal-stock-aviso').classList.remove('hidden');
                txt.textContent = '⚠ Solo quedan ' + stock + ' unidades disponibles';
                btn.disabled = false; btn.classList.remove('opacity-50','cursor-not-allowed');
            } else {
                txt.textContent = stock + ' unidades disponibles';
                btn.disabled = false; btn.classList.remove('opacity-50','cursor-not-allowed');
            }
            document.getElementById('modal-producto').classList.remove('hidden');
        }

        function cerrarModalProducto() { document.getElementById('modal-producto').classList.add('hidden'); }
        function cambiarCantidad(delta) {
            const input = document.getElementById('modal-cantidad');
            const max = parseInt(input.max) || 999;
            input.value = Math.min(max, Math.max(1, parseInt(input.value) + delta));
        }
        function setEliminarAction(url) { document.getElementById('form-eliminar').action = url; }
        function abrirModalReponer(tab) { cambiarTab(tab); document.getElementById('modal-reponer').classList.remove('hidden'); }
        function cambiarTab(tab) {
            const esAñadir = tab === 'añadir';
            document.getElementById('contenido-añadir').classList.toggle('hidden', !esAñadir);
            document.getElementById('contenido-eliminar').classList.toggle('hidden', esAñadir);
            document.getElementById('tab-añadir').style.borderColor  = esAñadir ? '#2D6A4F' : 'transparent';
            document.getElementById('tab-añadir').style.color        = esAñadir ? '#2D6A4F' : 'var(--color-text-muted)';
            document.getElementById('tab-eliminar').style.borderColor = !esAñadir ? '#DC2626' : 'transparent';
            document.getElementById('tab-eliminar').style.color       = !esAñadir ? '#DC2626' : 'var(--color-text-muted)';
        }
        document.getElementById('modal-producto').addEventListener('click', function(e) { if (e.target === this) cerrarModalProducto(); });
        function filtrarProductos() {
            const input = document.getElementById('buscador-productos').value.toLowerCase();
            document.querySelectorAll('[data-nombre]').forEach(card => {
                card.style.display = card.dataset.nombre.includes(input) ? '' : 'none';
            });
        }
    </script>

</body>
</html>
