<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Laravel\Socialite\Facades\Socialite;
use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleCalendar;

class CalendarioController extends Controller
{
   
    public function redirect()
    {
        return Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/calendar.events'])
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

    public function index()
    {
        
        
        if (! session()->has('google_token')) {
            return view('calendario', ['conectado' => false, 'eventos' => []]);
        }

        
        $accessToken = Crypt::decryptString(session('google_token'));

        
        $client = new GoogleClient();
        $client->setAccessToken($accessToken);

        
        $service = new GoogleCalendar($client);

        $resultados = $service->events->listEvents('primary', [
            'timeMin'      => now()->toRfc3339String(),
            'singleEvents' => true,
            'orderBy'      => 'startTime',
            'maxResults'   => 20,
        ]);

        // 4. Pasamos los eventos a la vista
        return view('calendario', [
            'conectado' => true,
            'eventos'   => $resultados->getItems(),
        ]);
    }
}