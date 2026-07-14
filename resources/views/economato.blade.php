<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Economato — PROMETE</title>
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

        /* ── ESPECÍFICO ECONOMATO ── */
        .producto-card { background-color: var(--color-card); border: 1px solid var(--color-border); border-radius: 0.6rem; overflow: hidden; backdrop-filter: blur(4px); transition: background-color 0.15s, border-color 0.15s; }
        .producto-card:hover { background-color: var(--color-card-hover); border-color: rgba(102,100,96,0.8); }
        .producto-img { aspect-ratio: 1 / 1; background-color: rgba(102,100,96,0.1); overflow: hidden; position: relative; }
        .producto-img img { width: 100%; height: 100%; object-fit: cover; }
        .producto-precio { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.15rem; color: var(--color-text); }

        .badge { font-size: 0.62rem; text-transform: uppercase; letter-spacing: 0.08em; padding: 0.2rem 0.6rem; border-radius: 999px; font-weight: 600; }
        .badge-ok  { background-color: rgba(45,106,79,0.15);  border: 1px solid rgba(45,106,79,0.4);  color: #2D6A4F; }
        .badge-low { background-color: rgba(212,184,122,0.2); border: 1px solid rgba(212,184,122,0.5); color: #8A6D2F; }
        .badge-out { background-color: rgba(220,38,38,0.1);   border: 1px solid rgba(220,38,38,0.3);  color: #DC2626; }

        .modal-overlay { position: fixed; inset: 0; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50; }
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
                <a href="{{ route('economato') }}"     class="text-sm uppercase tracking-widest transition" style="color: #F0D69C;">Economato</a>
                <a href="{{ route('transacciones') }}" class="text-sm uppercase tracking-widest transition" style="color: #D4B87A;" onmouseover="this.style.color='#F0D69C'" onmouseout="this.style.color='#D4B87A'">Transacciones</a>
                <a href="{{ route('inventario') }}"    class="text-sm uppercase tracking-widest transition" style="color: #D4B87A;" onmouseover="this.style.color='#F0D69C'" onmouseout="this.style.color='#D4B87A'">Inventario</a>
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
                <h2 class="page-title">Economato</h2>
                <p class="page-subtitle">Gestión de mercaderías y compras del servicio</p>
            </div>
            @if($user->rol === 'administrador')
            <button onclick="document.getElementById('modal-nuevo-producto').classList.remove('hidden')" class="btn-primary">
                + Añadir producto
            </button>
            @endif
        </div>

        {{-- ── STATS ── --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="card p-5">
                <p class="text-xs uppercase tracking-widest mb-2" style="color: var(--color-text-muted);">Total productos</p>
                <p class="font-syne text-3xl font-bold" style="color: var(--color-text);">{{ $totalProductos }}</p>
            </div>
            <div class="card p-5">
                <p class="text-xs uppercase tracking-widest mb-2" style="color: var(--color-text-muted);">Stock bajo</p>
                <p class="font-syne text-3xl font-bold" style="color: #8A6D2F;">{{ $stockBajo }}</p>
            </div>
            <div class="card p-5">
                <p class="text-xs uppercase tracking-widest mb-2" style="color: var(--color-text-muted);">Compras este mes</p>
                <p class="font-syne text-3xl font-bold" style="color: var(--color-text);">{{ $comprasMes }}</p>
            </div>
        </div>

        {{-- ── BUSCADOR ── --}}
        <div class="mb-6 flex justify-between items-center">
            <h3 class="font-syne text-lg font-bold" style="color: var(--color-text);">Catálogo de productos</h3>
            <input type="text" id="buscador" placeholder="Buscar producto..." onkeyup="filtrarGrid()" class="form-input" style="width: 16rem;">
        </div>

        {{-- ── GRID DE PRODUCTOS ── --}}
        <div id="grid-productos" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-10">
            @forelse($productos as $producto)
            <div class="producto-card">
                <div class="producto-img">
                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="color: rgba(102,100,96,0.35);">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                    <div class="absolute top-2 left-2">
                        @if($producto->stock === 0)
                            <span class="badge badge-out">Sin stock</span>
                        @elseif($producto->stock < 10)
                            <span class="badge badge-low">Stock bajo · {{ $producto->stock }}</span>
                        @else
                            <span class="badge badge-ok">Stock · {{ $producto->stock }}</span>
                        @endif
                    </div>
                </div>
                <div class="p-4">
                    <p class="font-syne font-bold text-sm mb-1" style="color: var(--color-text);">{{ $producto->nombre }}</p>
                    <p class="text-xs mb-3" style="color: var(--color-text-muted); min-height: 2rem;">{{ $producto->descripcion ?? '—' }}</p>
                    <div class="flex items-center justify-between mb-3">
                        <span class="producto-precio">{{ number_format($producto->precio, 2) }}€</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        @if($producto->stock > 0)
                        <button onclick="abrirModalCarrito({{ $producto->id }}, '{{ addslashes($producto->nombre) }}', {{ $producto->stock }})" class="btn-primary flex-1" style="padding: 0.5rem 0.5rem; font-size: 0.68rem;">
                            Añadir al carrito
                        </button>
                        @else
                        <span class="text-xs uppercase tracking-wider flex-1 text-center" style="color: var(--color-text-muted);">No disponible</span>
                        @endif
                    </div>
                    @if($user->rol === 'administrador')
                    <div class="flex gap-3 mt-3 pt-3" style="border-top: 1px solid var(--color-border-inner);">
                        <button onclick="abrirModalEditar({{ $producto->id }}, '{{ addslashes($producto->nombre) }}', '{{ addslashes($producto->descripcion ?? '') }}', {{ $producto->precio }}, {{ $producto->stock }})" class="text-xs uppercase tracking-wider transition" style="color: #8A6D2F;">
                            Editar
                        </button>
                        <form method="POST" action="{{ route('productos.destroy', $producto->id) }}" onsubmit="return confirm('¿Eliminar {{ addslashes($producto->nombre) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs uppercase tracking-wider" style="color: #DC2626;">Eliminar</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="card p-16 text-center col-span-full">
                <p style="color: var(--color-text-muted);">No hay productos registrados aún.</p>
            </div>
            @endforelse
        </div>

        {{-- ── MIS MOVIMIENTOS ── --}}
        <div class="card mb-8">
            <div class="card-header">
                <p class="card-title">Mis últimos movimientos</p>
                <p class="card-subtitle">Tus compras más recientes en el economato</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full tabla">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($misMovimientos as $mov)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y H:i') }}</td>
                            <td>{{ $mov->producto->nombre ?? '—' }}</td>
                            <td>{{ $mov->cantidad }}</td>
                            <td>{{ $mov->total !== null ? number_format($mov->total, 2) . '€' : '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-8" style="color: var(--color-text-muted);">Todavía no has hecho ninguna compra.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── TODOS LOS MOVIMIENTOS (ADMIN) ── --}}
        @if($user->rol === 'administrador')
        <div class="card mb-8">
            <div class="card-header">
                <p class="card-title">Todos los movimientos</p>
                <p class="card-subtitle">Últimas compras de todos los usuarios</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full tabla">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todosMovimientos as $mov)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y H:i') }}</td>
                            <td>{{ $mov->usuario->nombre ?? '—' }}</td>
                            <td>{{ $mov->producto->nombre ?? '—' }}</td>
                            <td>{{ $mov->cantidad }}</td>
                            <td>{{ $mov->total !== null ? number_format($mov->total, 2) . '€' : '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-8" style="color: var(--color-text-muted);">No hay movimientos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </main>

    {{-- ── MODAL AÑADIR AL CARRITO ── --}}
    <div id="modal-carrito" class="hidden modal-overlay">
        <div class="card p-8 w-full max-w-md">
            <h3 class="font-syne text-xl font-bold mb-1" style="color: var(--color-text);">Añadir al carrito</h3>
            <p id="modal-carrito-nombre" class="text-sm mb-6" style="color: var(--color-text-muted);"></p>
            <form method="POST" action="{{ route('carrito.agregar') }}">
                @csrf
                <input type="hidden" name="id_prod" id="modal-carrito-id">
                <div class="mb-6">
                    <label class="form-label">Cantidad <span id="modal-carrito-stock" style="text-transform: none;"></span></label>
                    <input type="number" name="cantidad" id="modal-carrito-cantidad" min="1" required class="form-input">
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('modal-carrito').classList.add('hidden')" class="btn-outline">Cancelar</button>
                    <button type="submit" class="btn-primary">Añadir</button>
                </div>
            </form>
        </div>
    </div>

    @if($user->rol === 'administrador')

    {{-- ── MODAL NUEVO PRODUCTO ── --}}
    <div id="modal-nuevo-producto" class="hidden modal-overlay">
        <div class="card p-8 w-full max-w-md">
            <h3 class="font-syne text-xl font-bold mb-6" style="color: var(--color-text);">Nuevo producto</h3>
            <form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" required class="form-input">
                </div>
                <div class="mb-4">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="descripcion" required maxlength="255" class="form-input">
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="form-label">Precio (€)</label>
                        <input type="number" name="precio" step="0.01" min="0" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Stock inicial</label>
                        <input type="number" name="stock" min="0" required class="form-input">
                    </div>
                </div>
                <div class="mb-6">
                    <label class="form-label">Imagen (opcional · máx. 5 MB)</label>
                    <input type="file" name="imagen" accept="image/*" class="form-input" style="padding: 0.45rem 1rem;">
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('modal-nuevo-producto').classList.add('hidden')" class="btn-outline">Cancelar</button>
                    <button type="submit" class="btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODAL EDITAR PRODUCTO ── --}}
    <div id="modal-editar-producto" class="hidden modal-overlay">
        <div class="card p-8 w-full max-w-md">
            <h3 class="font-syne text-xl font-bold mb-6" style="color: var(--color-text);">Editar producto</h3>
            <form method="POST" id="form-editar" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="editar-nombre" required class="form-input">
                </div>
                <div class="mb-4">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="descripcion" id="editar-descripcion" required maxlength="255" class="form-input">
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="form-label">Precio (€)</label>
                        <input type="number" name="precio" id="editar-precio" step="0.01" min="0" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Stock</label>
                        <input type="number" name="stock" id="editar-stock" min="0" required class="form-input">
                    </div>
                </div>
                <div class="mb-6">
                    <label class="form-label">Imagen (vacío = mantener la actual)</label>
                    <input type="file" name="imagen" accept="image/*" class="form-input" style="padding: 0.45rem 1rem;">
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('modal-editar-producto').classList.add('hidden')" class="btn-outline">Cancelar</button>
                    <button type="submit" class="btn-primary">Guardar cambios</button>
                </div>
            </form>
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

        function filtrarGrid() {
            const filtro = document.getElementById('buscador').value.toLowerCase();
            document.querySelectorAll('#grid-productos .producto-card').forEach(card => {
                card.style.display = card.textContent.toLowerCase().includes(filtro) ? '' : 'none';
            });
        }

        function abrirModalCarrito(id, nombre, stock) {
            document.getElementById('modal-carrito-id').value = id;
            document.getElementById('modal-carrito-nombre').textContent = nombre;
            document.getElementById('modal-carrito-stock').textContent = '(disponible: ' + stock + ')';
            const cantidad = document.getElementById('modal-carrito-cantidad');
            cantidad.max = stock;
            cantidad.value = 1;
            document.getElementById('modal-carrito').classList.remove('hidden');
        }

        function abrirModalEditar(id, nombre, descripcion, precio, stock) {
            document.getElementById('editar-nombre').value = nombre;
            document.getElementById('editar-descripcion').value = descripcion;
            document.getElementById('editar-precio').value = precio;
            document.getElementById('editar-stock').value = stock;
            document.getElementById('form-editar').action = '/productos/' + id;
            document.getElementById('modal-editar-producto').classList.remove('hidden');
        }
    </script>

</body>
</html>