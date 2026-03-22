<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackSessionHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $sessionId = $request->session()->getId();

            $history = \App\Models\SessionHistory::where('session_id', $sessionId)->first();

            if ($history) {
                // Actualiza el timestamp de inactividad
                $history->update([
                    'user_id' => \Illuminate\Support\Facades\Auth::id(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'last_active_at' => now(),
                    'is_active' => true,
                ]);
            } else {
                // Registra por primera vez con su login_at original
                \App\Models\SessionHistory::create([
                    'session_id' => $sessionId,
                    'user_id' => \Illuminate\Support\Facades\Auth::id(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'login_at' => now(),
                    'last_active_at' => now(),
                    'is_active' => true,
                ]);
            }
        }

        return $next($request);
    }
}
