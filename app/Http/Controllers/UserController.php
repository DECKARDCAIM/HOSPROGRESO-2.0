<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::with(['role', 'workDepartment', 'unityExecution'])->get();
        $totalUsers = $users->count();
        $activeUsers = $users->where('is_active', true)->count();
        $inactiveUsers = $users->where('is_active', false)->count();

        return view('modules.user.index', compact('users', 'totalUsers', 'activeUsers', 'inactiveUsers'));
    }

    /**
     * Actualizar el estado del usuario autenticado
     */
    public function updateEstado(Request $request)
    {
        try {
            $request->validate([
                'estado' => 'required|in:disponible,ocupado,ausente,privado'
            ]);

            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Actualizar directamente en la base de datos para evitar problemas con timestamps
            DB::table('users')
                ->where('id', $user->id)
                ->update(['estado' => $request->estado]);
            
            // Refrescar el modelo para obtener el valor actualizado
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente',
                'estado' => $user->estado
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar estado: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar la preferencia de tema del usuario autenticado
     */
    public function updateThemePreference(Request $request)
    {
        try {
            $request->validate([
                'theme' => 'required|in:auto,default,dark'
            ]);

            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Actualizar directamente en la base de datos
            DB::table('users')
                ->where('id', $user->id)
                ->update(['theme_preference' => $request->theme]);
            
            // Refrescar el modelo
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Preferencia de tema actualizada correctamente',
                'theme' => $user->theme_preference
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar preferencia de tema: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la preferencia de tema: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar el perfil público de un usuario y los miembros de su departamento
     */
    public function showProfile($id)
    {
        $user = \App\Models\User::with(['role', 'workDepartment', 'unityExecution'])->findOrFail($id);

        // Miembros del departamento del usuario
        $departamentMembers = \App\Models\User::where('id', '!=', $user->id)
            ->where('is_active', true)
            ->where(function ($query) use ($user) {
                if ($user->unity_execution_id) {
                    $query->where('unity_execution_id', $user->unity_execution_id);
                } elseif ($user->work_department_id) {
                    $query->where('work_department_id', $user->work_department_id);
                } else {
                    $query->whereRaw('1 = 0');
                }
            })
            ->limit(10)
            ->get();

        return view('modules.users.profile', compact('user', 'departamentMembers'));
    }
}

