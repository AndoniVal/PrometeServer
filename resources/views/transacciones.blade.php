<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transacciones — PROMETE</title>
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
                <span class="text-gray-500 font-normal text-base ml-2">/ Transacciones</span>
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

    <main class="px-8 py-8 max-w-5xl mx-auto">

        {{-- ── CABECERA ── --}}
        <div class="mb-8">
            <h2 class="font-syne text-3xl font-bold text-white">Transacciones Recientes</h2>
            <p class="text-gray-400 text-sm mt-1">Últimas 20 operaciones registradas en el sistema</p>
        </div>

        {{-- ── BUSCADOR ── --}}
        <div class="flex justify-end mb-4">
            <input
                type="text"
                id="buscador"
                placeholder="Buscar..."
                onkeyup="filtrarTabla()"
                class="bg-gray-800 border border-gray-700 text-gray-200 text-sm px-4 py-2 focus:outline-none focus:border-yellow-500 w-64"
            >
        </div>

        {{-- ── TABLA ── --}}
        <div class="bg-gray-900 border border-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="tabla-transacciones">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-widest">
                            <th class="text-left px-6 py-3">Fecha</th>
                            <th class="text-left px-6 py-3">Usuario</th>
                            <th class="text-left px-6 py-3">Producto</th>
                            <th class="text-left px-6 py-3">Tipo</th>
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
                            <td class="px-6 py-4 font-medium text-white">{{ $t->producto->nombre }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $t->producto->tipo }}</td>
                            <td class="px-6 py-4">
                                <span class="text-yellow-400 font-medium">{{ $t->cantidad }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-600">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-4xl">📋</span>
                                    <span>No hay transacciones registradas aún.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transacciones->count() > 0)
            <div class="px-6 py-3 border-t border-gray-800 text-gray-600 text-xs">
                Mostrando {{ $transacciones->count() }} transacciones más recientes
            </div>
            @endif
        </div>

    </main>

    <script>
        function filtrarTabla() {
            const input = document.getElementById('buscador').value.toLowerCase();
            document.querySelectorAll('#tabla-transacciones tbody tr').forEach(fila => {
                fila.style.display = fila.textContent.toLowerCase().includes(input) ? '' : 'none';
            });
        }
    </script>

</body>
</html>
