<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Country;
use App\Models\Department;
use App\Models\Municipality;
use App\Models\Specialty;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 25);
        $search = $request->get('search');

        $query = \App\Models\User::with(['role', 'workDepartment', 'unityExecution', 'specialty', 'schedule']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('first_last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('role', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('workDepartment', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $users = $query->paginate($perPage)->appends($request->query());
        
        // Para los contadores seguimos queriendo el total sin paginar
        $totalUsers = \App\Models\User::count();
        $activeUsers = \App\Models\User::where('is_active', true)->count();
        $inactiveUsers = \App\Models\User::where('is_active', false)->count();

        return view('modules.user.index', compact('users', 'totalUsers', 'activeUsers', 'inactiveUsers'));
    }

    /**
     * Actualizar el estado del usuario autenticado
     */
    public function updateEstado(Request $request)
    {
        try {
            $request->validate([
                'estado' => 'required|in:disponible,ocupado,ausente,privado,desconectado'
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

    public function create()
    {
        $roles         = Role::where('is_active', true)->get();
        $schedules     = Schedule::where('is_active', true)->get();
        $countries     = Country::orderBy('name')->get();
        $departments   = Department::orderBy('name')->get();
        $municipalities = Municipality::orderBy('name')->get();
        $specialties   = Specialty::where('is_active', true)->orderBy('name')->get();

        return view('modules.user.create', compact(
            'roles', 'schedules', 'countries', 'departments', 'municipalities', 'specialties'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'first_last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        try {
            DB::beginTransaction();

            $user = \App\Models\User::create([
                'first_name' => $request->first_name,
                'second_name' => $request->second_name,
                'third_name' => $request->third_name,
                'first_last_name' => $request->first_last_name,
                'second_last_name' => $request->second_last_name,
                'married_last_name' => $request->married_last_name,
                'email' => $request->email,
                'password' => $request->password, // Password hashing is handled by User model cast
                'cui' => $request->cui,
                'nit' => $request->nit,
                'marital_status' => $request->marital_status,
                'phone' => $request->phone,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'role_id' => $request->role_id,
                'specialty_id' => $request->specialty_id,
                'schedule_id' => $request->schedule_id,
                'country_id' => $request->country_id,
                'department_id' => $request->department_id,
                'municipality_id' => $request->municipality_id,
                'address' => $request->address,
                'is_active' => true,
                'estado' => 'disponible'
            ]);

            DB::commit();

            return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear usuario: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al crear el usuario: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = \App\Models\User::findOrFail($id);
        return view('modules.user.edit', compact('user'));
    }

    /**
     * Mantener la sesión activa (Ping)
     */
    public function ping()
    {
        return response()->json(['success' => true]);
    }
}
