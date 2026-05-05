<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta — PROMETE</title>
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
            
        </div>
        <a href="{{ route('dashboard') }}" class="font-syne text-xl font-bold text-white hover:opacity-80 transition">
            Promet<span class="text-yellow-500">e</span>
            <span class="text-gray-500 font-normal text-base ml-2">/ Mi Cuenta</span>
        </a>
        {{-- Avatar dropdown --}}
        <div class="relative" id="user-menu">
            <button onclick="toggleDropdown()" class="flex items-center gap-3 focus:outline-none group">
                <div class="w-9 h-9 rounded-full overflow-hidden border-2 border-gray-700 group-hover:border-yellow-500 transition">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->nombre }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-yellow-500/20 flex items-center justify-center">
                            <span class="text-yellow-500 text-xs font-bold font-syne">
                                {{ strtoupper(substr($user->nombre, 0, 2)) }}
                            </span>
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
                    <a href="{{ route('mi-cuenta') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-yellow-500 hover:bg-gray-800 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Mi cuenta
                    </a>
                </div>
                <div class="border-t border-gray-800 py-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:bg-gray-800 hover:text-red-300 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="px-8 py-8 max-w-3xl mx-auto">

        {{-- ── CABECERA ── --}}
        <div class="mb-8">
            <h2 class="font-syne text-3xl font-bold text-white">Mi Cuenta</h2>
            <p class="text-gray-400 text-sm mt-1">Gestiona tu perfil, datos personales y contraseña</p>
        </div>

        {{-- ── ALERTAS ── --}}
        @if(session('success'))
        <div class="mb-6 bg-green-900/30 border border-green-700 text-green-400 px-5 py-3 text-sm">
            {{ session('success') }}
        </div>
        @endif

        @if(session('success_pass'))
        <div class="mb-6 bg-green-900/30 border border-green-700 text-green-400 px-5 py-3 text-sm">
            {{ session('success_pass') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-red-900/30 border border-red-700 text-red-400 px-5 py-3 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        {{-- ── AVATAR ── --}}
        <div class="bg-gray-900 border border-gray-800 mb-6">
            <div class="px-6 py-4 border-b border-gray-800">
                <h3 class="font-syne text-lg font-bold">Foto de Perfil</h3>
                <p class="text-gray-500 text-xs mt-1">JPG, PNG o GIF. Máximo 2MB.</p>
            </div>
            <div class="p-6 flex items-center gap-6">

                {{-- Preview avatar --}}
                <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-700 flex-shrink-0">
                    @if($user->avatar)
                        <img id="avatar-preview" src="{{ asset('storage/' . $user->avatar) }}"
                             alt="{{ $user->nombre }}" class="w-full h-full object-cover">
                    @else
                        <div id="avatar-initials" class="w-full h-full bg-yellow-500/20 flex items-center justify-center">
                            <span class="text-yellow-500 text-2xl font-bold font-syne">
                                {{ strtoupper(substr($user->nombre, 0, 2)) }}
                            </span>
                        </div>
                        <img id="avatar-preview" src="" alt="" class="w-full h-full object-cover hidden">
                    @endif
                </div>

                {{-- Form subida --}}
                <form method="POST" action="{{ route('mi-cuenta.avatar') }}" enctype="multipart/form-data" class="flex-1">
                    @csrf
                    <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Seleccionar imagen</label>
                    <div class="flex gap-3 items-center">
                        <input type="file" name="avatar" id="avatar-input" accept="image/*"
                            onchange="previewAvatar(this)"
                            class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-medium file:uppercase file:tracking-wider file:bg-yellow-500 file:text-gray-950 hover:file:bg-yellow-400 file:cursor-pointer file:transition">
                        <button type="submit"
                            class="flex-shrink-0 bg-yellow-500 text-gray-950 px-5 py-2 text-xs font-medium uppercase tracking-wider hover:bg-yellow-400 transition">
                            Subir
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── DATOS PERSONALES ── --}}
        <div class="bg-gray-900 border border-gray-800 mb-6">
            <div class="px-6 py-4 border-b border-gray-800">
                <h3 class="font-syne text-lg font-bold">Datos Personales</h3>
                <p class="text-gray-500 text-xs mt-1">Actualiza tu nombre, email y edad</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('mi-cuenta.actualizar') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}" required
                                class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500 transition">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Edad</label>
                            <input type="number" name="edad" value="{{ old('edad', $user->edad) }}" min="1" max="120"
                                class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500 transition">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500 transition">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Rol</label>
                        <div class="w-full bg-gray-800/50 border border-gray-700/50 text-gray-500 px-4 py-2.5 text-sm">
                            {{ ucfirst($user->rol) }}
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-yellow-500 text-gray-950 px-6 py-2.5 text-sm font-medium uppercase tracking-wider hover:bg-yellow-400 transition">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── CAMBIAR CONTRASEÑA ── --}}
        <div class="bg-gray-900 border border-gray-800">
            <div class="px-6 py-4 border-b border-gray-800">
                <h3 class="font-syne text-lg font-bold">Cambiar Contraseña</h3>
                <p class="text-gray-500 text-xs mt-1">Mínimo 8 caracteres</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('mi-cuenta.password') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Contraseña actual</label>
                        <input type="password" name="password_actual" required
                            class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500 transition">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Nueva contraseña</label>
                        <input type="password" name="password_nuevo" required
                            class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500 transition">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">Confirmar nueva contraseña</label>
                        <input type="password" name="password_nuevo_confirmation" required
                            class="w-full bg-gray-800 border border-gray-700 text-white px-4 py-2.5 focus:outline-none focus:border-yellow-500 transition">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-yellow-500 text-gray-950 px-6 py-2.5 text-sm font-medium uppercase tracking-wider hover:bg-yellow-400 transition">
                            Actualizar contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>

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
            if (!menu.contains(e.target)) {
                document.getElementById('dropdown').classList.add('hidden');
                document.getElementById('chevron').style.transform = 'rotate(0deg)';
            }
        });

        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatar-preview');
                    const initials = document.getElementById('avatar-initials');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (initials) initials.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</body>
</html>