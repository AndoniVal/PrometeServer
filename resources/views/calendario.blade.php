<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Mi calendario</title>
</head>
<body>
    <h1>Mi calendario de Google</h1>

    @if (! $conectado)
        <p>No has conectado tu cuenta de Google.</p>
        <a href="{{ route('google.redirect') }}">Conectar con Google</a>
    @else
        @if (count($eventos) === 0)
            <p>No tienes eventos próximos.</p>
        @else
            <ul>
                @foreach ($eventos as $evento)
                    <li>
                        <strong>{{ $evento->getSummary() }}</strong><br>
                        {{ $evento->getStart()->getDateTime() ?? $evento->getStart()->getDate() }}
                    </li>
                @endforeach
            </ul>
        @endif
    @endif
</body>
</html>