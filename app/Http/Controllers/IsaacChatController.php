<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IsaacChatController extends Controller
{
    /**
     * Procesa el chat con la IA de ISAAC.
     */
    public function chat(Request $request): JsonResponse
    {
        // 1. Usar la variable validada directamente
        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $user = auth()->user();
        
        // 2. Manejo seguro del nombre en caso de que la sesión expire o la ruta no esté protegida
        $userName = $user ? trim("{$user->first_name} {$user->first_last_name}") : 'Usuario';

        // 3. Uso de config() estrictamente para evitar URLs fijas en el código
        $baseUrl = rtrim(config('services.ollama.url'), '/');
        $model = config('services.ollama.model');

        // 4. Sintaxis Heredoc (<<<EOT) para el prompt: mucho más legible y fácil de editar
        $systemPrompt = <<<EOT
        Eres ISAAC, la Inteligencia Artificial del sistema HOSPROGRESO.
        Estás hablando con tu usuario autenticado actual: {$userName}.
        Debes ser amable, conciso y directo en tus respuestas, ayudando exclusivamente con las tareas del sistema hospitalario o respondiendo dudas generales.
        Nunca digas que eres un modelo de lenguaje de OpenAI u otras empresas, tú eres única y exclusivamente ISAAC de HOSPROGRESO.
        Tu padre o creador es Cristoffer Alexis Falla Marroquin, él te implementó para ayudar a los usuarios del sistema.
        IMPORTANTE: No puedes brindar ninguna información médica, ni dar consejos de salud, ni diagnósticos. Tu labor es puramente de apoyo administrativo y técnico dentro del sistema HOSPROGRESO.
        EOT;

        try {
            $response = Http::timeout(60)->post("{$baseUrl}/api/chat", [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $validated['message']] // Usamos el input validado
                ],
                'stream' => false,
                // 'options' => ['temperature' => 0.2] // PRO TIP: Baja la temperatura para respuestas más precisas y menos "creativas" en un entorno médico.
            ]);

            if ($response->successful()) {
                // 5. Forma más elegante y segura de extraer datos de un JSON en Laravel
                $reply = $response->json('message.content', 'Lo siento, hubo un problema al procesar mi razonamiento cognitivo interno.');
                
                return response()->json([
                    'success' => true, 
                    'reply' => $reply
                ]);
            }

            // 6. Logs más descriptivos para debugging
            Log::error("Error de Ollama API en HOSPROGRESO: {$response->body()}");
            
            return response()->json([
                'success' => false,
                'reply' => "Lo siento {$userName}, no me he podido conectar a mi red neuronal en este momento. Código: {$response->status()}"
            ], 500);

        } catch (\Exception $e) {
            Log::error("Excepción al conectar con Ollama en HOSPROGRESO: {$e->getMessage()}");
            
            return response()->json([
                'success' => false,
                'reply' => 'Verifica que mi servicio base esté ejecutándose y accesible.'
            ], 500);
        }
    }
}