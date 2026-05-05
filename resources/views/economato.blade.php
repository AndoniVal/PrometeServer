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
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-yellow-500 transition text-sm uppercase tracking-widest">
                ← Volver
            </a>
            <span class="text-gray-700">|</span>
            <h1 class="font-syne text-xl font-bold text-white">
                Promet<span class="text-yellow-500">e</span>
                <span class="text-gray-500 font-normal text-base ml-2">/ Economato</span>
            </h1>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-gray-400 text-sm">{{ $user->nombre }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-sm text-red-400 hover:text-red-300 transition">Cerrar sesión</button>
            </form>
        </div>
    </nav>

    <main class="px-8 py-8 max-w-7xl mx-auto">

        {{-- ── ALERTAS ── --}}
        @if(session('success'))
        <div class="mb-6 bg-green-900/30 border border-green-700 text-green-400 px-5 py-3 text-sm">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-red-900/30 border border-red-700 text-red-400 px-5 py-3 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        {{-- ── CABECERA ── --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="font-syne text-3xl font-bold text-white">Economato</h2>
                <p class="text-gray-400 text-sm mt-1">Gestión de mercaderías y compras del servicio</p>
            </div>
            @if(Auth::user()->rol === 'administrador')
            <button
                onclick="document.getElementById('modal-nuevo-producto').classList.remove('hidden')"
                class="bg-yellow-500 text-gray-950 px-5 py-2.5 text-sm font-medium uppercase tracking-wider hover:bg-yellow-400 transition">
                + Añadir Producto
            </button>
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

        {{-- ── TABLA DE PRODUCTOS ── --}}
        <div class="bg-gray-900 border border-gray-800 mb-8">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="font-syne text-lg font-bold">Catálogo de Productos</h3>
                <input
                    type="text"
                    id="buscador"
                    placeholder="Buscar producto..."
                    onkeyup="filtrarTabla()"
                    class="bg-gray-800 border border-gray-700 text-gray-200 text-sm px-4 py-2 focus:outline-none focus:border-yellow-500 w-64"
                >
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="tabla-productos">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-widest">
                            <th class="text-left px-6 py-3">ID</th>
                            <th class="text-left px-6 py-3">Nombre</th>
                            <th class="text-left px-6 py-3">Tipo</th>
                            <th class="text-left px-6 py-3">Stock</th>
                            <th class="text-left px-6 py-3">Estado</th>
                            <th class="text-left px-6 py-3">Comprar</th>
                            @if(Auth::user()->rol === 'administrador')
                            <th class="text-left px-6 py-3">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                            <td class="px-6 py-4 text-gray-500">#{{ $producto->id }}</td>
                            <td class="px-6 py-4 font-medium text-white">{{ $producto->nombre }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $producto->tipo }}</td>
                            <td class="px-6 py-4 text-white">{{ $producto->stock }}</td>
                            <td class="px-6 py-4">
                                @if($producto->stock === 0)
                                    <span class="bg-red-900/30 text-red-400 text-xs px-3 py-1 uppercase tracking-wider">Sin stock</span>
                                @elseif($producto->stock < 10)
                                    <span class="bg-yellow-900/30 text-yellow-400 text-xs px-3 py-1 uppercase tracking-wider">Stock bajo</span>
                                @else
                                    <span class="bg-green-900/30 text-green-400 text-xs px-3 py-1 uppercase tracking-wider">Disponible</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($producto->stock > 0)
                                <button
                                    onclick="abrirModalCompra({{ $producto->id }}, '{{ addslashes($producto->nombre) }}', {{ $producto->stock }})"
                                    class="text-yellow-500 hover:text-yellow-400 text-xs uppercase tracking-wider border border-yellow-500/30 hover:border-yellow-400 px-3 py-1 transition">
                                    Comprar
                                </button>
                                @else
                                <span class="text-gray-700 text-xs uppercase tracking-wider">No disponible</span>
                                @endif
                            </td>
                            @if(Auth::user()->rol === 'administrador')
                            <td class="px-6 py-4">
                                <div class="flex gap-3">
                                    <button
                                        onclick="abrirModalEditar({{ $producto->id }}, '{{ addslashes($producto->nombre) }}', '{{ addslashes($producto->tipo) }}', {{ $producto->stock }})"
                                        class="text-yellow-500 hover:text-yellow-400 text-xs uppercase tracking-wider">
                                        Editar
                                    </button>
                                    <form method="POST" action="{{ route('productos.destroy', $producto->id) }}"
                                        onsubmit="return confirm('¿Eliminar {{ addslashes($producto->nombre) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 text-xs uppercase tracking-wider">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-600">
                                No hay productos registrados aún.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── HISTORIAL DE COMPRAS ── --}}
        <div class="bg-gray-900 border border-gray-800">
            <div class="px-6 py-4 border-b border-gray-800">
                <h3 class="font-syne text-lg font-bold">Últimas Compras</h3>
                <p class="text-gray-500 text-xs mt-1">Las 10 transacciones más recientes</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-widest">
                            <th class="text-left px-6 py-3">Fecha</th>
                            <th class="text-left px-6 py-3">Usuario</th>
                            <th class="text-left px-6 py-3">Producto</th>
                            <th class="text-left px-6 py-3">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transacciones as $t)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                            <td class="px-6 py-4 text-gray-400">
                                {{ \Carbon\Carbon::parse($t->fecha)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-white">{{ $t->usuario->nombre }}</td>
                            <td class="px-6 py-4 text-white">{{ $t->producto->nombre }}</td>
                            <td class="px-6 py-4 text-yellow-400 font-medium">{{ $t->cantidad }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-600">
                                No hay compras registradas aún.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    {{-- ── MODAL COMPRAR ── --}}
    <div id="modal-comprar" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">
        <div class="bg-gray-900 border border-gray-700 p-8 w-full max-w-md">
            <h3 class="font-syne text-xl font-bold mb-1">Registrar Compra</h3>
            <p id="modal-comprar-nombre" class="text-gray-400 text-sm mb-6"></p>
            <form method="POST" action="{{ route('economato.comprar') }}">
                @csrf
                <input type="hidden" name="id_prod" id="modal-comprar-id">
                <div class="mb-6">
                    <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">
                        Cantidad <span id="modal-comprar-stock" class="text-gray-600 normal-case text-xs"></span>
                    </label>
                    <input type="number" name="cantidad" id="modal-comprar-cantidad" min="1" required
                        class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button"
                        onclick="document.getElementById('modal-comprar').classList.add('hidden')"
                        class="px-5 py-2.5 text-sm text-gray-400 border border-gray-700 hover:border-gray-500 transition uppercase tracking-wider">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm bg-yellow-500 text-gray-950 font-medium hover:bg-yellow-400 transition uppercase tracking-wider">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODALES ADMIN ── --}}
    @if(Auth::user()->rol === 'administrador')

    {{-- Modal nuevo producto --}}
    <div id="modal-nuevo-producto" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">
        <div class="bg-gray-900 border border-gray-700 p-8 w-full max-w-md">
            <h3 class="font-syne text-xl font-bold mb-6">Nuevo Producto</h3>
            <form method="POST" action="{{ route('productos.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Nombre</label>
                    <input type="text" name="nombre" required
                        class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Tipo</label>
                    <input type="text" name="tipo" required
                        class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Stock inicial</label>
                    <input type="number" name="stock" min="0" required
                        class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button"
                        onclick="document.getElementById('modal-nuevo-producto').classList.add('hidden')"
                        class="px-5 py-2.5 text-sm text-gray-400 border border-gray-700 hover:border-gray-500 transition uppercase tracking-wider">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm bg-yellow-500 text-gray-950 font-medium hover:bg-yellow-400 transition uppercase tracking-wider">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal editar producto --}}
    <div id="modal-editar-producto" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">
        <div class="bg-gray-900 border border-gray-700 p-8 w-full max-w-md">
            <h3 class="font-syne text-xl font-bold mb-6">Editar Producto</h3>
            <form method="POST" id="form-editar" action="">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Nombre</label>
                    <input type="text" name="nombre" id="editar-nombre" required
                        class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Tipo</label>
                    <input type="text" name="tipo" id="editar-tipo" required
                        class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Stock</label>
                    <input type="number" name="stock" id="editar-stock" min="0" required
                        class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500">
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button"
                        onclick="document.getElementById('modal-editar-producto').classList.add('hidden')"
                        class="px-5 py-2.5 text-sm text-gray-400 border border-gray-700 hover:border-gray-500 transition uppercase tracking-wider">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm bg-yellow-500 text-gray-950 font-medium hover:bg-yellow-400 transition uppercase tracking-wider">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    @endif

    <script>
        function filtrarTabla() {
            const input = document.getElementById('buscador').value.toLowerCase();
            document.querySelectorAll('#tabla-productos tbody tr').forEach(fila => {
                fila.style.display = fila.textContent.toLowerCase().includes(input) ? '' : 'none';
            });
        }

        function abrirModalCompra(id, nombre, stock) {
            document.getElementById('modal-comprar-id').value = id;
            document.getElementById('modal-comprar-nombre').textContent = nombre;
            document.getElementById('modal-comprar-stock').textContent = '(disponible: ' + stock + ')';
            document.getElementById('modal-comprar-cantidad').max = stock;
            document.getElementById('modal-comprar-cantidad').value = 1;
            document.getElementById('modal-comprar').classList.remove('hidden');
        }

        function abrirModalEditar(id, nombre, tipo, stock) {
            document.getElementById('editar-nombre').value = nombre;
            document.getElementById('editar-tipo').value = tipo;
            document.getElementById('editar-stock').value = stock;
            document.getElementById('form-editar').action = '/productos/' + id;
            document.getElementById('modal-editar-producto').classList.remove('hidden');
        }
    </script>

</body>
</html>