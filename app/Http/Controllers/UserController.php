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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 25);
        $search = $request->get('search');

        $query = \App\Models\User::with(['role', 'workDepartment', 'unityExecution', 'specialty', 'schedule', 'country', 'department', 'municipality']);

        // Buscador
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('first_last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('collegiate_number', 'like', "%{$search}%")
                  ->orWhereHas('role', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('workDepartment', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtros avanzados
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('work_department_id')) {
            $query->where('work_department_id', $request->work_department_id);
        }

        if ($request->filled('specialty_id')) {
            $query->where('specialty_id', $request->specialty_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        } else {
            // Por defecto mostrar solo activos
            $query->where('is_active', true);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('municipality_id')) {
            $query->where('municipality_id', $request->municipality_id);
        }

        if ($request->filled('birth_date_from')) {
            $query->whereDate('birth_date', '>=', $request->birth_date_from);
        }

        if ($request->filled('birth_date_to')) {
            $query->whereDate('birth_date', '<=', $request->birth_date_to);
        }

        $users = $query->paginate($perPage)->appends($request->query());
        
        // Datos para los filtros
        $roles = Role::where('is_active', true)->orderBy('name')->get();
        $workDepartments = \App\Models\WorkDepartment::orderBy('name')->get();
        $specialties = Specialty::where('is_active', true)->orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $municipalities = Municipality::orderBy('name')->get();

        // Contadores
        $totalUsers = \App\Models\User::count();
        $activeUsers = \App\Models\User::where('is_active', true)->count();
        $inactiveUsers = \App\Models\User::where('is_active', false)->count();

        return view('modules.administration.user.index', compact(
            'users', 'totalUsers', 'activeUsers', 'inactiveUsers',
            'roles', 'workDepartments', 'specialties', 'countries', 'departments', 'municipalities'
        ));
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
    public function show($id)
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

        return view('modules.administration.user.profile', compact('user', 'departamentMembers'));
    }

    public function create()
    {
        $roles           = Role::where('is_active', true)->get();
        $schedules       = Schedule::where('is_active', true)->get();
        $countries       = Country::orderBy('name')->get();
        $specialties     = Specialty::where('is_active', true)->orderBy('name')->get();
        $unityExecutions = \App\Models\UnityExecution::orderBy('name')->get();
        $workDepartments = \App\Models\WorkDepartment::orderBy('name')->get();

        return view('modules.administration.user.create', compact(
            'roles', 'schedules', 'countries', 'specialties', 'unityExecutions', 'workDepartments'
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

            $userData = [
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
                'is_active' => $request->input('is_active', 1) == '1',
                'estado' => 'disponible'
            ];

            // Subir Avatar si existe
            if ($request->hasFile('profile_photo')) {
                $avatarFile = $request->file('profile_photo');
                $avatarName = Str::random(40) . '.' . $avatarFile->getClientOriginalExtension();
                $avatarPath = 'profile_photos/' . $avatarName;
                
                $avatarFile->move(public_path('storage/profile_photos'), $avatarName);
                $userData['profile_photo_path'] = $avatarPath;
            }

            $user = \App\Models\User::create($userData);

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

        $roles           = Role::where('is_active', true)->get();
        $schedules       = Schedule::where('is_active', true)->get();
        $countries       = Country::orderBy('name')->get();
        $specialties     = Specialty::where('is_active', true)->orderBy('name')->get();
        $unityExecutions = \App\Models\UnityExecution::orderBy('name')->get();
        $workDepartments = \App\Models\WorkDepartment::orderBy('name')->get();

        // Cargar departamentos según el país del usuario (igual que PatientController)
        $departments = $user->country_id
            ? Department::where('country_id', $user->country_id)->where('is_active', true)->orderBy('name')->get()
            : collect();

        // Cargar municipios según el departamento del usuario
        $municipalities = $user->department_id
            ? Municipality::where('department_id', $user->department_id)->where('is_active', true)->orderBy('name')->get()
            : collect();

        return view('modules.administration.user.edit', compact(
            'user', 'roles', 'schedules', 'countries', 'departments', 'municipalities',
            'specialties', 'unityExecutions', 'workDepartments'
        ));
    }

    public function update(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'first_last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'first_name' => $request->first_name,
                'second_name' => $request->second_name,
                'third_name' => $request->third_name,
                'first_last_name' => $request->first_last_name,
                'second_last_name' => $request->second_last_name,
                'married_last_name' => $request->married_last_name,
                'email' => $request->email,
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
                'is_active' => $request->input('is_active', 1) == '1',
            ];

            if ($request->filled('password')) {
                $updateData['password'] = $request->password;
            }

            // Subir Avatar si existe
            if ($request->hasFile('profile_photo')) {
                // Eliminar anterior
                if ($user->profile_photo_path) {
                    $oldPublicFile = public_path('storage/' . $user->profile_photo_path);
                    if (File::exists($oldPublicFile)) {
                        try {
                            File::delete($oldPublicFile);
                        } catch (\Exception $e) {}
                    }
                }

                $avatarFile = $request->file('profile_photo');
                $avatarName = Str::random(40) . '.' . $avatarFile->getClientOriginalExtension();
                $avatarPath = 'profile_photos/' . $avatarName;
                
                $avatarFile->move(public_path('storage/profile_photos'), $avatarName);
                $updateData['profile_photo_path'] = $avatarPath;
            }

            $user->update($updateData);

            DB::commit();

            return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar usuario: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Mantener la sesión activa (Ping)
     */
    public function ping()
    {
        return response()->json(['success' => true]);
    }

    /**
     * Desactivar un usuario (Borrado lógico)
     */
    public function destroy($id)
    {
        try {
            $user = \App\Models\User::findOrFail($id);
            $user->is_active = false;
            $user->save();

            return redirect()->route('users.index')->with('success', 'El usuario ' . $user->first_name . ' has sido desactivado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al desactivar usuario: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al desactivar el usuario.');
        }
    }

    /**
     * Reactivar un usuario
     */
    public function restore($id)
    {
        try {
            $user = \App\Models\User::findOrFail($id);
            $user->is_active = true;
            $user->save();

            return redirect()->route('users.index')->with('success', 'El usuario ' . $user->first_name . ' ha sido reactivado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al reactivar usuario: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al reactivar el usuario.');
        }
    }
}
