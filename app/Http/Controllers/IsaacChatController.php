<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class IsaacChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = auth()->user();
        $userName = $user ? trim("{$user->first_name} {$user->first_last_name}") : 'Usuario';
        $message = trim($validated['message']);

        $executed = RateLimiter::attempt(
            'isaac-chat:'.$user->id,
            $perMinute = 5,
            function () {}
        );

        if (! $executed) {
            return response()->json([
                'success' => false,
                'reply' => "Despacio {$userName}, mis neuronas están procesando mucho. Intenta de nuevo en un momento.",
            ], 429);
        }

        $cacheKey = 'isaac_reply_'.md5($message);

        $reply = Cache::tags(['isaac_chat'])->remember($cacheKey, now()->addDay(), function () use ($message, $userName) {

            $baseUrl = rtrim(config('services.ollama.url'), '/');
            $model = config('services.ollama.model');

            $systemPrompt = <<<EOT
            Eres ISAAC, la Inteligencia Artificial del sistema HOSPROGRESO.
            Estás hablando con tu usuario: {$userName}.
            Debes ser amable, conciso y directo. Ayuda exclusivamente con tareas administrativas o técnicas del sistema.
            Tu creador es Cristoffer Alexis Falla Marroquin.
            REGLA CRÍTICA: No des información médica, diagnósticos ni consejos de salud. Tu labor es soporte técnico y administrativo de HOSPROGRESO.
            EOT;

            try {
                $response = Http::timeout(90)->post("{$baseUrl}/api/chat", [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $message],
                    ],
                    'stream' => false,
                    'options' => [
                        'temperature' => 0.3,
                        'num_predict' => 250,
                    ],
                ]);

                if ($response->successful()) {
                    return $response->json('message.content');
                }

                return null;

            } catch (\Exception $e) {
                Log::error('Excepción en ISAAC: '.$e->getMessage());

                return null;
            }
        });

        if (! $reply) {
            return response()->json([
                'success' => false,
                'reply' => "Lo siento {$userName}, mi red neuronal está teniendo una interferencia. ¿Podrías intentar preguntarme de otra forma?",
            ], 500);
        }

        return response()->json([
            'success' => true,
            'reply' => $reply,
            'cached' => Cache::tags(['isaac_chat'])->has($cacheKey),
        ]);
    }

    public function clearMemory()
    {
        Cache::tags(['isaac_chat'])->flush();

        return response()->json(['message' => 'Memoria de ISAAC reiniciada.']);
    }
}
