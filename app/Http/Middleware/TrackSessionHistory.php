<?php

namespace App\Http\Middleware;

use App\Models\SessionHistory;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackSessionHistory
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $sessionId = $request->session()->getId();

            // Llave de throttle (ejemplo: 600 segundos = 10 minutos)
            $throttleKey = 'active_session_write_'.$sessionId;

            if (! Cache::has($throttleKey)) {

                // Buscamos la sesión primero para decidir si es INSERT o UPDATE
                $history = SessionHistory::where('session_id', $sessionId)->first();

                if ($history) {
                    // Si ya existe, solo actualizamos la actividad (UPDATE)
                    $history->update([
                        'user_id' => $user->id,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'last_active_at' => now(),
                        'is_active' => true,
                    ]);
                } else {
                    // Si NO existe, creamos el registro incluyendo 'login_at' (INSERT)
                    SessionHistory::create([
                        'session_id' => $sessionId,
                        'user_id' => $user->id,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'login_at' => now(), // <--- ESTO SOLUCIONA EL ERROR 1364
                        'last_active_at' => now(),
                        'is_active' => true,
                    ]);
                }

                // Guardamos en Redis el bloqueo por 10 minutos (o el tiempo que prefieras)
                Cache::put($throttleKey, true, 600);
            }
        }

        return $next($request);
    }
}
