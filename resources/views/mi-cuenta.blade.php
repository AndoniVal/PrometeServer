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

        .form-input { width: 100%; background-color: rgba(102,100,96,0.08); border: 1px solid var(--color-border); border-radius: 0.35rem; color: var(--color-text); padding: 0.6rem 1rem; font-size: 0.875rem; outline: none; transition: border-color 0.15s; }
        .form-input:focus { border-color: var(--color-accent); }
        .form-label { display: block; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-text-muted); margin-bottom: 0.4rem; }

        .btn-primary { background-color: #1C1C1C; color: #F0D69C; padding: 0.6rem 1.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 0.35rem; border: 1px solid #333; transition: background-color 0.15s; cursor: pointer; }
        .btn-primary:hover { background-color: #333; }

        .dropdown { background-color: #3A3836; border: 1px solid var(--color-border); border-radius: 0.5rem; }
        .dropdown-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 1rem; font-size: 0.875rem; color: rgba(255,255,255,0.65); transition: background-color 0.15s, color 0.15s; }
        .dropdown-item:hover { background-color: rgba(255,255,255,0.08); color: #fff; }
        .dropdown-item.danger { color: #DC2626; }
        .dropdown-divider { border-top: 1px solid rgba(255,255,255,0.1); }

        .saldo-pill { display: flex; align-items: center; gap: 0.4rem; padding: 0.3rem 0.75rem; border: 1px solid rgba(212,184,122,0.35); border-radius: 999px; background-color: rgba(212,184,122,0.1); font-size: 0.72rem; font-weight: 600; font-family: 'Syne', sans-serif; }
        .saldo-ok     { color: #D4B87A; }
        .saldo-danger { color: #DC2626; }

        .page-title    { font-family: 'Syne', sans-serif; font-size: 1.875rem; font-weight: 700; color: var(--color-text); }
        .page-subtitle { font-size: 0.875rem; color: var(--color-text-muted); margin-top: 0.25rem; }

        .field-readonly { width: 100%; background-color: rgba(102,100,96,0.04); border: 1px solid rgba(102,100,96,0.2); border-radius: 0.35rem; color: var(--color-text-muted); padding: 0.6rem 1rem; font-size: 0.875rem; }

        .saldo-grande-ok     { font-family: 'Syne', sans-serif; font-size: 2.25rem; font-weight: 700; color: #2D6A4F; }
        .saldo-grande-danger { font-family: 'Syne', sans-serif; font-size: 2.25rem; font-weight: 700; color: #DC2626; }
        .saldo-aviso-ok      { font-size: 0.72rem; color: var(--color-text-muted); margin-top: 0.5rem; }
        .saldo-aviso-danger  { font-size: 0.72rem; color: #DC2626; margin-top: 0.5rem; }

        input[type="file"]::file-selector-button {
            background-color: #1C1C1C;
            color: #F0D69C;
            border: 1px solid #333;
            border-radius: 0.35rem;
            padding: 0.4rem 1rem;
            font-size: 0.7rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: background-color 0.15s;
            margin-right: 1rem;
        }
        input[type="file"]::file-selector-button:hover {
            background-color: #333;
        }

    </style>
</head>
<body class="min-h-screen" style="background-color: #F5DDC4; background-image: url('{{ asset('imagenes/PrometePuñal.png') }}'); background-size: 30%; background-repeat: no-repeat; background-position: center; background-attachment: fixed;">

    {{-- NAVBAR --}}
    <nav style="background-color: #1C1C1C; border-bottom: 1px solid #333333;" class="px-8 py-4 flex justify-between items-center sticky top-0 z-50 shadow-md">
        <div class="flex items-center gap-6">
            <a href="{{ route('dashboard') }}" class="font-syne text-xl font-bold hover:opacity-80 transition" style="color: #F0D69C;">
                Promet<span style="color: #D4B87A;">e</span>
                <span class="font-normal text-base ml-2" style="color: rgba(212,184,122,0.5);">/ Mi Cuenta</span>
            </a>
        </div>
        <div class="flex items-center gap-4">
            <div class="saldo-pill {{ $user->saldo < 0 ? 'saldo-danger' : 'saldo-ok' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                {{ number_format($user->saldo, 2) }}€
            </div>
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
                        <a href="{{ route('mi-cuenta') }}" class="dropdown-item" style="color: #D4B87A;">
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

        <div class="mb-8">
            <h2 class="page-title">Mi Cuenta</h2>
            <p class="page-subtitle">Gestiona tu perfil, datos personales y contraseña</p>
        </div>

        @if(session('success'))
        <div class="mb-6 px-5 py-3 text-sm rounded-md" style="background-color: rgba(45,106,79,0.15); border: 1px solid rgba(45,106,79,0.4); color: #2D6A4F;">{{ session('success') }}</div>
        @endif
        @if(session('success_pass'))
        <div class="mb-6 px-5 py-3 text-sm rounded-md" style="background-color: rgba(45,106,79,0.15); border: 1px solid rgba(45,106,79,0.4); color: #2D6A4F;">{{ session('success_pass') }}</div>
        @endif
        @if($errors->any())
        <div class="mb-6 px-5 py-3 text-sm rounded-md" style="background-color: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.3); color: #DC2626;">{{ $errors->first() }}</div>
        @endif

        {{-- CARTERA --}}
        <div class="card mb-6">
            <div class="card-header">
                <p class="card-title">Mi Cartera</p>
                <p class="card-subtitle">Saldo disponible para compras en el economato</p>
            </div>
            <div class="p-6 flex items-center justify-between">
                <div>
                    <p class="form-label">Saldo actual</p>
                    <p class="{{ $user->saldo < 0 ? 'saldo-grande-danger' : 'saldo-grande-ok' }}">
                        {{ number_format($user->saldo, 2) }}€
                    </p>
                    @if($user->saldo < 0)
                    <p class="saldo-aviso-danger">Tienes una deuda pendiente de {{ number_format(abs($user->saldo), 2) }}€</p>
                    @else
                    <p class="saldo-aviso-ok">El saldo lo gestiona el administrador</p>
                    @endif
                </div>
                <div class="w-16 h-16 flex items-center justify-center rounded-md" style="background-color: rgba(102,100,96,0.1); border: 1px solid var(--color-border);">
                    <svg class="w-8 h-8" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- FOTO DE PERFIL --}}
        <div class="card mb-6">
            <div class="card-header">
                <p class="card-title">Foto de Perfil</p>
                <p class="card-subtitle">JPG, PNG o GIF · Máximo 2 MB</p>
            </div>
            <div class="p-6 flex items-center gap-6">
                <div class="w-20 h-20 rounded-full overflow-hidden border-2 flex-shrink-0" style="border-color: var(--color-border);">
                    @if($user->avatar)
                        <img id="avatar-preview" src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->nombre }}" class="w-full h-full object-cover">
                    @else
                        <div id="avatar-initials" class="w-full h-full flex items-center justify-center" style="background-color: rgba(102,100,96,0.15);">
                            <span class="font-syne text-2xl font-bold" style="color: var(--color-text-muted);">{{ strtoupper(substr($user->nombre, 0, 2)) }}</span>
                        </div>
                        <img id="avatar-preview" src="" alt="" class="w-full h-full object-cover hidden">
                    @endif
                </div>
                <form method="POST" action="{{ route('mi-cuenta.avatar') }}" enctype="multipart/form-data" class="flex-1">
                    @csrf
                    <div class="flex gap-3 items-center">
                        <input type="file" name="avatar" id="avatar-input" accept="image/*" onchange="previewAvatar(this)"
                        class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-medium file:uppercase file:tracking-wider file:rounded file:cursor-pointer file:transition"
                        style="color: var(--color-text-muted); --tw-file-bg: #1C1C1C;"
                        onmouseover="">
                        <button type="submit" class="btn-primary flex-shrink-0">Subir</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- DATOS PERSONALES --}}
        <div class="card mb-6">
            <div class="card-header">
                <p class="card-title">Datos Personales</p>
                <p class="card-subtitle">Actualiza tu nombre, email y edad</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('mi-cuenta.actualizar') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Edad</label>
                            <input type="number" name="edad" value="{{ old('edad', $user->edad) }}" min="1" max="120" class="form-input">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input">
                    </div>
                    <div class="mb-6">
                        <label class="form-label">Rol</label>
                        <div class="field-readonly">{{ ucfirst($user->rol) }}</div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- CAMBIAR CONTRASENA --}}
        <div class="card">
            <div class="card-header">
                <p class="card-title">Cambiar Contraseña</p>
                <p class="card-subtitle">Mínimo 8 caracteres</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('mi-cuenta.password') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Contraseña actual</label>
                        <input type="password" name="password_actual" required class="form-input">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Nueva contraseña</label>
                        <input type="password" name="password_nuevo" required class="form-input">
                    </div>
                    <div class="mb-6">
                        <label class="form-label">Confirmar nueva contraseña</label>
                        <input type="password" name="password_nuevo_confirmation" required class="form-input">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">Actualizar contraseña</button>
                    </div>
                </form>
            </div>
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
            if (menu && !menu.contains(e.target)) {
                document.getElementById('dropdown').classList.add('hidden');
                document.getElementById('chevron').style.transform = 'rotate(0deg)';
            }
        });
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview  = document.getElementById('avatar-preview');
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