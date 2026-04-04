<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\CivilStatus;
use App\Models\Country;
use App\Models\Department;
use App\Models\Gender;
use App\Models\Municipality;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Specialty;
use App\Models\UnityExecution;
use App\Models\User;
use App\Models\WorkDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = 'users_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['users', 'roles', 'work_departments', 'specialties', 'countries', 'departments', 'municipalities', 'genders', 'civil_statuses'])
            ->remember($cacheKey, now()->addHours(2), function () use ($request) {

                $query = User::with([
                    'role', 'staff.workDepartment', 'staff.unityExecution',
                    'staff.specialty', 'staff.schedule', 'staff.municipality.department.country',
                    'staff.civilStatus',
                ]);

                if ($search = $request->get('search')) {
                    $query->where(function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('first_last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhereHas('staff', function ($q) use ($search) {
                                $q->where('collegiate_number', 'like', "%{$search}%")
                                    ->orWhereHas('workDepartment', fn ($sq) => $sq->where('name', 'like', "%{$search}%"));
                            });
                    });
                }

                if ($request->filled('role_id')) {
                    $query->where('role_id', $request->role_id);
                }
                if ($request->filled('work_department_id')) {
                    $query->whereHas('staff', fn ($q) => $q->where('work_department_id', $request->work_department_id));
                }
                if ($request->filled('specialty_id')) {
                    $query->whereHas('staff', fn ($q) => $q->where('specialty_id', $request->specialty_id));
                }
                if ($request->filled('gender_id')) {
                    $query->whereHas('staff', fn ($q) => $q->where('gender_id', $request->gender_id));
                }
                if ($request->filled('country_id')) {
                    $query->whereHas('staff.municipality.department', fn ($q) => $q->where('country_id', $request->country_id));
                }
                if ($request->filled('department_id')) {
                    $query->whereHas('staff.municipality', fn ($q) => $q->where('department_id', $request->department_id));
                }
                if ($request->filled('municipality_id')) {
                    $query->whereHas('staff', fn ($q) => $q->where('municipality_id', $request->municipality_id));
                }
                if ($request->filled('birth_date_from')) {
                    $query->whereHas('staff', fn ($q) => $q->whereDate('birth_date', '>=', $request->birth_date_from));
                }
                if ($request->filled('birth_date_to')) {
                    $query->whereHas('staff', fn ($q) => $q->whereDate('birth_date', '<=', $request->birth_date_to));
                }

                $query->where('is_active', $request->input('status') === 'inactive' ? false : true);

                $perPage = $request->get('per_page', 25);
                $users = $query->paginate($perPage)->appends($request->query());

                $roles = Role::where('is_active', true)->orderBy('name')->get();
                $workDepartments = WorkDepartment::orderBy('name')->get();
                $specialties = Specialty::where('is_active', true)->orderBy('name')->get();
                $countries = Country::orderBy('name')->get();
                $departments = Department::orderBy('name')->get();
                $municipalities = Municipality::orderBy('name')->get();
                $genders = Gender::where('is_active', true)->orderBy('name')->get();
                $civilStatuses = CivilStatus::where('is_active', true)->orderBy('name')->get();

                $totalUsers = User::count();
                $activeUsers = User::where('is_active', true)->count();
                $inactiveUsers = User::where('is_active', false)->count();

                return compact(
                    'users', 'totalUsers', 'activeUsers', 'inactiveUsers',
                    'roles', 'workDepartments', 'specialties', 'countries',
                    'departments', 'municipalities', 'genders', 'civilStatuses'
                );
            });

        return view('modules.administration.user.index', $data);
    }


    public function updateThemePreference(Request $request)
    {
        try {
            $request->validate(['theme' => 'required|in:auto,default,dark']);
            $user = Auth::user();

            if (! $user) {
                return response()->json(['success' => false, 'message' => 'Usuario no autenticado'], 401);
            }

            DB::table('users')->where('id', $user->id)->update(['theme_preference' => $request->theme]);
            $user->refresh();

            return response()->json(['success' => true, 'message' => 'Preferencia de tema actualizada correctamente', 'theme' => $user->theme_preference]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar preferencia de tema: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Error al actualizar la preferencia de tema: '.$e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $user = User::with(['role', 'staff.workDepartment', 'staff.unityExecution'])->findOrFail($id);

        $departamentMembers = User::where('id', '!=', $user->id)
            ->where('is_active', true)
            ->whereHas('staff', function ($query) use ($user) {
                if ($user->staff && $user->staff->unity_execution_id) {
                    $query->where('unity_execution_id', $user->staff->unity_execution_id);
                } elseif ($user->staff && $user->staff->work_department_id) {
                    $query->where('work_department_id', $user->staff->work_department_id);
                } else {
                    $query->whereRaw('1 = 0');
                }
            })
            ->limit(10)->get();

        return view('modules.administration.user.profile', compact('user', 'departamentMembers'));
    }

    public function create()
    {
        $data = $this->getCachedCatalogs();

        return view('modules.administration.user.create', $data);
    }

    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();

            $userData = [
                'first_name' => $validated['first_name'],
                'second_name' => $validated['second_name'] ?? null,
                'third_name' => $validated['third_name'] ?? null,
                'first_last_name' => $validated['first_last_name'],
                'second_last_name' => $validated['second_last_name'] ?? null,
                'married_last_name' => $validated['married_last_name'] ?? null,
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role_id' => $validated['role_id'],
                'is_active' => $request->input('is_active', 1) == '1',
            ];

            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                $name = Str::random(40).'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/profile_photos'), $name);
                $userData['profile_photo_path'] = 'profile_photos/'.$name;
            }

            $user = User::create($userData);

            $user->staff()->create([
                'cui' => $validated['cui'] ?? null,
                'nit' => $validated['nit'] ?? null,
                'civil_status_id' => $validated['civil_status_id'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'gender_id' => $validated['gender_id'] ?? null,
                'specialty_id' => $validated['specialty_id'] ?? null,
                'schedule_id' => $validated['schedule_id'] ?? null,
                'municipality_id' => $validated['municipality_id'] ?? null,
                'address' => $validated['address'] ?? null,
                'unity_execution_id' => $validated['unity_execution_id'] ?? null,
                'work_department_id' => $validated['work_department_id'] ?? null,
                'collegiate_number' => $validated['collegiate_number'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear usuario: '.$e->getMessage());

            return back()->withInput()->with('error', 'Ocurrió un error al crear el usuario.');
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $data = $this->getCachedCatalogs();

        $data['departments'] = ($user->staff && $user->staff->municipality && $user->staff->municipality->department)
            ? Department::where('country_id', $user->staff->municipality->department->country_id)->where('is_active', true)->orderBy('name')->get()
            : collect();

        $data['municipalities'] = ($user->staff && $user->staff->municipality)
            ? Municipality::where('department_id', $user->staff->municipality->department_id)->where('is_active', true)->orderBy('name')->get()
            : collect();

        $data['user'] = $user;

        return view('modules.administration.user.edit', $data);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        try {
            DB::beginTransaction();
            $validated = $request->validated();

            $updateData = [
                'first_name' => $validated['first_name'],
                'second_name' => $validated['second_name'] ?? null,
                'third_name' => $validated['third_name'] ?? null,
                'first_last_name' => $validated['first_last_name'],
                'second_last_name' => $validated['second_last_name'] ?? null,
                'married_last_name' => $validated['married_last_name'] ?? null,
                'email' => $validated['email'],
                'role_id' => $validated['role_id'],
                'is_active' => $request->input('is_active', 1) == '1',
            ];

            if (! empty($validated['password'])) {
                $updateData['password'] = $validated['password']; // Laravel lo hasheará por el mutator/cast
            }

            if ($request->hasFile('profile_photo')) {
                if ($user->profile_photo_path) {
                    $oldPath = public_path('storage/'.$user->profile_photo_path);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $file = $request->file('profile_photo');
                $name = Str::random(40).'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/profile_photos'), $name);
                $updateData['profile_photo_path'] = 'profile_photos/'.$name;
            }

            $user->update($updateData);

            $user->staff()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'cui' => $validated['cui'] ?? null,
                    'nit' => $validated['nit'] ?? null,
                    'civil_status_id' => $validated['civil_status_id'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'birth_date' => $validated['birth_date'] ?? null,
                    'gender_id' => $validated['gender_id'] ?? null,
                    'specialty_id' => $validated['specialty_id'] ?? null,
                    'schedule_id' => $validated['schedule_id'] ?? null,
                    'municipality_id' => $validated['municipality_id'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'unity_execution_id' => $validated['unity_execution_id'] ?? null,
                    'work_department_id' => $validated['work_department_id'] ?? null,
                    'collegiate_number' => $validated['collegiate_number'] ?? null,
                ]
            );

            DB::commit();

            return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar usuario: '.$e->getMessage());

            return back()->withInput()->with('error', 'Ocurrió un error al actualizar el usuario.');
        }
    }

    public function ping()
    {
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update(['is_active' => false]);

            return redirect()->route('users.index')->with('success', "El usuario {$user->first_name} ha sido desactivado.");
        } catch (\Exception $e) {
            Log::error('Error al desactivar usuario: '.$e->getMessage());

            return back()->with('error', 'Ocurrió un error al desactivar el usuario.');
        }
    }

    public function restore($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update(['is_active' => true]);

            return redirect()->route('users.index')->with('success', "El usuario {$user->first_name} ha sido reactivado.");
        } catch (\Exception $e) {
            Log::error('Error al reactivar usuario: '.$e->getMessage());

            return back()->with('error', 'Ocurrió un error al reactivar el usuario.');
        }
    }

    private function getCachedCatalogs()
    {
        return [
            'roles' => Cache::tags(['roles'])->remember('active_roles', now()->addDays(1), fn () => Role::where('is_active', true)->get()),
            'schedules' => Cache::tags(['schedules'])->remember('active_schedules', now()->addDays(1), fn () => Schedule::where('is_active', true)->get()),
            'countries' => Cache::tags(['countries'])->remember('active_countries', now()->addDays(1), fn () => Country::orderBy('name')->get()),
            'specialties' => Cache::tags(['specialties'])->remember('active_specialties', now()->addDays(1), fn () => Specialty::where('is_active', true)->orderBy('name')->get()),
            'unityExecutions' => Cache::tags(['unity_executions'])->remember('active_unity_executions', now()->addDays(1), fn () => UnityExecution::orderBy('name')->get()),
            'workDepartments' => Cache::tags(['work_departments'])->remember('active_work_departments', now()->addDays(1), fn () => WorkDepartment::orderBy('name')->get()),
            'genders' => Cache::tags(['genders'])->remember('active_genders', now()->addDays(1), fn () => Gender::where('is_active', true)->orderBy('name')->get()),
            'civilStatuses' => Cache::tags(['civil_statuses'])->remember('active_civil_statuses', now()->addDays(1), fn () => CivilStatus::where('is_active', true)->orderBy('name')->get()),
        ];
    }
}
