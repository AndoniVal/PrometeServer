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
                <a href="{{ route('economato') }}" class="text-yellow-500 text-sm uppercase tracking-widest">Economato</a>
                <a href="{{ route('transacciones') }}" class="text-gray-400 hover:text-yellow-500 transition text-sm uppercase tracking-widest">Transacciones</a>
                @if(Auth::user()->rol === 'administrador')
                    <a href="{{ route('inventario') }}" class="text-gray-400 hover:text-yellow-500 transition text-sm uppercase tracking-widest">Inventario</a>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('carrito') }}" class="relative text-gray-400 hover:text-yellow-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                @php $cantidadCarrito = count(session()->get('carrito', [])); @endphp
                @if($cantidadCarrito > 0)
                <span class="absolute -top-2 -right-2 bg-yellow-500 text-gray-950 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                    {{ $cantidadCarrito }}
                </span>
                @endif
            </a>
            {{-- Saldo --}}
            <div class="flex items-center gap-1.5 border border-gray-800 px-3 py-1.5">
                <svg class="w-3.5 h-3.5 {{ $user->saldo < 0 ? 'text-red-400' : 'text-yellow-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <span class="text-xs font-medium font-syne {{ $user->saldo < 0 ? 'text-red-400' : 'text-yellow-500' }}">
                    {{ number_format($user->saldo, 2) }}€
                </span>
            </div>
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

    <main class="px-8 py-8 max-w-7xl mx-auto">

        @if(session('success'))
        <div class="mb-6 bg-green-900/30 border border-green-700 text-green-400 px-5 py-3 text-sm">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="mb-6 bg-red-900/30 border border-red-700 text-red-400 px-5 py-3 text-sm">{{ $errors->first() }}</div>
        @endif

        {{-- ── CABECERA con botones ── --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="font-syne text-3xl font-bold text-white">Economato</h2>
                <p class="text-gray-400 text-sm mt-1">Selecciona los productos que necesitas</p>
            </div>
            @if(Auth::user()->rol === 'administrador')
            <div class="flex gap-3">
                <button onclick="abrirModalReponer('añadir')"
                    class="bg-gray-800 border border-gray-700 text-gray-300 px-5 py-2.5 text-sm font-medium uppercase tracking-wider hover:border-green-500 hover:text-green-400 transition">
                    + Reponer Stock
                </button>
                <button onclick="document.getElementById('modal-admin').classList.remove('hidden')"
                    class="bg-gray-800 border border-gray-700 text-gray-300 px-5 py-2.5 text-sm font-medium uppercase tracking-wider hover:border-yellow-500 hover:text-yellow-500 transition">
                    ⚙ Administrar
                </button>
            </div>
            @endif
        </div>

        {{-- ── STATS ── --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-gray-900 border border-gray-800 p-5">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Total Productos</p>
                <p class="font-syne text-3xl font-bold text-white">{{ $totalProductos }}</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-5">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Stock Bajo</p>
                <p class="font-syne text-3xl font-bold text-yellow-500">{{ $stockBajo }}</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-5">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Compras Este Mes</p>
                <p class="font-syne text-3xl font-bold text-white">{{ $comprasMes }}</p>
            </div>
        </div>

        {{-- ── BUSCADOR DE PRODUCTOS ── --}}
        <div class="mb-5">
            <input type="text" id="buscador-productos" placeholder="Buscar producto..."
                onkeyup="filtrarProductos()"
                class="w-full bg-gray-900 border border-gray-800 text-gray-200 text-sm px-4 py-2.5 focus:outline-none focus:border-yellow-500">
        </div>

        {{-- ── GRID DE PRODUCTOS ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-8">
            @forelse($productos as $producto)
            <div onclick="abrirModalProducto(
                    {{ $producto->id }},
                    '{{ addslashes($producto->nombre) }}',
                    '{{ addslashes($producto->descripcion) }}',
                    {{ $producto->stock }},
                    {{ $producto->precio }},
                    '{{ $producto->imagen ? asset('storage/' . $producto->imagen) : '' }}'
                )"
                data-nombre="{{ strtolower($producto->nombre) }}"
                class="bg-gray-900 border border-gray-800 hover:border-yellow-500/40 transition cursor-pointer group">


                <div class="aspect-square bg-gray-800 overflow-hidden relative">
                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}"
                             alt="{{ $producto->nombre }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-700">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                    @if($producto->stock === 0)
                    <div class="absolute top-2 left-2 bg-red-900/80 text-red-300 text-xs px-2 py-0.5 uppercase tracking-wider">Sin stock</div>
                    @elseif($producto->stock < 10)
                    <div class="absolute top-2 left-2 bg-yellow-900/80 text-yellow-300 text-xs px-2 py-0.5 uppercase tracking-wider">⚠ Stock bajo</div>
                    @endif
                </div>
                <div class="p-4">
                    <p class="font-syne font-bold text-white text-sm mb-1">{{ $producto->nombre }}</p>
                    <p class="text-gray-500 text-xs mb-3">{{ $producto->descripcion }}</p>
                    <div class="flex justify-between items-center">
                        <span class="font-syne text-yellow-500 font-bold">{{ number_format($producto->precio, 2) }}€</span>
                        <span class="text-gray-600 text-xs">{{ $producto->stock }} uds.</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-4 py-20 text-center text-gray-600">
                <p class="text-4xl mb-3">📦</p>
                <p>No hay productos en el economato aún.</p>
            </div>
            @endforelse
        </div>


        {{-- ── MIS ÚLTIMOS MOVIMIENTOS ── --}}
        <div class="bg-gray-900 border border-gray-800">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <div>
                    <h3 class="font-syne text-lg font-bold">Mis Últimas Compras</h3>
                    <p class="text-gray-500 text-xs mt-0.5">Tus 10 movimientos más recientes en el economato</p>
                </div>
                <a href="{{ route('transacciones') }}" class="text-yellow-500 hover:text-yellow-400 text-xs uppercase tracking-wider transition">Ver todos →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-widest">
                            <th class="text-left px-6 py-3">Fecha</th>
                            <th class="text-left px-6 py-3">Producto</th>
                            <th class="text-left px-6 py-3">Cantidad</th>
                            <th class="text-left px-6 py-3">Precio/ud</th>
                            <th class="text-left px-6 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($misMovimientos as $t)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                            <td class="px-6 py-4 text-gray-400">{{ \Carbon\Carbon::parse($t->fecha)->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 font-medium text-white">{{ $t->producto->nombre }}</td>
                            <td class="px-6 py-4 text-white">{{ $t->cantidad }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ number_format($t->precio_unidad, 2) }}€</td>
                            <td class="px-6 py-4 text-yellow-400 font-medium">{{ number_format($t->total, 2) }}€</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-600">No has realizado ninguna compra aún.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- ── MODALES ── --}}
    {{-- ══════════════════════════════════════════════ --}}

    {{-- Modal detalle producto --}}
    <div id="modal-producto" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4">
        <div class="bg-gray-900 border border-gray-700 w-full max-w-2xl">
            <div class="aspect-video bg-gray-800 overflow-hidden relative">
                <img id="modal-img" src="" alt="" class="w-full h-full object-cover">
                <div id="modal-img-placeholder" class="hidden absolute inset-0 flex items-center justify-center text-gray-700">
                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <button onclick="cerrarModalProducto()" class="absolute top-3 right-3 bg-black/50 text-white hover:bg-black/80 transition w-8 h-8 flex items-center justify-center">✕</button>
                <div id="modal-stock-aviso" class="hidden absolute top-3 left-3 bg-yellow-900/90 text-yellow-300 text-xs px-3 py-1 uppercase tracking-wider">⚠ Stock bajo</div>
                <div id="modal-sin-stock-aviso" class="hidden absolute top-3 left-3 bg-red-900/90 text-red-300 text-xs px-3 py-1 uppercase tracking-wider">Sin stock</div>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 id="modal-nombre" class="font-syne text-2xl font-bold text-white"></h3>
                        <p id="modal-tipo" class="text-gray-500 text-sm mt-1"></p>
                    </div>
                    <span id="modal-precio" class="font-syne text-2xl font-bold text-yellow-500"></span>
                </div>
                <p id="modal-stock-texto" class="text-gray-500 text-xs mb-6"></p>
                <form method="POST" action="{{ route('carrito.agregar') }}">
                    @csrf
                    <input type="hidden" name="id_prod" id="modal-id-prod">
                    <div class="flex gap-3 items-center">
                        <div class="flex items-center border border-gray-700 bg-gray-800">
                            <button type="button" onclick="cambiarCantidad(-1)" class="px-3 py-2 text-gray-400 hover:text-white transition text-lg">−</button>
                            <input type="number" name="cantidad" id="modal-cantidad" value="1" min="1" class="w-16 bg-transparent text-white text-center py-2 focus:outline-none">
                            <button type="button" onclick="cambiarCantidad(1)" class="px-3 py-2 text-gray-400 hover:text-white transition text-lg">+</button>
                        </div>
                        <button type="submit" id="btn-agregar" class="flex-1 bg-yellow-500 text-gray-950 py-2.5 text-sm font-medium uppercase tracking-wider hover:bg-yellow-400 transition">
                            Añadir al carrito
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── MODALES SOLO ADMIN ── --}}
    @if(Auth::user()->rol === 'administrador')

    {{-- Modal administrar --}}
    <div id="modal-admin" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4">
        <div class="bg-gray-900 border border-gray-700 w-full max-w-3xl max-h-screen overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center sticky top-0 bg-gray-900 z-10">
                <h3 class="font-syne text-xl font-bold">⚙ Administrar Economato</h3>
                <button onclick="document.getElementById('modal-admin').classList.add('hidden')" class="text-gray-500 hover:text-white transition">✕</button>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col gap-3">
                    <h4 class="text-gray-500 text-xs uppercase tracking-widest mb-1">Acciones</h4>
                    <button onclick="abrirSubModal('modal-nuevo-producto')" class="w-full bg-gray-800 border border-gray-700 text-left px-5 py-4 hover:border-yellow-500 hover:text-yellow-500 transition">
                        <p class="font-medium">+ Añadir producto</p>
                        <p class="text-gray-500 text-xs mt-0.5">Crear un nuevo producto en el economato</p>
                    </button>
                    <button onclick="abrirSubModal('modal-eliminar-producto')" class="w-full bg-gray-800 border border-gray-700 text-left px-5 py-4 hover:border-red-500 hover:text-red-400 transition">
                        <p class="font-medium">🗑 Eliminar producto</p>
                        <p class="text-gray-500 text-xs mt-0.5">Eliminar un producto del economato</p>
                    </button>
                    <button onclick="abrirSubModal('modal-stock')" class="w-full bg-gray-800 border border-gray-700 text-left px-5 py-4 hover:border-yellow-500 hover:text-yellow-500 transition">
                        <p class="font-medium">📦 Gestionar stock</p>
                        <p class="text-gray-500 text-xs mt-0.5">Actualizar cantidades de productos</p>
                    </button>
                    <button onclick="abrirSubModal('modal-precios')" class="w-full bg-gray-800 border border-gray-700 text-left px-5 py-4 hover:border-yellow-500 hover:text-yellow-500 transition">
                        <p class="font-medium">💰 Gestionar precios</p>
                        <p class="text-gray-500 text-xs mt-0.5">Actualizar precios de productos</p>
                    </button>
                    <a href="{{ route('finanzas') }}" class="w-full bg-gray-800 border border-gray-700 text-left px-5 py-4 hover:border-yellow-500 hover:text-yellow-500 transition block">
                        <p class="font-medium">📊 Vista Finanzas</p>
                        <p class="text-gray-500 text-xs mt-0.5">Control de gastos, deudas e inventario</p>
                    </a>
                </div>
                <div>
                    <h4 class="text-gray-500 text-xs uppercase tracking-widest mb-3">Últimos movimientos (todos)</h4>
                    <div class="flex flex-col gap-2 max-h-80 overflow-y-auto">
                        @forelse($todosMovimientos as $t)
                        <div class="bg-gray-800 border border-gray-700/50 px-4 py-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-white text-sm font-medium">{{ $t->usuario->nombre }}</p>
                                    <p class="text-gray-500 text-xs mt-0.5">{{ $t->producto->nombre }} × {{ $t->cantidad }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-yellow-500 text-sm font-bold">{{ number_format($t->total, 2) }}€</p>
                                    <p class="text-gray-600 text-xs">{{ \Carbon\Carbon::parse($t->fecha)->format('d/m H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-600 text-sm text-center py-8">No hay movimientos aún.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Submodal: Nuevo producto --}}
    <div id="modal-nuevo-producto" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-[60] p-4">
        <div class="bg-gray-900 border border-gray-700 w-full max-w-md">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="font-syne text-xl font-bold">Nuevo Producto</h3>
                <button onclick="document.getElementById('modal-nuevo-producto').classList.add('hidden')" class="text-gray-500 hover:text-white transition">✕</button>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Nombre</label>
                        <input type="text" name="nombre" required class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Descripción</label>
                        <input type="text" name="descripcion" required class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Stock inicial</label>
                            <input type="number" name="stock" min="0" required class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Precio (€)</label>
                            <input type="number" name="precio" min="0" step="0.01" required class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Imagen</label>
                        <input type="file" name="imagen" accept="image/*" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-medium file:uppercase file:bg-yellow-500 file:text-gray-950 hover:file:bg-yellow-400 file:cursor-pointer">
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('modal-nuevo-producto').classList.add('hidden')" class="px-5 py-2.5 text-sm text-gray-400 border border-gray-700 hover:border-gray-500 transition uppercase tracking-wider">Cancelar</button>
                        <button type="submit" class="px-5 py-2.5 text-sm bg-yellow-500 text-gray-950 font-medium hover:bg-yellow-400 transition uppercase tracking-wider">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Submodal: Eliminar producto --}}
    <div id="modal-eliminar-producto" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-[60] p-4">
        <div class="bg-gray-900 border border-gray-700 w-full max-w-md">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="font-syne text-xl font-bold text-red-400">Eliminar Producto</h3>
                <button onclick="document.getElementById('modal-eliminar-producto').classList.add('hidden')" class="text-gray-500 hover:text-white transition">✕</button>
            </div>
            <div class="p-6">
                <p class="text-gray-400 text-sm mb-4">Selecciona el producto que deseas eliminar. Esta acción no se puede deshacer.</p>
                <form method="POST" id="form-eliminar" action="" onsubmit="return confirm('¿Seguro que quieres eliminar este producto?')">
                    @csrf
                    @method('DELETE')
                    <div class="mb-6">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Producto</label>
                        <select id="select-eliminar" onchange="setEliminarAction(this.value)" class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-red-500">
                            <option value="">— Selecciona un producto —</option>
                            @foreach($productos as $producto)
                            <option value="{{ route('productos.destroy', $producto->id) }}">{{ $producto->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('modal-eliminar-producto').classList.add('hidden')" class="px-5 py-2.5 text-sm text-gray-400 border border-gray-700 hover:border-gray-500 transition uppercase tracking-wider">Cancelar</button>
                        <button type="submit" class="px-5 py-2.5 text-sm bg-red-600 text-white font-medium hover:bg-red-500 transition uppercase tracking-wider">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Submodal: Gestionar stock --}}
    <div id="modal-stock" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-[60] p-4">
        <div class="bg-gray-900 border border-gray-700 w-full max-w-lg">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="font-syne text-xl font-bold">Gestionar Stock</h3>
                <button onclick="document.getElementById('modal-stock').classList.add('hidden')" class="text-gray-500 hover:text-white transition">✕</button>
            </div>
            <div class="p-6 max-h-96 overflow-y-auto">
                @foreach($productos as $producto)
                <form method="POST" action="{{ route('productos.update', $producto->id) }}" class="flex items-center gap-3 mb-3">
                    @csrf
                    @method('PUT')
                    <span class="text-white text-sm flex-1 truncate">{{ $producto->nombre }}</span>
                    <input type="number" name="stock" value="{{ $producto->stock }}" min="0" class="w-24 bg-gray-800 border border-gray-700 text-white px-3 py-1.5 text-sm focus:outline-none focus:border-yellow-500">
                    <input type="hidden" name="nombre" value="{{ $producto->nombre }}">
                    <input type="hidden" name="tipo" value="{{ $producto->descripcion }}">
                    <input type="hidden" name="precio" value="{{ $producto->precio }}">
                    <button type="submit" class="bg-yellow-500 text-gray-950 px-3 py-1.5 text-xs font-medium uppercase hover:bg-yellow-400 transition">OK</button>
                </form>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Submodal: Gestionar precios --}}
    <div id="modal-precios" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-[60] p-4">
        <div class="bg-gray-900 border border-gray-700 w-full max-w-lg">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="font-syne text-xl font-bold">Gestionar Precios</h3>
                <button onclick="document.getElementById('modal-precios').classList.add('hidden')" class="text-gray-500 hover:text-white transition">✕</button>
            </div>
            <div class="p-6 max-h-96 overflow-y-auto">
                @foreach($productos as $producto)
                <form method="POST" action="{{ route('productos.update', $producto->id) }}" class="flex items-center gap-3 mb-3">
                    @csrf
                    @method('PUT')
                    <span class="text-white text-sm flex-1 truncate">{{ $producto->nombre }}</span>
                    <div class="flex items-center gap-1">
                        <input type="number" name="precio" value="{{ $producto->precio }}" min="0" step="0.01" class="w-24 bg-gray-800 border border-gray-700 text-white px-3 py-1.5 text-sm focus:outline-none focus:border-yellow-500">
                        <span class="text-gray-500 text-xs">€</span>
                    </div>
                    <input type="hidden" name="nombre" value="{{ $producto->nombre }}">
                    <input type="hidden" name="tipo" value="{{ $producto->descripcion }}">
                    <input type="hidden" name="stock" value="{{ $producto->stock }}">
                    <button type="submit" class="bg-yellow-500 text-gray-950 px-3 py-1.5 text-xs font-medium uppercase hover:bg-yellow-400 transition">OK</button>
                </form>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Modal reponer / eliminar stock --}}
    <div id="modal-reponer" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-[60] p-4">
        <div class="bg-gray-900 border border-gray-700 w-full max-w-md">
            <div class="flex border-b border-gray-800">
                <button id="tab-añadir" onclick="cambiarTab('añadir')"
                    class="flex-1 px-6 py-4 text-sm font-medium uppercase tracking-wider transition border-b-2 border-green-500 text-green-400">
                    + Añadir Stock
                </button>
                <button id="tab-eliminar" onclick="cambiarTab('eliminar')"
                    class="flex-1 px-6 py-4 text-sm font-medium uppercase tracking-wider transition border-b-2 border-transparent text-gray-500 hover:text-gray-300">
                    − Retirar Stock
                </button>
                <button onclick="document.getElementById('modal-reponer').classList.add('hidden')" class="px-4 text-gray-500 hover:text-white transition">✕</button>
            </div>
            <div id="contenido-añadir" class="p-6">
                <p class="text-gray-400 text-sm mb-5">Selecciona el producto y las unidades a reponer.</p>
                <form method="POST" action="{{ route('stock.añadir') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Producto</label>
                        <select name="id_prod" required class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-green-500">
                            <option value="">— Selecciona un producto —</option>
                            @foreach($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombre }} (stock: {{ $producto->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Unidades a añadir</label>
                        <input type="number" name="cantidad" min="1" required class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-green-500">
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('modal-reponer').classList.add('hidden')" class="px-5 py-2.5 text-sm text-gray-400 border border-gray-700 hover:border-gray-500 transition uppercase tracking-wider">Cancelar</button>
                        <button type="submit" class="px-5 py-2.5 text-sm bg-green-600 text-white font-medium hover:bg-green-500 transition uppercase tracking-wider">Añadir</button>
                    </div>
                </form>
            </div>
            <div id="contenido-eliminar" class="p-6 hidden">
                <p class="text-gray-400 text-sm mb-5">Selecciona el producto y las unidades a retirar del stock.</p>
                <form method="POST" action="{{ route('stock.eliminar') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Producto</label>
                        <select name="id_prod" required class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-red-500">
                            <option value="">— Selecciona un producto —</option>
                            @foreach($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombre }} (stock: {{ $producto->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Unidades a retirar</label>
                        <input type="number" name="cantidad" min="1" required class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-red-500">
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('modal-reponer').classList.add('hidden')" class="px-5 py-2.5 text-sm text-gray-400 border border-gray-700 hover:border-gray-500 transition uppercase tracking-wider">Cancelar</button>
                        <button type="submit" class="px-5 py-2.5 text-sm bg-red-600 text-white font-medium hover:bg-red-500 transition uppercase tracking-wider">Retirar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endif
    {{-- FIN MODALES ADMIN --}}

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
            if (imagen) {
                img.src = imagen;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            const avisoStock = document.getElementById('modal-stock-aviso');
            const avisoSinStock = document.getElementById('modal-sin-stock-aviso');
            const btnAgregar = document.getElementById('btn-agregar');
            const stockTexto = document.getElementById('modal-stock-texto');

            avisoStock.classList.add('hidden');
            avisoSinStock.classList.add('hidden');

            if (stock === 0) {
                avisoSinStock.classList.remove('hidden');
                stockTexto.textContent = 'Producto sin stock disponible';
                btnAgregar.disabled = true;
                btnAgregar.classList.add('opacity-50', 'cursor-not-allowed');
            } else if (stock < 10) {
                avisoStock.classList.remove('hidden');
                stockTexto.textContent = '⚠ Solo quedan ' + stock + ' unidades disponibles';
                btnAgregar.disabled = false;
                btnAgregar.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                stockTexto.textContent = stock + ' unidades disponibles';
                btnAgregar.disabled = false;
                btnAgregar.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            document.getElementById('modal-producto').classList.remove('hidden');
        }

        function cerrarModalProducto() {
            document.getElementById('modal-producto').classList.add('hidden');
        }

        function cambiarCantidad(delta) {
            const input = document.getElementById('modal-cantidad');
            const max = parseInt(input.max) || 999;
            input.value = Math.min(max, Math.max(1, parseInt(input.value) + delta));
        }

        function setEliminarAction(url) {
            document.getElementById('form-eliminar').action = url;
        }

        function abrirModalReponer(tab) {
            cambiarTab(tab);
            document.getElementById('modal-reponer').classList.remove('hidden');
        }

        function cambiarTab(tab) {
            const esAñadir = tab === 'añadir';
            document.getElementById('contenido-añadir').classList.toggle('hidden', !esAñadir);
            document.getElementById('contenido-eliminar').classList.toggle('hidden', esAñadir);
            document.getElementById('tab-añadir').className = `flex-1 px-6 py-4 text-sm font-medium uppercase tracking-wider transition border-b-2 ${esAñadir ? 'border-green-500 text-green-400' : 'border-transparent text-gray-500 hover:text-gray-300'}`;
            document.getElementById('tab-eliminar').className = `flex-1 px-6 py-4 text-sm font-medium uppercase tracking-wider transition border-b-2 ${!esAñadir ? 'border-red-500 text-red-400' : 'border-transparent text-gray-500 hover:text-gray-300'}`;
        }

        document.getElementById('modal-producto').addEventListener('click', function(e) {
            if (e.target === this) cerrarModalProducto();
        })
        function filtrarProductos() {
            const input = document.getElementById('buscador-productos').value.toLowerCase();
            document.querySelectorAll('[data-nombre]').forEach(card => {
                card.style.display = card.dataset.nombre.includes(input) ? '' : 'none';
            });
        }
    </script>

</body>
</html>
