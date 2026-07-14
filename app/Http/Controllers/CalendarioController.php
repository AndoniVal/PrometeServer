<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Laravel\Socialite\Facades\Socialite;
use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleCalendar;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Asignacion;
use App\Models\Material;

class CalendarioController extends Controller
{
   
    public function redirect()
    {
        return Socialite::driver('google')
             ->scopes(['https://www.googleapis.com/auth/calendar.readonly'])
            ->with([
                'access_type' => 'offline',
                'prompt'      => 'consent',
            ])
            ->redirect();
    }

   
   public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/dashboard')->withErrors(['google' => 'No se pudo conectar con Google.']);
        }

        session([
            'google_token'      => Crypt::encryptString($googleUser->token),
            'google_expires_at' => now()->addSeconds($googleUser->expiresIn)->timestamp,
        ]);

        if ($googleUser->refreshToken) {
            session(['google_refresh_token' => Crypt::encryptString($googleUser->refreshToken)]);
        }

        return redirect('/dashboard')->with('success', 'Cuenta de Google conectada en esta sesión.');
    }

    public function verEvento($eventId)
    {
        $accessToken = Crypt::decryptString(session('google_token'));
        $client = new GoogleClient();
        $client->setAccessToken($accessToken);
        $service = new GoogleCalendar($client);

        // Localizamos el calendario "Local"
        $calendarioId = 'primary';
        foreach ($service->calendarList->listCalendarList()->getItems() as $cal) {
            if ($cal->getSummary() === 'Local') {
                $calendarioId = $cal->getId();
                break;
            }
        }

        $evento = $service->events->get($calendarioId, $eventId);

        // Horas de inicio y fin (los eventos con hora usan dateTime; los de día completo, date)
        $inicio = $evento->getStart()->getDateTime() ?? $evento->getStart()->getDate();
        $fin    = $evento->getEnd()->getDateTime() ?? $evento->getEnd()->getDate();

        // Materiales ya asignados a este evento
        $asignados = Asignacion::with('material')
            ->where('google_event_id', $eventId)
            ->get()
            ->map(fn ($a) => [
                'id'     => $a->id,
                'nombre' => $a->material->nombre,
            ]);

        return response()->json([
            'titulo'  => $evento->getSummary() ?? '(sin título)',
            'creador' => $evento->getCreator() ? $evento->getCreator()->getDisplayName()
                                               ?? $evento->getCreator()->getEmail() : 'Desconocido',
            'inicio'  => \Carbon\Carbon::parse($inicio)->format('d/m/Y H:i'),
            'fin'     => \Carbon\Carbon::parse($fin)->format('d/m/Y H:i'),
            'materiales' => $asignados,
        ]);
    }

    // Devuelve los materiales que NO están asignados a ningún evento (los libres)
    public function materialesLibres()
    {
        if (auth()->user()->rol !== 'administrador') {
            abort(403);
        }

        $libres = Material::doesntHave('asignacion')
            ->get(['id', 'nombre'])
            ->map(fn ($m) => ['id' => $m->id, 'nombre' => $m->nombre]);

        return response()->json($libres);
    }

    // Asigna un material a un evento (reservarlo)
    public function asignarMaterial(Request $request)
    {
        // 1. Solo administradores
        if (auth()->user()->rol !== 'administrador') {
            abort(403, 'No autorizado.');
        }

        // 2. Validamos lo que llega
        $datos = $request->validate([
            'google_event_id'     => 'required|string',
            'google_event_titulo' => 'nullable|string',
            'id_mat'              => 'required|exists:materiales,id',
        ]);

        // 3. Comprobamos en el servidor que el material sigue libre
        if (Asignacion::where('id_mat', $datos['id_mat'])->exists()) {
            return response()->json(['error' => 'Ese material ya está asignado a un evento.'], 422);
        }

        // 4. Creamos la asignación
        $asignacion = Asignacion::create([
            'google_event_id'     => $datos['google_event_id'],
            'google_event_titulo' => $datos['google_event_titulo'] ?? null,
            'id_mat'              => $datos['id_mat'],
            'id_us'               => auth()->id(),
        ]);

        $asignacion->load('material');

        return response()->json([
            'id'     => $asignacion->id,
            'nombre' => $asignacion->material->nombre,
        ]);
    }

    // Quita un material de un evento (liberarlo)
    public function quitarMaterial(Request $request)
    {
        if (auth()->user()->rol !== 'administrador') {
            abort(403, 'No autorizado.');
        }

        $datos = $request->validate([
            'asignacion_id' => 'required|exists:asignaciones,id',
        ]);

        Asignacion::where('id', $datos['asignacion_id'])->delete();

        return response()->json(['ok' => true]);
    }

   public function index(Request $request)
    {
        if (! session()->has('google_token')) {
            return view('calendario', [
                'conectado' => false,
                'user'      => auth()->user(),
            ]);
        }

        $mesBase = $request->query('mes')
            ? Carbon::createFromFormat('Y-m', $request->query('mes'))->startOfMonth()
            : now()->startOfMonth();

        $accessToken = Crypt::decryptString(session('google_token'));
        $client = new GoogleClient();
        $client->setAccessToken($accessToken);
        $service = new GoogleCalendar($client);

        $calendarioId = 'primary'; 
        foreach ($service->calendarList->listCalendarList()->getItems() as $cal) {
            if ($cal->getSummary() === 'Local') {
                $calendarioId = $cal->getId();
                break;
            }
        }


        $resultados = $service->events->listEvents($calendarioId, [
            'timeMin'      => $mesBase->copy()->startOfMonth()->toRfc3339String(),
            'timeMax'      => $mesBase->copy()->endOfMonth()->toRfc3339String(),
            'singleEvents' => true,
            'orderBy'      => 'startTime',
        ]);

        $eventosPorDia = [];
        foreach ($resultados->getItems() as $evento) {
            $inicio = $evento->getStart()->getDateTime() ?? $evento->getStart()->getDate();
            $clave  = Carbon::parse($inicio)->format('Y-m-d');
            $eventosPorDia[$clave][] = [
                'id'     => $evento->getId(),
                'titulo' => $evento->getSummary() ?? '(sin título)',
            ];
        }

        $inicioRejilla = $mesBase->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $finRejilla    = $mesBase->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $semanas = [];
        $cursor  = $inicioRejilla->copy();
        while ($cursor <= $finRejilla) {
            $semana = [];
            for ($i = 0; $i < 7; $i++) {
                $clave = $cursor->format('Y-m-d');
                $semana[] = [
                    'numero'  => $cursor->day,
                    'delMes'  => $cursor->month === $mesBase->month,
                    'esHoy'   => $cursor->isToday(),
                    'eventos' => $eventosPorDia[$clave] ?? [],
                ];
                $cursor->addDay();
            }
            $semanas[] = $semana;
        }

        // Meses para el desplegable: el actual + los 11 siguientes
        $mesesDisponibles = [];
        $cursorMes = now()->startOfMonth();
        for ($i = 0; $i < 12; $i++) {
            $mesesDisponibles[] = [
                'valor' => $cursorMes->format('Y-m'),
                'label' => ucfirst($cursorMes->locale('es')->isoFormat('MMMM YYYY')),
            ];
            $cursorMes = $cursorMes->copy()->addMonth();
        }

        return view('calendario', [
            'conectado'        => true,
            'user'             => auth()->user(),
            'semanas'          => $semanas,
            'tituloMes'        => ucfirst($mesBase->locale('es')->isoFormat('MMMM YYYY')),
            'mesActualValor'   => $mesBase->format('Y-m'),
            'mesesDisponibles' => $mesesDisponibles,
        ]);
    }

    

}