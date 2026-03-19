<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Country;

use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Department::with('country');

        // Buscador
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('country', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filtro por País
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $is_active = $request->status === 'active';
            $query->where('is_active', $is_active);
        }

        $perPage             = $request->input('per_page', 25);
        $departments         = $query->orderBy('name')->paginate($perPage)->appends($request->query());
        
        $totalDepartments    = Department::count();
        $activeDepartments   = Department::where('is_active', true)->count();
        $inactiveDepartments = Department::where('is_active', false)->count();
        $countries           = Country::orderBy('name')->get();

        return view('modules.ubication.departments.index', compact(
            'departments', 'countries', 'totalDepartments', 'activeDepartments', 'inactiveDepartments'
        ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::all(); // Carga todos los países desde la tabla `countries`
        return view('modules.ubication.departments.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:5',
            'country_id' => 'required|exists:countries,id', // Asegúrate de que el país existe
            'description' => 'nullable|string|max:320',
        ];
        $messages = [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'country_id.required' => 'El campo país es obligatorio.',
            'name.min' => 'El campo nombre debe tener al menos 5 caracteres.',
            'description.string' => 'El campo descripción debe ser una cadena de texto.',
            'description.max' => 'El campo descripción no puede tener más de 320 caracteres.',
        ];

        $this->validate($request, $rules, $messages);

        $departments = new Department();
        $departments->country_id = $request->input('country_id'); // Asigna el ID del país seleccionado
        $departments->name = $request->input('name');
        $departments->description = $request->input('description');
        $departments->save();
        $notification = [
            'message' => 'El departamento ' . $departments->name . ' se ha creado correctamente.',
            'alert-type' => 'Creación Éxitosa'
        ];
        return redirect()->route('departments.index')->with(compact('notification'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
    //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $countries = Country::all(); // Carga todos los países desde la tabla `countries`
        return view('modules.ubication.departments.edit', compact('department', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $rules = [
            'name' => 'required|string|min:5',
            'country_id' => 'required|exists:countries,id', // Asegúrate de que el país existe
            'description' => 'nullable|string|max:320',
        ];
        $messages = [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'country_id.required' => 'El campo país es obligatorio.',
            'name.min' => 'El campo nombre debe tener al menos 5 caracteres.',
            'description.string' => 'El campo descripción debe ser una cadena de texto.',
            'description.max' => 'El campo descripción no puede tener más de 320 caracteres.',
        ];

        $this->validate($request, $rules, $messages);

        $department->name = $request->input('name');
        $department->country_id = $request->input('country_id'); // Asigna el ID del país seleccionado
        $department->description = $request->input('description');
        $department->save();
        $notification = [
            'message' => 'El departamento ' . $department->name . ' se ha actualizado correctamente.',
            'alert-type' => 'Actualización Éxitosa'
        ];
        return redirect()->route('departments.index')->with(compact('notification'));
    }

    public function destroy(Department $department)
    {
        $department->is_active = false;
        $department->save();
        $notification = [
            'message'    => 'El departamento ' . $department->name . ' ha sido desactivado correctamente.',
            'alert-type' => 'Actualización Éxitosa'
        ];
        return redirect()->route('departments.index')->with(compact('notification'));
    }

    /**
     * Reactivar el recurso.
     */
    public function restore(Department $department)
    {
        $department->is_active = true;
        $department->save();
        $notification = [
            'message'    => 'El departamento ' . $department->name . ' ha sido reactivado correctamente.',
            'alert-type' => 'Actualización Éxitosa'
        ];
        return redirect()->route('departments.index')->with(compact('notification'));
    }
}